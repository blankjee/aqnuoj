<?php
require_once("../../includes/config.inc.php");
require_once ("../../includes/my_func.inc.php");

if(!authProblemManage()){
    //如果不具有权限（高级权限&问题编辑权限），提示登录。
    $result = ['code' => 0, 'msg' => '添加失败！您没有权限！', 'url' => '/admin/problemlist.php'];
    echo json_encode($result);
    return ;
}
if(function_exists('system')){
    $id=intval($_POST['id']);

    $basedir = "$OJ_DATA/$id";
    if(strlen($basedir)>16){
        system("rm -rf $basedir");
    }
    $sql="delete FROM `problem` WHERE `problem_id`=?";
    pdo_query($sql,$id) ;
    $sql = "delete from `privilege` where `rightstr`=? ";
    pdo_query($sql, "p$id");
    $sql = "update solution set problem_id=0 where `problem_id`=? ";
    pdo_query($sql, $id);

    $sql="select max(problem_id) FROM `problem`" ;
    $result=pdo_query($sql);
    $row=$result[0];
    $max_id=$row[0];
    $max_id++;
    if($max_id<1000)$max_id=1000;

    $sql="ALTER TABLE problem AUTO_INCREMENT = $max_id";
    pdo_query($sql);

    $res = ['code' => 1, 'msg' => '删除成功！', 'url' => '/admin/problemlist.php'];

}else{
    $res = ['code' => 0, 'msg' => '删除失败！', 'url' => '系统错误！'];
}
echo json_encode($res);
