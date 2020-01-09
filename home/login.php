<?php
	require_once( "../includes/config.inc.php" );
	$backurl = cleanParameter("GET", "backurl");
        if ($backurl == null) $backurl="/";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title>登录 - AQNUOJ</title>

    <link type="text/css" rel="stylesheet" href="../static/libs/bootstrap/css/bootstrap.min.css"/>
    <script language="javascript" type="text/javascript" src="../static/libs/jquery/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/libs/bootstrap/js/bootstrap.min.js"></script>

    <!--IE -->
    <script language="javascript" type="text/javascript" src="../static/self/js/html5shiv.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/self/js/respond.min.js"></script>
    <!--IE-->
    <link type="text/css" rel="stylesheet" href="../static/self/css/home.css"/>
    <link type="text/css" rel="stylesheet" href="../static/self/css/base.css"/>
    <link type="text/css" rel="stylesheet" href="../static/self/css/login.css"/>
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

    <div class="main">

        <div class="login">
            <h2 class="text-center">AQNUOJ 登陆</h2>
            <form id="login-form" action="" method="post">
		<input hidden name="backurl" value="<?php echo $backurl;?>">
                <div class="account-input">
                    <input type="text" name="user_id" id="user_id" placeholder="用户名" ></input>
                </div>
                <div class="account-input">
                    <input type="password" name="password" id="password" placeholder="密码"></input>
                </div>
                <button type="submit">登录</button>
            </form>
        </div>

    </div>

    <!-- Footer START -->
    <?php include('partials/footer.php'); ?>
    <!-- Footer END -->
</div>


<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            $("#login-form").validate({
                rules: {
                    user_id: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                },
                messages: {
                    user_id: {
                        required: "请输入用户名",
                    },
                    password: {
                        required: "请输入密码",
                    },
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
    $("#login-form").submit(function(){
        Base.post("/home/tool/signIn.php", $('#login-form').serialize(),
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
