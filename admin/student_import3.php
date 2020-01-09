<?php
require_once("../includes/config.inc.php");
require_once("../includes/my_func.inc.php");

if (!empty($_FILES['excel_file'])){
    $file_array = explode(".", $_FILES["excel_file"]["name"]);
    if ($file_array[1] == "xls" || $file_array[1] == "xlsx"){
        include("../includes/PHPExcel/PHPExcel/IOFactory.php");
        $object = PHPExcel_IOFactory::load($_FILES["excel_file"]["tmp_name"]);
        $sqlstr = "";
        foreach ($object->getWorksheetIterator() as $worksheet){
            $highestRow = $worksheet->getHighestRow();
            for ($row=2; $row<=$highestRow; $row++){
                $classIndex = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $className = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $studentNum = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
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

        $result = pdo_query($sqlstr);
        //var_dump($sqlstr);exit;
        if ($result){
            echo "<script>alert('导入成功，已经生成学生账号！');location.href='importstudent.php';</script>";
        }else{
            echo "<script>alert('导入失败！');location.href='importstudent.php';</script>";
        }
        //echo $sqlstr;
    }else{
        echo "<script>alert('无效的文件！');location.href='importstudent.php';</script>";
    }
}
