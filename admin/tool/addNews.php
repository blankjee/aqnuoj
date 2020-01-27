<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");

    if(!authNewsManage()){
        $result = ['code' => 0, 'msg' => '添加失败！您没有权限！', 'url' => '/admin/addnews.php'];
        echo json_encode($result);
        return ;
    }

    /*获取用户身份信息*/
    $user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];
    /*POST字段处理*/
    $title = $_POST['title'];
    $title = str_replace(",", "&#44;", $title);

    $content = $_POST['content'];
    $content = str_replace("<p>", "", $content);
    $content = str_replace("</p>", "<br />", $content);

    $important = $_POST['important'];

    if(get_magic_quotes_gpc()){
        $title = stripslashes($title);
        $content = stripslashes($content);
    }

    $title = RemoveXSS($title);
    $content = RemoveXSS($content);

    $now = getNow();

    /*插入公告记录*/
    $sql = "INSERT INTO news (user_id, title, content, create_time, importance, defunct) VALUES ('$user_id', '$title', '$content', '$now', '$important', 'N')";
    $result = pdo_query($sql);

    if ($result){
        $result = ['code' => 1, 'msg' => '添加成功！', 'url' => "/admin/addnews.php"];
    }else{
        $result = ['code' => 0, 'msg' => '添加失败！', 'url' => "/admin/addnews.php"];
    }

    echo json_encode($result);


?>
