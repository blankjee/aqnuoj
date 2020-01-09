<?php


function addproblem($title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $visible, $hint, $source, $OJ_DATA) {

//	$spj=($spj);
	//插入数据
	$now = getNow();
	$sql = "INSERT into problem (title, time_limit, memory_limit,
	description, input, output, sample_input, sample_output, hint, source, create_time, defunct, visible_by)
	VALUES('$title', '$time_limit', '$memory_limit' , '$description' , '$input' , '$output' , '$sample_input' , '$sample_output' , '$hint' , '$source' , '$now', 'Y', '$visible')";
	//echo $sql;
	$pid = pdo_query($sql);

	//echo "<br>成功添加问题：$pid  ";
	if (isset ( $_POST ['contest_id'] )) {
		$cid =intval($_POST ['contest_id']);
		$sql = "select count(*) FROM `contest_problem` WHERE `contest_id`=?";
		$result = pdo_query( $sql,$cid ) ;
		$row =$result[0];
		$num = $row [0];
		$sql = "INSERT INTO `contest_problem` (`problem_id`,`contest_id`,`num`) VALUES(?,?,?)";	
		pdo_query($sql,$pid,$cid,$num);
	}
//	$basedir = "$OJ_DATA/$pid";
//	if(!isset($OJ_SAE)||!$OJ_SAE){
//			echo "[$title]data in $basedir";
//	}
	return $pid;
}
function mkdata($pid,$filename,$input,$OJ_DATA){

	$basedir = "$OJ_DATA/$pid";

	$fp = @fopen ( $basedir . "/$filename", "w" );
	if($fp){
		fputs ( $fp, preg_replace ( "(\r\n)", "\n", $input ) );
		fclose ( $fp );
	}else{
		echo "Error while opening".$basedir . "/$filename ,try [chgrp -R www-data $OJ_DATA] and [chmod -R 771 $OJ_DATA ] ";
	}
}


?>
