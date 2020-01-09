<?php
function pdo_query($sql){
    $num_args = func_num_args();
    $args = func_get_args();       //获得传入的所有参数的数组
    $args=array_slice($args,1,--$num_args);

    /**
     * DB_HOST:数据服务器地址
     * DB_NAME:数据库名
     * DB_USER:数据库用户名
     * DB_PASS:数据库密码
     * dbh:是否已经连接数据库的标志
     */
    global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, $dbh;

    static 	$DB_HOST="localhost";
    static 	$DB_NAME="";
    static 	$DB_USER="";
    static 	$DB_PASS="";

    if(!$dbh){
        $dbh=new PDO("mysql:host=".$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET names utf8"));
    }
   
    $sth = $dbh->prepare($sql);
    $sth->execute($args);
    $result=array();
    if(stripos($sql,"select") === 0){
        $result=$sth->fetchAll();
    }else if(stripos($sql,"insert") === 0){
	$result=$dbh->lastInsertId();
    }else{
        $result=$sth->rowCount();
    }
    //print($sql);
    $sth->closeCursor();
    return $result;
}
?>
