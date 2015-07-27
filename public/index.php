<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
if( time() > strtotime('02 March 2014') ) {
  $config=dirname(__FILE__).'/../protected/config/current.php';
}
else{
  $config=dirname(__FILE__).'/../protected/config/main.php';
#  $config=dirname(__FILE__).'/../protected/config/current.php';
}

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
