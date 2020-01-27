<?php

require_once("../../includes/config.inc.php");
require_once("../../includes/my_func.inc.php");

if (isset($_POST['table']) && isset($_POST['id']) && isset($_POST['importance'])){
    $table = $_POST['table'];
    $id = $_POST['id'];
    $importance = $_POST['importance'];
    $param = $table.'_id';
    $url = "/admin/newslist.php";

    //公告管理员权限检测
    if ($table == "news" && !authNewsManage()){
        $result = ['code' => 0, 'msg' => '修改失败！无权限！', 'url' => $url];
        echo json_encode($result);
        exit;
    }

    $sql = "UPDATE $table SET importance = $importance WHERE $param = '$id'";
    $result = pdo_query($sql);
    if ($result){
        $result = ['code' => 1, 'msg' => '修改成功！', 'url' => $url];
    }else{
        $result = ['code' => 0, 'msg' => '修改失败！', 'url' => $url];
    }
}else{
    $result = ['code' => 0, 'msg' => '修改失败！', 'url' => $url];
}
echo json_encode($result);

?>