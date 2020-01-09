<?php
    require_once("../../includes/config.inc.php");
    //加载一些公有方法
    require_once("../../includes/my_func.inc.php");

    // 校验POST是否接收
    if (isset($_POST['user_id']) && $_POST['user_id']) {
        $user_id = trim($_POST['user_id']);
        $email = trim($_POST['email']);
        $school = trim($_POST['school']);
        $sql = "UPDATE users SET email = '$email', school = '$school' WHERE user_id = '$user_id'";
        $result = pdo_query($sql);
        if ($result){
            //更新成功
            $res = ['code' => 1, 'msg' => '用户信息更新成功！', 'url' => '/home/userinfo.php'];
        }else{
            //更新失败
            $res = ['code' => 0, 'msg' => '用户信息更新失败！', 'url' => '/home/updateinfo.php'];
        }
    }else{
        $res = ['code' => 0, 'msg' => '系统错误！', 'url' => '/home/updateinfo.php'];
    }
    echo json_encode($res);
?>