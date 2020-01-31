<?php
   
    require_once('../includes/config.inc.php');
    require_once("../includes/my_func.inc.php");

    isLogined();


    /*分页数据*/
    //获取当前页数
    if (isset($_GET['page'])){
        $page = $_GET["page"];
    }else{
        $page = 1;
    }

    //设置每页最多显示的记录数
    $each_page = $PAGE_EACH;
    $index = ($page - 1) * $each_page + 1;

    //计算页面的开始位置
    if (!$page || $page == 1) {
        $start = 0;
    } else {
        $offset = $page - 1;
        $start = ($offset * $each_page);
    }

    /*进入列表或者搜索页面*/
    if (isset($_GET['id'])){
        $id = $_GET["id"];
    }else{
        echo "无此竞赛！";
    }
    if(isAdministorRB() == false){	//判断是否是管理员，是的话不用进行处理。
    	//判断竞赛的状态，如未开始已结束等，防止用户从URL直接访问。
    	accessContest($id);
    }
    /*查找用户AC的题*/
    $sub_arr=Array();
    // submit
    if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
        $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`=?".
            //  " AND `problem_id`>='$pstart'".
            // " AND `problem_id`<'$pend'".
            " group by `problem_id`";
        $result=pdo_query($sql,$_SESSION[$OJ_NAME.'_'.'user_id']);
        foreach ($result as $row)
            $sub_arr[$row[0]]=true;
    }

    $acc_arr=Array();
    // ac
    if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
        $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`=?".
            //  " AND `problem_id`>='$pstart'".
            //  " AND `problem_id`<'$pend'".
            " AND `result`=4".
            " group by `problem_id`";
        $result=pdo_query($sql,$_SESSION[$OJ_NAME.'_'.'user_id']);
        foreach ($result as $row)
            $acc_arr[$row[0]]=true;
    }

    /*查询当前竞赛信息*/
    $sql = "SELECT * FROM contest WHERE contest_id = '$id'";
    $result = pdo_query($sql);
    $contestInfo = $result[0];
    //判断竞赛的状态是否私有的
    $isPrivate = $contestInfo['private'];
    if ($isPrivate == 1 && !isset($_SESSION[$OJ_NAME.'_'."c$id"])){
        $url = "/home/contestverify.php?id=" . $id;
        echo "<script>location.href='$url';</script>";
        return false;
    } 

    //计算结束时间
    $endTime = $contestInfo['end_time'];
    //如果过了结束时间便为true
    $timeRemaining = countContestIsEnd($endTime);
    /*查询竞赛问题列表*/
    //获取总页数
    $sql = "SELECT COUNT(*) FROM problem p INNER JOIN contest_problem cp ON p.problem_id = cp.problem_id AND cp.contest_id='$id' order by cp.num";
    $result = pdo_query($sql);
    $total_num = (int)$result[0][0];
    $total_page = ceil($total_num / $each_page);

    //查询竞赛题目列表
    $sql="SELECT p.title, p.problem_id, p.source, cp.num as pnum, cp.c_accepted accepted, cp.c_submit submit FROM problem p INNER JOIN contest_problem cp ON p.problem_id = cp.problem_id AND cp.contest_id='$id' order by cp.num LIMIT $start, $each_page";
    $result = pdo_query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=9"/>

    <title>问题列表 - AQNUOJ</title>

    <link type="text/css" rel="stylesheet" href="../static/libs/bootstrap/css/bootstrap.min.css"/>
    <script language="javascript" type="text/javascript" src="../static/libs/jquery/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/libs/bootstrap/js/bootstrap.min.js"></script>
    <!--IE -->
    <script language="javascript" type="text/javascript" src="../static/self/js/html5shiv.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/self/js/respond.min.js"></script>
    <!--IE-->
    <link type="text/css" rel="stylesheet" href="../static/self/css/home.css"/>
    <link type="text/css" rel="stylesheet" href="../static/self/css/base.css"/>
    <script language="javascript" src="../static/self/js/nowtime.js"></script>
    <?php if ($timeRemaining !== true){ ?>
        <script language="javascript" src="../static/self/js/timeremain.js"></script>
    <?php }?>
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
        <div class="container">
	  <div class="row block block-success" style="padding-top:10px;">
            <h3 class="problem-header"><?php echo $contestInfo['title'];?></h3>
            <div class="prob-info">
                <span class="user-black">开始时间：&nbsp;<?php echo $contestInfo['start_time'];?>&nbsp;&nbsp;</span>
                <span class="user-black">结束时间：&nbsp;<?php echo $contestInfo['end_time'];?></span>
            </div>
            <div class="prob-info">
                <span class="user-black">竞赛类型：&nbsp;<?php echo $contestInfo['private'] == '1' ? '私有' : '公开';?>&nbsp;&nbsp;</span>
                <span class="user-black">竞赛状态：&nbsp;<?php echo $timeRemaining === true ? '结束' : '进行中';?></span>
            </div>
            <div>
                <center>
                    <p><?php echo $contestInfo['description'];?></p>
			<a href="/home/status.php?cid=<?php echo $id;?>" class="btn btn-default btn-sm ">状态</a>
                   	 <a href="/home/contestrank.php?cid=<?php echo $id;?>" class="btn btn-default btn-sm">排名</a>
                </center>
            </div>
        </div>

            <div class="row">
                <table class="table table-bordered table-hover">
                    <thead>
                    <thead>
                    <tr>
                        <?php
                            if ($timeRemaining === true){?>
                                <th colspan="5" class="text-right"><span style="color: red">竞赛已结束</span></th>
                            <?php }else{?>
                                <th colspan="5" class="text-right">剩余时间： <span id="time-remaining" style="color: green"><?php echo $timeRemaining;?></span></th>
                            <?php }?>
                    </tr>
                    <tr>
                        <th class="col-md-1">已解决</th>
                        <th class="col-md-4">问题</th>
                        <th class="col-md-2">通过</th>
                        <th class="col-md-2">提交</th>
                        <th class="col-md-1">通过率</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
		   //$index = 'A';
                    //循环获取记录内容
                   foreach ($result as $rows){
                        ?>
                        <tr>
                             <td><?php
                                    if (isset($acc_arr[$rows['problem_id']]) && $acc_arr[$rows['problem_id']] == true){?>
                                        <span style="color: #17C671">AC</span>
                                   <?php }
                                ?>
                            </td>

                            <td>
                                <a href="/home/problem.php?cid=<?php echo $id; ?>?&pid=<?php echo $rows['pnum']; ?>"><?php echo 'Problem '.$index++ . ' - ' . $rows['title']; ?></a>
                            </td>
                            <td><?php echo $rows['accepted']; ?></td>
                            <td><?php echo $rows['submit']; ?></td>
                            <td><?php echo countRate($rows['accepted'], $rows['submit']); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="bootpage ">
                                <div>
                                    <div class="page-box">
                                        <div class="page-list">
                                            <?php
                                            echo pageLinkForFront($page, $total_num, $each_page, 9, ""); ?>
                                        </div>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    $(".bootpage div").addClass("btn-group btn-group-sm");
                                    $(".bootpage a").addClass('btn btn-default ');
                                    $(".bootpage span").addClass('btn btn-primary');
                                </script>
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

    <!-- Footer START -->
    <?php include('partials/footer.php'); ?>
    <!-- Footer END -->
</div>
</body>
</html>
