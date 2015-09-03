<?PHP
/**
 * Short description for Base.php
 *
 *
 *
 *
 * @package Base
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

abstract class BaseController extends Yaf_Controller_Abstract{
	public function init(){
		$this->getView()->assign(Data::$config);
		Yaf_Dispatcher::getInstance()->autoRender(FALSE);
	}
}

