<?php
$this->breadcrumbs=array(
	Yii::t('global','Dashboard'),
);

$this->menu=array(
	array('label'=>Yii::t('global','Dashboard'), 'url'=>array('default/index')),
	array('label'=>Yii::t('order','Create Sales'), 'url'=>array('orders/create'),'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'orders',Yii::app()->user->id,'create_p')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('order-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="col-sm-12">
<div class="panel panel-default row">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('order','Income');?></h4>
	</div>
	<div class="panel-body">
		<div class="search-form">
			<?php $this->renderPartial('_search',array('model'=>$model));?>
		</div>
		<div class="table-responsive">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'order-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
							'htmlOptions'=>array('style'=>'text-align:center;'),
						),
						array(
							'name'=>Yii::t('order','Date'),
							'type'=>'raw',
							'value'=>'$data[\'date\']'
						),
						array(
							'name'=>Yii::t('order','Initial Capital'),
							'type'=>'raw',
							'value'=>'number_format(PaymentSession::getModal($data[\'date\']),0,\',\',\'.\')'
						),
						array(
							'name'=>Yii::t('order','Number Of Sales'),
							'type'=>'raw',
							'value'=>'$data[\'total_pembelian\']'
						),
						array(
							'name'=>Yii::t('order','Total Income'),
							'type'=>'raw',
							'value'=>'number_format($data[\'total_pendapatan\'],0,\',\',\'.\')',
							'htmlOptions'=>array('style'=>'text-align:left;'),
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{view}',
							'buttons'=>array
								(
									'view'=>array(
											'label'=>'<i class="fa fa-search"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/reports/detail",array(\'date\'=>$data[\'date\']))',
											'options'=>array('title'=>'View','id'=>'view-list','target'=>'_newtab'),
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
