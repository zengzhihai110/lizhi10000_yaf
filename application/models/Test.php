<?PHP
/**
 * Short description for Admin.php
 *
 *
 *
 *
 * @package Admin
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

class TestModel extends BaseModel{
	public $table = 'test';

	public $rules = array(
		array('admin','minlength',2,'管理员名最小长度为6'),
		array('admin','maxlength',40,'管理员名最大长度为40'),
		array('admin','require','管理员名必填'),
		array('pass','require','密码必填'),
		array('pass','minlength',6,'密码最小长度6位'),
		array('qpass','require','确认密码必填'),
		array('qpass','minlength',6,'确认密码最小长度6位'),
		array('pass','compare','qpass','密码和确认密码必须一致'),
		array('admin','unique','管理员名已经存在'),
	);

	public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}


