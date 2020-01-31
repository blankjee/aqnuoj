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

    //设置每页的起始序号index

    $index = ($page - 1) * $each_page +1;

    //计算页面的开始位置
    if (!$page || $page == 1) {
        $start = 0;
    } else {
        $offset = $page - 1;
        $start = ($offset * $each_page);
    }
    $where = "user_id NOT IN ('admin')";

        //获取总页数，只显示前100名。
        $total_num = 100;
        $total_page = ceil($total_num / $each_page);
        //查询用户列表，按 AC 数递减排序
        $sql = "SELECT * FROM users WHERE ".$where." ORDER BY solved DESC, pass_ratio DESC LIMIT $start, $each_page";
        $result = pdo_query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=9"/>

    <title>用户排名 - AQNUOJ</title>

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

                        </div>
                    </div>
                </div><br/>
		<div class="alert alert-success alert-dismissable">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                      <strong>提示：</strong> TOP100，每小时更新一次，加油上榜吧！
                </div>
               
            </div>
            <div class="row">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="col-md-2">名次</th>
                        <th class="col-md-2">用户</th>
                        <th class="col-md-2">昵称</th>
                        <th class="col-md-2">通过数 / 总提交数</th>
                        <th class="col-md-2">通过率</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //循环获取记录内容
              
                    foreach ($result as $rows){
                        ?>
                        <tr>
                            <td><?php echo $index ++; ?></td>
                            <td>
                                <a href="/home/userinfo.php?user=<?php echo $rows['user_id']; ?>"><?php echo $rows['user_id']; ?></a>
                            </td>
                            <td><?php echo $rows['nick']; ?></td>
                            <td><?php echo $rows['solved']; ?> / <?php echo $rows['submited']; ?></td>
                            <td><?php echo countRate($rows['solved'], $rows['submited']); ?></td>
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
</div>
<!-- Footer START -->
<?php include('partials/footer.php'); ?>
<!-- Footer END -->
</body>
</html>
