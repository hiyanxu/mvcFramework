<?php
$currentdir=dirname(__FILE__);
require_once($currentdir."/require.list.php");
foreach($paths as $key=>$val){
	require_once($currentdir."/".$val);
}

class PC{
	public static $controller;
	public static $method;
	public static $config;

	public static function init_db(){
		DB::init(self::$config["dbConfig"]["dbType"],self::$config["dbConfig"]);
	}

	public static function init_view(){
		VIEW::init("Smarty",self::$config["viewConfig"]);
	} 

	public static function init_controller(){
		self::$controller=isset($_GET["controller"])?addslashes($_GET["controller"]):"index";
	}

	public static function init_method(){
		self::$method=isset($_GET["method"])?addslashes($_GET["method"]):"homePage";
	}

	public static function run($config){
		self::$config=$config;
		self::init_db();
		self::init_view();
		self::init_controller();
		self::init_method();

		C(self::$controller,self::$method);
	}
}