<?php
    require_once( "../../includes/config.inc.php" );
    require_once( "../../includes/login-" . $OJ_LOGIN_MOD . ".php" );

    /*验证码校验*/
//    $vcode = "";
//    if ( isset( $_POST[ 'vcode' ] ) )$vcode = trim( $_POST[ 'vcode' ] );
//    if ( $OJ_VCODE && ( $vcode != $_SESSION[ $OJ_NAME . '_' . "vcode" ] || $vcode == "" || $vcode == null ) ) {
//        echo "<script language='javascript'>\n";
//        echo "alert('Verify Code Wrong!');\n";
//        echo "history.go(-1);\n";
//        echo "</script>";
//        exit( 0 );
//    }
    $view_errors = "";
    /*获取表单数据并校验*/
    $backurl = $_POST['backurl'];
    $user_id = $_POST[ 'user_id' ];
    $password = $_POST[ 'password' ];
    if ( get_magic_quotes_gpc() ) {
        $user_id = stripslashes( $user_id );
        $password = stripslashes( $password );
    }

    //验证登录，返回值是user_id
    $login = check_login( $user_id, $password );
    if ($login) {
        //将身份信息发送至SESSION
        //1.user_id
        $_SESSION[ $OJ_NAME . '_' . 'user_id' ] = $login;
        //2.登录用户的权限信息
        $sql = "SELECT rightstr FROM privilege WHERE user_id='$user_id'";
        $result = pdo_query($sql);
        foreach ($result as $rows){
            $_SESSION[ $OJ_NAME . '_' . $rows[ 'rightstr' ] ] = true;
        }

        $result = ['code' => 1, 'msg' => '登录成功！Enjoy it...', 'url' => $backurl];
    }else {
        if ( $view_errors ) {
            $result = ['code' => 0, 'msg' => '登录失败！', 'url' => '/home/login.php'];
        } else {
            $result = ['code' => 0, 'msg' => '账号或密码错误！', 'url' => '/home/login.php'];
        }
    }
    echo json_encode($result);
?>
