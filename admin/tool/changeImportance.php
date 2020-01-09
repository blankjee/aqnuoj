<?php

require_once("../../includes/config.inc.php");
if (isset($_POST['table']) && isset($_POST['id']) && isset($_POST['importance'])){
    $table = $_POST['table'];
    $id = $_POST['id'];
    $importance = $_POST['importance'];
    $param = $table.'_id';

    $sql = "UPDATE $table SET importance = $importance WHERE $param = '$id'";
    $result = pdo_query($sql);
    if ($result){
        $result = ['code' => 1, 'msg' => '修改成功！', 'url' => "/admin/newslist.php"];
    }else{
        $result = ['code' => 0, 'msg' => '修改失败！', 'url' => "/admin/newslist.php"];
    }
}else{
    $result = ['code' => 0, 'msg' => '修改失败！', 'url' => "/admin/newslist.php"];
}
echo json_encode($result);

?>