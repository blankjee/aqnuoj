<?php

require_once('../includes/config.inc.php');
require_once('../includes/db_info.inc.php');
require_once("../includes/const.inc.php");
require_once("../includes/my_func.inc.php");

isLogined();


class TM{
    var $solved=0;
    var $time=0;
    var $p_wa_num;
    var $p_ac_sec;
    var $user_id;
    var $nick;
    var $pass_rate;
    function TM(){
        $this->solved=0;
        $this->time=0;
        $this->p_wa_num=array(0);
        $this->p_ac_sec=array(0);
	$this->pass_rate=array(0.00);
    }
    function Add($pid,$sec,$res,$pass_rate){
        global $OJ_CE_PENALTY;
//              echo "Add $pid $sec $res<br>";
        if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0)
            return;
        if ($res!=4){
            if(isset($OJ_CE_PENALTY)&&!$OJ_CE_PENALTY&&$res==11) return;  // ACM WF punish no ce
	    if ($pass_rate==NULL){
		$pass_rate = 0.00;
	    }else{
		$pass_rate = (float)$pass_rate;
	    }
	    //var_dump($pass_rate);
	    if (!isset($this->pass_rate[$pid])) 
     	        $this->pass_rate[$pid] = $pass_rate;
	    else if($this->pass_rate[$pid] < $pass_rate)
		$this->pass_rate[$pid] = $pass_rate;

            if(isset($this->p_wa_num[$pid])){
                $this->p_wa_num[$pid]++;
            }else{
                $this->p_wa_num[$pid]=1;
            }
	   
        }else{
	    $this->pass_rate[$pid]=1.00;
            $this->p_ac_sec[$pid]=$sec;
            $this->solved++;
            if(!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid]=0;
            $this->time+=$sec+$this->p_wa_num[$pid]*1200;
//                      echo "Time:".$this->time."<br>";
//                      echo "Solved:".$this->solved."<br>";
        }
    }
}

function s_cmp($A,$B){
//      echo "Cmp....<br>";
    if ($A->solved!=$B->solved) return $A->solved<$B->solved;
    else return $A->time>$B->time;
}

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);

if($OJ_MEMCACHE){
    $sql="SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`=$cid";
    require("./include/memcache.php");
    $result = mysql_query_cache($sql);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
}else{
    $sql="SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`=?";
    $result = pdo_query($sql,$cid);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
}


$start_time=0;
$end_time=0;
if ($rows_cnt>0){
//       $row=$result[0];

    if($OJ_MEMCACHE)
        $row=$result[0];
    else
        $row=$result[0];
    date_default_timezone_set("Asia/Shanghai");
    $start_time=strtotime($row['start_time']);
    $end_time=strtotime($row['end_time']);
    $now = strtotime(getNow());//当前时间 时间戳
    $title=$row['title'];
    $timeRemaining = countContestIsEnd($row['end_time']);
    $timeElapsed = countTime($now - $start_time);
}
if(!$OJ_MEMCACHE)
    if ($start_time==0){
        $view_errors= "No Such Contest";
        echo $view_errors;
        exit(0);
    }

if ($start_time>time()){
    $view_errors= "Contest Not Started!";
    echo $view_errors;
    exit(0);
}
if(time()<$end_time && stripos($title,"noip")){
    $view_errors =  "<h2>NOIP contest !</h2>";
   echo $view_errors;
    exit(0);
}
if(!isset($OJ_RANK_LOCK_PERCENT)) $OJ_RANK_LOCK_PERCENT=0;
$lock=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;

//echo $lock.'-'.date("Y-m-d H:i:s",$lock);
$view_lock_time = $start_time + ($end_time - $start_time) * (1 - $OJ_RANK_LOCK_PERCENT);
$locked_msg = "";
if (time() > $view_lock_time && time() < $end_time + $OJ_RANK_LOCK_DELAY) {
    $locked_msg = "The board has been locked.";
}

if($OJ_MEMCACHE){
    $sql="SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`='$cid'";
//        require("./include/memcache.php");
    $result = mysql_query_cache($sql);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
}else{
    $sql="SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`=?";
    $result = pdo_query($sql,$cid);
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
}

if($OJ_MEMCACHE)
    $row=$result[0];
else
    $row=$result[0];

// $row=$result[0];
$pid_cnt=intval($row['pbc']);

require("../includes/contest_solutions.php");
//var_dump($result);exit;
$user_cnt=0;
$user_name='';
$U=array();
$U[$user_cnt]=new TM();
for ($i=0;$i<$rows_cnt;$i++){
    $row=$result[$i];
    $n_user=$row['user_id'];
    if (strcmp($user_name,$n_user)){
        $user_cnt++;
        $U[$user_cnt]=new TM();

        $U[$user_cnt]->user_id=$row['user_id'];
        $U[$user_cnt]->nick=$row['nick'];

        $user_name=$n_user;
    }
    if(time()<$end_time+$OJ_RANK_LOCK_DELAY&&$lock<strtotime($row['in_date'])){
        $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,0, $row['pass_rate']);
    } else {
        $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']), $row['pass_rate']);
    }
}


usort($U,"s_cmp");
//var_dump($U);exit;
////firstblood
$first_blood=array();
for($i=0;$i<$pid_cnt;$i++){
    $first_blood[$i]="";
}


if($OJ_MEMCACHE){
    $sql="select s.num,s.user_id from solution s ,
        (select num,min(solution_id) minId from solution where contest_id=$cid and result=4 GROUP BY num ) c where s.solution_id = c.minId";
    $fb = mysql_query_cache($sql);
    if($fb) $rows_cnt=count($fb);
    else $rows_cnt=0;
}else{
    $sql="select s.num,s.user_id from solution s ,
        (select num,min(solution_id) minId from solution where contest_id=? and result=4 GROUP BY num ) c where s.solution_id = c.minId";
    $fb = pdo_query($sql,$cid);
    if($fb) $rows_cnt=count($fb);
    else $rows_cnt=0;
}

for ($i=0;$i<$rows_cnt;$i++){
    $row=$fb[$i];
    $first_blood[$row['num']]=$row['user_id'];
}


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title>竞赛排名</title>

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
        <link type="text/css" rel="stylesheet" href="../static/self/css/ranklist.css">

        <div class="container">
            <div id="head" class="row">
                <h3 class="text-center contest-title"><?php echo $title;?></h3>

                <!--contest time panel-->
                <div class="contest-time-panel">
                    <!--start time & end time-->
                    <div class="col-md-6 text-left">
                        <span class="contest-progress-label text-mid-bold">开始：</span>
                        <span id="start-time" class="contest-progress-value"><?php echo date('Y-m-d H:i:s', $start_time);?></span>
                    </div>
                    <div class="col-md-6 text-right">
                        <span class="contest-progress-label text-mid-bold">结束：</span>
                        <span id="end-time" class="contest-progress-value"><?php echo date('Y-m-d H:i:s', $end_time);?></span>
                    </div>
                    <div class="clearfix"></div>
                    <!--progress-->
                    <div class="progress">
                        <div id="contest-progress" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            <span class="sr-only"></span>
                        </div>
                    </div>
                    <!--time elapsed & contest status & time remaining-->
                    <div class="col-md-5 text-left">
                        <span class="text-mid-bold contest-progress-label">比赛进行时间：</span>
                        <span id="time-elapsed" class="contest-progress-value"></span>
                    </div>
                    <div id="contest-time-label" class="col-md-2 text-center">
                        <span id="contest-status" class="text-mid-bold contest-status-label"></span>
                    </div>
                    <div class="col-md-5 text-right">
                        <span class="text-mid-bold contest-progress-label">剩余时间：</span>
                        <span id="time-remaining" class="contest-progress-value"></span>
                    </div>
                    <div class="clearfix"></div>
                    <!--pending timer-->
                    <div id="contest-pending-timer" class="col-md-12 text-center" style="display: none;"></div>
                </div>

                <!--ranklist-->
                <div id="ranklist">
                    <!--toolbar-->
                    <div class="unidiv toolbar">
                        <!--left toolbar-->
                        <div class="pull-left toolbar-buttons">
                            <div class="seg">
                                <div class="btn-group btn-group-sm">
                                    <button id="export-ranklist" class="btn btn-default">导出竞赛结果</button>
                                </div>
                            </div>
                            <div class="seg">
                                <span class="seg-label">自动刷新</span>
                                <div id="auto-refresh-buttons" class="btn-group btn-group-sm">
                                    <a id="auto-refresh-on" class="btn btn-default" role="button" data-value="1">On</a>
                                    <a id="auto-refresh-off" class="btn btn-default" role="button" data-value="0">Off</a>
                                </div>
                            </div>
                        </div>
                        <!--right toolbar-->
                        <div class="pull-right toolbar-tips">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="sample-result">
                                        <span class="sample-result-color accepted"></span>
                                        <span>Accepted</span>
                                    </div>
                                    <div class="sample-result">
                                        <span class="sample-result-color fb"></span>
                                        <span>First Blood</span>
                                    </div>
<!--                                    <div class="sample-result">-->
<!--                                        <span class="sample-result-color rejected"></span>-->
<!--                                        <span>Rejected</span>-->
<!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div id="ranklist-table" class="unidiv">
                        <table class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th class="single-rank" rowspan="2">排名</th>
                                <th class="single-name text-left" rowspan="2">用户名</th>
                                <th class="single-solved" rowspan="2">已解决</th>
                                <th class="single-time" rowspan="2">用时</th>
                                <?php
                                    for ($i =0 ; $i < $pid_cnt; $i++){?>
                                        <th class="single-prob thead-top"><?php echo 1+$i;?></th>
                                   <?php }
                                ?>
				<th class="single-rank" rowspan="2">成绩</th>
                            <tr>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $index = 1;
                                foreach ($U as $uu){ ?>
                                    <tr>
                                        <input type="hidden" value="<?php echo $uu->user_id;?>" name="hid_username">
                                        <td><?php echo $index++;?></td>
                                        <td class="text-left nowrap-td"><?php echo $uu->user_id;?></td>
                                        <td><?php echo $uu->solved;?></td>
                                        <td><?php echo countTime($uu->time);?></td>
                                        <?php
                                        $p_ac_secs = $uu->p_ac_sec;
					$score = 0.00;
                                        for ($i = 0; $i < $pid_cnt; $i++){
					    //计算score
					    if (isset($uu->pass_rate[$i])){
				            	$score += $uu->pass_rate[$i] * 100;
					    }
                                            if (isset($p_ac_secs[$i]) && $p_ac_secs[$i] != 0){?>
                                                <td class="accepted" data-subm-total="6"><p class="detail-time"><?php echo countTime($p_ac_secs[$i]);?></p></td>
                                          <?php  }else{?>
                                                <td data-subm-total="6"><p class="detail-time"></p><p class="detail-rj-count"></p></td>
                                          <?php  }
                                        }
                                        ?>
					<td><?php echo $score;?></td>
                                    </tr>
                            <?php }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>


        <script src="../static/libs/jquery/jquery.scrollTo.min.js"></script>
        <script src="../static/libs/jquery/jquery.timers.min.js"></script>
        <script src="../static/self/js/xlsx.core.min.js"></script>
        <script src="../static/self/js/Blob.js"></script>
        <script src="../static/self/js/FileSaver.min.js"></script>
        <script src="../static/self/js/tableexport.min.js"></script>
        <script src="../static/self/js/ranklist_redesign.js"></script>

        <script>
            initContest();
        </script>


    </div>

    <!-- Footer START -->
    <?php include('partials/footer.php'); ?>
    <!-- Footer END -->
</div>
</body>
</html>
