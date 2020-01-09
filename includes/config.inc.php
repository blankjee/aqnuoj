<?php
    //ini_set("error_reporting","E_ALL & ~E_NOTICE");
    header("Content-type: text/html; charset=utf-8");
    /*系统参数设置*/
    //数据库
    static 	$DB_HOST="localhost";
    static 	$DB_NAME="aqnuoj";
    static 	$DB_USER="root";
    static 	$DB_PASS="";

    //管理员用户
    define('ADMIN_USER', "admin");
    //分页设置
    static $PAGE_EACH = 20;


    //系统变量
    static 	$OJ_NAME="AQNUOJ";
    static 	$OJ_HOME="./";
    static 	$OJ_ADMIN="root@localhost";
    static 	$OJ_DATA="/home/judge/data";
    static 	$OJ_BBS="";
    static  $OJ_ONLINE=false;
    static  $OJ_LANG="en";
    static  $OJ_SIM=false;
    static  $OJ_DICT=false;
    static  $OJ_LANGMASK=0; //1:C 2:CPP 4:Pascal 8:Java 16:Ruby 32:Bash 1008:所有语言
    static  $OJ_EDITE_AREA=true;//true: 语法高亮显示
    static  $OJ_ACE_EDITOR=true;
    static  $OJ_AUTO_SHARE=false;//true: One can view all AC submit if he/she has ACed it onece.
    static  $OJ_CSS="white.css";
    //static  $OJ_SAE=false; //用新浪云的引擎
    static  $OJ_VCODE=false;
    static  $OJ_APPENDCODE=false;
    static  $OJ_CE_PENALTY=false;
    static  $OJ_PRINTER=false;
    static  $OJ_MAIL=false;
    static  $OJ_MEMCACHE=false;
    static  $OJ_MEMSERVER="127.0.0.1";
    static  $OJ_MEMPORT=11211;
    static  $OJ_REDIS=false;
    static  $OJ_REDISSERVER="127.0.0.1";
    static  $OJ_REDISPORT=6379;
    static  $OJ_REDISQNAME="hustoj";
    static  $SAE_STORAGE_ROOT="http://hustoj-web.stor.sinaapp.com/";
    static  $OJ_CDN_URL="";  //  http://cdn.hustoj.com/  https://raw.githubusercontent.com/zhblue/hustoj/master/trunk/web/
    static  $OJ_TEMPLATE="bs3"; //使用的默认模板, [bs3 ie ace sweet sae] work with discuss3, [classic bs] work with discuss
    if(isset($_GET['tp'])) $OJ_TEMPLATE=$_GET['tp'];
    static  $OJ_LOGIN_MOD="aqnuoj";
    static  $OJ_REGISTER=true; //允许注册新用户
    static  $OJ_REG_NEED_CONFIRM=false; //新注册用户需要审核
    static  $OJ_NEED_LOGIN=false; //需要登录才能访问
    static  $OJ_RANK_LOCK_PERCENT=0; //比赛封榜时间比例
    static  $OJ_SHOW_DIFF=false; //是否显示WA的对比说明
    static  $OJ_TEST_RUN=false; //提交界面是否允许测试运行
    static  $OJ_BLOCKLY=false; //是否启用Blockly界面
    static  $OJ_ENCODE_SUBMIT=false; //是否启用base64编码提交的功能，用来回避WAF防火墙误拦截。
    static  $OJ_OI_1_SOLUTION_ONLY=false; //比赛是否采用noip中的仅保留最后一次提交的规则。true则在新提交发生时，将本场比赛该题老的提交计入练习。
    static  $OJ_SHOW_METAL=true;//榜单上是否按比例显示奖牌
    static  $OJ_RANK_LOCK_DELAY=3600;//赛后封榜持续时间，单位秒。根据实际情况调整，在闭幕式颁奖结束后设为0即可立即解封。
    static  $OJ_BENCHMARK_MODE=true; //此选项将影响代码提交，不再有提交间隔限制，提交后会返回solution id

    //static  $OJ_EXAM_CONTEST_ID=1000; // 启用考试状态，填写考试比赛ID
    //static  $OJ_ON_SITE_CONTEST_ID=1000; //启用现场赛状态，填写现场赛比赛ID

    /* share code */
    static  $OJ_SHARE_CODE = false; // 代码分享功能

    //$OJ_ON_SITE_TEAM_TOTAL用于根据比例的计算奖牌的队伍总数
    //CCPC比赛的一种做法是比赛结束后导出终榜看AC至少1题的不打星的队伍数，现场修改此值即可正确计算奖牌
    //0表示根据榜单上的出现的队伍总数计算(包含了AC0题的队伍和打星队伍)
    static $OJ_ON_SITE_TEAM_TOTAL=0;

    static $OJ_OPENID_PWD = '8a367fe87b1e406ea8e94d7d508dcf01';

    /* weibo config here */
    static  $OJ_WEIBO_AUTH=false;
    static  $OJ_WEIBO_AKEY='1124518951';
    static  $OJ_WEIBO_ASEC='df709a1253ef8878548920718085e84b';
    static  $OJ_WEIBO_CBURL='http://192.168.0.108/JudgeOnline/login_weibo.php';

    /* renren config here */
    static  $OJ_RR_AUTH=false;
    static  $OJ_RR_AKEY='d066ad780742404d85d0955ac05654df';
    static  $OJ_RR_ASEC='c4d2988cf5c149fabf8098f32f9b49ed';
    static  $OJ_RR_CBURL='http://192.168.0.108/JudgeOnline/login_renren.php';
    /* qq config here */
    static  $OJ_QQ_AUTH=false;
    static  $OJ_QQ_AKEY='1124518951';
    static  $OJ_QQ_ASEC='df709a1253ef8878548920718085e84b';
    static  $OJ_QQ_CBURL='192.168.0.108';



    /*公共函数*/
    function countRate($a, $b){
        if ($b <= 0) {
            return 0;
        }
        return round($a * 1.0 / $b,2);
    }
    function cleanParameter($method, $str){
        if ($method == 'post' || $method == 'POST'){
            if ($str != null){
                if (isset($_POST[$str])){
                    return $_POST[$str];
                }
            }
        }else if ($method == 'get' || $method == 'GET'){
            if ($str != null){
                if (isset($_GET[$str])){
                    return $_GET[$str];
                }
            }
        }
        return NULL;
    }

    /*初始化程序*/
    //开启SESSION
    session_start();
    //打开数据连接
    require_once("pdo.php");

?>
