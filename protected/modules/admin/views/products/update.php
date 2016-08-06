<?php
$this->breadcrumbs=array(
	'Product'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Product', 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' Product', 'url'=>array('create')),
	array('label'=>Yii::t('global','List').' '.Yii::t('product','Product Type'), 'url'=>array('productTypes/view')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Update');?> Product <?php echo $model->id; ?></h4>
	</div>
	<div class="panel-body">
		<?php if(Yii::app()->user->hasFlash('update')): ?>
			<div class="alert alert-success">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
				<?php echo Yii::app()->user->getFlash('update'); ?>
			</div>
		<?php endif; ?>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'model2'=>$model2)); ?>
	</div>
</div>