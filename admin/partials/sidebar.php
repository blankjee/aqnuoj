<!-- # sidebar -->
<?php
    $url=basename($_SERVER['REQUEST_URI']);
    $realurl=basename($_SERVER['REQUEST_URI']);
    $url=str_replace(strrchr($url, "?"),"",$url);
    $url = substr($url,0,strrpos($url,'.'));

    //进行权限判断
    $authUser = authUserManage();
    $authProblem = authProblemManage();
    $authContest = authContestManage();
    $authNews = authNewsManage();

    $normal = false;        //常规栏
    $user_mg = false;       //用户管理
    $problem_mg = false;    //问题管理
    $conetst_mg = false;    //作业管理

    if ($url == 'setindexmsg' || $url == 'newslist' || $url == 'addnews'){
        $normal = true;
    }
    if ($url == 'userlist' || $url == 'privilegelist' || $url == 'addprivilege' || $url == 'importstudent'){
        $user_mg = true;
    }
    if ($url == 'problemlist' || $url == 'addproblem' || $url == 'importproblem' || $url == 'exportproblem'){
        $problem_mg = true;
    }
    if ($url == 'contestlist' || $url == 'addcontest' || $url == 'contestcreator' || $url == 'exportstucode'){
        $conetst_mg = true;
    }
?>
<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
    <div class="nano">
        <div class="nano-content">
            <ul>
		        <li><a href="/home" class=""><i class="ti-back-left"></i> 返回前台 </a></li>
                <li><a href="/admin" class=""><i class="ti-home"></i> 后台主页 </a></li>
                <?php
                if ($normal == true){?>
            <li class="active open"><a class="sidebar-sub-toggle"><i class="ti-layout-grid2"></i>常规设置  <span class="sidebar-collapse-icon ti-angle-down"></span></a>
            <?php }else{?>
                <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid2"></i>常规设置  <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                    <?php }?>
                    <ul>
                        <li><a href="newslist.php">公告列表</a></li>
                        <?php
                            if ($authNews){?>
                                <li><a href="setindexmsg.php">首页公告</a></li>
                                <li><a href="addnews.php">添加公告</a></li>
                         <?php   }
                        ?>
                    </ul>
                </li>
                <?php
                if ($user_mg == true){?>
                <li class="active open"><a class="sidebar-sub-toggle"><i class="ti-user"></i>用户管理  <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                <?php }else{?>
                    <li><a class="sidebar-sub-toggle"><i class="ti-user"></i>用户管理  <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <?php }?>
                        <ul>
                            <li><a href="userlist.php">用户列表</a></li>
                            <?php
                                if ($authUser){?>
                                    <li><a href="importstudent.php">导入用户</a></li>
                                    <li><a href="privilegelist.php">权限列表</a></li>
                                    <li><a href="addprivilege.php">添加权限</a></li>
                             <?php   }
                            ?>
                        </ul>
                    </li>

                <?php
                if ($problem_mg == true){?>
                <li class="active open"><a class="sidebar-sub-toggle"><i class="ti-receipt"></i>问题管理  <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                    <?php }else{?>
                <li><a class="sidebar-sub-toggle"><i class="ti-receipt"></i>问题管理  <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                    <?php }?>
                    <ul>
                        <li><a href="problemlist.php">问题列表</a></li>
                        <?php
                        if ($authProblem){?>
                            <li><a href="addproblem.php">添加问题</a></li>
                            <li><a href="importproblem.php">批量导入问题</a></li>
                        <?php }?>
                        
                    </ul>
                </li>
                <?php
                    if ($conetst_mg == true){?>
                <li class="active open"><a class="sidebar-sub-toggle"><i class="ti-cup"></i>竞赛&作业  <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                    <?php }else{?>
                <li><a class="sidebar-sub-toggle"><i class="ti-cup"></i>竞赛&作业  <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                    <?php }?>
                    <ul>
                        <li><a href="contestlist.php">竞赛&作业列表</a></li>
                        <?php
                            if ($authContest){?>
                                <li><a href="addcontest.php">添加竞赛&作业</a></li>
                                <li><a href="exportstucode.php">导出竞赛学生代码</a></li>
                                <!--                        <li><a href="contestcreator.php">比赛队伍账号生成器</a></li>-->
                        <?php    }
                        ?>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- /# sidebar -->
