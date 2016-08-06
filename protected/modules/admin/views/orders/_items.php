<?php 
if(Yii::app()->user->hasState('items_belanja')):
	$no=1; $tot_qty=0; $tot_disc=0; $tot_pay=0;
	foreach(Yii::app()->user->getState('items_belanja') as $index=>$data){?>
		<?php 
		$harga_bruto=$data['unit_price']*$data['qty'];
		$harga_netto=$harga_bruto-$data['discount'];
		$discount=$harga_bruto-$harga_netto;
		?>
		<tr class="<?php echo($no%2>0)? 'even':'odd';?>">
			<td style="text-align:center;"><?php echo $no;?></td>
			<td><?php echo $data['barcode'];?></td>
			<td><?php echo $data['name'];?></td>
			<td style="text-align:right;"><?php echo number_format($data['unit_price'],0,',','.');?></td>
			<td style="text-align:center;"><?php echo CHtml::textField('qty',$data['qty'],array('size'=>3,'class'=>'text-center','onchange'=>'pushQty('.$index.',this.value,"'.Yii::app()->createUrl('admin/orders/updateQty').'")'));?></td>
			<td style="text-align:right;" id="discount-<?php echo $index;?>"><?php echo number_format($discount,0,',','.');?></td>
			<td style="text-align:right;" id="total-item-<?php echo $index;?>"><?php echo number_format($harga_netto,0,',','.');?></td>
			<td style="text-align:center;" class="table-action"><?php echo CHtml::link('<i class="fa fa-trash-o"></i>','javascript:void(0);',array('onclick'=>'deleteItem("'.$index.'");'));?></td>
		</tr>
<?php
		$no++;
	}?>
<?php
endif;
?>
