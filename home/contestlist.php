<?php
    $OJ_CACHE_SHARE = false;
    $cache_time = 0;
    require_once('../includes/config.inc.php');
    require_once('../includes/my_func.inc.php');

    /*分页数据*/
    //获取当前页数
    if (isset($_GET['page'])){
        $page = $_GET["page"];
    }else{
        $page = 1;
    }

    //设置每页最多显示的记录数
    $each_page = $PAGE_EACH;

    //计算页面的开始位置
    if (!$page || $page == 1) {
        $start = 0;
    } else {
        $offset = $page - 1;
        $start = ($offset * $each_page);
    }

    //获取当前时间
    $now = getNow();

    /*进入列表或者搜索页面*/
    //获取当前类型，竞赛0或实验1
    $cat = cleanParameter("get", "cat");
    $title = cleanParameter("get", "title");
    $cid = cleanParameter("get", "cid");

    if (!$title && !$cid){
        //搜索字段为空，即没有执行搜索
        //获取总页数
        $sql = "SELECT COUNT(*) FROM contest WHERE cat=?";
        $result = pdo_query($sql, $cat);
        $total_num = (int)$result[0][0];
        $total_page = ceil($total_num / $each_page);
        //查询问题列表
        $sql = "SELECT * FROM contest WHERE cat = '$cat' ORDER BY contest_id ASC LIMIT $start, $each_page";
        $result = pdo_query($sql);
    }else if ($title){
        //有搜索字段，执行搜索操作
        //获取总页数
        $sql = "SELECT COUNT(*) FROM contest WHERE cat = '$cat' AND title LIKE '%$title%'";
        $result = pdo_query($sql);
        $total_num = (int)$result[0][0];
        $total_page = ceil($total_num / $each_page);
        //查询问题列表
        $sql = "SELECT * FROM contest WHERE cat = '$cat' AND title LIKE '%$title%' ORDER BY contest_id ASC LIMIT $start, $each_page";
        $result = pdo_query($sql);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=9"/>

    <title>竞赛&作业列表 - AQNUOJ</title>

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

        <div class="container">
            <div class="row block block-info">
                <div class="col-md-6">
                    <div class="pad">
                        <div class="bootpage text-left">
                            <div class="page-box">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 form-inline">
                    <div class="pull-right pad">
                        <table>
                            <tr>
                                <td>
                                    <form name="" action="/home/problemlist?title=<?php echo $title;?>" method="get">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">标题</span>
                                            <input class="form-control" type="text" placeholder="输入您想搜索的内容..." name="title"
                                                   value="">
                                        </div>
                                        <button class="btn btn-default btn-sm" type="submit">搜索</button>
                                    </form>
                                </td>
                                <td>
                                    <form name="" action="/home/problemlist.php?pid=<?php echo $cid;?>" method="get">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                                            <input class="form-control" type="text" placeholder="竞赛&实验 ID" name="pid"
                                                   value="">
                                        </div>
                                        <button class="btn btn-default btn-sm" type="submit">搜索</button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="col-md-1">ID</th>
                        <th class="col-md-4">标题</th>
			<!--<th class="col-md-1">赛制</th>-->
                        <th class="col-md-1">开放</th>
                        <th class="col-md-1">状态</th>
                        <th class="col-md-2">开始时间</th>
                        <th class="col-md-2">结束时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //循环获取记录内容
                    foreach ($result as $rows){
                        $contest_id = $rows['contest_id'];
                        $private = $rows['private'];
                        $start_time = $rows['start_time'];
                        $end_time = $rows['end_time'];
                        if ($start_time > $now){
                            $isaccess = -1;
                        }else if ($start_time <= $now && $end_time > $now){
                            $isaccess = 0;
                        }else{
                            $isaccess = 1;
                        }
			if ($rows['type'] == '0'){
				$type = 'ACM';
			}else {
				$type = 'OI';
			}
                        ?>
                        <tr>
                            <td><?php echo $contest_id;?></td>
<!--                            <td>-->
<!--                                --><?php
//                                    if ($rows['private'] == 1){?>
<!--                                        <a href="/home/contestverify.php?id=--><?php //echo $rows['contest_id']; ?><!--">--><?php //echo $rows['title']; ?><!--</a>-->
<!--                                    --><?php //}else{?>
<!--                                        <a href="/home/contest.php?id=--><?php //echo $rows['contest_id']; ?><!--">--><?php //echo $rows['title']; ?><!--</a>-->
<!--                                    --><?php //}?>
<!--                            </td>-->
                            <td>
                                <p>
                                <a style="text-decoration: none" onclick="accessContest(<?php echo $contest_id;?>, <?php echo $private;?>, <?php echo $isaccess;?>)"><?php echo $rows['title']; ?></a>
                                </p>
                            </td>
			   <!-- <td><?php echo $type;?></td>-->
                            <?php
                            if ($private == '1'){?>
                                <td style="color: red">私有</td>
                            <?php }else{?>
                                <td style="color: green">公开</td>
                            <?php }?>
                            <?php
                            if ($isaccess == -1){?>
                                <td style="color: red">未开始</td>
                            <?php }else if ($isaccess == 0){?>
                                <td style="color: green">进行中</td>
                            <?php }else {?>
                                <td style="color: red">已结束</td>
                            <?php }?>
                            <td><?php echo $start_time; ?></td>
                            <td><?php echo $end_time; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="bootpage ">
                                <div>
                                    <div class="page-box">
                                        <div class="page-list">
                                            <?php if ($page == 1){ ?>
                                                <a class="page-item active" href="javascript:return false;">
                                                    首页
                                                </a>
                                                <a class="page-item active" href="javascript:return false;">
                                                    上一页
                                                </a>
                                                <?php
                                            }else {
                                                ?>
                                                <a class="page-item" href="/home/contestlist.php?cat=<?php echo $cat;?>&page=1">
                                                    首页
                                                </a>
                                                <a class="page-item" href="/home/contestlist.php?cat=<?php echo $cat;?>&page=<?php echo $page - 1;?>">
                                                    上一页
                                                </a>
                                            <?php }
                                            ?>

                                            <?php for ($i=1; $i<=$total_page; $i++){
                                                ?>
                                                <a <?php if ($page == $i){?> class="current btn btn-primary"<?php } ?> class="page-item" href="/home/contestlist.php?cat=<?php echo $cat;?>&page=<?php echo $i;?>">
                                                    <?php echo $i;?>
                                                </a>
                                                <?php
                                            }
                                            ?>

                                            <?php if ($page == $total_page){ ?>
                                                <a class="page-item active" href="javascript:return false;">
                                                    下一页
                                                </a>
                                                <a class="page-item active" href="javascript:return false;">
                                                    尾页
                                                </a>
                                                <?php
                                            }else {
                                                ?>
                                                <a class="page-item" href="/home/contestlist.php?cat=<?php echo $cat;?>&page=<?php echo $page + 1;?>">
                                                    下一页
                                                </a>
                                                <a class="page-item"  href="/home/contestlist.php?cat=<?php echo $cat;?>&page=<?php echo $total_page;?>">
                                                    尾页
                                                </a>
                                            <?php }
                                            ?>

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
</div>
<!-- Footer START -->
<?php include('partials/footer.php'); ?>
<!-- Footer END -->

<script type="text/javascript">
    function accessContest(contest_id, isprivate, isaccess){
        if (isaccess == -1){
            //不可访问状态
            alert("比赛未开始！");
            return false;
        }else if (isaccess == 1){
            alert("比赛已结束！");
            return false;
        } else{
            //可以访问
            var url = "/home/contest.php?id=" + contest_id;
            window.location.href = url;
        }
    }

</script>
</body>
</html>
