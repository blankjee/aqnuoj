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
        $sql="SELECT school, email, nick FROM users WHERE user_id='$user'";
        $result=pdo_query($sql);
        $row_cnt=count($result);
        if ($row_cnt==0){
            $view_errors= "查无此人！";
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

    <title>更新个人信息 - AQNUOJ</title>

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
                    <form id="updateinfo-form" class="form-horizontal ">
                        <div class="form-group">
                            <label class="col-md-3 control-label">用户名</label>
                            <div class="col-md-9">
                                <input id="user_id" readonly="true" type="text" name="user_id"  class="form-control" value="<?php echo $user;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">昵称</label>
                            <div class="col-md-9">
                                <input id="nick" readonly="true" type="text" name="nick"  class="form-control" value="<?php echo $nick;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email</label>
                            <div class="col-md-9">
                                <input id="email" type="text" name="email"  value="<?php echo $email;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">学校</label>
                            <div class="col-md-9">
                                <input id="school" type="text" name="school"  value="<?php echo $school;?>" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit"  class="btn btn-primary btn-bg">更新</button>
                                <input type="reset" onclick="restore()" class="btn btn-info btn-bg" value="还原"/>
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

<script>
    function restore() {
        window.location.reload();
    }
</script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>
<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            $("#updateinfo-form").validate({
                rules: {
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
    $("#updateinfo-form").submit(function(){
        var data = 'user_id=' + $('#user_id').val() + '&'
                    + 'email=' + $('#email').val() + '&'
                    + 'school=' + $('#school').val();

        Base.post("/home/tool/updateInfo.php", data,
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
