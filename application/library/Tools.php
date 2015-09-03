<?php
/**
 * Short description for Tools.php
 *
 *
 *
 *
 * @package Tools
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

class Tools{

	/**
	 * 判定是否是手机端
	 *
	 */
	public static function isMobile() {
		$isMobile = false;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array('Nokia','iPhone', 'iPod', 'Android', 'HTC', 'SonyEricsson', 'Sony', 'Motorola','Symbian', 'Mobile Explorer', 'Open Wave', 'Opera Mini', 'Palm', 'Avantgo', 'Xiino', 'Palmscape', 'Ericsson','BlackBerry', 'SmartPhone', 'WindowsCE', 'Unknown Mobile', 'Tablet');
		foreach ($mobile_agents as $device) {
            if (stristr($user_agent, $device)) {
                $isMobile = true;
                break;
            }
        }
        return $isMobile;
    }




    /**
     * 获取 ip
     * @return string
     */
    static public function getIp(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return $ip;
    }

    /**
     * json 数据输出
     * @param string $msg
     * @param int $type
     * @param array $data
     */
    static public function sendResponse($msg='操作失败，请稍后再试~',$type=0,$data=array()){
        echo json_encode(array('msg'=>$msg,'type'=>$type,'data'=>$data));
        exit;
    }


    //输出过滤
    static public function setHtmlString($data) {
        $string = '';
        if (is_array($data)) {
            if (count($data)) return $data;
            foreach ($data as $key=>$value) {
                $string[$key] = self::setHtmlString($value);  //递归
            }
        } elseif (is_object($data)) {
            foreach ($data as $key=>$value) {
                $string->$key = self::setHtmlString($value);  //递归
            }
        } else {
            $string = htmlspecialchars($data);
        }
        return $string;
	}




	//判定管理员是否登录
	static public function adminLogining(){
		if(!Yaf_Session::getInstance()->has('admin_user') || !Yaf_Session::getInstance()->has('admin_user_id')){
			return false;
		}
		return true;
	}




	/**
	 * 密码加密方式
	 */
	public static function addPass($user,$pass){
		return sha1($user.sha1($pass));
	}



}
