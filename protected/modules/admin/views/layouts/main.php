<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="robots" content="noindex">

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/bracket/css/style.default.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/bracket/css/jquery.datatables.css" />	

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class="leftpanel-collapsed stickyheader">
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<section>
	<div class="leftpanel sticky-leftpanel">
		<div class="logopanel">
		    <h3><span style="text-transform:uppercase;"><?php echo Yii::app()->name;?></span></h3>
			<div class="clearfix"></div>
		</div><!-- logopanel -->
    
    	<div class="leftpanelinner">
			<!-- This is only visible to small devices -->
			<?php if(!Yii::app()->user->isGuest):?>
		    <div class="visible-xs hidden-sm hidden-md hidden-lg">   
		        <div class="media userlogged">
		            <div class="media-body">
		                <h4><?php echo Yii::app()->user->name;?></h4>
		            </div>
		        </div>
		      
		        <h5 class="sidebartitle actitle">Account</h5>
				<?php
					$this->widget('zii.widgets.CMenu', array(
						'items'=>array(
							array('label'=>'<i class="glyphicon glyphicon-cog"></i> Ubah Password', 'url'=>array('/admin/profile/changePassword')),
							array('label'=>'<i class="glyphicon glyphicon-log-out"></i> LogOut', 'url'=>array('/admin/default/logout')),
						),	
						'htmlOptions'=>array('class'=>'dropdown-menu dropdown-menu-usermenu pull-right'),
						'encodeLabel'=>false,
					));
				?>
		    </div>
		  	<?php endif;?>
		  	<h5 class="sidebartitle">Navigation</h5>
			<?php $this->widget('adminMainMenu');?>
    	</div><!-- leftpanelinner -->
	</div><!-- leftpanel -->

	<div class="mainpanel">
    	<div class="headerbar">
      		<a class="menutoggle menu-collapsed"><i class="fa fa-bars"></i></a>
			<?php if(!Yii::app()->user->isGuest):?>
			<div class="header-right">
		    	<ul class="headermenu">
					<li style="border:none;">
		        		<div class="btn-group">
		          			<button type="button" class="btn btn-default dropdown-toggle">
		            			<i class="glyphicon glyphicon-user"></i> <?php echo (!Yii::app()->user->isGuest)? ucwords(Yii::app()->user->name) : '';?>
		            		</button>
		          			<button type="button" class="btn btn-default dropdown-toggle">
		            			<i class="glyphicon glyphicon-time"></i> <span id="server-time"></span> <?php echo date("d-m-Y");?>
		            		</button>
		        		</div>
		        		<div class="btn-group">
		          			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		            			<i class="glyphicon glyphicon-cog"></i> Setting
		            		<span class="caret"></span>
							</button>
		          			<?php
							$this->widget('zii.widgets.CMenu', array(
								'items'=>array(
									array('label'=>'<i class="glyphicon glyphicon-cog"></i> Ubah Password', 'url'=>array('/admin/profile/changePassword')),
									//array('label'=>'<i class="glyphicon glyphicon-log-out"></i> LogOut', 'url'=>array('/admin/default/logout')),
								),
								'htmlOptions'=>array('class'=>'dropdown-menu dropdown-menu-usermenu pull-right'),
								'encodeLabel'=>false,
							));
							?>
		        		</div>
		      		</li>
				</ul>
      		</div><!-- header-right -->
			<?php endif;?>
		</div><!-- headerbar -->
		<div class="contentpanel">
			<div class="row">
				<?php echo $content; ?>
			</div>
		</div><!-- contentpanel-->
	</div><!-- mainpanel -->
	<div class="rightpanel">
    	
	</div><!-- rightpanel -->
</section>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/bracket/js/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/bracket/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/bracket/js/jquery.datatables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/bracket/js/chosen.jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/bracket/js/jsclock-0.8.min.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/bracket/js/custom.js"></script>
<?php $this->widget('ext.widgets.loading.LoadingWidget');?>
<?php $this->widget('application.extensions.moneymask.MMask');?>
