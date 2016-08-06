<div class="hide">
<?php
	$this->widget('ext.mPrint.mPrint', array(
 		'title' => $model->invoiceFormatedNumber,        //the title of the document. Defaults to the HTML title
		'tooltip' => 'testing',    //tooltip message of the print icon. Defaults to 'print'
		'text' => 'Print Invoice', //text which will appear beside the print icon. Defaults to NULL
		'element' => '.table-fixed',      //the element to be printed.
		/*'exceptions' => array(     //the element/s which will be ignored
			'.link-view',
		),*/
		'publishCss' => 'true',       //publish the CSS for the whole page?
		));
	?>
</div>
<div class="table-fixed">
<table class="table table-striped">
	<tr>
		<td colspan="4">
			<center>
				<?php echo strtoupper(Yii::app()->config->get('site_name'));?><br/>
				<?php echo strtoupper(Yii::app()->config->get('address'));?>
			</center>
		</td>
	</tr>
	<tr>
		<td class="border-stripped"><?php echo date("d. m. y - H:i",strtotime($model->date_entry));?></td>
		<td colspan="3" class="border-stripped text-right"><?php echo $model->invoiceFormatedNumber;?>/<?php echo strtoupper($model->user_entry_rel->username);?></td>
	</tr>
	<?php foreach($model->items_rel as $item):?>
	<tr class="lower">
		<td><?php echo strtoupper($item->title);?></td>
		<td><?php echo $item->quantity;?></td>
		<td class="text-right"><?php echo number_format($item->orderPrice,0,',','.');?></td>
		<td class="text-right"><?php echo number_format($item->price,0,',','.');?></td>
	</tr>
	<?php endforeach;?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" class="text-right bordered-bottom">DISKON :</td>
		<td class="text-right bordered-bottom"><?php echo number_format($model->totalDiscount,0,',','.');?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" class="text-right bordered-bottom">HARGA JUAL :</td>
		<td class="text-right bordered-bottom"><?php echo number_format($model->totalPrice,0,',','.');?></td>
	</tr>
	<tr class="lower">
		<td>&nbsp;</td>
		<td colspan="2" class="text-right">TOTAL :</td>
		<td class="text-right"><?php echo number_format($model->totalPrice,0,',','.');?></td>
	</tr>
	<tr class="lower">
		<td>&nbsp;</td>
		<td colspan="2" class="text-right">TUNAI :</td>
		<td class="text-right"><?php echo number_format($model->cash,0,',','.');?></td>
	</tr>
	<tr class="lower">
		<td>&nbsp;</td>
		<td colspan="2" class="text-right">KEMBALI :</td>
		<td class="text-right"><?php echo number_format($model->change,0,',','.');?></td>
	</tr>
	<tr>
		<td colspan="4"><center>TERIMA KASIH ATAS KUNJUNGANNYA</center></td>
	</tr>
</table>
</div>
<style>
.table-fixed .table-striped {
	/*width:300px;*/
}
.table {
  	background: none;
	box-shadow: 0 0 0 rgba(12, 12, 12, 0.03);
	margin-top: -15px;
	margin-left: -13px;
	margin-right: 10px;
		font-size: 10px;
		text-transform: lowercase;
	color: #000;
	font-family: serif;
}
.table th {
	/*font-family: 'LatoBold';*/
}

.table thead > tr > th {
	border-color: #eee;
	background-color: #fff;
	padding-top: 10px;
	padding-bottom: 10px;
	color: #000;
}

.table thead > tr > th,
.table tbody > tr > th,
.table tfoot > tr > th,
.table thead > tr > td,
.table tbody > tr > td,
.table tfoot > tr > td {
	border-color: #eee;
}
.table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
  background-color: #fff;
}
.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
  border-color: #fff;
}
.table tbody > tr > td.border-stripped {
	border-top:1px solid #eee;
	border-bottom:1px solid #eee;
}
.table tbody > tr > td.bordered-bottom {
	border-bottom:1px solid #eee;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
 	line-height: 1.2;
	padding: 5px;
	vertical-align: top;
}
.table tbody > tr.lower > td {
	line-height: 1.2;
	padding: 3px;
}
.text-right {text-align:right;}
</style>
<script type="text/javascript">
$('#btn-print').click(function(){
	$('#mprint').trigger('click');
	return false;
});
</script>
