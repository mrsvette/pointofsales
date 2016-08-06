<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii-1.1.16/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
//ini_set('display_errors','Off');

require_once($yii);
date_default_timezone_set('Asia/Jakarta');
Yii::createWebApplication($config)->run();
