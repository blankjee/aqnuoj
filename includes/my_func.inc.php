<?php
require_once('config.inc.php');

function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
			if(function_exists("openssl_random_pseudo_bytes")){
				$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			}else{
				$rnd = hexdec(bin2hex(rand()."_".rand()));
			}
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}

function getToken($length=32){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}

/*密码加密*/
function pwGen($password,$md5ed=False) 
{
	if (!$md5ed) $password=md5($password);
	$salt = sha1(rand());
	$salt = substr($salt, 0, 4);
	$hash = base64_encode( sha1($password . $salt, true) . $salt ); 
	return $hash; 
}

function pwCheck($password,$saved)
{
	if (isOldPW($saved)){
		if(!isOldPW($password)) $mpw = md5($password);
		else $mpw=$password;
		if ($mpw==$saved) return True;
		else return False;
	}
	$svd=base64_decode($saved);
	$salt=substr($svd,20);
	if(!isOldPW($password)) $password=md5($password);
	$hash = base64_encode( sha1(($password) . $salt, true) . $salt );
	if (strcmp($hash,$saved)==0) return True;
	else return False;
}

function isOldPW($password)
{
	if(strlen($password)!=32) return false;
	for ($i=strlen($password)-1;$i>=0;$i--)
	{
		$c = $password[$i];
		if ('0'<=$c && $c<='9') continue;
		if ('a'<=$c && $c<='f') continue;
		if ('A'<=$c && $c<='F') continue;
		return False;
	}
	return True;
}

function is_valid_user_name($user_name){
	$len=strlen($user_name);
	for ($i=0;$i<$len;$i++){
		if (
			($user_name[$i]>='a' && $user_name[$i]<='z') ||
			($user_name[$i]>='A' && $user_name[$i]<='Z') ||
			($user_name[$i]>='0' && $user_name[$i]<='9') ||
			$user_name[$i]=='_'||
			($i==0 && $user_name[$i]=='*') 
		);
		else return false;
	}
	return true;
}

function sec2str($sec){
	return sprintf("%02d:%02d:%02d",$sec/3600,$sec%3600/60,$sec%60);
}
function is_running($cid){
   $now=strftime("%Y-%m-%d %H:%M",time());
	$sql="SELECT count(*) FROM `contest` WHERE `contest_id`=? AND `end_time`>?";
	$result=pdo_query($sql,$cid,$now);
	$row=$result[0];
	$cnt=intval($row[0]);
	return $cnt>0;
}
function check_ac($cid,$pid){
	//require_once("./include/db_info.inc.php");
	global $OJ_NAME;
	
	$sql="SELECT count(*) FROM `solution` WHERE `contest_id`=? AND `num`=? AND `result`='4' AND `user_id`=?";
	$result=pdo_query($sql,$cid,$pid,$_SESSION[$OJ_NAME.'_'.'user_id']);
	 $row=$result[0];
	$ac=intval($row[0]);
	if ($ac>0) return "<font color=green>Y</font>";
	$sql="SELECT count(*) FROM `solution` WHERE `contest_id`=? AND `num`=? AND `result`!=4 and `problem_id`!=0  AND `user_id`=?";
	$result=pdo_query($sql,$cid,$pid,$_SESSION[$OJ_NAME.'_'.'user_id']);
	$row=$result[0];
	$sub=intval($row[0]);
	
	if ($sub>0) return "<font color=red>N</font>";
	else return "";
}



function RemoveXSS($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=@avascript:alert('XSS')>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      // ;? matches the ;, which is optional
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

      // @ @ search for the hex values
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      // @ @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }

   // now the only remaining whitespace attacks are \t, \n, and \r   //, 'style'
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);

   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}

/*获取当前时间*/
function getNow(){
	$dt = new DateTime();
	return $dt->format('Y-m-d H:i:s');
}

/*计算时间差，以分钟、小时来呈现*/
function countTimeDiff($time){
	date_default_timezone_set("Asia/Shanghai");
	$one = strtotime($time);//开始时间 时间戳
	$tow = strtotime(getNow());//结束时间 时间戳
	$cle = $tow - $one; //得出时间戳差值

	$d = floor($cle/3600/24);
	$h = floor(($cle%(3600*24))/3600);  //%取余
	$m = floor(($cle%(3600*24))%3600/60);
	$s = floor(($cle%(3600*24))%60);
	if ($d != 0){
		return $d."天".$h.'小时';
	}else if ($h != 0){
		return $h.'小时'.$m.'分';
	}else if ($h == 0 && $m != 0){
		return $m.'分'.$s.'秒';
	}else {
		return $s.'秒';
	}
}

/*已知时间戳差，以分钟、小时来展示*/
function countTime($cle){
	$d = floor($cle/3600/24);
	$h = floor(($cle%(3600*24))/3600);  //%取余
	$m = floor(($cle%(3600*24))%3600/60);
	$s = floor(($cle%(3600*24))%60);
	return $d * 24 + $h . ':' . $m . ':' . $s;
}

/*计算竞赛到现在为止是否结束*/
function countContestIsEnd($time){
	date_default_timezone_set("Asia/Shanghai");
	$one = strtotime($time);//竞赛结束时间
	$tow = strtotime(getNow());//当前时间 时间戳
	$cle = $tow - $one; //得出时间戳差值
	if ($cle > 0){
		//当前时间时间戳>结束时间，那么已经结束
		return true;
	}else{
		//未结束
		$cle = - $cle;
		$d = floor($cle/3600/24);
		$h = floor(($cle%(3600*24))/3600);  //%取余
		$m = floor(($cle%(3600*24))%3600/60);
		$s = floor(($cle%(3600*24))%60);
		return $d * 24 + $h . ':' . $m . ':' . $s;
	}
}

/*对用户是否登录进行判断*/

function isLogined(){
	$backurl = $_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"];
	$url = "/home/login.php?backurl=" . $backurl;
	global $OJ_NAME;
	if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id' ])){
		echo "<script>alert('请先登录！');location.href='$url';</script>";
	}
}

/*对用户的身份权限进行判断，是否是管理员*/
function isAdministor(){
	global $OJ_NAME;
	$rightstr = array("administrator", "course_teacher", "normal_teacher", "user_manager", "problem_manager", "contest_manager", "notice_manager");

	//得到了用户的权限信息，存在变量里。
	$isadmin = false;
	foreach ($rightstr as $row){
		if (isset($_SESSION[ $OJ_NAME . '_' . $row ]) && $_SESSION[ $OJ_NAME . '_' . $row ] == true){
			$isadmin = true;
		}
	}

	if (!$isadmin){
		echo "<script>alert('权限不够！');location.href='/home/login.php';</script>";
	}
}
function isAdministorRB(){
        global $OJ_NAME;
        if (!isset($_SESSION[ $OJ_NAME . '_administrator' ])){
                return false;
        }else if ($_SESSION[ $OJ_NAME . '_administrator' ] != true){
                return false;
        }
	return true;
}

function accessContest($id){	
	$accessInfoSql = "SELECT `start_time`, `end_time` from `contest` where `contest_id`=?";
   	$accessInfo = pdo_query($accessInfoSql, $id);
	$num = count($accessInfo);
	if ($num < 1){
		echo "<script>alert('无此竞赛！');location.href='/home/contestlist.php?cat=1';</script>";
	}
    	$startTime = $accessInfo[0][0];
	$endTime = $accessInfo[0][1];
	$now = getNow();
	if ($startTime > $now){
		echo "<script>alert('比赛未开始！');location.href='/home/contestlist.php?cat=1';</script>";
	}
	if ($endTime < $now){
		echo "<script>alert('比赛已结束！');location.href='/home/contestlist.php?cat=1';</script>";
	}

}

/**
 * 权限控制-信息获取
 * */
function getAuth(){
	$OJ_NAME = "AQNUOJ";
	$rightstr = array("administrator", "course_teacher", "normal_teacher", "user_manager", "problem_manager", "contest_manager", "notice_manager");
	$rightstatus = array(["administrator", false, "超级管理员"], ["course_teacher", false, "任课老师"], ["normal_teacher", false, "普通老师"], ["user_manager", false, "用户管理员"], ["problem_manager", false, "问题管理员"], ["contest_manager", false, "竞赛管理员"], ["notice_manager", false, "公告管理员"]);

	//得到了用户的权限信息，存在变量里。
	foreach ($rightstr as $row){
		if (isset($_SESSION[ $OJ_NAME . '_' . $row ]) && $_SESSION[ $OJ_NAME . '_' . $row ] == true){
			if ($row == "administrator"){
				$rightstatus[0][1] = true;
			}elseif ($row == "course_teacher"){
				$rightstatus[1][1] = true;
			}elseif ($row == "normal_teacher"){
				$rightstatus[2][1] = true;
			}elseif ($row == "user_manager"){
				$rightstatus[3][1] = true;
			}elseif ($row == "problem_manager"){
				$rightstatus[4][1] = true;
			}elseif ($row == "contest_manager"){
				$rightstatus[5][1] = true;
			}elseif ($row == "notice_manager"){
				$rightstatus[6][1] = true;
			}
		}
	}
	return $rightstatus;
}
//得到当前URL的第一部分
function getFirUrl(){
	$url=($_SERVER['REQUEST_URI']);
	$url=str_replace(strrchr($url, "?"),"",$url);
	$url = substr($url,0,strrpos($url,'.'));
	$firsturl = substr($url, stripos($url,'/'),strrpos($url,'/'));
	return $firsturl;
}
//得到当前URL的第二部分
function getSecUrl(){
	$url=($_SERVER['REQUEST_URI']);
	$url=str_replace(strrchr($url, "?"),"",$url);
	$url = substr($url,0,strrpos($url,'.'));
	$secondurl = substr($url, strrpos($url,'/'));
	return $secondurl;
}

/**
 * 权限控制-页面控制
 */
function authPageContr(){
	$rightstatus = getAuth();
	$firsturl = getFirUrl();
	$secondurl = getSecUrl();

	$access = false;

	/*对一些页面进行控制访问*/
    if ($rightstatus[0][1] == true){
        $access = true;
    }
    if ($rightstatus[0][1] == false && ($rightstatus[1][1] == true || $rightstatus[2][1] == true) && $rightstatus[3][1] == false &&
		$rightstatus[4][1] == false && $rightstatus[5][1] == false && $rightstatus[6][1] == false) {
        //普通老师或任课老师
        $action = substr($secondurl, 1, 3);
        if (!($action == "add" || $action == "del" || $action == "edi" || $action == "imp" || $action == "exp" || $action == "set" || $action == "php")) {
            $access = true;
        }
    }
    if ($rightstatus[3][1] == true){
		//权限中包含用户管理员
		$model = $secondurl;
		if ($model == "/userlist" || $model == "/importstudent"){
			$access = true;
		}
	}
    if ($rightstatus[4][1] == true){
		//权限中包含问题管理员
		$model = $secondurl;
		if ($model == "/addproblem" || $model == "/editproblem" || $model == "/importproblem" || $model == "/problem_import_xml" || $model == "/problemlist" || $model == "phpfm"){
			$access = true;
		}
	}
    if ($rightstatus[5][1] == true){
		//权限中包含竞赛管理员
		$model = $secondurl;
		if ($model == "/addcontest" || $model == "/editcontest"){
			$access = true;
		}
	}
    if ($rightstatus[6][1] == true){
		//权限中包含公告管理员
		$model = $secondurl;
		if ($model == "/addnews" || $model == "/editnews" || $model == "/setindexmsg"){
			$access = true;
		}
	}
    if (!$access){
        require("../403.php");
        exit;
    }
}
/**
 * 权限控制-用户管理功能
 * true:具有用户管理的权限
 */
function authUserManage(){
	$rightstatus = getAuth();

	/*对用户身份进行判断*/
	if ($rightstatus[0][1] == true || $rightstatus[3][1] == true){
		return true;
	}
	return false;
}
/**
 * 权限控制-问题管理功能
 * true:具有问题管理的权限
 */
function authProblemManage(){
	$rightstatus = getAuth();

	/*对用户身份进行判断*/
	if ($rightstatus[0][1] == true || $rightstatus[4][1] == true){
		return true;
	}
	return false;
}
/**
 * 权限控制-竞赛管理功能
 * true:具有竞赛管理的权限
 */
function authContestManage(){
	$rightstatus = getAuth();

	/*对用户身份进行判断*/
	if ($rightstatus[0][1] == true || $rightstatus[5][1] == true){
		return true;
	}
	return false;
}

/**
 * 权限控制-公告管理功能
 * true:具有公告管理的权限
 */
function authNewsManage(){
	$rightstatus = getAuth();

	/*对用户身份进行判断*/
	if ($rightstatus[0][1] == true || $rightstatus[6][1] == true){
		return true;
	}
	return false;
}



/**
 * 返回分页链接字符串（后端使用）
 * @param int $page 当前页码 0或1均视为第一页
 * @param int $total 记录总数
 * @param int $pagesize 每页显示的记录数
 * @param int $number   每页显示的分页链接数量
 * @param string $url   分页链接url样式，默认直接使用GET['page']传递
 *        但针对一些特殊情况，如ajax分页，静态页分页，可能不能使用GET传递页码，需要定义url样式
 *        其中必须包含{page}字符串，将被替换为对应的页码 如
 *        $url = 'list_{page}.html';
 *        $url = 'javascript:funct(a,b,{page})';
 * @return string 分页链接HTML代码
 */
function pageLink($page=0, $total=0, $pagesize=0, $number=10, $url='') {
    $page = max(intval($page),1);
    $total = intval($total);
    $pagesize = max(intval($pagesize),0);
    $pages = ceil($total/$pagesize);

    //如果记录数为0，则提示结果是空。
    if ($total == 0){
        $s = "<p style='color: #F39C12'>啊哦，一条记录都没有找到~</p>";
        return $s;
    }
    if($pages < 2) return ;

    if(!$url) {
        $url = $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];
        $url = preg_replace('/\&*page=\d*\b/','',$url);
        $url .= empty($_SERVER['QUERY_STRING']) ? "page={page}" : "&page={page}";
    }

    $s = '';

    if($page > 1) {
        $s .= '<li class="paginate_button previous" id="bootstrap-data-table_previous"> <a aria-controls="bootstrap-data-table" href="'.str_replace('{page}',1,$url).'">首页</a>';
        $s .= '<li class="paginate_button previous" id="bootstrap-data-table_previous"> <a aria-controls="bootstrap-data-table" href="'.str_replace('{page}',($page - 1),$url).'">上一页</a>';
    }else {
        $s .= '<li class="paginate_button previous disabled" id="bootstrap-data-table_previous"> <a href="javascript:return false;" aria-controls="bootstrap-data-table">首页</a>';
        $s .= '<li class="paginate_button previous disabled" id="bootstrap-data-table_previous"> <a href="javascript:return false;" aria-controls="bootstrap-data-table">上一页</a>';
    }

    if($number%2) {
        $start = max($page -ceil($number/2)+1,1);
    }else {
        $start = max($page- intval($number/2),1);
    }


    $end = min($start+$number-1,$pages);

    if(($end - $start) < ($number-1)) {
        $start = max($end -$number+1, 1);
    }


    for($i = $start; $i <= $end; $i++) {
        $sel = $page == $i ? 'class="paginate_button active"' : '';
        $link = str_replace('{page}',$i,$url);
        $href = $page == $i ? '' : "href=\"$link\"";
        $s .= "<li $sel><a $href aria-controls='bootstrap-data-table' data-dt-idx=\"$i\"
                                                           tabindex=\"0\"> $i </a></li>";

    }

    if($page < $pages) {
        $link = str_replace('{page}',$page+1,$url);
        $s .= "<li class='paginate_button next' id='bootstrap-data-table_next'><a href=\"$link\" aria-controls='bootstrap-data-table'>下一页</a></li>";
        $link = str_replace('{page}',$pages,$url);
        $s .= "<li class='paginate_button next' id='bootstrap-data-table_last'><a href=\"$link\" aria-controls='bootstrap-data-table'>尾页</a></li>";
    }else {
        $s .= "<li class='paginate_button next disabled' id='bootstrap-data-table_next'><a href='javascript:return false;' aria-controls='bootstrap-data-table'>下一页</a></li>";
        $s .= "<li class='paginate_button next disabled' id='bootstrap-data-table_last'><a href='javascript:return false;' aria-controls='bootstrap-data-table'>尾页</a></li>";

    }

    $s .= "<li><a style='color: #00c292' class=\"total\">总数：$total </a></li>";

    return $s ;
}


/**
 * 返回分页链接字符串（前端使用）
 * @param int $page 当前页码 0或1均视为第一页
 * @param int $total 记录总数
 * @param int $pagesize 每页显示的记录数
 * @param int $number   每页显示的分页链接数量
 * @param string $url   分页链接url样式，默认直接使用GET['page']传递
 *        但针对一些特殊情况，如ajax分页，静态页分页，可能不能使用GET传递页码，需要定义url样式
 *        其中必须包含{page}字符串，将被替换为对应的页码 如
 *        $url = 'list_{page}.html';
 *        $url = 'javascript:funct(a,b,{page})';
 * @return string 分页链接HTML代码
 */
function pageLinkForFront($page=0, $total=0, $pagesize=0, $number=10, $url='') {

    $page = max(intval($page),1);
    $total = intval($total);
    $pagesize = max(intval($pagesize),0);
    $pages = ceil($total/$pagesize);

    //如果记录数为0，则提示结果是空。
    if ($total == 0){
        $s = "<p style='color: #F39C12'>啊哦，一条记录都没有找到~</p>";
        return $s;
    }

    if($pages < 2) return ;

    if(!$url) {
        $url = $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];
        $url = preg_replace('/\&*page=\d*\b/','',$url);
        $url .= empty($_SERVER['QUERY_STRING']) ? "page={page}" : "&page={page}";
    }

    $s = '';

    if($page > 1) {
        $s .= '<a class="page-item" href="'.str_replace('{page}',1,$url).'">首页</a>';
        $s .= '<a class="page-item" href="'.str_replace('{page}',($page - 1),$url).'">上一页</a>';
    }else {
        $s .= '<a class="page-item active" href="javascript:return false;">首页</a>';
        $s .= '<a class="page-item active" href="javascript:return false;">上一页</a>';
    }

    if($number%2) {
        $start = max($page -ceil($number/2)+1,1);
    }else {
        $start = max($page- intval($number/2),1);
    }


    $end = min($start+$number-1,$pages);

    if(($end - $start) < ($number-1)) {
        $start = max($end -$number+1, 1);
    }


    for($i = $start; $i <= $end; $i++) {
        $sel = $page == $i ? 'class="current btn btn-primary"' : '';
        $link = str_replace('{page}',$i,$url);
        $href = $page == $i ? '' : "href=\"$link\"";
        $s .= "<a $sel $href class='page-item'>$i</a>";
    }

    if($page < $pages) {
        $link = str_replace('{page}',$page+1,$url);
        $s .= "<a class=\"page-item\" href=\"$link\">下一页</a>";
        $link = str_replace('{page}',$pages,$url);
        $s .= "<a class=\"page-item\" href=\"$link\">尾页</a>";
    }else {
        $s .= " <a class=\"page-item active\" href=\"javascript:return false;\">下一页</a>";
        $s .= "<a class=\"page-item active\" href=\"javascript:return false;\">尾页</a>";
    }

    $s .= "<a style='color: #00c292' class=\"total\">总数：$total </a>";
    return $s ;
}

