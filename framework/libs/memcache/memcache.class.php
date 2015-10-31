<?php
/*
memcache缓存操作类
*/
class Mem_Cache{
	public static $mem;
	public static $config;
	/*
	memcache操作类的构造方法
	*/
	function __construct($config){
		self::$mem=new Memcache();
		self::$config=$config;
	}

	/*
	memcache连接方法
	参数：连接配置信息$config
	*/
	public static function mConnect(){
		if(!self::$mem->connect(self::$config["mhost"],self::$config["mport"])){
			return false;
		}
	}

	/*
	memcache缓存add方法
	参数：
		$key——将要分配给变量的key值
		$value——将要被存储的变量
	*/
	public static function mAdd($key,$value){
		if(!self::$mem->add($key,$value,MEMCACHE_COMPRESSED,self::$config["mtimeout"])){
			return false;
		}
	}

	/*
	memcache缓存set方法
	set方法等于add方法和replace方法的集合体
	参数：
		$key——将要被设置的变量的key值
		$value——将要被设置的变量的value值
	*/
	public static function mSet($key,$value){
		if(!self::$mem->set($key,$value,MEMCACHE_COMPRESSED,self::$config["mtimeout"])){
			return false;
		}
	}
	
	/*
	memcache缓存获取get方法
	参数：
		$key——将要取出的变量的key值
	*/
	public static function mGet($key){
		$get_val=self::$mem->get($key);
		if(!$get_val){
			return 0;  //0表示当前缓存服务器中没有对应为key的缓存数据
		}
		else{
			return $get_val;
		}
	}

	/*
	memcache缓存删除del方法
	参数：
		$key——将被删除的缓存服务器中对应为key的缓存数据
	*/
	public static function mDel($key){
		self::$mem->delete($key,0);
	}
}