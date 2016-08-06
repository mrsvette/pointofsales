<?php $this->beginContent('/layouts/main'); ?>
	<?php
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'filemanager-options'),
		));
	?>
	<?php echo $content; ?>
<?php $this->endContent(); ?>

<script type="text/javascript">
$(function(){
	dataReload();
	var site_name="<?php echo strtoupper(Yii::app()->config->get('site_name'));?>";
	$('ul[class="filemanager-options"]').append('<li class="filter-type"><b>'+site_name+'</b></li>');
	$('.nominal').maskMoney({symbol:'Rp ', showSymbol:false, thousands:'.', decimal:',', symbolStay: true});
});
function reloadGrid(id, data) {
	dataReload();
	return false;
}
function dataReload(){
	$('input[type=text]').addClass('form-control');
	$('textarea').addClass('form-control');
	$('select').addClass('form-control');
	$('input[type=password]').addClass('form-control');
	$('input[type=submit]').addClass('btn btn-primary');
	$('input[type=button]').addClass('btn btn-primary');
	$('.yiiPager').addClass('dataTables_paginate paging_full_numbers');
	$('.dataTables_paginate').removeClass('yiiPager');
	return false;
}
</script>
