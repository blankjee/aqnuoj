<?php

    if ($_SERVER['PHP_SELF'] === "/home/index.php"){
        require_once('../includes/config.inc.php');
        $msgtitle = file_get_contents("../static/text/msgtitle.txt");
        $msgcontent = file_get_contents("../static/text/msgcontent.txt");
    }else{
        require_once('includes/config.inc.php');
        $msgtitle = file_get_contents("static/text/msgtitle.txt");
        $msgcontent = file_get_contents("static/text/msgcontent.txt");
    }

    $day[1] = strtotime(date('Y-m-d',time()));
    $day[0] = $day[1] + 60*60*24;
    $day[2] = $day[1] - 60*60*24;
    $day[3] = $day[2] - 60*60*24;
    $day[4] = $day[3] - 60*60*24;
    $day[5] = $day[4] - 60*60*24;
    $day[6] = $day[5] - 60*60*24;
    $day[7] = $day[6] - 60*60*24;
    $sql ='SELECT * FROM `solution` WHERE UNIX_TIMESTAMP(`in_date`)>=? AND UNIX_TIMESTAMP(`in_date`)<?';
    for ($csadff = 1; $csadff <= 7; ++$csadff) {
        $subcount[$csadff] = count(pdo_query($sql, $day[$csadff], $day[$csadff - 1]));
        $account[$csadff] = count(pdo_query($sql.' AND `result`=4', $day[$csadff], $day[$csadff - 1]));
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <title>安庆师范大学 Online Judge</title>

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
        sessionUid = 0;
    </script>

    <div class="main">

        <div class="container">
            <div class="row">
                <?php
                    //如果服务器发生故障，在这里引用 partials 中的 server-error.php 文件
                    //include('partials/server-error.php');
                ?>


                <div class="welcome">
                    <p><h3 style="text-align:center;padding-top:10px;"><?php echo $msgtitle;?></h3></p>
                    <p class="text"><?php echo $msgcontent;?></p>
                    <br>
                </div><br><br><br>
                <div class="row">
                    <div width:80%; height:400px;">
                        <canvas id="myChart">
                        </canvas>
                    </div>

                    <script src="https://cdn.bootcss.com/Chart.js/2.7.3/Chart.bundle.js"></script>

                    <script>
                        var ctx = document.getElementById("myChart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: [<?php for ($i =1 ; $i <= 7; ++$i) {
                                    echo '\''.date('Y-m-d', $day[8-$i]).'\'';
                                    if ($i != 7) echo ',';
                                }?>],
                                datasets: [{
                                    label: '提交',
                                    data: [<?php for ($i =1 ; $i <= 7; ++$i) { echo $subcount[8-$i]; if ($i != 7) echo ',';} ?>],
                                    backgroundColor: '#2185d0',
                                    borderColor: '#2185d0',
                                    borderWidth: 1
                                },
                                    {
                                        label: '正确',
                                        data: [<?php for ($i = 1 ; $i <= 7; ++$i) {
                                            echo $account[8-$i];
                                            if ($i != 7) echo ',';
                                        }
                                            ?>],
                                        backgroundColor: '#4caf50',
                                        borderColor: '#4caf50',
                                        borderWidth: 1
                                    }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer START -->
    <?php include('partials/footer.php'); ?>
    <!-- Footer END -->
<script>
    var xlm_wid='15194';
    var xlm_url='https://www.xianliao.me/';
</script>
<script type='text/javascript' charset='UTF-8' src='https://www.xianliao.me/embed.js'></script>
                                                
</body>
</html>
