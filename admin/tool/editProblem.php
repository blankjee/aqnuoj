<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");

    if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))){
        //如果不具有权限（高级权限&问题编辑权限），提示登录。
        $result = ['code' => 0, 'msg' => '添加失败！您没有权限！', 'url' => '/admin/editusers.php'];
        echo json_encode($result);
        return ;
    }

    /*获取问题信息*/
    $problem_id = $_POST['id'];
   //获取当前用户的id
    $user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];


$title = $_POST['title'];
$title = str_replace(",", "&#44;", $title);
$time_limit = $_POST['time_limit'];
$memory_limit = $_POST['memory_limit'];

$description = $_POST['description'];
$description = str_replace("<p>", "", $description);
$description = str_replace("</p>", "<br />", $description);
$description = str_replace(",", "&#44;", $description);

$input = $_POST['input'];
$input = str_replace("<p>", "", $input);
$input = str_replace("</p>", "<br />", $input);
$input = str_replace(",", "&#44;", $input);

$output = $_POST['output'];
$output = str_replace("<p>", "", $output);
$output = str_replace("</p>", "<br />", $output);
$output = str_replace(",", "&#44;", $output);

$sample_input = $_POST['sample_input'];
$sample_output = $_POST['sample_output'];

//    $test_input = $_POST['test_input'];
//    $test_output = $_POST['test_output'];

$hint = $_POST['hint'];
$hint = str_replace("<p>", "", $hint);
$hint = str_replace("</p>", "<br />", $hint);
$hint = str_replace(",", "&#44;", $hint);

$visible = $_POST['visible'];


$source = $_POST['source'];

if(get_magic_quotes_gpc()){
    $title = stripslashes($title);
    $time_limit = stripslashes($time_limit);
    $memory_limit = stripslashes($memory_limit);
    $description = stripslashes($description);
    $input = stripslashes($input);
    $output = stripslashes($output);
    $sample_input = stripslashes($sample_input);
    $sample_output = stripslashes($sample_output);
//        $test_input = stripslashes($test_input);
//        $test_output = stripslashes($test_output);
    $hint = stripslashes($hint);
    $source = stripslashes($source);
    $spj = 'N';
    $visiable = stripslashes($visiable);
    $source = stripslashes($source);
}

$title = RemoveXSS($title);
$description = RemoveXSS($description);
$input = RemoveXSS($input);
$output = RemoveXSS($output);
$hint = RemoveXSS($hint);
$visible = RemoveXSS($visible);
    if ($visible == 1){
        $visible = $user_id;
    }else{
        $visible = '';
    }
$spj = 'N';
$now = getNow();

//var_dump($title);
//var_dump($now);
//var_dump($time_limit);
//var_dump($memory_limit);
//var_dump($description);
//var_dump($input);
//var_dump($output);
//var_dump($sample_input);
//var_dump($sample_output);
//var_dump($hint);
//var_dump($source);
//var_dump($problem_id);
//exit;
/*更新问题记录*/
$sql = "UPDATE problem SET title=?, create_time=?, time_limit=?, memory_limit=?, description=?, input=?, output=?, sample_input=?, sample_output=?, hint=?, source=?, spj='N', visible_by=? WHERE problem_id=?";
$result = pdo_query($sql, $title, $now, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $visible, $problem_id);
  
$url = "/admin/editproblem.php?id=" . $problem_id;
    if ($result){
        $result = ['code' => 1, 'msg' => '更新成功！', 'url' => $url];
    }else{
        $result = ['code' => 0, 'msg' => '添加失败！', 'url' => $url];
    }

    echo json_encode($result);

?>
