<?php
$this->breadcrumbs=array(
	Yii::t('order','Orders')=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array(
		'label'=>Yii::t('global','List').' '.Yii::t('order','Orders'), 
		'url'=>array('view'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'orders',Yii::app()->user->id,'read_p')
	),
	array(
		'label'=>Yii::t('global','Create').' '.Yii::t('order','Orders'), 
		'url'=>array('create'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'orders',Yii::app()->user->id,'create_p')
	),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('all-grid', {
		data: $(this).serialize()
	});
	$.fn.yiiGridView.update('cash-grid', {
		data: $(this).serialize()
	});
	$.fn.yiiGridView.update('credit-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<ul class="row stats">
	<li class="col-xs-3">
		<a class="btn btn-default" href="#cash-order"><?php echo $cashProvider->totalItemCount;?></a>
		<span><?php echo Yii::t('order','Cash Order');?></span>
	</li>
	<li class="col-xs-3">
		<a class="btn btn-default" href="#credit-order"><?php echo $creditProvider->totalItemCount;?></a>
		<span><?php echo Yii::t('order','Credit Order');?></span>
	</li>
	<li class="col-xs-6">
		<a class="btn btn-default" href="#all-list"><?php echo $dataProvider->totalItemCount;?></a>
		<span><?php echo Yii::t('order','All Order');?></span>
	</li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> <?php echo Yii::t('order','Orders');?></h4>
	</div>
	<div class="panel-body">
		<?php echo CHtml::link(Yii::t('global','Advanced Search'),'#',array('class'=>'search-button pull-right btn btn-default-alt')); ?>
		<ul class="nav nav-tabs">
			<li class="">
				<a data-toggle="tab" href="#cash-order">
					<strong><?php echo Yii::t('order','Cash Order');?></strong> <span class="badge badge-warning"><?php echo $cashProvider->totalItemCount;?></span>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#credit-order">
					<strong><?php echo Yii::t('order','Credit Order');?></strong> <span class="badge badge-warning"><?php echo $creditProvider->totalItemCount;?></span>
				</a>
			</li>
			<li class="active">
				<a data-toggle="tab" href="#all-list">
					<strong><?php echo Yii::t('order','All Order');?></strong> <span class="badge badge-warning"><?php echo $dataProvider->totalItemCount;?></span>
				</a>
			</li>
		</ul>
		<div class="search-form  col-sm-12 mar_top2" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$dataProvider->model,
		)); ?>
		</div><!-- search-form -->
		<div class="tab-content pill-content">
			<div id="cash-order" class="tab-pane">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$cashProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'cash-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'title',
							'type'=>'raw',
							'value'=>'$data->title'
						),
						array(
							'name'=>'quantity',
							'type'=>'raw',
							'value'=>'$data->quantity'
						),
						array(
							'name'=>'price',
							'type'=>'raw',
							'value'=>'number_format($data->price,0,\',\',\'.\')'
						),
						array(
							'name'=>'discount',
							'type'=>'raw',
							'value'=>'number_format($data->discount,0,\',\',\'.\')'
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'date("d-m-Y H:i",strtotime($data->date_entry))'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{invoice}{delete}',
							'buttons'=>array
								(
									'update'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/orders/update",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Update','id'=>'update-list'),
											'visible'=>'true',
										),
									'invoice'=>array(
											'label'=>'<i class="fa fa-file-text-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data->invoice_id))',
											'options'=>array('title'=>'Invoice','id'=>'invoice-list'),
											'visible'=>'true',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/orders/delete",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Delete','id'=>'delete-list'),
											'visible'=>'true',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
			<div id="credit-order" class="tab-pane">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$creditProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'credit-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'title',
							'type'=>'raw',
							'value'=>'$data->title'
						),
						array(
							'name'=>'quantity',
							'type'=>'raw',
							'value'=>'$data->quantity'
						),
						array(
							'name'=>'price',
							'type'=>'raw',
							'value'=>'number_format($data->price,0,\',\',\'.\')'
						),
						array(
							'name'=>'discount',
							'type'=>'raw',
							'value'=>'number_format($data->discount,0,\',\',\'.\')'
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'date("d-m-Y H:i",strtotime($data->date_entry))'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{invoice}{delete}',
							'buttons'=>array
								(
									'update'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/orders/update",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Update','id'=>'update-list'),
											'visible'=>'true',
										),
									'invoice'=>array(
											'label'=>'<i class="fa fa-file-text-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data->invoice_id))',
											'options'=>array('title'=>'Invoice','id'=>'invoice-list'),
											'visible'=>'true',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/orders/delete",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Delete','id'=>'delete-list'),
											'visible'=>'true',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
			<div id="all-list" class="tab-pane active">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'all-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'title',
							'type'=>'raw',
							'value'=>'$data->title'
						),
						array(
							'name'=>'quantity',
							'type'=>'raw',
							'value'=>'$data->quantity'
						),
						array(
							'name'=>'price',
							'type'=>'raw',
							'value'=>'number_format($data->price,0,\',\',\'.\')'
						),
						array(
							'name'=>'discount',
							'type'=>'raw',
							'value'=>'number_format($data->discount,0,\',\',\'.\')'
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'date("d-m-Y H:i",strtotime($data->date_entry))'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{invoice}{delete}',
							'buttons'=>array
								(
									'update'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/orders/update",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Update','id'=>'update-list'),
											'visible'=>'true',
										),
									'invoice'=>array(
											'label'=>'<i class="fa fa-file-text-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data->invoice_id))',
											'options'=>array('title'=>'Invoice','id'=>'invoice-list'),
											'visible'=>'true',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/orders/delete",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Delete','id'=>'delete-list'),
											'visible'=>'true',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('.stats').find('.btn').click(function(){
	$('.nav-tabs').find('a[href="'+$(this).attr('href')+'"]').trigger('click');
	return false;
});
</script>
