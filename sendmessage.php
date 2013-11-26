<?php
header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");header("Cache-Control: no-cache, must-revalidate");header("Pragma: no-cache");header("Content-Type: text/xml;charset=utf-8");$dp="";include "{$dp}class.dbconnect.php";include("{$dp}class.mail.php");include("{$dp}class.gammu.php");/*.require_module 'mysqli';.*//*.require_module 'standard';.*/set_time_limit(0);$SA=new serverAdminMail();$TM=new contactFormMail();$call=new callContactForm();$sendSuccess;$xmlSuccess=true;$servtime=$_SERVER['REQUEST_TIME'];

//Change variables below to fit your needs.
//dmv is for contact method for SMS 7=gammu, 5=google voice, 1=email as SMS
//pcontact is your phone, with email extension
//econtact is your email
$dmv=7;$pcontact="";$econtact="";

if(isset($_POST['subTime'])){$initTime=$_POST['subTime'];$tranTime=$servtime-$initTime;}else{$tranTime=0;}
if(isset($_POST['subRef'])){$subRef=$_POST['subRef'];}else{$subRef=null;}
if(isset($_POST['subAgent'])){$subAgent=$_POST['subAgent'];}else{$subAgent=null;}
if(isset($_POST['subName'])){$subName=$_POST['subName'];}else{$subName=null;}
if(isset($_POST['subIP'])){$subIP=$_POST['subIP'];}else{$subIP=null;}
if(isset($_POST['subCType'])){$subCType=$_POST['subCType'];}else{$subCType=null;}
if(isset($_POST['subSession'])){$subSession=$_POST['subSession'];}else{$subSession=null;}
if(isset($_POST['subContact'])){$subContact=$_POST['subContact'];}else{$subContact=null;}
if(isset($_POST['subMessage'])){$subMessage=$_POST['subMessage'];}else{$subMessage=null;}

if($subCType=="text"){$sendSuccess=$TM->send_mail($dmv,"{$subName} said: {$subMessage}  Their contact info is {$subContact}.",$pcontact);}
else if($subCType=="email"){$sendSuccess=$TM->send_mail(1,"{$subName} said: {$subMessage}  Their contact info is {$subContact}.",$econtact);}
else if($subCType=="call"){$sendSuccess=$call->call($subContact);$TM->send_mail($dmv,"{$subName} said: {$subMessage}  Their contact info is {$subContact}.",$pcontact);}
else{$SA->which_mail_client(1,"Contact Type incorrect.","{$subName} said: {$subMessage}  Their contact info is {$subContact}. Contact type was: {$subCType}.");$sendSuccess=false;}

$DB=new ConnectContact();$link=$DB->make_connect(true,false);
if(!$link){$SA->which_mail_client(1,"Database Link failed","Link returned false, problem with MySQLi Class. Hopefully another message came. Good Luck!");}
else
{
	$initTime=@mysqli_real_escape_string($link,mb_substr($initTime,0,11));
	$servtime=@mysqli_real_escape_string($link,mb_substr($servtime,0,11));
	$tranTime=@mysqli_real_escape_string($link,mb_substr($tranTime,0,11));
	$subRef=@mysqli_real_escape_string($link,mb_substr($subRef,0,100));
	$subAgent=@mysqli_real_escape_string($link,mb_substr($subAgent,0,100));
	$subIP=@mysqli_real_escape_string($link,mb_substr($subIP,0,15));
	$subSession=@mysqli_real_escape_string($link,mb_substr($subSession,0,25));
	$subName=@mysqli_real_escape_string($link,mb_substr($subName,0,30));
	$subContact=@mysqli_real_escape_string($link,mb_substr($subContact,0,30));
	$subMessage=@mysqli_real_escape_string($link,mb_substr($subMessage,0,550));
	$subCType=@mysqli_real_escape_string($link,mb_substr($subCType,0,10));
	$query="insert into message_info (pageLoad,messageSubmit,timeElapse,referrer,agent,ip,session,name,contact,message,contactType,success) values ({$initTime},{$servtime},{$tranTime},'{$subRef}','{$subAgent}','{$subIP}','{$subSession}','{$subName}','{$subContact}','{$subMessage}','{$subCType}',1);";
	$qr=@mysqli_query($link,$query);
	if(!$qr){$xmlSuccess=false;$SA->which_mail_client(1,"Insert error: \n Query:\n {$query}");}
	$DB->close();
}
echo "<?xml version='1.0' ?><response><send>{$sendSuccess}</send><insert>{$xmlSuccess}</insert></response>";
?>