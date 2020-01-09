<?php 
	require_once("config.inc.php");
	require_once( "my_func.inc.php" );
    
	function check_login($user_id,$password){
		global $view_errors,$OJ_EXAM_CONTEST_ID,$MSG_WARNING_DURING_EXAM_NOT_ALLOWED,$MSG_WARNING_LOGIN_FROM_DIFF_IP;	
		$pass2 = 'No Saved';
		session_destroy();
		session_start();
		$sql="SELECT user_id, password FROM users WHERE user_id='$user_id' and defunct='N' ";
		$result=pdo_query($sql);
		$nums = count($result);
		if($nums == 1){
			$row = $result[0];
			if( pwCheck($password, $row['password'])){
				$user_id=$row['user_id'];
				/*获取当前登录IP*/
				$ip = ($_SERVER['REMOTE_ADDR']);
				if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
				    $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
				    $tmp_ip=explode(',',$REMOTE_ADDR);
				    $ip =(htmlentities($tmp_ip[0],ENT_QUOTES,"UTF-8"));
				}

				/*竞赛相关*/
//				if(isset($OJ_EXAM_CONTEST_ID)&&intval($OJ_EXAM_CONTEST_ID)>0){
//					$ccid=$OJ_EXAM_CONTEST_ID;
//					$sql="select min(start_time) from contest where start_time<=now() and end_time>=now() and contest_id>=?";
//					$rows=pdo_query($sql,$ccid);
//					$start_time=$rows[0][0];
//					$sql="select ip from loginlog where user_id=? and time>? order by time desc";
//					$rows=pdo_query($sql,$user_id,$start_time);
//					$lastip=$rows[0][0];
//					if((!empty($lastip))&&$lastip!=$ip) {
//						$view_errors="$MSG_WARNING_LOGIN_FROM_DIFF_IP($lastip/$ip) $MSG_WARNING_DURING_EXAM_NOT_ALLOWED!";
//						return false;
//					}
//				}
				$now = getNow();
				$sql="INSERT INTO loginlog (user_id, ip, time) VALUES('$user_id', '$ip', '$now')";
				$result_loginlog = pdo_query($sql);

				$sql="UPDATE users set access_time='$now' WHERE user_id='$user_id'";
				$result_users = pdo_query($sql);

				if ($result_loginlog && $result_users){
					return $user_id;
				}else{
					return false;
				}
			}
		}
		return false; 
	}
?>
