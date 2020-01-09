<?php
    require_once("../../includes/config.inc.php");
    //加载一些公有方法
    require_once("../../includes/my_func.inc.php");

    // 校验POST是否接收
    if (isset($_POST['user_id']) && $_POST['user_id']) {
        $user_id = trim($_POST['user_id']);
        $old_pswd = trim($_POST['old_pswd']);
        $new_pswd = trim($_POST['new_pswd']);
        $re_new_pswd = trim($_POST['re_new_pswd']);
        if (strcmp($new_pswd, $re_new_pswd) != 0){
            //如果两次输入的密码不相同，则不进行下面的操作。
            $res = ['code' => 0, 'msg' => '两次输入的新密码不一致！', 'url' => '/home/updatepswd.php'];
        }else{
            //查询用户输入的旧密码是否正确
            $sql = "SELECT password FROM users WHERE user_id = '$user_id'";
            $result = pdo_query($sql);
            $nums = count($result);
            $row = count($result);
            if ($nums == 1){
                $passwd = $result[0]['password'];
//                var_dump($old_pswd);
//                var_dump($passwd);
                if (pwCheck($old_pswd, $passwd)){

                    //如果密码相同，则可以进行更新。
                    //密码加密
                    $password=pwGen($new_pswd);
                    $sql = "UPDATE users SET password = '$password' WHERE user_id = '$user_id'";
                    $result = pdo_query($sql);
                    if ($result){
                        //更新成功
                        $res = ['code' => 1, 'msg' => '用户密码更新成功！请重新登录...', 'url' => '/home/logout.php'];
                    }else{
                        //更新失败
                        $res = ['code' => 0, 'msg' => '用户密码更新失败！', 'url' => '/home/updatepswd.php'];
                    }
                }else{
                    $res = ['code' => 0, 'msg' => '原密码输入错误，无法修改！', 'url' => '/home/updatepswd.php'];
                }
            }else{
                $res = ['code' => 0, 'msg' => '用户不存在！', 'url' => '/home/updatepswd.php'];
            }
        }
    }else{
        $res = ['code' => 0, 'msg' => '系统错误！', 'url' => '/home/updatepswd.php'];
    }


    echo json_encode($res);
    ?>