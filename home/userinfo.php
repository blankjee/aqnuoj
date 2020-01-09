<?php
    require_once('../includes/config.inc.php');
    require_once('../includes/cache_start.php');
    require_once("../includes/my_func.inc.php");
    $cache_time=10;
    $OJ_CACHE_SHARE=false;
    $isSelf = false;            //是否是本人，true是则开启修改信息功能，默认false不开启。
    if (isset($_GET['user']) && $_GET['user']){
        $user = $_GET['user'];
    }else if (isset($_SESSION[$OJ_NAME . '_' . 'user_id']) && $_SESSION[$OJ_NAME . '_' . 'user_id']){
        $user = $_SESSION[$OJ_NAME . '_' . 'user_id'];
        $isSelf = true;
    }else{
        echo "<script>alert('获取用户信息失败！请重新登录！');location.href='/home/login.php';</script>";
        exit(0);
    }


    /*数据库中查询数据*/
    $sql="SELECT school, class_name, email, nick, create_time, access_time, solved, submited, pass_ratio, defunct,avatar FROM users WHERE user_id='$user'";
    $result=pdo_query($sql);

    $row_cnt=count($result);
    if ($row_cnt==0){
        $view_errors= "查无此人！";
        exit(0);
    }

    $row = $result[0];
    $school = $row['school'];
    $className = $row['class_name'];
    $email = $row['email'];
    $nick = $row['nick'];
    $ip = $row['ip'];
    $create_time = $row['create_time'];
    $access_time = $row['access_time'];

    $solved = $row['solved'];
    $submited = $row['submited'];
    $passRate = $row['pass_ratio'];

    $defunct = $row['defunct'];
    $avatar = $row['avatar'];
    //处理defunct
    if (!strcmp($defunct, 'Y')){
        $defunct = "禁用";
    }else {
        $defunct = "可用";
    }

    //查询排名
    $sql="SELECT count(*) as rank FROM users WHERE pass_ratio > '$passRate'";
    //$sql="SELECT count(*) as rank FROM users WHERE countRate($solved, $submited); 
    $result = pdo_query($sql);
    $row = $result[0];
    $rank=intval($row[0]) + 1;

    //查询已解决了的题目
    $sql="SELECT DISTINCT problem_id as ac FROM solution WHERE user_id='$user' AND result=4";
    $result = pdo_query($sql);
    $row_ac = $result;

//    //查询未解决哪些题目
//    $sql="SELECT DISTINCT problem_id, solution_id FROM solution WHERE user_id='$user' and  result>4";
//    $result = pdo_query($sql);
//    $row_unac = $result;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title>用户详情 - AQNUOJ</title>

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
    <script language="javascript" src="../static/self/js/nowtime.js"></script>
 <script language="javascript" src="../includes/baidu_analysis.js"></script>

</head>
<body>
<div class="everything">
    
    <!-- Header START -->
    <?php include('partials/header.php'); ?>
    <!-- Header END -->


    <div class="main">

        <input type="hidden" value="56091" name="hid_uid">

        <div class="container">
            <div class="block block-info" style="padding-top:40px;"></div>
            <div class="row" style="margin-bottom: 45px;">
                <div class="col-md-12">
                    <div class="col-md-4" style="text-align: center;">
                        <a href="" target="_blank">
                            <?php $avatar_url = "../static/img/identicon" . $avatar . ".png";?>
                            <img class="avatar avatar-lg" src="<?php echo $avatar_url;?>">
                        </a>
                    </div>

                    <div class="col-md-4">
                        <h4 class="text-primary">用户名：&nbsp&nbsp<small><?php echo $user;?></small></h4>
                        <h4 class="text-warning">昵称：&nbsp&nbsp<small><?php echo $nick;?></small></h4>
                        <h4 class="text-warning">Email：&nbsp&nbsp<small><?php echo $email;?></small></h4>
                        <h4 class="text-success">学校：&nbsp&nbsp<small><?php echo $school;?></small></h4>
			<h4 class="text-primary">班级：&nbsp&nbsp<small><?php echo $className;?></small></h4>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-info">已解决：&nbsp&nbsp<small><?php echo $solved;?></small></h4>
                        <h4 class="text-warning">已提交：&nbsp&nbsp<small><?php echo $submited;?></small></h4>
                        <h4 class="text-danger">AC率：&nbsp&nbsp<small><?php echo countRate($solved, $submited);?></small></h4>
                        <h4 class="text-primary">名次：&nbsp&nbsp<small><?php echo $rank;?></small></h4>
                        <h4 class="text-info">注册时间：&nbsp&nbsp<small><?php echo $create_time;?></small></h4>
                    </div>


                    <div class="col-md-offset-4 col-md-8">
                        <h4 id="linked-account-codeforces" class="text-muted hidden">Codeforces:&nbsp&nbsp<small id="linked-account-codeforces-info"></small></h4>
                    </div>            </div>


            </div>


            <?php if ($isSelf == true){
                ?>
                <div class="row">
                    <div class="col-md-12 text-right" style="padding-top: 10px;padding-bottom: 10px;" id="user-op-buttons">
<!--                        <a tabindex="0" class="btn btn-default" role="button" data-toggle="popover"-->
<!--                           data-trigger="focus" data-html="true" data-placement="left"-->
<!--                           data-content="点击头像区域，在跳转的网站中注册并上传头像即可（注册邮箱需和 OJ 个人信息中的邮箱相同）。<br/>由于缓存，上传后可能存在数十分钟的更新延迟。<br/><br/>如仍有疑问，请自行搜索如何注册 Gravatar 头像。">-->
<!--                            修改头像-->
<!--                        </a>-->
                        <a class="btn btn-default" href="/home/updateinfo.php" >修改个人信息</a>
                        <a class="btn btn-default" href="/home/updatepswd.php" >修改密码</a>
                    </div>
                </div>

            <?php }
            ?>

            <div class="row">
                <div class="block block-danger">
                    <div class="block-content">
                        <div class="heading"><span class="ce-text bold">已提交</span></div>
                        <div class="inner problem_list">
                            <span>
                                <a href="status.php?uid=<?php echo $user;?>">题目集</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="block block-success">
                    <div class="block-content">
                        <div class="heading"><span class="accept-text bold">已解决 - 题目号</span></div>
                        <div class="inner problem_list">
                            <span>
                                <?php
                                    foreach ($row_ac as $row){?>
                                        <a href="problem.php?id=<?php echo $row[0];?>"><?php echo $row[0] . "  ";?></a>
                                   <?php }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="row">-->
<!--                <div class="block block-warning">-->
<!--                    <div class="block-content">-->
<!--                        <div class="heading"><span class="wrong-text bold">未解决 - 运行号</span></div>-->
<!--                        <div class="inner problem_list">-->
<!--                            <span>-->
<!--                                 --><?php
//                                 foreach ($row_unac as $row){?>
<!--                                     <a href="status.php?sid=--><?php //echo $row[0];?><!--">--><?php //echo $row[0];?><!--  </a>-->
<!--                                 --><?php //}
//                                 ?>
<!--                            </span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>



    </div>

    <!-- Footer START -->
    <?php include('partials/footer.php'); ?>
    <!-- Footer END -->
</div>
</body>
</html>
