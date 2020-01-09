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
    $user_id = $_POST[ 'user_id' ];
    $password = $_POST[ 'password' ];
    if ( get_magic_quotes_gpc() ) {
        $user_id = stripslashes( $user_id );
        $password = stripslashes( $password );
    }

    $login = check_login( $user_id, $password );
    if ($login) {
        $_SESSION[ $OJ_NAME . '_' . 'user_id' ] = $login;

        $result = ['code' => 1, 'msg' => '登录成功！Enjoy it...', 'url' => '/home'];
    }else {
        if ( $view_errors ) {
            $result = ['code' => 0, 'msg' => '登录失败！', 'url' => '/home/login.php'];
        } else {
            $result = ['code' => 0, 'msg' => '账号或密码错误！', 'url' => '/home/login.php'];
        }
    }
    echo json_encode($result);
?>