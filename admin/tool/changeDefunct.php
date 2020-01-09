<?php

    require_once("../../includes/config.inc.php");
    if (isset($_POST['table']) && isset($_POST['id']) && isset($_POST['defunct'])){
        $table = $_POST['table'];
        $id = $_POST['id'];
        $defunct = $_POST['defunct'];
        $param = $table.'_id';
        $url = "/admin/" . $table . "list.php";

        $sql = "UPDATE $table SET defunct = $defunct WHERE $param = '$id'";
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