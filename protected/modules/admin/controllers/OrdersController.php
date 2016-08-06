<?php

class OrdersController extends EController
{
	public $layout='column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('create','suggestItems','scan','updateQty','paymentRequest','changeRequest','deleteItem','cancelTransaction'),
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('viewItems','print','promocode'),
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('view'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('update'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionCreate()
	{ 
		Yii::app()->user->setState('items_data',Product::getArrayData());
		if(!Yii::app()->user->hasState('items_belanja'))
			Yii::app()->user->setState('promocode',null);
		else{
			if(count(Yii::app()->user->getState('items_belanja'))==0)
				Yii::app()->user->setState('promocode',null);
		}
		if(Yii::app()->user->hasState('promocode'))
			$promocode=Promo::model()->findByPk(Yii::app()->user->getState('promocode'))->code;
		$this->render('create',array(
			'model'=>$model,
			'promocode'=>$promocode,
		));
	}

	/**
	 * For autocomplete only
	 */
	public function actionSuggestItems()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$items=array();
			if(Yii::app()->user->hasState('items_data')){
				$keyword=strtolower($keyword);
				foreach(Yii::app()->user->getState('items_data') as $item_id=>$data){
					if((is_array($data['tag']) && in_array($keyword,$data['tag'])) | strripos(strtolower($data['name']),$keyword) | strripos($data['barcode'],$keyword)){
					//if(strrpos($data['name'],$keyword)){
						$items[]=$data['barcode'].' - '.$data['name'];
					}
				}
			}

			if(is_array($items))
				echo implode("\n",$items);
		}
	}
	
	public function actionScan()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			if(!empty($_POST['item'])){
				$pecah=explode(" - ",$_POST['item']);
				$criteria=new CDbCriteria;
				$criteria->compare('barcode',$pecah[0]);
				$count=Product::model()->count($criteria);
				if($count>0){
					$model=Product::model()->find($criteria);
					if($model->price->current_stock>=1){ //jika persediaan masih ada
						if(!Yii::app()->user->hasState('items_belanja'))
							Yii::app()->user->setState('items_belanja',array());
				
						$items=array(
								'id'=>$model->id,
								'barcode'=>$model->barcode,
								'name'=>$model->name,
								'desc'=>$model->description,
								'cost_price'=>$model->price->purchase_price,
								'unit_price'=>$model->price->sold_price,
								'qty'=>1,
								'discount'=>0,
							);
				
						$items_belanja=Yii::app()->user->getState('items_belanja');
						$new_items_belanja=array();
						if(count($items_belanja)>0){
							$any=0;
							foreach($items_belanja as $index=>$data){
								if($data['id']==$items['id']){
									$data['qty']=$data['qty']+1;
									$any=$any+1;
								}
								$new_items_belanja[]=$data;
							}
							if($any<=0)
								array_push($new_items_belanja,$items);
						}else{
							array_push($new_items_belanja,$items);
						}
						//renew state
						Yii::app()->user->setState('items_belanja',$new_items_belanja);

						echo CJSON::encode(array(
							'status'=>'success',					
							'div'=>$this->renderPartial('_items',array('model'=>$model),true,true),
							'subtotal'=>number_format($this->getTotalBelanja(),0,',','.'),
						));
					}else{
						echo CJSON::encode(array(
							'status'=>'failed',					
							'message'=>$model->name.' is out of stock.',
						));
					}
				}else{
					echo CJSON::encode(array(
						'status'=>'failed',
						'message'=>'No item found!'
					));
				}
				exit;
			}
		}
	}

	public function actionUpdateQty()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			if(Yii::app()->user->hasState('items_belanja')){
				$items_belanja=Yii::app()->user->getState('items_belanja');
				$id=$_POST['id'];
				$cart_discount=$items_belanja[$id]['discount']/$items_belanja[$id]['qty'];
				$items_belanja[$id]['qty']=(int)$_POST['qty'];
				$model=Product::model()->findByPk($items_belanja[$id]['id']);
				if((int)$_POST['qty']<=(int)$model->price->current_stock){ //jika kurang dari atau sm dengan persediaan
					$price=0;
					if($model->discount_rel_count>0){
						foreach($model->getDiscontedItems() as $index=>$data){
							if($data->quantity<=0)
								$data->quantity=1;
							$bagi=$items_belanja[$id]['qty']/$data->quantity;
							$mod=$items_belanja[$id]['qty']%$data->quantity;
							if(((int)$bagi>0) & ($bagi<=$data->quantity)){
								if(time()>=strtotime($data->date_start) && time()<=strtotime($data->date_end)){
									$price=(int)$bagi*$data->price;
									if($mod>0)
										$price=$price+$items_belanja[$id]['unit_price']*$mod;
									$items_belanja[$id]['discount']=($items_belanja[$id]['unit_price']*$items_belanja[$id]['qty'])-$price;
								}
							}
						}
					}else{
						$price=$model->price->sold_price*$_POST['qty'];
						if(Yii::app()->user->hasState('promocode'))
							$items_belanja[$id]['discount']=Promo::getDiscountValue(Yii::app()->user->getState('promocode'),$price);
					}
					Yii::app()->user->setState('items_belanja',$items_belanja);
					if($price>0)
						$total=$price;
					else
						$total=$items_belanja[$id]['unit_price']*$items_belanja[$id]['qty'];
					//$discount=($model->price->sold_price*$_POST['qty'])-$total;
					$discount=$items_belanja[$id]['discount'];
					
					echo CJSON::encode(array(
						'status'=>'success',					
						'div'=>(int)$_POST['qty'],
						'total'=>number_format($total-$discount,0,',','.'),
						'subtotal'=>number_format($this->getTotalBelanja(),0,',','.'),
						'discount'=>number_format($discount,0,',','.'),
					));
				}else{
					echo CJSON::encode(array(
						'status'=>'failed',					
						'message'=>$_POST['qty'].' is not allowed, max '.$model->price->current_stock.' ready stock.',
					));
				}
				exit;
			}
		}
	}

	public function actionPaymentRequest()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

			$model=new PaymentForm;
			if(isset($_POST['PaymentForm'])){
				if(Yii::app()->user->hasState('items_belanja')){
					$model2=new Invoice;
					if(Yii::app()->user->hasState('customer')){
						$customer=Yii::app()->user->getState('customer');
						$model2->customer_id=(!empty($customer))? $customer->id : 0;
					}
					$model2->status=1;
					$model2->cash=$this->money_unformat($_POST['PaymentForm']['amount_tendered']);
					$model2->serie=$model2->getInvoiceNumber($model2->status,'serie');
					$model2->nr=$model2->getInvoiceNumber($model2->status,'nr');
					if($model2->status==1)
						$model2->paid_at=date(c);
					$model2->date_entry=date(c);
					$model2->user_entry=Yii::app()->user->id;
					if($model2->save()){
						$invoice_id=$model2->id;
						$group_id=Order::getNextGroupId();
						foreach(Yii::app()->user->getState('items_belanja') as $index=>$data){
							$model3=new Order;
							$model3->product_id=$data['id'];
							$model3->customer_id=$model2->customer_id;
							$product=Product::item($model3->product_id);
							$model3->title=$product->name;
							$model3->group_id=$group_id;
							$model3->group_master=($index==0)? 1:0;
							$model3->invoice_id=$model2->id;
							$model3->quantity=$data['qty'];
							$model3->price=$product->price->sold_price;
							$model3->discount=$data['discount'];
							if(Yii::app()->user->hasState('promocode')){
								$model3->promo_id=Yii::app()->user->getState('promocode');
								$model3->discount=Promo::getDiscountValue(Yii::app()->user->getState('promocode'),$model3->price);
							}
							$model3->type=$_POST['PaymentForm']['type'];
							$model3->status=1;
							$model3->date_entry=date(c);
							$model3->user_entry=Yii::app()->user->id;
							if($model3->save()){
								$product->price->current_stock=$product->price->current_stock-$model3->quantity;
								if(!$product->price->update(array('current_stock'))){
									var_dump($product->price->errors);exit;
								}
								
								$model4=new InvoiceItem;
								$model4->invoice_id=$model2->id;
								$model4->type='order';
								$model4->rel_id=$model3->id;
								$model4->title=$model3->title;
								$model4->quantity=$model3->quantity;
								$model4->price=$model3->quantity*($model3->price-$model3->discount);
								$model4->date_entry=date(c);
								$model4->user_entry=Yii::app()->user->id;
								$model4->save();
							}
						}
						Yii::app()->user->setState('items_belanja',null);
						Yii::app()->user->setState('customer',null);
						Yii::app()->user->setState('promocode',null);
					}
					//add queue for analytics
					$queue=new Queue;
					$queue->invoice_id=$invoice_id;
					$queue->date_entry=date(c);
					$queue->user_entry=Yii::app()->user->id;
					$queue->save();
					echo CJSON::encode(array(
						'status'=>'success',
						'invoice_id'=>$invoice_id,
					));
					exit;
				}
			}
			echo CJSON::encode(array(
				'status'=>'success',					
				'div'=>$this->renderPartial('_payment',array('model'=>$model),true,true),
			));
			exit;
		}
	}

	public function actionChangeRequest()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$change=$this->money_unformat($_POST['amount_tendered'])-$this->getTotalBelanja();
			$model=new PaymentForm;
			
			echo CJSON::encode(array(
				'status'=>($change>=0)? 'success' : 'failed',					
				'div'=>($change>=0)? $this->renderPartial('_change',array('model'=>$model,'change'=>$change),true,true) : 'Not enough tendered !',
			));
			exit;
		}
	}

	public function actionDeleteItem()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			if(Yii::app()->user->hasState('items_belanja')){
				$items=array();
				foreach(Yii::app()->user->getState('items_belanja') as $index=>$data){
					if(!($index==$_POST['id']))
						$items[$index]=$data;
				}
				Yii::app()->user->setState('items_belanja',$items);

				echo CJSON::encode(array(
					'status'=>'success',					
					'div'=>$this->renderPartial('_items',null,true,true),
					'subtotal'=>number_format($this->getTotalBelanja(),0,',','.'),
					'count'=>count($items),
				));
				exit;
			}
		}
	}

	public function actionCancelTransaction()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			if(Yii::app()->user->hasState('items_belanja')){
				Yii::app()->user->setState('items_belanja',null);
				Yii::app()->user->setState('customer',null);
				Yii::app()->user->setState('promocode',null);

				echo CJSON::encode(array(
					'status'=>'success',					
					'div'=>$this->renderPartial('_items',null,true,true),
					'subtotal'=>number_format($this->getTotalBelanja(),0,',','.'),
				));
				exit;
			}
		}
	}

	public function actionViewItems()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
			
			$criteria=new CDbCriteria;

			if(Yii::app()->user->hasState('items_filter') & !isset($_POST['Product']))
				$_POST=Yii::app()->user->getState('items_filter');
			
			if(isset($_POST['Product'])){
				$criteria->compare('barcode',$_POST['Product']['barcode'],true);
				$criteria->compare('LOWER(description)',strtolower($_POST['items_name']),true);
				Yii::app()->user->setState('items_filter',$_POST);
			}

			$dataProvider=new CActiveDataProvider('Product',array(
				'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>10,
					'pageVar' => 'page',
					'currentPage'=>$_GET['page'] - 1,
				)
			));
		
			echo CJSON::encode(array(
				'status'=>'success',					
				'div'=>$this->renderPartial('_view_items',array('dataProvider'=>$dataProvider),true,true),
			));
			exit;
		}
	}

	public function actionPrint()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

			$amount_tendered=$this->money_unformat($_POST['amount_tendered']);
			$change=$amount_tendered-$this->getTotalBelanja();

			echo CJSON::encode(array(
				'status'=>'success',					
				'div'=>$this->renderPartial('_print',array('amount_tendered'=>$amount_tendered,'change'=>$change),true,true),
			));
			exit;
		}
	}

	public function actionPromocode()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

			$criteria=new CDbCriteria;
			//$criteria->compare('active',1);
			$criteria->compare('code',$_POST['promocode']);

			$model=Promo::model()->find($criteria);
			if(!empty($model->id)){
				if(strtotime($model->end_at)>0){
					if(time()<=strtotime($model->end_at) && time()>=strtotime($model->start_at))
						Yii::app()->user->setState('promocode',$model->id);
					else
						Yii::app()->user->setState('promocode',null);
				}else
					Yii::app()->user->setState('promocode',$model->id);

				if(Yii::app()->user->hasState('items_belanja')){
					$items=array();
					foreach(Yii::app()->user->getState('items_belanja') as $index=>$data){
						$data['discount']=Promo::getDiscountValue($model->id,$data['unit_price']);
						$items[$index]=$data;
					}
					Yii::app()->user->setState('items_belanja',$items);
					if(Yii::app()->user->hasState('promocode')){
						echo CJSON::encode(array(
							'status'=>'success',					
							'div'=>Yii::t('order','Promo Code succesfully apllied.'),
							'cart'=>$this->renderPartial('_items',null,true,true),
							'subtotal'=>number_format($this->getTotalBelanja(),0,',','.'),
						));
					}else{
						echo CJSON::encode(array(
							'status'=>'failed',					
							'div'=>Yii::t('order','Could not found Promocode, or your promocode is expired'),
						));
					}
				}
			}else{
				Yii::app()->user->setState('promocode',null);
				echo CJSON::encode(array(
					'status'=>'failed',					
					'div'=>Yii::t('order','Could not found Promocode, or your promocode is expired'),
				));
			}
			exit;
		}
	}

	public function getTotalBelanja()
	{
		$num=0;
		if(Yii::app()->user->hasState('items_belanja')){
			$items_belanja=Yii::app()->user->getState('items_belanja');
			foreach($items_belanja as $index=>$data){
				$num=$num+($data['unit_price']*$data['qty'])-$data['discount'];
			}
		}
		return $num;
	}

	public function actionView()
	{
		$this->layout='column2';
		$criteria1=new CDbCriteria;
		$criteria2=new CDbCriteria;
		$criteria3=new CDbCriteria;
		if(isset($_GET['Order'])){
			$criteria1->compare('customer_id',$_GET['Order']['customer_id']);
			$criteria1->compare('id',$_GET['Order']['id']);
			$criteria1->addBetweenCondition('DATE_FORMAT(date_entry,"%Y-%m-%d")',$_GET['Order']['date_from'],$_GET['Order']['date_to'],'AND');
			$criteria2->compare('customer_id',$_GET['Order']['customer_id']);
			$criteria2->compare('id',$_GET['Order']['id']);
			$criteria2->addBetweenCondition('DATE_FORMAT(date_entry,"%Y-%m-%d")',$_GET['Order']['date_from'],$_GET['Order']['date_to'],'AND');
			$criteria3->compare('customer_id',$_GET['Order']['customer_id']);
			$criteria3->compare('id',$_GET['Order']['id']);
			$criteria3->addBetweenCondition('DATE_FORMAT(date_entry,"%Y-%m-%d")',$_GET['Order']['date_from'],$_GET['Order']['date_to'],'AND');
		}

		$criteria1->order='date_entry DESC';
		$dataProvider=new CActiveDataProvider('Order',array('criteria'=>$criteria1));

		$criteria2=new CDbCriteria;
		$criteria2->compare('type',0);
		$criteria2->order='date_entry DESC';
		$creditProvider=new CActiveDataProvider('Order',array('criteria'=>$criteria2));

		$criteria3=new CDbCriteria;
		$criteria3->compare('type',1);
		$criteria3->order='date_entry DESC';
		$cashProvider=new CActiveDataProvider('Order',array('criteria'=>$criteria3));

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
			'creditProvider'=>$creditProvider,
			'cashProvider'=>$cashProvider
		));
	}

	public function actionUpdate($id)
	{
		$model=Order::model()->findByPk($id);
		if(isset($_POST['Order'])){
			$model->attributes=$_POST['Order'];
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
			if($model->save()){
				Yii::app()->user->setFlash('update',Yii::t('global','Your data has been saved successfully.'));
				$this->refresh();
			}
		}
		$this->render('update',array('model'=>$model));
	}

	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{	
			// we only allow deletion via POST request
			Order::model()->findByPk($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
}
