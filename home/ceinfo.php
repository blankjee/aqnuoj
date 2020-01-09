<?php
require_once('../includes/config.inc.php');

if (isset($_GET['sid'])){
	$id = $_GET['sid'];
	$sql = "SELECT * from compileinfo WHERE solution_id = '$id'";
	$result = pdo_query($sql);
	$rows = $result[0];
}else{
	echo "无此编译错误信息";
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" />

	<title>编译错误详情</title>

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
					<h3 class="text-center" style="margin-top: 0; margin-bottom: 30px;">运行编号：<?php echo $rows['solution_id'];?> - 编译错误原因</h3>
					<center><h6></h6></center><br/><br/>
					<div>
						<p><pre><?php echo $rows['error'];?></pre>
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
</div>
</body>
</html>
