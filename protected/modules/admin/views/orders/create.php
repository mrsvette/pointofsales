<?php
$this->breadcrumbs=array(
	Yii::t('order','Orders')=>array('view'),
	Yii::t('global','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('order','Orders'), 'url'=>array('view')),
	array('label'=>Yii::t('global','List').' '.Yii::t('order','Invoices'), 'url'=>array('invoices/view')),
	array('label'=>Yii::t('global','List').' '.Yii::t('order','Products'), 'url'=>array('products/view')),
	array('label'=>Yii::t('global','List').' '.Yii::t('order','Promotions'), 'url'=>array('promotions/view')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><i class="glyphicon glyphicon-shopping-cart"></i> <?php echo Yii::t('order','Create Sales');?></h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model,'promocode'=>$promocode)); ?>
	</div>
</div>
