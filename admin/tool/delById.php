<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");

    $table = $_POST['table'];
    $idName = $table . '_id';
    $id = $_POST['id'];
    $url = '/admin/' . $table . 'list.php';
    //对用户表进行特殊处理
    if ($table == 'user') $table = 'users';
    //对权限表进行特殊处理
    if ($table == 'privilege') $idName = 'id';
    $sql = "DELETE FROM $table WHERE $idName = '$id'";
    $result = pdo_query($sql);
    if ($result){
        $res = ['code' => 1, 'msg' => '删除成功！', 'url' => $url];
    }else{
        $res = ['code' => 0, 'msg' => '删除失败！请重试...', 'url' => $url];
    }
    echo json_encode($res);
?>