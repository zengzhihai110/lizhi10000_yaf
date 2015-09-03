<?PHP
/**
 * Short description for Model.php
 *
 *
 *
 *
 * @package Model
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */

class BaseModel extends DB{
	public static $models = array();
	protected $rDb = null;
	protected $wDb = null;

	public function __construct(){
		$this->rDb = parent::getDbRead();
		$this->wDb = parent::getDbWrite();
	}

	//实例化自身
    public static function model($className=__CLASS__)
    {
        if(isset(self::$models[$className]))
            return self::$models[$className];
        else
        {
            $model=self::$models[$className]=new $className(null);
            return $model;
        }
    }


	//新增
    public function save(Array $saveData) {
		if(($msgArr = $this->beforeSave(1)) && is_array($msgArr)){
			return $msgArr;
		}
        return $this->wDb->add($this->getTable(),$saveData);
    }

	//修改
	public function modify(Array $saveData,Array $whereData,$isCheck = true){
		if($isCheck){
			if(($msgArr = $this->beforeSave(2)) && is_array($msgArr)){
				return $msgArr;
			}
		}
        return $this->wDb->update($this->getTable(),$saveData,$whereData);
	}


    private function beforeSave($type){
        if($model = self::$models[get_called_class()]){
			if($model->rules){
				foreach($model->rules as $k=>$v){
					if(count($v) == 3 || count($v) == 4 ){
						switch ($v[1]){
							case 'minlength':
								if(!isset($_POST[$v[0]]) || mb_strlen(trim($_POST[$v[0]]),'utf-8') < $v[2]){
									return array('fail'=>$v[3]);
								}
								break;
							case 'maxlength':
								if(!isset($_POST[$v[0]]) || mb_strlen(trim($_POST[$v[0]]),'utf-8') > $v[2]){
									return array('fail'=>$v[3]);
								}
								break;
							case 'require':
								if(!isset($_POST[$v[0]]) || empty($_POST[$v[0]])){
									return array('fail'=>$v[2]);
								}
								break;
							case 'compare':
								if(!isset($_POST[$v[0]]) || trim($_POST[$v[0]]) != trim($_POST[$v[2]])){
									return array('fail'=>$v[3]);
								}
								break;
							case 'unique':
								$return = '';
								if($type == 1){
									$sql = "select count(1) as c from ".$this->getTable()." where $v[0]=:$v[0]";
									$return = $this->rDb->queryScalar($sql,array($v[0]=>$_POST[$v[0]]));
								}elseif($type == 2){
									$sql = "select count(1) as c from ".$this->getTable()." where $v[0]=:$v[0] and id != :id";
									$return = $this->rDb->queryScalar($sql,array($v[0]=>$_POST[$v[0]],'id'=>Yaf_Dispatcher::getInstance()->getRequest()->getParam('id')));
								}
								if($return){
									return array('fail'=>$v[2]);
								}
								break;
						}
					}
				}
            }
		}
        return true;
    }

    private function getTable(){
        $className = get_called_class();
        if($model = self::$models[$className]){
            if($table = $model->table){
                return $table;
            }else{
                return strtolower(substr($className,0,strpos(strtolower($className),'model')));
            }
        }
        $model = self::$_models[$className]=new $className(null);
        if($table = $model->table){
            return $table;
        }else {
            return strtolower(substr($className,0,strpos(strtolower($className),'model')));
        }
    }


	//计数器
	public function counters($updateArr,$whereArr){
		return  $this->wDb->updateCounters($this->getTable(),$updateArr,$whereArr);
	}


	//列表公用
	public function getTableDataList($offset = null,$pageSize = null,$whereArr = array(),$order='order by id desc'){
		$where = $limit = '';
		$paramArr = array();
		foreach($whereArr as $k=>$v){
			$where = ' '.$k.' = '.':'.$k.' and'; 
			$paramArr[':'.$k] = $v;
		}
		if($where){
			$where = 'where'.substr($where,0,strlen($where) - 3);
		}
		if(is_numeric($offset) && is_numeric($pageSize)){
			$limit = "limit $offset,$pageSize";
		}
		$sql = "select * from ".$this->getTable()." $where $order $limit";
		return $this->rDb->queryAll($sql,$paramArr);
	}

	//计算总数据公用
	public function getTableCount($whereArr = array()){
		$where = '';
		$paramArr = array();
		foreach($whereArr as $k=>$v){
			$where = ' '.$k.' = '.':'.$k.' and'; 
			$paramArr[':'.$k] = $v;
		}
		if($where){
			$where = 'where'.substr($where,0,strlen($where) - 3);
		}
		$sql = "select count(1) from ".$this->getTable()." $where";
		return $this->rDb->queryScalar($sql,$paramArr);		
	}

	//通过条件获取单一数据
	public function getOneData($whereArr = array()){
		$where = '';
		$paramArr = array();
		foreach($whereArr as $k=>$v){
			$where = ' '.$k.' = '.':'.$k.' and'; 
			$paramArr[':'.$k] = $v;
		}
		if($where){
			$where = 'where'.substr($where,0,strlen($where) - 3);
		}
		$sql = "select * from ".$this->getTable()." $where";
		return $this->rDb->queryRow($sql,$paramArr);	
	}



	//删除一条数据公用
	public function delOneData($whereArr){
		return $this->wDb->delete($this->getTable(),$whereArr);
	}



}


