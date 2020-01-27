<?php

    require_once('../../includes/my_func.inc.php');


if (!authNewsManage()){
    $result = ['code' => 0, 'msg' => '设置失败！您没有权限！', 'url' => '/admin/newslist.php'];
    echo json_encode($result);
    return ;
}

    if(isset($_POST['msgtitle']) && isset($_POST['msgcontent'])){
        $fp1 = fopen("../../static/text/msgtitle.txt","w");
        $fp2 = fopen("../../static/text/msgcontent.txt","w");
        if ($fp1 != NULL && $fp2 != NULL){
            $msgtitle = $_POST['msgtitle'];
            $msgcontent = $_POST['msgcontent'];

            if(get_magic_quotes_gpc()){
                $msgtitle = stripslashes($msgtitle);
                $msgcontent = stripslashes($msgcontent);
            }
            $msgcontent = str_replace("\r\n","<br />",$msgcontent);
            $msgtitle = RemoveXSS($msgtitle);
            $msgcontent = RemoveXSS($msgcontent);
            fputs($fp1, $msgtitle);
            fputs($fp2, $msgcontent);
            fclose($fp1);
            fclose($fp2);

            $result = ['code' => 1, 'msg' => '更新成功！', 'url' => "/admin/setindexmsg.php"];

        }else{
            $result = ['code' => 0, 'msg' => '更新失败！请重试...', 'url' => "/admin/setindexmsg.php"];
        }
    }else{
        $result = ['code' => 0, 'msg' => '更新失败！', 'url' => "/admin/setindexmsg.php"];
    }

    echo json_encode($result);

    ?>
