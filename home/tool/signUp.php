<?php
    require_once("../../includes/config.inc.php");
    //加载一些公有方法
    require_once("../../includes/my_func.inc.php");

    //判断是否允许注册，不允许则不执行以下操作。
    if(isset($OJ_REGISTER)&&!$OJ_REGISTER) exit(0);

    /*定义变量*/
    //错误的字段，用来在注册界面提示
    $err_str="";
    //错误的数量
    $err_cnt=0;

    /*获取表单POST值并简单处理*/
    //trim(): 去除字符串首尾处的空白字符
    $user_id=trim($_POST['user_id']);
    $email=trim($_POST['email']);
    $school=trim($_POST['school']);
    $nick=trim($_POST['nick']);

    //$vcode=trim($_POST['vcode']); 暂时没有验证码
//    if($OJ_VCODE&&($vcode!= $_SESSION[$OJ_NAME.'_'."vcode"]||$vcode==""||$vcode==null) ){
//        $_SESSION[$OJ_NAME.'_'."vcode"]=null;
//        $err_str=$err_str."验证码错误！\\n";
//        $err_cnt++;
//    }

    /*用户名验证*/
    $len=strlen($user_id);
    if($len>20){
        $err_str=$err_str."用户名不能超过20个字符！\\n";
        $err_cnt++;
    }else if ($len<3){
        $err_str=$err_str."用户名不能少于3个字符！\\n";
        $err_cnt++;
    }
    if (!is_valid_user_name($user_id)){
        $err_str=$err_str."用户名不合法！\\n";
        $err_cnt++;
    }
    /*昵称验证*/
    $len=strlen($nick);
    if ($len>32){
        $err_str=$err_str."昵称太长了！\\n";
        $err_cnt++;
    }else if ($len==0){
        //如果未填写昵称，则昵称为用户名
        $nick=$user_id;
    }
    /*密码验证*/
    if (strcmp($_POST['password'],$_POST['repassword'])!=0){
        $err_str=$err_str."两次输入的密码不相同！\\n";
        $err_cnt++;
    }
    if (strlen($_POST['password'])<6){
        $err_cnt++;
        $err_str=$err_str."密码小于6位！\\n";
    }
    /*学校名验证*/
    $len=strlen($_POST['school']);
    if ($len>100){
        $err_str=$err_str."学校名太长！\\n";
        $err_cnt++;
    }
    /*Email验证*/
    $len=strlen($_POST['email']);
    if ($len>100){
        $err_str=$err_str."Email地址太长！\\n";
        $err_cnt++;
    }
    /*进行头像随机生成*/
    $avatar = rand(1, 4);

    /*进行错误展示*/
    if ($err_cnt>0){
        //如果有错，那么alert到注册页面*/
        $result = ['code' => 0, 'msg' => $err_str, 'url' => ''];
    }else{
        /*字段没有错误，进行数据库读写操作*/
        //密码加密
        $password=pwGen($_POST['password']);
        //判断用户名是否存在
        $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
        $result = pdo_query($sql);
        $rows_cnt=count($result);
        if ($rows_cnt == 1){
            //用户名已存在
            $result = ['code' => 0, 'msg' => '用户名已存在！', 'url' => '/home/register.php'];
        }else{
            /*再次对字段进行处理*/
            $nick=(htmlentities ($nick,ENT_QUOTES,"UTF-8"));
            $school=(htmlentities ($school,ENT_QUOTES,"UTF-8"));
            $email=(htmlentities ($email,ENT_QUOTES,"UTF-8"));
            $ip = ($_SERVER['REMOTE_ADDR']);
            if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
                $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $tmp_ip=explode(',',$REMOTE_ADDR);
                $ip =(htmlentities($tmp_ip[0],ENT_QUOTES,"UTF-8"));
            }
            if(isset($OJ_REG_NEED_CONFIRM)&&$OJ_REG_NEED_CONFIRM) $defunct='Y';
            else $defunct='N';
            /*插入数据库*/
            //更新用户表
            $now = getNow();
            $sql_users="INSERT INTO users (user_id, email, ip, create_time, password, nick, school, defunct, avatar) VALUES ('$user_id', '$email', '$ip', '$now', '$password', '$nick', '$school', '$defunct', '$avatar')";
            $result_users = pdo_query($sql_users);

            //更新用户登录日志表
            $sql_loginlog="INSERT INTO loginlog (user_id, ip, time) VALUES ('$user_id', '$ip', '$now')";
            $result_loginlog = pdo_query($sql_loginlog);

            if(!isset($OJ_REG_NEED_CONFIRM)||!$OJ_REG_NEED_CONFIRM){
                //如果不需要审核，那么直接给予权限（普通权限：primary）。
                $sql_rightstr = "INSERT INTO privilege ('$user_id', 'primary', '$defunct')";
                $result_rightstr = pdo_query($sql_rightstr);
            }
            if ($result_users){
                $result = ['code' => 1, 'msg' => '注册成功！正在跳转至登录界面...', 'url' => '/home/login.php'];
            }else{
                $result = ['code' => 0, 'msg' => '注册失败！系统错误...', 'url' => '/home/register.php'];
            }
        }
    }
    echo json_encode($result);
?>
