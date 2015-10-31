<?php
/*
数据库引擎的工厂类
*/
class DB{
	public static $db;  //用于存放初始化的数据库操作类的对象
	public static $mem;  //用于存放初始化的缓存操作类的对象
	
	/*
	初始化：
	用于初始化数据库操作类对象和连接数据库
	*/
	public static function init($dbType,$config){
		self::$db=new $dbType;
		self::$db->connect($config);

		self::$mem=new Mem_Cache($config);
		self::$mem->mConnect();
	}

	/*
	执行sql语句的方法
	成功则返回查询后的资源信息
	否则返回错误信息
	*/
	public static function query($sql){
		return self::$db->query($sql);		
	}

	/*
	查看缓存服务器中是否存在数据的方法
	若存在则返回1；若不存在则返回0
	*/
	public static function m_is_exit($key){
		$is_exit=self::$mem->mGet($key);
		if($is_exit==0){
			return 0;  //0表示当前缓存服务器不存在$key的缓存数据
		}
		else{
			return 1;  //1表示当前缓存服务器存在$key的缓存数据
		}
	}

	/*
	将传入的sql语句md5加密
	*/
	public function getKeyBySql($sql){
		return md5($sql);
	}

	/*
	获取多条数据的方法
	成功则返回多条数据的数组
	否则返回错误信息
	*/
	public static function fetchAll($sql){
		//$query=self::query($sql);
		//return self::$db->fetchAll($query);

		$key=md5($sql);  //获取要执行的sql语句的md5加密后的$key
		$is_exit=self::m_is_exit($key);
		if($is_exit==0){  //当前不存在$key的缓存
			$query=self::query($sql);
			$data=self::$db->fetchAll($query);
			self::$mem->mSet($key,$data);
			return $data;
		}
		else{
			//当前缓存中存在，则从缓存中获取数据
			return self::$mem->mGet($key);
		}
	}
	
	/*
	获取一条数据的方法
	成功则返回一条数据的数组
	否则返回错误信息
	*/
	public static function fetchOne($sql){
		//$query=self::query($sql);
		//return self::$db->fetchOne($sql);

		$key=md5($sql);  //获取要执行的sql语句的md5加密后的$key
		$is_exit=self::m_is_exit($key);
		if($is_exit==0){  //当前不存在$key的缓存
			$query=self::query($sql);
			return self::$db->fetchOne($query);
		}
		else{
			//当前缓存中存在，则从缓存中获取数据
			return self::$mem->mGet($key);
		}
	}

	/*
	插入函数：
	成功则返回插入行数的主键id
	否则返回错误信息
	*/
	public static function insert($table,$arr){
		return self::$db->insert($table,$arr);
	}

	/*
	更新函数：
	成功则返回受影响的行数的主键id
	则否返回错误信息
	*/
	public static function update($table,$arr,$where){
		return self::$db->update($table,$arr,$where);
	}
	
	/*
	删除函数：
	成功则返回受影响的行数
	否则返回错误信息
	*/
	public static function del($table,$where){
		return self::$db->del($table,$where);
	}
        
        public static function findResult($sql, $row = 0, $filed = 0){
		$query = self::$db->query($sql);
		return self::$db->findResult($query, $row, $filed);
	}
}