<?PHP
/**
 * Short description for Error.php
 *
 *
 *
 *
 * @package Error
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

class ErrorController extends BaseController{
	public function errorAction($exception) {
        echo "<pre>";
        print_r($exception);
    }
}

