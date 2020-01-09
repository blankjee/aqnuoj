<?php
     require_once('../includes/config.inc.php');
     ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title>AQNUOJ - 注册</title>

    <link type="text/css" rel="stylesheet" href="../static/libs/bootstrap/css/bootstrap.min.css"/>
    <script language="javascript" type="text/javascript" src="../static/libs/jquery/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/libs/bootstrap/js/bootstrap.min.js"></script>

    <!--IE -->
    <script language="javascript" type="text/javascript" src="../static/self/js/html5shiv.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/self/js/respond.min.js"></script>
    <!--IE-->
    <link type="text/css" rel="stylesheet" href="../static/self/css/home.css"/>
    <link type="text/css" rel="stylesheet" href="../static/self/css/base.css"/>
    <link type="text/css" rel="stylesheet" href="../static/self/css/register.css"/>
    <link type="text/css" rel="stylesheet" href="../static/libs/toastr/toastr.min.css"/>

    <script language="javascript" src="../static/self/js/nowtime.js"></script>

    <!--Jquery Validation -->
    <script type="text/javascript" src="../static/libs/jquery/validation/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../static/libs/jquery/validation/localization/messages_zh.min.js"></script>
 <script language="javascript" src="../includes/baidu_analysis.js"></script>


</head>
<body>
<div class="everything">
    <div class="banner">
        <div class="container">
        </div>
    </div>

    <!-- Header START -->
    <?php include('partials/header.php'); ?>
    <!-- Header END -->

    <script>
        sessionUid = 0;	</script>

    <div class="main">

        <div class="register">
            <h2>注册 - AQNUOJ</h2>
            <form id="register-form" action="" method="post" class="form-horizontal ">
                <div class="account-input">
                    <p>
                    <input type="text" name="user_id"  placeholder="用户名（字母数字组合 3-20 个字符）" />
                    </p>
                </div>
                <div class="account-input">
                    <p>
                        <input id="new_password" type="password" name="password" placeholder="密码（字母数组组合 6-20 个字符）"/>
                    </p>
                </div>
                <div class="account-input">
                    <p>
                        <input type="password" name="repassword" placeholder="再次密码（与第一次相同）"/>
                    </p>
                </div>
                <div class="account-input">
                    <input type="text" name="nick"  placeholder="昵称（字母数字汉字组合 3-20 个字符）"  />
                </div>
                <div class="account-input">
                    <input type="text" name="email"  placeholder="Email" />
                </div>
                <div class="account-input">
                    <input type="text" name="school"  placeholder="学校（如：安庆师范大学）" />
                </div>

                <button id="register-btn" type="submit" >注册</button>
            </form>
        </div>

    </div>

    <!-- Footer START -->
    <?php include('partials/footer.php'); ?>
    <!-- Footer END -->

</div>

<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>
<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            $("#register-form").validate({
                rules: {
                    user_id: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 20
                    },
                    repassword: {
                        required: true,
                        equalTo:"#new_password"
                    },
                    nick: {
                        required: true,
                        maxlength: 32
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    school: {
                        required: true,
                        maxlength: 32
                    }
                },
                messages: {
                    user_id: {
                        required: "请输入用户名",
                        minlength: "请输入3位以上用户名",
                        maxlength: "请输入20位以下用户名"
                    },
                    password: {
                        required: "请输入密码",
                        minlength: "密码长度不能小于6位",
                        maxlength: "请输入20位以下密码"
                    },
                    repassword: {
                        required: "请再输入一次密码",
                        equalTo: "两次密码输入不一致"
                    },
                    nick: {
                        required: "请输入昵称",
                        maxlength: "请输入32位以下昵称"
                    },
                    email: {
                        required: "请输入邮箱",
                        email: "请输入正确的邮箱格式"
                    },
                    school: {
                        required: "请输入学校名称",
                        maxlength: "请输入32位以下的字符"
                    }
                }
            });
        });
    });
</script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",//弹出窗的位置
        "timeOut": "1000",
        "progressBar": "true",
    };
    $("#register-form").submit(function(){
        Base.post("/home/tool/signUp.php", $('#register-form').serialize(),
            function (res) {
                if (res && res.code == 1) {
                    toastr.success(res.msg);
                    setTimeout(function() {
                        window.location.href = res.url;
                    }, 1000);
                } else if (res && res.code == 0) {
                    toastr.error(res.msg);
                    setTimeout(function() {
                        window.location.href = res.url;
                    }, 1000);
                }
            });
        return false;
    })
</script>



</body>
</html>
