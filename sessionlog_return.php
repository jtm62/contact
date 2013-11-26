<?php
header("Last-Modified: ".gmdate("D, d M Y H:i:s" )."GMT");header("Cache-Control: no-cache, must-revalidate");header("Pragma: no-cache");header("Content-Type: text/xml;charset=utf-8");$dp="";include "{$dp}class.dbconnect.php";include("{$dp}class.mail.php");/*.require_module 'mysqli';.*//*.require_module 'standard';.*/set_time_limit(0);
$servtime=$_SERVER['REQUEST_TIME'];$myhost="";$mytimeout=30*60;
$a=@session_id();if(empty($a))@session_start();$a = @session_id();

@session_set_cookie_params($mytimeout);@session_cache_expire($mytimeout/60);

$ipaddress=$_SERVER['REMOTE_ADDR'];
$page="http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
$referrer=$_SERVER['HTTP_REFERER'];
$datetime=date("Y-m-d H:i:s",mktime(date("H",$servtime),date("i",$servtime),date("s",$servtime),date("m",$servtime),date("d",$servtime),date("Y",$servtime)));
$useragent=$_SERVER['HTTP_USER_AGENT'];
$remotehost=@getHostByAddr($ipaddress);

$DB=new SessionLog();$link=$DB->make_connect(true,false);
if(!$link){$SA->which_mail_client(2,"Database Link failed","Link returned false, problem with MySQLi Class. Hopefully another message came. Good Luck!");}
else
{
	if($ipaddress==$myhost){$sql="insert into trivia_site_mine(SessionID,VisIP,VisHost,VisRef,VisURL,VisDate,VisAgent) values ('{$a}','{$ipaddress}','{$remotehost}','{$referrer}','{$page}','{$datetime}','{$useragent}');";}
	else{$sql="insert into trivia_site(SessionID,VisIP,VisHost,VisRef,VisURL,VisDate,VisAgent) values ('{$a}','{$ipaddress}','{$remotehost}','{$referrer}','{$page}','{$datetime}','{$useragent}');";}
	$qr=@mysqli_query($link,$sql);
	$DB->close();
}
echo "<?xml version='1.0' ?><response><servtime>{$servtime}</servtime><ip>{$ipaddress}</ip><session>{$a}</session><ref>{$referrer}</ref><agent>{$useragent}</agent></response>";
?>