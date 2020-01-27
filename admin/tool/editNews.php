<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");

    if(!authNewsManage()){
        //如果不具有权限（高级权限&问题编辑权限），提示登录。
        $result = ['code' => 0, 'msg' => '编辑失败！您没有权限！', 'url' => '/admin/editnews.php'];
        echo json_encode($result);
        return ;
    }

    /*获取新闻信息*/
    $news_id = $_POST['id'];

    $title = $_POST['title'];
    $title = str_replace(",", "&#44;", $title);

    $content = $_POST['content'];
    $content = str_replace("<p>", "", $content);
    $content = str_replace("</p>", "<br />", $content);

    if(get_magic_quotes_gpc()){
        $title = stripslashes($title);
        $content = stripslashes($content);
    }

    $title = RemoveXSS($title);
    $content = RemoveXSS($content);

    $now = getNow();

    /*更新公告记录*/
    $sql = "UPDATE news SET title=?, content=?, create_time=? WHERE news_id=?";
    $result = pdo_query($sql, $title, $content, $now, $news_id);

    $url = "/admin/editnews.php?id=" . $news_id;
    if ($result){
        $result = ['code' => 1, 'msg' => '更新成功！', 'url' => $url];
    }else{
        $result = ['code' => 0, 'msg' => '添加失败！', 'url' => $url];
    }

    echo json_encode($result);


?>
