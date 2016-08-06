<ul class="nav nav-tabs">
	<li class="active">
		<a data-toggle="tab" href="#general">
			<strong><?php echo Yii::t('order','General Information');?></strong>
		</a>
	</li>
	<li class="">
		<a data-toggle="tab" href="#price">
			<strong><?php echo Yii::t('order','Product Price');?></strong>
		</a>
	</li>
</ul>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'promo-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="tab-content pill-content">
	<div id="general" class="tab-pane active">
		<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model,'name',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
		</div>

		<div class="form-group col-md-4 mb20">
			<?php echo $form->labelEx($model,'type',array('class'=>'control-label')); ?>
			<?php echo $form->dropDownList($model,'type',ProductType::items(Yii::t('product','- Choose Type -')),array('class'=>'form-control')); ?>
		</div>

		<div class="form-group col-md-8">
			<?php echo $form->labelEx($model,'description',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model,'description',array('class'=>'form-control')); ?>
		</div>

		<?php if(!$model->isNewRecord):?>
		<div class="form-group col-md-8">
			<?php echo $form->labelEx($model,'tag',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model,'tag',array('class'=>'form-control')); ?>
			<p class="hint"><?php echo Yii::t('global','Please separate different tags with commas.');?></p>
		</div>
		<?php endif;?>
	</div>
	<div id="price" class="tab-pane">
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'purchase_date',array('class'=>'control-label')); ?>
			<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model2, //Model object
						'attribute'=>'purchase_date', //attribute name
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'yy-mm-dd',
							'changeMonth' => 'true',
							'changeYear'=>'true',
							'constrainInput' => 'false'
						),
						'htmlOptions'=>array(
							'class'=>'form-control',
							'value'=>(!empty($model2->purchase_date))? date('Y-m-d',strtotime($model2->purchase_date)) : date('Y-m-d'),
						),
					));
			?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'purchase_price',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model2,'purchase_price',array('class'=>'form-control')); ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'purchase_stock',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model2,'purchase_stock',array('class'=>'form-control')); ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'current_stock',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model2,'current_stock',array('class'=>'form-control')); ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'sold_price',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model2,'sold_price',array('class'=>'form-control')); ?>
		</div>
	</div>
</div>
<div class="form-group col-md-12 mt10">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
</div>
<?php $this->endWidget(); ?>
