<?php
/*
视图引擎工厂类
*/
class VIEW{
	public static $view;  //定义一个静态的view变量，用于存储将视图操作类实例化后的对象
	
	/*
	视图操作类初始化和配置
	*/
	public static function init($viewType,$config){
		self::$view=new $viewType;  //实例化视图操作类
		
		//进行视图操作类配置
		foreach($config as $key=>$val){
			self::$view->$key=$val;
		}
	}
	
	/*
	assign()方法的重写
	*/
	public static function assign($name,$data){
            self::$view->assign($name,$data);   //向模板文件中写入动态信息		
	}
	
	/*
	显示模板文件的方法
	*/
	public static function display($url){
		self::$view->display($url);  //显示编写好的模板文件
	}
} 