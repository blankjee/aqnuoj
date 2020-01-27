<?php
require_once("../includes/config.inc.php");
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();
authPageContr();

if (!empty($_FILES['excel_file'])){
    $file_array = explode(".", $_FILES["excel_file"]["name"]);
    if ($file_array[1] == "xls" || $file_array[1] == "xlsx"){
        include("../includes/PHPExcel/PHPExcel/IOFactory.php");
        $object = PHPExcel_IOFactory::load($_FILES["excel_file"]["tmp_name"]);
        $sqlstr = "";
	$result = false;
        foreach ($object->getWorksheetIterator() as $worksheet){
	    $failNum = 0;
	    $existMsg = "";
            $highestRow = $worksheet->getHighestRow();
            for ($row=2; $row<=$highestRow; $row++){
                $classIndex = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $className = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $studentNum = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
		//执行一次查询操作，看看是否存在此用户。
		$usersqlstr = "SELECT COUNT(1) FROM users WHERE user_id = '$studentNum'";
		$userresult = pdo_query($usersqlstr);
		$isExist = $userresult[0][0];
		if ($isExist >= '1'){
		    $existMsg .= ($studentNum . "已存在系统中\n");
		    $failNum ++;
		    continue;
		}
                $realName = $worksheet->getCellByColumnAndRow(4, $row)->getFormattedValue();
                $nick = $worksheet->getCellByColumnAndRow(5, $row)->getFormattedValue();
                $password = $worksheet->getCellByColumnAndRow(6, $row)->getValue();


                //制作insertSQL语句
                $avatar = rand(1, 4);
                $now = getNow();
                $password=pwGen($password);
                $school = "安庆师范大学";
                $defunct = 'N';
                $sqlstr .= "INSERT INTO users (user_id, create_time, password, nick, school, defunct, avatar, realname, class_name, class_index) VALUES ('$studentNum', '$now', '$password', '$nick', '$school', '$defunct', '$avatar', '$realName', '$className', '$classIndex');";
            }
        }
	//var_dump($sqlstr);exit;
	if ($sqlstr != "")	$result = pdo_query($sqlstr);
        //var_dump($sqlstr);exit;
	$successNum = $highestRow - 1 - $failNum;
	if($successNum < 0) $successNum = 0;
	$resultMsg = $existMsg . "\n" . $successNum . "条记录成功导入，" . $failNum . "失败";
//var_dump($resultMsg);exit;

	echo  $resultMsg;
	echo "<a href='importstudent.php'>返回</a>";
        //if ($result){
        //    echo "<script>alert('导入成功，已经生成学生账号！');location.href='importstudent.php';</script>";
        //}else{
        //    echo "<script>alert('导入失败！');location.href='importstudent.php';</script>";
        //}
        //echo $sqlstr;
    }else{
        echo "<script>alert('无效的文件！');location.href='importstudent.php';</script>";
    }
}
