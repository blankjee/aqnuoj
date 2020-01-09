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

    /*进入列表或者搜索页面*/

    //获取搜索字段，并处理。
    $title = cleanParameter("get", "title");
    $pid = cleanParameter("get", "pid");
    $now = getNow();

    if (!$title && !$pid){
        //搜索字段为空，即没有执行搜索
        //获取总页数
	 $sql = "SELECT COUNT(*) FROM problem WHERE `defunct`='N' AND `problem_id` NOT IN(
		  SELECT  `problem_id` 
		  FROM contest c
		  INNER JOIN  `contest_problem` cp ON c.contest_id = cp.contest_id
		  AND (
			  c.`end_time` >  '$now'
			  OR c.private =1
		  )
	    )";

        $result = pdo_query($sql);
        $total_num = (int)$result[0][0];
        $total_page = ceil($total_num / $each_page);

        //查询问题列表
        //首先进行权限判断（不判断权限，都不能显示不能够显示的！）
        //if (isset($_SESSION[ $OJ_NAME . '_administrator' ])){
        //    $sql = "SELECT * FROM problem ORDER BY problem_id ASC LIMIT $start, $each_page";
        //}else{
//            $sql = "SELECT * FROM problem WHERE defunct = 'N' AND problem_id NOT IN (
//                      SELECT problem_id FROM contest c INNER JOIN contest_problem cp ON c.contest_id = cp.contest_id AND (c.end_time >  '$now' OR c.private =1)
//                    ) ORDER BY problem_id ASC LIMIT $start, $each_page";
        $sql = "SELECT * FROM problem WHERE `defunct`='N' AND `problem_id` NOT IN(
		  SELECT  `problem_id` 
		  FROM contest c
		  INNER JOIN  `contest_problem` cp ON c.contest_id = cp.contest_id
		  AND (
			  c.`end_time` >  '$now'
			  OR c.private =1
		  )
	    ) ORDER BY problem_id ASC LIMIT $start, $each_page";

        $result = pdo_query($sql);
    }else if ($title){
        //有搜索字段，执行搜索操作
        //获取总页数
       // $sql = "SELECT COUNT(*) FROM problem WHERE title LIKE '%$title%'";
	 $sql = "SELECT COUNT(*) FROM problem WHERE `title` LIKE '%$title%' AND `defunct`='N' AND `problem_id` NOT IN(
		  SELECT  `problem_id` 
		  FROM contest c
		  INNER JOIN  `contest_problem` cp ON c.contest_id = cp.contest_id
		  AND (
			  c.`end_time` >  '$now'
			  OR c.private =1
		  )
	    )";

        $result = pdo_query($sql);
        $total_num = (int)$result[0][0];
        $total_page = ceil($total_num / $each_page);
        //查询问题列表
        //首先进行权限判断（不判断）
        //if (isset($_SESSION[ $OJ_NAME . '_administrator' ])) {
        //    $sql = "SELECT * FROM problem WHERE title LIKE '%$title%' ORDER BY problem_id ASC LIMIT $start, $each_page";
        //}else{
        //    $sql = "SELECT * FROM problem WHERE title LIKE '%$title%' AND defunct = 'N' ORDER BY problem_id ASC LIMIT $start, $each_page";
        //}
	 $sql = "SELECT * FROM problem WHERE `title` LIKE '%$title%' AND `defunct`='N' AND `problem_id` NOT IN(
		  SELECT  `problem_id` 
		  FROM contest c
		  INNER JOIN  `contest_problem` cp ON c.contest_id = cp.contest_id
		  AND (
			  c.`end_time` >  '$now'
			  OR c.private =1
		  )
	    ) ORDER BY problem_id ASC LIMIT $start, $each_page";

        $result = pdo_query($sql);
    }else if ($pid){
	//判断是否存在
         $sql = "SELECT COUNT(*) FROM problem WHERE problem_id = $pid";

        $result = pdo_query($sql);
        $total_num = (int)$result[0][0];
	if ($total_num == 1){

        $total_page = 1;
        
	 $sql = "SELECT * FROM problem WHERE `problem_id` = $pid AND `defunct`='N' AND `problem_id` NOT IN(
		  SELECT  `problem_id` 
		  FROM contest c
		  INNER JOIN  `contest_problem` cp ON c.contest_id = cp.contest_id
		  AND (
			  c.`end_time` >  '$now'
			  OR c.private =1
		  )
	    )";

        $result = pdo_query($sql);
	}
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
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

    <script>
        sessionUid = 0;    </script>

    <div class="main">

        <div class="container">
            <div class="row block block-info">
                <div class="col-md-6">
                    <div class="pad">
                        <div class="bootpage text-left">
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
                                        <a class="page-item" href="/home/problemlist.php?page=1">
                                            首页
                                        </a>
                                        <a class="page-item" href="/home/problemlist.php?page=<?php echo $page - 1;?>">
                                            上一页
                                        </a>
                                    <?php }
                                    ?>

                                    <?php for ($i=1; $i<=$total_page; $i++){
                                        ?>
                                        <a <?php if ($page == $i){?> class="current btn btn-primary"<?php } ?> class="page-item" href="/home/problemlist.php?page=<?php echo $i;?>">
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
                                        <a class="page-item" href="/home/problemlist.php?page=<?php echo $page + 1;?>">
                                            下一页
                                        </a>
                                        <a class="page-item"  href="/home/problemlist.php?page=<?php echo $total_page;?>">
                                            尾页
                                        </a>
                                    <?php }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 form-inline">
                    <div class="pull-right pad">
                        <table>
                            <tr>
                                <td>
                                    <form name="" action="/home/problemlist.php?title=<?php echo $title;?>" method="get">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">题目</span>
                                            <input class="form-control" type="text" placeholder="输入您想搜索的内容..." name="title"
                                                   value="">
                                        </div>
                                        <button class="btn btn-default btn-sm" type="submit">搜索</button>
                                    </form>
                                </td>
                                <td>
                                    <form name="" action="/home/problemlist.php?pid=<?php echo $pid;?>" method="get">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                                            <input class="form-control" type="text" placeholder="题目 ID" name="pid"
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
                        <th class="col-md-1">已解决</th>
                        <th class="col-md-5">问题</th>
                        <th class="col-md-2">通过数 / 总提交数</th>
                        <th class="col-md-1">通过率</th>
                        <!--                        <th class="col-md-3">分类</th>-->
                        <th class="col-md-3">来源</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
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
                                <a href="/home/problem.php?id=<?php echo $rows['problem_id']; ?>"><?php echo $rows['problem_id'] . ' - ' . $rows['title']; ?></a>
                            </td>
                            <td><a href="#"><?php echo $rows['accepted']; ?></a> / <a
                                        href="#"><?php echo $rows['submited']; ?></a></td>
                            <td><?php echo countRate($rows['accepted'], $rows['submited']) * 100; ?>%</td>
                            <td><?php echo $rows['source']; ?></td>
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
                                                <a class="page-item" href="/home/problemlist.php?page=1">
                                                    首页
                                                </a>
                                                <a class="page-item" href="/home/problemlist.php?page=<?php echo $page - 1;?>">
                                                    上一页
                                                </a>
                                            <?php }
                                            ?>

                                            <?php for ($i=1; $i<=$total_page; $i++){
                                                ?>
                                                <a <?php if ($page == $i){?> class="current btn btn-primary"<?php } ?> class="page-item" href="/home/problemlist.php?page=<?php echo $i;?>">
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
                                                <a class="page-item" href="/home/problemlist.php?page=<?php echo $page + 1;?>">
                                                    下一页
                                                </a>
                                                <a class="page-item"  href="/home/problemlist.php?page=<?php echo $total_page;?>">
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

</body>
</html>
