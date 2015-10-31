<?php
/*
 * 实例化控制器对象，且访问控制器对象的方法
 */
function C($controller,$method){
	require_once("libs/Controller/".$controller."Controller.class.php");
	$controllerClass=$controller."Controller";
	$obj=new $controllerClass;
	$method=$method."()";
	eval('$obj->'.$method.';');	
}

/*
 * 实例化模型Model对象
 */
function M($model){
    require_once("libs/Model/".$model."Model.class.php");
    $modelClass=$model."Model";
    $obj=new $modelClass;
    return $obj;
}
