<?php
	$this->widget('zii.widgets.CMenu', array(
		'items'=>array(
			array('label'=>'<i class="fa fa-home"></i> <span>Dashboard</span>', 'url'=>array('/admin/default/index'),'visible'=>!Yii::app()->user->isGuest),
			array('label'=>'<i class="fa fa-briefcase"></i> <span>'.Yii::t('order','Orders').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>array(
					array('label'=>'<i class="fa fa-caret-right"></i> '.Yii::t('order','Overview'), 'url'=>array('/admin/orders/view'),'visible'=>RbacUserAccess::isChecked('admin','orders',Yii::app()->user->id,'read_p')),
					array('label'=>'<i class="fa fa-caret-right"></i> '.Yii::t('order','Create Sales'), 'url'=>array('/admin/orders/create'),'visible'=>RbacUserAccess::isChecked('admin','orders',Yii::app()->user->id,'create_p')),
				),
				'itemOptions'=>array('class'=>'nav-parent'),
				'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
				'visible'=>RbacUserAccess::isChecked('admin','orders',Yii::app()->user->id,'read_p')
			),
			array('label'=>'<i class="glyphicon glyphicon-usd"></i> <span>'.Yii::t('order','Invoices').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>array(
					array('label'=>'<i class="fa fa-caret-right"></i> '.Yii::t('order','Overview'), 'url'=>array('/admin/invoices/view'),'visible'=>RbacUserAccess::isChecked('admin','invoices',Yii::app()->user->id,'read_p')),
				),
				'itemOptions'=>array('class'=>'nav-parent'),
				'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
				'visible'=>RbacUserAccess::isChecked('admin','invoices',Yii::app()->user->id,'read_p')
			),
			array('label'=>'<i class="glyphicon glyphicon-list"></i> <span>'.Yii::t('order','Products').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>array(
					array('label'=>'<i class="fa fa-caret-right"></i> '.Yii::t('order','Overview'), 'url'=>array('/admin/products/view'),'visible'=>RbacUserAccess::isChecked('admin','products',Yii::app()->user->id,'read_p')),
					array('label'=>'<i class="fa fa-caret-right"></i> '.Yii::t('global','Create').' '.Yii::t('order','Product'), 'url'=>array('/admin/products/create'),'visible'=>RbacUserAccess::isChecked('admin','products',Yii::app()->user->id,'create_p')),
				),
				'itemOptions'=>array('class'=>'nav-parent'),
				'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
				'visible'=>RbacUserAccess::isChecked('admin','orders',Yii::app()->user->id,'read_p')
			),
			array('label'=>'<i class="fa fa-money"></i> <span>'.Yii::t('order','Promo').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>array(
					array('label'=>'<i class="fa fa-caret-right"></i> '.Yii::t('order','Overview'), 'url'=>array('/admin/promos/view'),'visible'=>RbacUserAccess::isChecked('admin','promos',Yii::app()->user->id,'read_p')),
					array('label'=>'<i class="fa fa-caret-right"></i> '.Yii::t('global','Create').' '.Yii::t('order','Promo'), 'url'=>array('/admin/promos/create'),'visible'=>RbacUserAccess::isChecked('admin','promos',Yii::app()->user->id,'create_p')),
				),
				'itemOptions'=>array('class'=>'nav-parent'),
				'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
				'visible'=>RbacUserAccess::isChecked('admin','promos',Yii::app()->user->id,'read_p')
			),
			array('label'=>'<i class="fa fa-bar-chart-o"></i> <span>'.Yii::t('menu','Reports').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>array(
					array('label'=>'<i class="fa fa-caret-right"></i> Pendapatan', 'url'=>array('/admin/reports/view'),'visible'=>RbacUserAccess::isChecked('admin','reports',Yii::app()->user->id,'read_p')),
					array('label'=>'<i class="fa fa-caret-right"></i> Analitik', 'url'=>array('/admin/reports/analytic'),'visible'=>RbacUserAccess::isChecked('admin','reports',Yii::app()->user->id,'read_p')),
				),
				'itemOptions'=>array('class'=>'nav-parent'),
				'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
				'visible'=>RbacUserAccess::isChecked('admin','reports',Yii::app()->user->id,'read_p')
			),
			array('label'=>'<i class="glyphicon glyphicon-cog"></i> <span>'.Yii::t('global','Manage').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>array(
					array('label'=>'<i class="fa fa-caret-right"></i> Users', 'url'=>array('/admin/users/view'),'visible'=>RbacUserAccess::isChecked('admin','users',Yii::app()->user->id,'read_p')),
					array('label'=>'<i class="fa fa-caret-right"></i> Parameter', 'url'=>array('/admin/params/view'),'visible'=>RbacUserAccess::isChecked('admin','params',Yii::app()->user->id,'read_p')),
				),
				'itemOptions'=>array('class'=>'nav-parent'),
				'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
				'visible'=>RbacUserAccess::isChecked('admin','users',Yii::app()->user->id,'read_p') || RbacUserAccess::isChecked('admin','params',Yii::app()->user->id,'read_p')
			),
			array('label'=>'<i class="glyphicon glyphicon-off"></i> <span>Logout</span>', 'url'=>array('/admin/default/logout'),'visible'=>!Yii::app()->user->isGuest),
		),
		'htmlOptions'=>array('class'=>'nav nav-pills nav-stacked nav-bracket'),
		'encodeLabel'=>false,
		'submenuHtmlOptions'=>array('class'=>'children')
	));
?>
