<?php
$this->breadcrumbs=array(
	'Product'=>array('view'),
	Yii::t('global','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('product','Product'), 'url'=>array('view')),
	array('label'=>Yii::t('global','List').' '.Yii::t('product','Product Type'), 'url'=>array('productTypes/view')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Create');?> Product</h4>
	</div>
	<div class="panel-body">
		<?php if(Yii::app()->user->hasFlash('create')): ?>
			<div class="alert alert-success">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
				<?php echo Yii::app()->user->getFlash('create'); ?>
			</div>
		<?php endif; ?>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'model2'=>$model2)); ?>
	</div>
</div>
