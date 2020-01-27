<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");

    $table = $_POST['table'];
    $idName = $table . '_id';
    $id = $_POST['id'];
    $url = '/admin/' . $table . 'list.php';
    //对用户表进行特殊处理
    if ($table == 'user') {
        $table = 'users';
        if (!authUserManage()){
            $res = ['code' => 0, 'msg' => '删除失败！您无权限！', 'url' => $url];
            echo json_encode($res);
            exit;
        }
    }
    //对权限表进行特殊处理
    if ($table == 'privilege') {
        $idName = 'id';
        if (!authUserManage()){
            $res = ['code' => 0, 'msg' => '删除失败！您无权限！', 'url' => $url];
            echo json_encode($res);
            exit;
        }
    }
    //对问题管理权限进行检测
    if ($table == 'problem' && !authProblemManage()){
        $res = ['code' => 0, 'msg' => '删除失败！您无权限！', 'url' => $url];
        echo json_encode($res);
        exit;
    }
    //对竞赛管理权限进行检测
    if ($table == 'contest' && !authContestManage()){
        $res = ['code' => 0, 'msg' => '删除失败！您无权限！', 'url' => $url];
        echo json_encode($res);
        exit;
    }

    //对公告管理权限进行检测
    if ($table == 'news' && !authNewsManage()){
        $res = ['code' => 0, 'msg' => '删除失败！您无权限！', 'url' => $url];
        echo json_encode($res);
        exit;
    }

    $sql = "DELETE FROM $table WHERE $idName = '$id'";
    $result = pdo_query($sql);
    if ($result){
        $res = ['code' => 1, 'msg' => '删除成功！', 'url' => $url];
    }else{
        $res = ['code' => 0, 'msg' => '删除失败！请重试...', 'url' => $url];
    }
    echo json_encode($res);
?>