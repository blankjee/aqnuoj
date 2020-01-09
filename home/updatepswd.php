<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();

    $cache_time=10;
    $OJ_CACHE_SHARE=false;

    // 校验用户名
    if (isset($_SESSION[$OJ_NAME . '_' . 'user_id']) && $_SESSION[$OJ_NAME . '_' . 'user_id']){
        $user = $_SESSION[$OJ_NAME . '_' . 'user_id'];
        /*数据库中查询数据*/
        $sql = "SELECT school, email, nick FROM users WHERE user_id='$user'";
        $result = pdo_query($sql);
        $row_cnt = count($result);
        if ($row_cnt == 0){
            $view_errors = "查无此人！";
            exit(0);
        }

        $row = $result[0];
        $school = $row['school'];
        $email = $row['email'];
        $nick = $row['nick'];


    }


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title>更新密码 - AQNUOJ</title>

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


    <div class="main">

        <div style="padding-top: 80px;"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <form id="updatepswd-form" action="" method="post" class="form-horizontal">
                        <input hidden type="text" name="user_id" value="<?php echo $user;?>"/>
                        <div class="form-group">
                            <label class="col-md-3 control-label">原密码</label>
                            <div class="col-md-9">
                                <input type="password" name="old_pswd"  class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">新密码</label>
                            <div class="col-md-9">
                                <input id="new_password" type="password" name="new_pswd"  value="" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">新密码</label>
                            <div class="col-md-9">
                                <input type="password" name="re_new_pswd"  value="" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit"  class="btn btn-primary btn-bg">更新</button>
                                <button type="reset" class="btn btn-info btn-bg">还原</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div style="padding-top: 80px;"></div>

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
            $("#updatepswd-form").validate({
                rules: {
                    old_pswd: {
                        required: true,
                    },
                    new_pswd: {
                        required: true,
                        minlength: 6,
                        maxlength: 20
                    },
                    re_new_pswd: {
                        required: true,
                        equalTo: "#new_password"
                    }
                },
                messages: {
                    old_pswd: {
                        required: "请输入旧密码",
                    },
                    new_pswd: {
                        required: "请输入密码",
                        minlength: "密码长度不能小于6位",
                        maxlength: "请输入20位以下密码"
                    },
                    re_new_pswd: {
                        required: "请再输入一次密码",
                        equalTo: "两次密码输入不一致"
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
    $("#updatepswd-form").submit(function(){
        Base.post("/home/tool/updatePswd.php", $("#updatepswd-form").serialize(),
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

<script>
    var i=0;
    $("school").on("change",function(){
        $("school").attr("value", i++);
    })
</script>

</body>
</html>
