<?php
/**
 * Short description for Route.php
 *
 *
 *
 *
 * @package Route
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

class RouterPlugin extends Yaf_Plugin_Abstract {

    //是否是内网域名访问
    protected $internal = false;
    //当前域名
    protected $host     = '';
    //当前模块
    protected $module   = '';
	
	/**
     * 路由开始之前，加载适合的路由规则
     * @param Yaf_Request_Abstract $request
     * @param Yaf_Response_Abstract $response
     */
	public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	    if(!$request->isCli()) {
	    }
	}

    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
       
    }

    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        if($request->isCli()) {
            $request->setModuleName('Cli');
            switch ($_SERVER['argc']) {
            	case 2 :
            	    if (preg_match("/^[a-zA-Z0-9\.]*$/", $_SERVER['argv'][1]) && 
            	        $dt = explode('.',$_SERVER['argv'][1]) ) {
            	        if ($dt[0] && $dt[1]) {
            	            $request->setControllerName ($dt[0]);
            	            $request->setActionName ($dt[1]);
            	            break;
            	        }
            	    }
            	default:
            	    $request->setControllerName ('Error');
            	    $request->setActionName ('run');
            }
            return;
        }

		/*
		 * 是否登录
        if (!Tools::adminLogining()) {
             $request->setModuleName('Index');
             $request->setControllerName('Index');
			 $request->setActionName('login');
             return;
		}
		 */
    }
    
    //分发循环结束
    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

    }

}

