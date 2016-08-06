<?php
$this->pageTitle=Yii::app()->config->get('site_name') . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
Yii::app()->clientScript->registerScript('recovery', "
$('.request-password').click(function(){
	passrequest();
	$('#jdl').text('Request Password');
	return false;
});
");
?>
<section>
    <div class="row signinpanel">
		<div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3" id="div-for-form">
			<?php if(Yii::app()->user->hasFlash('login')): ?>

			<div class="flash-success">
				<?php 
					header('refresh: 2; url='.Yii::app()->user->returnUrl);
					echo Yii::app()->user->getFlash('login'); 
				?>
			</div>

			<?php endif; ?>	
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			)); ?>
				
				<h4 class="nomargin">Login</h4>
				<p class="mt5 mb20">Silakan masukkan username dan password Anda untuk mengakses akun.</p>
                <?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

                   <?php echo $form->textField($model,'username',array('class'=>'form-control uname','placeholder'=>'Username')); ?>
                   <?php echo $form->passwordField($model,'password',array('class'=>'form-control pword','placeholder'=>'Password')); ?>
                   <?php echo CHtml::submitButton(Yii::t('global','Login'),array('style'=>'min-width:100px;','id'=>'tombol','class'=>'btn btn-success btn-block')); ?>
                    
			<?php $this->endWidget(); ?>
		</div><!-- col-sm-12 -->
    </div><!-- signin -->
</section>

<script type="text/javascript">
function passrequest()
{	
	$.ajax({
	'beforeSend': function() { Loading.show(); },
	'complete': function() { Loading.hide(); },
      	'url': '<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/password/request');?>',
      	'dataType': 'json',
      	'success': function(data){
			if(data.status=='success'){
				$('#div-for-form').html(data.div);
			}
      		},
    	});
    return false;
 
}
$(function(){
	$('body').addClass('leftpanel-collapsed');
});
</script>
