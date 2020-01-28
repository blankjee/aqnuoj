<?php
$OJ_CACHE_SHARE = false;
$cache_time = 0;
require_once('../includes/config.inc.php');

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

/*进入列表或者搜索页面*/

//获取搜索字段
$title = cleanParameter("get", "title");
if (!$title){
    //搜索字段为空，即没有执行搜索
    //获取总页数
    $sql = "SELECT COUNT(*) FROM news";
    $result = pdo_query($sql);
    $total_num = (int)$result[0][0];
    $total_page = ceil($total_num / $each_page);
    //查询用户列表，按 AC 数递减排序
    $sql = "SELECT * FROM news WHERE `defunct`='N'ORDER BY importance DESC, create_time DESC LIMIT $start, $each_page";
    $result = pdo_query($sql);
}else {
    //有搜索字段，执行搜索操作，模糊搜索
    //获取总页数
    $sql = "SELECT COUNT(*) FROM news WHERE `defunct`='N' AND title LIKE '%$title%'";

    $result = pdo_query($sql);
    $total_num = (int)$result[0][0];
    $total_page = ceil($total_num / $each_page);
    //查询搜索的用户列表
    $sql = "SELECT * FROM news WHERE `defunct`='N' AND title LIKE '%$title%' ORDER BY importance DESC, create_time DESC LIMIT $start, $each_page";

    $result = pdo_query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=9"/>

    <title>公告 - AQNUOJ</title>

    <link type="text/css" rel="stylesheet" href="../static/libs/bootstrap/css/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="../static/libs/bootstrap/css/badge.css"/>
    <script language="javascript" type="text/javascript" src="../static/libs/jquery/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/libs/bootstrap/js/bootstrap.min.js"></script>
    <!--IE -->
    <script language="javascript" type="text/javascript" src="../static/self/js/html5shiv.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/self/js/respond.min.js"></script>
    <!--IE-->
    <link type="text/css" rel="stylesheet" href="../static/self/css/home.css"/>
    <link type="text/css" rel="stylesheet" href="../static/self/css/base.css"/>
    <script language="javascript" src="../static/self/js/nowtime.js"></script>


    <link href="../static/libs/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="../static/libs/themify/themify-icons.css" rel="stylesheet">
    <link href="../static/libs/unix/unix.css" rel="stylesheet">
    <link href="../static/self/css/admin.css" rel="stylesheet">
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

                        </div>
                    </div>
                </div>
            </div>
            <section id="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header pr">
                                <h4>网站公告</h4>
                                <div class="search-action">
                                    <form name="" action="/home/newslist.php?title=<?php echo $title;?>" method="get">
                                    <div class="search-type dib">
                                        <input class="form-control input-rounded" type="text" placeholder="输入您想搜索的公告标题..." name="title" value="">
                                    </div>
                                    <div class="search-type dib">
                                        <div style="display: inline"><button class="btn btn-default btn-sm" type="submit">搜索</button></div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <div class="recent-comment m-t-15">
                                    <?php
                                    foreach ($result as $rows) {
                                        if ($rows['importance'] == '1') {
                                            ?>
                                            <div class="media">
                                                <a href="newsdetail.php?id=<?php echo $rows['news_id']; ?>">
                                                    <div class="media-body">
                                                        <h4 class="media-heading color-primary"><span
                                                                    class="badge badge-danger"
                                                                    style="color: white">置顶</span>&nbsp;<?php echo $rows['title']; ?>
                                                        </h4>
                                                        <p><?php echo mb_substr(strip_tags($rows['content']), 0, 50, 'utf-8') . '...'; ?></p>
                                                        <p class="comment-date"><?php echo $rows['create_time']; ?></p>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } else {
                                            ?>
                                            <div class="media">
                                                <a href="newsdetail.php?id=<?php echo $rows['news_id']; ?>">
                                                    <div class="media-body">
                                                        <h4 class="media-heading color-primary"><?php echo $rows['title']; ?></h4>
                                                        <p><?php echo mb_substr(strip_tags($rows['content']), 0, 50, 'utf-8') . '...'; ?></p>
                                                        <p class="comment-date"><?php echo $rows['create_time']; ?></p>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php }

                                    }?>
                                </div>
                            </div>
                            <!-- /# card -->
                        </div>
                        <!-- /# column -->

                    </div>
                    <!-- /# row -->

                </div></section>
        </div>

    </div>
</div>
<!-- Footer START -->
<?php include('partials/footer.php'); ?>
<!-- Footer END -->
</body>
</html>
