<?php
/*
本部分主要是对mysql数据库操作的一些列封装
*/
class mysql{
	/*
	报错函数
	参数：$error
	*/
	function err($error){
		die("对不起，您的操作有误，错误原因为：".$error);   //die()函数有两种作用：输出和终止，相当于echo和exit的组合
	}
	
	/*
	数据库连接方法
	参数：连接配置的一个数组
	*/
	function connect($config){
		if(!$con=mysql_connect($config["dbHost"],$config["dbUser"],$config["dbPwd"])){
			$this->err(mysql_error());
		}
		if(!mysql_select_db($config["dbName"],$con)){
			$this->err(mysql_error());
		}
		mysql_query("set names ".$config["dbCharset"]);
	}
	
	/*
	数据库查询方法
	参数：sql语句$sql
	返回：查询获得的资源
	*/
	function query($sql){
		$query=mysql_query($sql);
		if(!$query){
			$this->err(mysql_error());
		}
		else{
			return $query;
		}
	}
	
	/*
	获取全部信息的方法
	参数：$query——执行sql语句后查询获得的资源
	*/
	function fetchAll($query){
		while($rs=mysql_fetch_array($query,MYSQL_ASSOC)){
			$list[]=$rs;
		}
		return isset($list)?$list:"";
	}
	
	/*
	获取一条数据的方法
	参数：$query——执行sql语句后查询获得的资源
	*/
	function fetchOne($query){
		$rs=mysql_fetch_array($query,MYSQL_ASSOC);
		return $rs;
	}
	
	/*
	insert into table (a,b,c) values(av,bv,cv)
	数据插入方法：
	参数：$table——要插入数据的表名
		  $arr——要插入的数据
	*/
	function insert($table,$arr){            
		foreach($arr as $key=>$val){
			$value=mysql_real_escape_string($val);
			$keyArr[]=$key;
			$valArr[]="'".$value."'";
		}
		$keyStr=implode(",",$keyArr);  //将对应参数转换为字符串
		$valStr=implode(",",$valArr);                
                $sql = "insert into ".$table."(".$keyStr.") values(".$valStr.")";
		$this->query($sql);
		return mysql_insert_id();  //mysql_insert_id()返回上一步insert语句产生的id
	}
	
	/*
	update table set a=av,b=bv,c=cv where ...
	参数：$table——被更新数据所在表名
		  $arr——更新后的数据
		  $where——update语句的条件
	*/
	function update($table,$arr,$where){
		foreach($arr as $key=>$val){
			$value=mysql_real_escape_string($val);
			$keyToVal[]=$key."='".$val."'";
		}
		$keyToValStr=implode(",",$keyToVal);
		$sql="update $table set ".$keyToValStr." where $where";                
		$this->query($sql);
                return mysql_affected_rows();
	}

	/*
	delete from table where ...
	*/
	function del($table,$where){
		$sql="delete from $table where $where";
		$this->query($sql);
	}
        
        /**
	*指定行的指定字段的值
	*
	*@param source $query sql语句通过mysql_query执行出的来的资源
	*return array   返回指定行的指定字段的值
	**/
	function findResult($query, $row = 0, $filed = 0){
		$rs = mysql_result($query,  $row, $filed);
		return $rs;
	}
}