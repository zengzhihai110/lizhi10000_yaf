<?PHP
/**
 * Short description for Index.php
 *
 *
 *
 *
 * @package Index
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

class IndexController extends BaseController{
	public function indexAction(){
		header("Content-type:text/html;charset=utf-8");
		echo "<p style='text-align:center'>欢迎访问：<a href='http://lizhi10000.com'>lizhi10000.com</a> by 追麾</p>";
	}

}


