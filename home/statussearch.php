<?php
    $OJ_CACHE_SHARE = false;
    $cache_time = 0;
    require_once('../includes/config.inc.php');

    //获取搜索字段
    $sid = cleanParameter("get", "sid");
    $uid = cleanParameter("get", "uid");
    $pid = cleanParameter("get", "pid");
    //设置是否为竞赛
    $isContest = false;

    //设置一个查询条件，不搜索到（admin）用户
    $where = "user_id NOT IN ('admin')";

   /*进入列表或者竞赛状态列表或者搜索页面*/
    if (isset($_GET['cid']) && $_GET['cid']!=0){
	$isContest = true;
        $cid = $_GET['cid'];
        $sql = "SELECT COUNT(*) FROM solution WHERE contest_id = ? AND " . $where;
        $result = pdo_query($sql, $cid);
        $total_num = (int)$result[0][0];
        $total_page = ceil($total_num / $each_page);
        //查询状态列表
        $sql = "SELECT * FROM solution WHERE contest_id = ? AND ".$where. " ORDER BY in_date DESC LIMIT $start, $each_page";
        $result = pdo_query($sql, $cid);
    }else{

    if (!$uid && !$pid && !$sid){
        //搜索字段为空，即没有执行搜索
    	echo "<script>alert('请输入搜索内容！');location.href='/home/status.php';</script>";    
    }else {
        //有搜索字段，执行搜索操作
        //获取总页数
        if ($sid){
            //查询状态列表
            $sql = "SELECT * FROM solution WHERE solution_id = '$sid' AND ".$where;
        }else if ($uid){
            //查询问题列表
            $sql = "SELECT * FROM solution WHERE user_id LIKE '%$uid%' AND ".$where." ORDER BY in_date DESC";
        }else if ($pid){
            //查询问题列表
            $sql = "SELECT * FROM solution WHERE problem_id = '$pid' AND ".$where." ORDER BY in_date DESC";
        }
        $result = pdo_query($sql);
	}    
     }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=9"/>

    <title>状态列表 - AQNUOJ</title>

    <link type="text/css" rel="stylesheet" href="../static/libs/bootstrap/css/bootstrap.min.css"/>
    <script language="javascript" type="text/javascript" src="../static/libs/jquery/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/libs/bootstrap/js/bootstrap.min.js"></script>
    <!--IE -->
    <script language="javascript" type="text/javascript" src="../static/self/js/html5shiv.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/self/js/respond.min.js"></script>
    <!--IE-->
    <link type="text/css" rel="stylesheet" href="../static/self/css/home.css"/>
    <link type="text/css" rel="stylesheet" href="../static/self/css/base.css"/>
    <link type="text/css" rel="stylesheet" href="../static/libs/bootstrap/css/badge.css"/>
    <script language="javascript" src="../static/self/js/nowtime.js"></script>
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
            <div class="row block block-info">
                <div class="col-md-2">
                    <div class="pad">
                        <div class="bootpage text-left">
                            <div class="page-box">

                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="col-md-1">运行编号</th>
                        <th class="col-md-2">用户</th>
                        <th class="col-md-1">问题</th>
                        <th class="col-md-2">结果</th>
                        <th class="col-md-1">内存</th>
                        <th class="col-md-1">耗时</th>
                        <th class="col-md-1">语言</th>
                        <th class="col-md-1">代码长度</th>
                        <th class="col-md-2">提交时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //循环获取记录内容
                    foreach ($result as $rows){
                        ?>
                        <tr>
			    <td><a href="/home/submitsolution.php?id=<?php echo $rows['problem_id'];?>&sid=<?php echo $rows['solution_id'];?>"><?php echo $rows['solution_id']; ?></a></td>
                            <td><a href="<?php echo '/home/userinfo.php?user='.$rows['user_id']; ?>"><?php echo $rows['user_id']; ?></a></td>
			    <?php
				$letter = 1;
                                if ($isContest){?>
                                    <td><a href="<?php echo '/home/problem.php?cid='.$rows['contest_id'].'&pid='.$rows['num']; ?>"> <?php echo 'Problem '.($letter + $rows['num']); ?></td>
                                <?php  }else{?>
                                    <td><a href="<?php echo '/home/problem.php?id='.$rows['problem_id']; ?>"> <?php echo $rows['problem_id']; ?></td>
                                <?php  }
                            ?>
                            <?php
                            $judge_result = $rows['result'];
                            if ($judge_result == 13){
                            ?>
                                <td><span class="badge badge-pink">TEST RUN</span> </td>
                            <?php } else if ($judge_result == 12){
                                ?>
                                <td><span class="badge badge-primary">Compile OK</span> </td>
                            <?php
                            }else if ($judge_result == 11){
                            ?>
                                <td><a href="ceinfo.php?sid=<?php echo $rows['solution_id'];?>"><span class="badge badge-warning">Complie Error</span></a> </td>
                            <?php
                            }else if ($judge_result == 10){
                            ?>
                                <td><span class="badge badge-danger">Runtime Error</span> </td>
                            <?php
                            }else if ($judge_result == 9){
                            ?>
                                <td><span class="badge badge-danger">Output Limit Exceed</span> </td>
                            <?php
                            }else if ($judge_result == 8){
                            ?>
                                <td><span class="badge badge-danger">Memory Limit Exceed</span> </td>
                            <?php
                            } else if ($judge_result == 7){
                                ?>
                                <td><span class="badge badge-danger">Time Limit Exceed</span> </td>
                            <?php
                            }else if ($judge_result == 6){
                            ?>
                            <td><a href="#"><span class="badge badge-danger">Wrong Answer - <?php echo $rows['pass_rate'] * 100 . "%";?></span></a>  </td>
			    <?php
                            }else if ($judge_result == 5){
                            ?>
                                <td><span class="badge badge-warning">Presentation Error</span> </td>
                            <?php
                            }else if ($judge_result == 4){
                                ?>
                                <td><span class="badge badge-success">Accepted</span> </td>
                            <?php
                            }else if ($judge_result == 3){
                                ?>
                                <td><span class="badge badge-primary">Running</span> </td>
                            <?php
                            }else if ($judge_result == 2){
                                ?>
                                <td><span class="badge badge-primary">Compiling</span> </td>
                            <?php
                            }else if ($judge_result == 1){
                                ?>
                                <td><span class="badge badge-primary">Pending Rejudging</span> </td>
                            <?php
                            }else if ($judge_result == 0){
                                ?>
                                <td><span class="badge badge-primary">Pending</span> </td>
                            <?php
                            }
                            ?>
                            <td><?php echo $rows['memory']; ?></td>
                            <td><?php echo $rows['time']; ?></td>
                            <td>
                                <?php
                                    $lang = $rows['language'];
                                    if ($lang == 0){?>
                                        C
                                  <?php  }else if ($lang == 1){?>
                                        C++
                                  <?php  }else if ($lang == 2){?>
                                        Pascal
                                  <?php  }else if ($lang == 3){?>
                                        Java
                                  <?php  }else if ($lang == 6){?>
                                        Python
                                  <?php  } ?>
                            </td>
                            <td><?php echo $rows['code_length']; ?></td>
                            <td><?php echo $rows['in_date']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<!-- Footer START -->
<?php include('partials/footer.php'); ?>
<!-- Footer END -->
</body>
</html>
