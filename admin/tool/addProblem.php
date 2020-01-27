<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");
    require_once ("problem.php");

    if(!authProblemManage()){
        $result = ['code' => 0, 'msg' => '添加失败！您没有权限！', 'url' => '/admin/addproblem.php'];
        echo json_encode($result);
        return ;
    }
    //获取当前用户的id
    $user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];

    /*POST字段处理*/
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

    $sample_input = $_POST['sampleInput'];
    $sample_input = str_replace("<p>", "", $sample_input);
    $sample_input = str_replace("</p>", "\n", $sample_input);
    $sample_input = str_replace(",", "&#44;", $sample_input);
    $sample_input = str_replace("<br>", "\n", $sample_input);
    $sample_output = $_POST['sampleOutput'];
    $sample_output = str_replace("<p>", "", $sample_output);
    $sample_output = str_replace("</p>", "\n", $sample_output);
    $sample_output = str_replace(",", "&#44;", $sample_output);
    $sample_output = str_replace("<br>", "\n", $sample_output);
//    $test_input = $_POST['test_input'];
//    $test_output = $_POST['test_output'];

    $hint = $_POST['hint'];
    $hint = str_replace("<p>", "", $hint);
    $hint = str_replace("</p>", "<br />", $hint);
    $hint = str_replace(",", "&#44;", $hint);

    $source = $_POST['source'];

    $visible = $_POST['visible'];

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
        $source = stripslashes($source);

        $visible = stripslashes($visible);
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

    /*插入问题记录*/
    $pid = addproblem($title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output,$visible, $hint, $source, $spj, $OJ_DATA);
//var_dump(1);
    /*生成测试文件路径*/
    $basedir = "$OJ_DATA/$pid";     
    mkdir($basedir);
    if(strlen($sample_output) && !strlen($sample_input)) $sample_input = "0";
    if(strlen($sample_input)) mkdata($pid, "sample.in", $sample_input, $OJ_DATA);
    if(strlen($sample_output)) mkdata($pid, "sample.out", $sample_output, $OJ_DATA);
    /*写入用户对此题的权限*/
    $user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];
    $sql = "INSERT INTO privilege (user_id, rightstr, defunct) values('$user_id', 'p$pid', 'N')";
    $result = pdo_query($sql);

    $_SESSION[$OJ_NAME.'_'."p$pid"] = true;

    $result = ['code' => 1, 'msg' => '添加成功！现在继续添加更多测试数据...', 'url' => "phpfm.php?frame=3&pid=$pid"];
    echo json_encode($result);


?>
