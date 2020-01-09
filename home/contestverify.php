<?php
    require_once('../includes/config.inc.php');
    require_once('../includes/my_func.inc.php');
    isLogined();
    $cid = cleanParameter("get", "id");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title>竞赛验证</title>

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

    <!-- Header START -->
    <?php include('partials/header.php'); ?>
    <!-- Header END -->

    <div class="main">

        <div style="padding-top: 80px;"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h1 class="text-center">比赛密码验证</h1>
                    <form id="contestVerify-form" class="form-horizontal" method="POST">
                        <div class="form-group">
                            <label class="col-md-2 control-label">比赛密码:</label>
                            <div class="col-md-10">
                                <input type="password" name="password" class="form-control" />
                                <input type="hidden" name="cid" value="<?php echo $cid;?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2">
                                <button type="submit" class="btn btn-primary btn-bg">进入比赛</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div style="padding-top: 80px;"></div>

    </div>

</div>
</div>
<!-- Footer START -->
<?php include('partials/footer.php'); ?>
<!-- Footer END -->


<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",//弹出窗的位置
        "timeOut": "1000",
        "progressBar": "true",
    };
    $("#contestVerify-form").submit(function(){
        Base.post("/home/tool/contestVerify.php", $('#contestVerify-form').serialize(),
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
