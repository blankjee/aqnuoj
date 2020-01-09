<?php
    require_once('../includes/config.inc.php');

    if (isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "SELECT * from news WHERE news_id = '$id'";
        $result = pdo_query($sql);
        $rows = $result[0];
    }else{
        echo "无此公告";
        exit;
    }
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title><?php echo $rows['title'];?> - 新闻详情</title>

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
        sessionUid = 0;	</script>

    <div class="main">

        <div class="container">
            <div class="block block-info"></div>
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-offset-2 col-md-8">
                    <h3 class="text-center" style="margin-top: 0; margin-bottom: 30px;"><?php echo $rows['title'];?></h3>
                    <center><h6 style="border-bottom: 1px dashed #eee; color: #999999; margin: 0 16px; padding-bottom: 10px;">作者：<?php echo $rows['user_id'];?>&nbsp;&nbsp;发布时间：<?php echo $rows['create_time'];?></h6></center><br/><br/>
                    <div>
                        <p>
				<?php echo $rows['content'];?>
                        </p>

                    </div>

<!--                    <h3 style="margin-top: 60px;">评论</h3>-->
<!--                    <div id="lv-container" data-id="city" data-uid="MTAyMC8zNDAwOS8xMDU0Nw==">-->
<!--                        <script type="text/javascript">-->
<!--                            (function (d, s) {-->
<!--                                var j, e = d.getElementsByTagName(s)[0];-->
<!---->
<!--                                if(typeof LivereTower === 'function') {-->
<!--                                    return;-->
<!--                                }-->
<!---->
<!--                                j = d.createElement(s);-->
<!--                                j.src = 'https://cdn-city.livere.com/js/embed.dist.js';-->
<!--                                j.async = true;-->
<!---->
<!--                                e.parentNode.insertBefore(j, e);-->
<!--                            })(document, 'script');-->
<!--                        </script>-->
<!--                        <noscript> 为正常使用来必力评论功能请激活JavaScript</noscript>-->
<!--                    </div>-->
                </div>
            </div>
        </div>

    </div>

</div>
<!-- Footer START -->
<?php include('partials/footer.php'); ?>
<!-- Footer END -->
</div>
</body>
</html>
