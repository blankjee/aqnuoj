<?php

/**
 * 权限控制-是否是管理员
 */
function authIsAdmin(){
    $OJ_NAME = "AQNUOJ";
    $rightstr = array("administrator", "course_teacher", "normal_teacher", "user_manager", "problem_manager", "contest_manager", "notice_manager");
    $rightstatus = array("administrator", "course_teacher", "normal_teacher", "user_manager", "problem_manager", "contest_manager", "notice_manager");
    //初始化权限状态
    foreach ($rightstatus as $rs){
        $rs = false;
    }

    //得到了用户的权限信息，存在变量里。
    $isadmin = false;
    foreach ($rightstr as $row){
        if (isset($_SESSION[ $OJ_NAME . '_' . $row ]) && $_SESSION[ $OJ_NAME . '_' . $row ] == true){
            $isadmin = true;
        }
    }
    return $isadmin;
}

    $isAdmin = false;
    //判断当前账户的权限
    $isAdmin = authIsAdmin();

    if (isset($_GET['cid'])){
       $isContest = $_GET['cid'];
    }else{
       $isContest = false;
    }

?>
<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">安庆师范大学OJ</a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="/">主页</a></li>
		<?php
                    if ($isContest != false){?>
                        <li><a href="/home/contest.php?id=<?php echo $cid;?>">问题列表</a></li>
                        <li><a href="/home/status.php?cid=<?php echo $cid;?>">状态</a></li>
                        <li><a href="/home/contestrank.php?cid=<?php echo $cid;?>">排行榜</a></li>
                <?php }else{?>
                        <li><a href="/home/problemlist.php">问题列表</a></li>
                        <li><a href="/home/contestlist.php?cat=0">竞赛</a></li>
                        <li><a href="/home/contestlist.php?cat=1">作业</a></li>
                        <li><a href="/home/status.php">状态</a></li>
                        <li><a href="/home/ranklist.php">排行榜</a></li>
                        <li><a href="/home/newslist.php">公告</a></li>
                <?php }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    if (isset($_SESSION[$OJ_NAME . '_' . 'user_id']) && $_SESSION[$OJ_NAME . '_' . 'user_id']){
                        ?>
                        <li><a href="/home/userinfo.php"><?php echo $_SESSION[$OJ_NAME . '_' . 'user_id'];?></a></li>
                        <?php if ($isAdmin == true){ ?>
                            <li><a href="/admin">管理</a></li>
                        <?php }?>
                        <li><a href="/home/logout.php">注销</a></li>
                <?php
                    }else{
                        ?>
                        <li><a href="/home/login.php">登录</a></li>
                        <li><a href="/home/register.php">注册</a></li>
                <?php
                    }
                ?>

        </div> <!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
