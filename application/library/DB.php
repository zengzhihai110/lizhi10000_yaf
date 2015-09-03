<?php
/**
 * Short description for DB.php
 *
 *
 *
 *
 * @package DB
 * @author  <lizhi10000@yeah.net>
 * @version 0.1
 * @copyright (C) 2015  <lizhi10000@yeah.net>
 * @license MIT
 */
class DB{
	static private $writeInstance = null;
	static private $readInstance = null;
	private $pdo=null;
	private $dbConfig = array();

	static public function getDbWrite(){
		if (!self::$writeInstance instanceof self){
			self::$writeInstance=new self('write');
		}
		return self::$writeInstance;
	}

	static public function getDbRead(){
		if (!self::$readInstance instanceof self){
			self::$readInstance=new self('read');
		}
		return self::$readInstance;
	}
	
	private function __clone(){}
	
	private function __construct($type){
		try {
			$config = Yaf_Registry::get("config");
			$dbConfig = $config->db;
			if($type == 'read'){
				$this->dbConfig = $dbConfig['read'];
			}else{
				$this->dbConfig = $dbConfig['write'];
			}
			$this->pdo=new PDO('mysql:host='.$this->dbConfig['host'].';dbname='.$this->dbConfig['dbname'].';port='.$this->dbConfig['port'], $this->dbConfig['username'], $this->dbConfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$this->dbConfig['charset']));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			exit('数据库连接错误：'.$e->getMessage());
		}
	}


	//防止mysql链接超时断掉
	private function tryMysqlAgain(){
		if(!$this->pdo){
			$this->pdo=new PDO($this->dbConfig['dns'], $this->dbConfig['username'], $this->dbConfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$this->dbConfig['charset']));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}

	
	public function add($table,$adddata){
		$dataFields = $dataValues = $paramArr = array();
		foreach ($adddata as $key=>$value){
			$dataFields[] = $key;
			$dataValues[] = ':'.$key;
			$paramArr[':'.$key] = $value;
		}
		$dataF=implode(',', $dataFields);
		$dataV=implode(',', $dataValues);
		$sql="INSERT INTO $table ($dataF) VALUES ($dataV)";
		$stmt = $this->pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute($paramArr);
		return $this->pdo->lastInsertId();
	}
	
	
	protected function delete($table,$whereData){
		$where = '';
		$paramArr = array();
		foreach ($whereData as $key=>$value){
			$where.= $key.' = :'.$key.' and ';
			$paramArr[':'.$key] = $value;
		}
		$where=substr($where, 0,-4);
		$sql="DELETE FROM $table where $where LIMIT 1";
		$stmt = $this->pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute($paramArr);
		return $stmt->rowCount();
	}
	
	protected function update($table,$updateData,$whereData){
		$where = $setData = '';
		$dataFields = $dataValues = $paramArr = array();
		foreach ($whereData as $key=>$value){
			$where.= $key.' = :'.$key.' and ';
			$paramArr[':'.$key] = $value;
		}
		foreach ($updateData as $key=>$value){
			$setData.= $key.' = :'.$key.' , ';
			$paramArr[':'.$key] = $value;
		}
		$setData=substr($setData, 0,-2);
		$where=substr($where, 0,-4);
		$sql="UPDATE $table SET $setData WHERE $where LIMIT 1";
		$stmt = $this->pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute($paramArr);
		return $stmt->rowCount();
	}
	
	//获得总记录数
	protected function total($_tables,$_param=array()){
		$_where=$_And=$_or='';
		if (Validate::isArray($_param)&&!Validate::isNullArray($_param)){
			if (isset($_param['where'])&&Validate::isArray($_param['where'])) {
				foreach ($_param['where'] as $_key=>$_value) {
					$_And.="$_key='$_value' AND ";
				}
				$_where = 'WHERE '.substr($_And, 0, -4);
			} elseif (isset($_param['where'])) {
				$_where = 'WHERE '.$_param['where'];
			}
			$_or=isset($_param['or'])?$_param['or']:'';
		}
		$_sql="SELECT COUNT(*) as count FROM $_tables[0] $_where $_or";
		$_stmt=$this->execute($_sql);
		return $_stmt->fetchObject()->count;
	}


	//获取一个值
	public function queryScalar($sql,$paramArr = array()){
		$stmt = $this->pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute($paramArr);
		$res = $stmt->fetchColumn(0);
		return $res;
	}



	//执行sql 返回一条数据
	public function queryRow($sql,$paramArr = array()){
		$stmt = $this->pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute($paramArr);
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		return $res;
	}

	//返回一列
	public function queryColumn($sql,$paramArr = array()){
		$stmt = $this->pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute($paramArr);
		$res = array();
		while($obj = $stmt->fetchColumn()){
			$res[] = $obj;
		}
		return $res;
	}


	//返回所有
	public function queryAll($sql,$paramArr = array()){
		$stmt = $this->pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute($paramArr);
		$res = $stmt->fetchAll();
		return $res;
	}

	//计数器自增
	public function updateCounters($table,Array $counters = array(),$whereArr = array()){
		$sql = "update $table set ";
		$paramArr = array();
		foreach($counters as $k=>$v){
			$sql .= $k.'='.$k.' + :'.$k.',';
			$paramArr[':'.$k] = $v;
		}
		$sql = substr($sql,0,strlen($sql)-1);
		$sql .= ' where ';
		foreach($whereArr as $key=>$value){
			$sql .= $key.'=:'.$key.',';
			$paramArr[':'.$key] = $value;
		}
		$sql = substr($sql,0,strlen($sql)-1);
		$stmt = $this->pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute($paramArr);
		$res = $stmt->rowCount();
		return $res;	
	}

	
}
