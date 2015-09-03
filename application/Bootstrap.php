<?PHP
/**
 * Short description for Bootstrap.php
 *
 *
 *
 *
 * @package Bootstrap
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

class Bootstrap extends Yaf_Bootstrap_Abstract{
	/**
	 *初始化加载
	 */
	public function _initLoader(Yaf_Dispatcher $dispatcher){
		//配置本地类库前缀
        //Yaf_Loader::getInstance()->registerLocalNameSpace(
        //	array("controllers")
        //);
		
		//注册插件
        $plugin_router = new RouterPlugin();
        $dispatcher->registerPlugin($plugin_router);       
		
	}

	/**
	 * 初始化配置
	 */
	public function _initConfig() {
        $config = Yaf_Application::app()->getConfig();
		Yaf_Registry::set("config", $config);
	}


}


