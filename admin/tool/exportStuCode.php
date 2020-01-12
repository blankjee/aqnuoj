<?php
/**
 * Created by PhpStorm.
 * User: huang
 * Date: 2020/1/11
 * Time: 13:42
 */
header("Content-Type: text/html; charset=UTF-8");
include "../../includes/PHPWord/PHPWord.php";
require_once '../../includes/PHPWord/PHPWord/IOFactory.php';

require_once("../../includes/config.inc.php");
//require_once("../../includes/check_post_key.php");
require_once ("../../includes/my_func.inc.php");

if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))){
    //如果不具有权限（高级权限&问题编辑权限），提示登录。
    $result = ['code' => 0, 'msg' => '添加失败！您没有权限！', 'url' => '/admin/exportstucode.php'];
    echo json_encode($result);
    return ;
}
//生成文件夹
function create_folders($dir){
    return is_dir($dir) or (create_folders(dirname($dir)) and mkdir($dir, 0777));
}

/*获取问题信息*/
$contest_id = $_POST['contest'];

date_default_timezone_set("Asia/Shanghai");//设置一个时区
$tm=date('Y-m-d H:i:s');
$time=date('Y-m-d-H-i-s');

//以当前时间戳创建一个新的文件夹。
$dir = "/home/jkq/stucode/" . $contest_id . "/" . $time;
//$dir = "D://word/stucode/" . $contest_id . "/" . $time;

//$fileNameArr 就是一个存储文件路径的数组 比如 array('/a/1.jpg,/a/2.jpg....');
$fileNameArr = array();

//开始获取学生代码
$sql = "select solution_id, result, user_id, num, pass_rate, in_date, judgetime from solution where contest_id = ? order by user_id asc ";
$result = pdo_query($sql, $contest_id);
$user = "-999999999";     //定义一个不存在的用户名
$empty = true;
foreach ($result as $row){
    if ($user != $row['user_id']){
        if ($user != "-999999999"){
            $empty = false;
            //$section->addText($content);
            // Save File
            $fileName = $user . "-contest-code.docx";
            //把文件地址添加到fileNameArr中便于打包下载
            array_push($fileNameArr, $dir . '/' . $fileName);
            //让页面弹出下载框的代码
            //header("Content-type: application/vnd.ms-word");
            //header("Content-Disposition:attachment;filename=".$fileName.".docx");
            //header('Cache-Control: max-age=0');
            $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
            //让页面弹出下载框的代码
            //$objWriter->save('php://output');
            //保存到服务器指定位置

            create_folders($dir);

            $objWriter->save($dir . '/' . $fileName);
        }
        //开始生成Word文档
        $PHPWord = new PHPWord();

//        $fontStyle = array('bold'=>true, 'align'=>'center');
//        //设置标题
//        $PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
//        $PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
        $section = $PHPWord->createSection();

        $content = "";
        $user = $row['user_id'];
    }
    $sql = "select source from source_code where solution_id=?";
    $code = pdo_query($sql, $row['solution_id']);
    $content = "-----------------------------------------------------------------------------------\n";
    $section->addText($content);
    $section->addTextBreak(1);
    $content = "用户名：" . $row['user_id'] . " - " . "竞赛题号：" . $row['num'] . " - 提交时间：" . $row['in_date'] . " - 测评时间：" . $row['judgetime'] . " - ";
    $content  .= "代码状态：";
    $judge_result = $row['result'];
    if ($judge_result == 13){
        $content  .= "测试运行";
    } else if ($judge_result == 12){
        $content  .= "Compile OK";
    }else if ($judge_result == 11){
        $content  .= "Complie Error";
    }else if ($judge_result == 10){
        $content  .= "Runtime Error";
    }else if ($judge_result == 9){
        $content  .= "Output Limit Exceed";
    }else if ($judge_result == 8){
        $content  .= "Memory Limit Exceed";
    } else if ($judge_result == 7){
        $content  .= "Time Limit Exceed";
    }else if ($judge_result == 6){
        $content  .= "Wrong Answer - " . $row['pass_rate'] * 100 . "%";
    }else if ($judge_result == 5){
        $content  .= "Presentation Error";
    }else if ($judge_result == 4){
        $content  .= "Accepted";
    }else if ($judge_result == 3){
        $content  .= "Running";
    }else if ($judge_result == 2){
        $content  .= "Compiling";
    }else if ($judge_result == 1){
        $content  .= "Pending Rejudging";
    }else if ($judge_result == 0){
        $content  .= "Pending";
    }
    $section->addText($content);
    $section->addTextBreak(1);
    if (isset($code[0])){
        $content = $code[0]['source'];
    }
    $section->addText($content);
    $section->addTextBreak(1);
    //$content  .= htmlentities($code[0]['source'],ENT_QUOTES,"UTF-8") . "\n";

}

//还有最后一个文件需要生成docx
if ($empty == false){
    $fileName = $user . "-contest-code.docx";
    //把文件地址添加到fileNameArr中便于打包下载
    array_push($fileNameArr, $dir . '/' . $fileName);
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
    create_folders($dir);
    $objWriter->save($dir . '/' . $fileName);
}

$filename = "./" . $contest_id . "-student-code-" .date ( 'YmdH' ) . ".zip"; // 最终生成的文件名（含路径）
// 生成文件
$zip = new ZipArchive (); // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
if ($zip->open ( $filename, ZIPARCHIVE::CREATE ) !== TRUE) {
    exit ( '无法打开文件，或者文件创建失败' );
}
//$fileNameArr 就是一个存储文件路径的数组 比如 array('/a/1.jpg,/a/2.jpg....');
foreach ( $fileNameArr as $val ) {
    //var_dump($val);
    $zip->addFile ( $val, basename ( $val ) ); // 第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
}
$zip->close (); // 关闭

//下面是输出下载;
header ( "Cache-Control: max-age=0" );
header ( "Content-Description: File Transfer" );
header ( 'Content-disposition: attachment; filename=' . basename ( $filename ) ); // 文件名
header ( "Content-Type: application/zip" ); // zip格式的
header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
header ( 'Content-Length: ' . filesize ( $filename ) ); // 告诉浏览器，文件大小

@readfile ( $dir . '/' . $filename );//输出文件;

?>