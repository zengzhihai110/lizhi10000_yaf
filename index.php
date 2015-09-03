<?PHP
/**
 * Short description for index.php
 *
 *
 *
 *
 * @package index
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

error_reporting(E_ALL);
define("APP_PATH", realpath(dirname(__FILE__).'/'));
define('APP_VIEW',APP_PATH.'/application/views/');
define('APP_STATIC','/public/static/default/');
if(isset($_SERVER['setting'])){
	$app  = new Yaf_Application(APP_PATH . "/conf/local.ini");
}else{
	$app  = new Yaf_Application(APP_PATH . "/conf/application.ini");
}
$app->bootstrap()->run();

