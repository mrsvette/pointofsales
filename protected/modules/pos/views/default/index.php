<?php
$this->breadcrumbs=array(
	Yii::t('global','Dashboard'),
);

$this->menu=array(
	array('label'=>Yii::t('global','Dashboard'), 'url'=>array('index')),
	array('label'=>Yii::t('order','Create Sales'), 'url'=>array('orders/create'),'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'orders',Yii::app()->user->id,'create_p')),
);

$this->pageTitle='Dashboard | '.Yii::app()->config->get('site_name');
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<?php echo Yii::t('order','Transaction');?> - 
			<?php echo Yii::t('global',date('l'));?>, 
			<?php echo date('d');?> 
			<?php echo Yii::t('global',date('F'));?> 
			<?php echo date('Y');?> 
		</h4>
	</div>
	<div class="panel-body">
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
							'header'=>Yii::t('order','Invoice Number'),
							'type'=>'raw',
							'value'=>'CHtml::link($data->invoice_rel->invoiceFormatedNumber,array(\'invoices/update\',\'id\'=>$data->invoice_id))'
						),
						array(
							'header'=>Yii::t('order','Item Name'),
							'type'=>'raw',
							'value'=>'$data->title'
						),
						array(
							'header'=>Yii::t('order','Total Item'),
							'type'=>'raw',
							'value'=>'Order::getCountOrderItemDate(date("Y-m-d"),$data->product_id)',
							'htmlOptions'=>array('style'=>'text-align:left;'),
						),
						array(
							'header'=>Yii::t('order','Price'),
							'type'=>'raw',
							'value'=>'number_format($data->price,0,\',\',\'.\')',
							'htmlOptions'=>array('style'=>'text-align:right;'),
						),
						array(
							'header'=>Yii::t('order','Sub Total'),
							'type'=>'raw',
							'value'=>'number_format(Order::getTotalOrderDate(date("Y-m-d"),$data->product_id),0,\',\',\'.\')',
							'htmlOptions'=>array('style'=>'text-align:right;'),
						),
					),
				)); ?>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('order','Statistic');?></h4>
	</div>
	<div class="panel-body">
		<div class="table-responsive mar_top1">
				<table class="table table-striped mb30 items">
					<thead>
						<tr>
							<th>Metric</th>
							<th><?php echo Yii::t('order','Today');?></th>
							<th><?php echo Yii::t('order','Yesterday');?></th>
							<th><?php echo Yii::t('order','This Month So Far');?></th>
							<th><?php echo Yii::t('order','Last Month');?></th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Income</th>
							<th>Rp. <?php echo number_format(Order::getIncome('today'),2,',','.');?></th>
							<th>Rp. <?php echo number_format(Order::getIncome('yesterday'),2,',','.');?></th>
							<th>Rp. <?php echo number_format(Order::getIncome('thismonth'),2,',','.');?></th>
							<th>Rp. <?php echo number_format(Order::getIncome('lastmonth'),2,',','.');?></th>
							<th>Rp. <?php echo number_format(Order::getIncome('total'),2,',','.');?></th>
						</tr>
						<tr>
							<th>Order</th>
							<th><?php echo Order::getOrder('today');?></th>
							<th><?php echo Order::getOrder('yesterday');?></th>
							<th><?php echo Order::getOrder('thismonth');?></th>
							<th><?php echo Order::getOrder('lastmonth');?></th>
							<th><?php echo Order::getOrder('total');?></th>
						</tr>
						<tr>
							<th>Invoice</th>
							<th><?php echo Order::getInvoice('today');?></th>
							<th><?php echo Order::getInvoice('yesterday');?></th>
							<th><?php echo Order::getInvoice('thismonth');?></th>
							<th><?php echo Order::getInvoice('lastmonth');?></th>
							<th><?php echo Order::getInvoice('total');?></th>
						</tr>
					</tbody>
				</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><?php echo Yii::t('order','Chart');?></h4>
			</div>
			<div class="panel-body">
				<div id="basicflot" style="width: 100%; height: 300px; margin-bottom: 20px"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><?php echo Yii::t('order','Top 10 Product Sales');?></h4>
			</div>
			<div class="panel-body">
				<div class="table-responsive mar_top1">
						<table class="table table-striped">
							<tbody>
								<?php foreach(Order::getRankOrder() as $index=>$data):?>
								<tr>
									<td><?php echo $data['name'];?></td>
									<td><?php echo $data['tot'];?></td>
								</tr>
								<?php endforeach;?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/brain/js/plugins/charts/flot.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/brain/js/plugins/charts/flot.resize.min.js"></script>
<script type="text/javascript">
$(function(){
	$.ajax({
		'url':"<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/default/plot');?>",
		'type':'post',
		'dataType':'json',
		'success':function(data){
			if(data.status=='success'){
				var now="<?php echo date('d');?>";
				var income=JSON.parse(data.income.replace(/&quot;/g,'"')); 
				
	 			var plot = jQuery.plot(jQuery("#basicflot"),
				[
					{ data: income,label: "Income on this month",color: "#428BCA"},
			  	],
      			{
			  		series: {
				 		lines: {show: true,fill: true,lineWidth: 1,fillColor: {colors: [ { opacity: 0.3 }, { opacity: 0.3 }]}
		      		},
				 	points: {show: true},
		      		shadowSize: 1
				},
		  		legend: {position: 'nw'},
		  		grid: {
          			hoverable: true,clickable: true,borderColor: '#ddd', borderWidth: 1,labelMargin: 2,backgroundColor: '#fff',lineWidth: 1},
		  			yaxis: {
          				min: 0,
          				color: '#eee',
						tickDecimals:'no',
        			},
        			xaxis: {
          				color: '#eee',
          				min: 0,
						max: now,
						tickDecimals:'no',
						tickSize:1,
						mode: 'date',
        			}
				});
		
	 			var previousPoint = null;
	 			jQuery("#basicflot").bind("plothover", function (event, pos, item) {
      				jQuery("#x").text(pos.x.toFixed(2));
      				jQuery("#y").text(pos.y.toFixed(2));
		
	 			});
		
	 			jQuery("#basicflot").bind("plotclick", function (event, pos, item) {
					if(item) {
		  				plot.highlight(item.series, item.datapoint);
					}
	 			});
			}
		},
	});
	return false;
});
</script>
