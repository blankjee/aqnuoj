<?php
    $cache_time=30;
    $OJ_CACHE_SHARE=false;

    require_once('../includes/cache_start.php');
    require_once('../includes/config.inc.php');
    require_once('../includes/const.inc.php');
    require_once('../includes/my_func.inc.php');

    $now=strftime("%Y-%m-%d %H:%M",time());

    $pr_flag=false;
    $co_flag=false;
    $is_practice = true;
    //GET请求的问题ID
    if(isset($_GET['id'])){
        // 练习模式
        $id=intval($_GET['id']);

        if(!isset($_SESSION[$OJ_NAME.'_'.'administrator']) && $id!=1000&&!isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])){
            //不是管理者&不是竞赛发起人&此ID不是1000
            $sql="SELECT * FROM problem WHERE problem_id='$id' AND defunct='N' AND problem_id NOT IN(
                SELECT problem_id FROM contest_problem WHERE contest_id IN(
                  SELECT contest_id FROM contest WHERE end_time>'$now' or private='1'))";
        } else {
            $sql="SELECT * FROM problem WHERE problem_id='$id'";
        }
        $pr_flag=true;
        $result=pdo_query($sql);
    }else if(isset($_GET['cid']) && isset($_GET['pid'])){
        // 竞赛模式
        $is_practice = false;   //设标志为竞赛模式
        $cid=intval($_GET['cid']);
        $pid=intval($_GET['pid']);

	//获取竞赛的结束时间
        $sql = "SELECT * FROM contest WHERE contest_id = '$cid'";
        $result = pdo_query($sql);
        $contestInfo = $result[0];
        $endTime = $contestInfo['end_time'];
        //如果过了结束时间便为true
        $timeRemaining = countContestIsEnd($endTime);

        if(!isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
            //不是管理员
            $sql="SELECT langmask,private,defunct FROM contest WHERE defunct='N' AND contest_id='$cid' AND start_time <= '$now'";
        } else {
            $sql="SELECT langmask,private,defunct FROM contest WHERE defunct='N' AND contest_id='$cid'";
        }
        $result=pdo_query($sql);
        $rows_cnt=count($result);
        if ($rows_cnt==0) {
            //没有此竞赛
            $view_errors=  "<title>$MSG_CONTEST</title><h2>无此竞赛！</h2>";
            echo $view_errors;
            exit(0);
        }

        $row=$result[0];
        $contest_ok=true;

        //对用户的权限控制，暂时不做处理
//        if($row[1] && !isset($_SESSION[$OJ_NAME.'_'.'c'.$cid])){
//            $contest_ok=false;
//        }

        if($row['defunct']=='Y'){
            $contest_ok=false;
        }

        if(isset($_SESSION[$OJ_NAME.'_'.'administrator']))
            $contest_ok=true;

        $ok_cnt = $rows_cnt === 1 ? true : false;

        $langmask=$row[0];

        if(!$contest_ok){
            $view_errors= "不允许参加此竞赛！";
            echo $view_errors;
            exit(0);
        }

        if($ok_cnt == false){
            // 不开始
            $view_errors=  "无此竞赛！";
            echo $view_errors;
            exit(0);
        }else{
            $sql="SELECT * FROM problem WHERE defunct='N' AND problem_id=(
            SELECT problem_id FROM contest_problem WHERE contest_id=? AND num=?)";
            $result=pdo_query($sql,$cid,$pid);
        }
        $co_flag=true;

    }else{
        $view_errors="没有这个题目！";
        echo $view_errors;
        exit(0);
    }

    $rows = $result[0];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title><?php echo $rows['problem_id'] . '-->' . $rows['title'];?></title>

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

<!--    <link href="../static/libs/KaTeX/katex.min.css" rel="stylesheet">-->
 <script language="javascript" src="../includes/baidu_analysis.js"></script>


</head>
<body>
<div class="everything">
    <div class="banner">
        <div class="container">
		<span hidden id="time-remaining" style="color: green"><?php echo $timeRemaining;?></span>
        </div>
    </div>

    <!-- Header START -->
    <?php include('partials/header.php'); ?>
    <!-- Header END -->

    <div class="main">

        <div class="container">
            <div class="row">
                <div class="block block-success"></div>
                <div class="block-content block-container istyle">
                    <h3 class="problem-header"><?php echo $rows['title'];?></h3>
                    <div class="prob-info">
                        <span class="user-black">Time Limit:&nbsp;<?php echo $rows['time_limit'] * 1000;?> ms</span>
                        <span class="user-black" style="margin-left: 12px;">Memory Limit:&nbsp;<?php echo $rows['memory_limit'];?> MB</span>
                    </div>
                    <div align="center" class="form-group form-inline">
                        <?php
                            if ($is_practice) { ?>
                                <a href="/home/submitsolution.php?id=<?php echo $id;?>" class="btn btn-default btn-sm ">提交</a>
                               <!-- <a href="/home/status.php?pid=<?php echo $id;?>" class="btn btn-default btn-sm">统计</a>-->
                            <?php } else {?>
                                <a href="/home/submitsolution.php?cid=<?php echo $cid;?>&pid=<?php echo $pid;?>" class="btn btn-default btn-sm ">提交</a>
                                <a href="/home/status.php?pid=<?php echo $pid;?>" class="btn btn-default btn-sm">统计</a>
                            <?php }
                        ?>

<!--                        <a href="" class="btn btn-default btn-sm">讨论</a>-->
                    </div>
                    <br/>
                    <h4>问题描述</h4>
                    <div class="prob-content">
                        <p>
                            <?php echo $rows['description'];?>
                        </p>
                    </div>
                    <br/>
                    <h4>输入描述</h4>
                    <div class="prob-content">
                        <p>
                            <?php echo $rows['input'];?>
                        </p>
                    </div>
                    <br/>
                    <h4>输出描述</h4>
                    <div class="prob-content">
                        <p>
                            <?php echo $rows['output'];?>
                        </p>
                    </div>
                    <br/>
                    <h4>样例输入</h4>
                    <div class="prob-content">
					<pre><?php echo $rows['sample_input'];?>
                    </pre>
                    </div>
                    <br/>
                    <h4>样例输出</h4>
                    <div class="prob-content">
					<pre><?php echo $rows['sample_output'];?>
                    </pre>
                    </div>
                    <br/>
                    <h4>提示</h4>
                    <div class="prob-content">
                        <p>
                            <?php echo $rows['hint'];?>
                        </p>
                    </div>
                    <br/>
                    <h4>来源</h4>
                    <div class="prob-content">
                        <p>
                            <?php echo $rows['source'];?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer START -->
<?php include('partials/footer.php'); ?>
<!-- Footer END -->
</body>
</html>
