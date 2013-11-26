<?php
/*-----------------------------------------+
| ORGINAL AUTHOR INFORMATION
| Gammu phpClass
| Author      : Stieven R. Kalengkian
| Contact     : stieven.kalengkian@gmail.com
| Website     : www.sleki.org - My Blog http://stieven.glowciptamedia.com/
| Version     : 3.0
| Last Update : Dec, 08 2009
|
| MY INFORMATION
| Author      : Joshua T. McCauley
| Contact     : jtmccauley62@gmail.com
| Website     : www.jtmccauleyonline.com
| Version     : 1.0
| Last Update : 14 November 2013
| Notice	  : I modified this code to meet my requirements.  And much is different than the orginal source, but without it as a guide I would have been clueless.
------------------------------------------*/

abstract class gammu{
	/* Initializing gammu bin/EXE */
	//                  /usr/bin/gammu -c /etc/gammurc/config -s 0 --identify
	private $gammu="/usr/bin/gammu";public $data=array();private $errormes="";private $reponse=array();
	
	public function __construct($gammu_bin_location='',$gammu_config_file='',$gammu_config_section='')
	{
		$this->data[0]=false;
		$this->gammu = $gammu_bin_location ? $gammu_bin_location : '/usr/bin/gammu';
		if (!file_exists($this->gammu)) {
			$this->errorFunct(1);
		} else {
			$this->gammu = $gammu_config_file != '' ? $this->gammu." -c {$gammu_config_file}" : $this->gammu;
			$this->gammu = $gammu_config_section != '' ? $this->gammu." -s ". (int) $gammu_config_section ."" : $this->gammu;
		}
	}
	
	private function gammu_exec($options='--identify',$break=0) {
		$exec="/usr/bin/gammu -c /etc/gammurc/config -s 0 ".$options;
		exec($exec,$r);
		if ($break == 1) { return $r; }
		else { return $this->unbreak($r); }
	}
	
	private function unbreak($r) {
		for($i=0;$i<count($r);$i++) {
			$response.=$r[$i]."\r\n";
		}
		return $response;
	}
	
	public function Identify(&$response)
	{
		$r = $this->gammu_exec('--identify',1);
		if (preg_match("#Error opening device|No configuration file found|Gammu is not installed#si", $this->unbreak($r),$s)) {
			$response = $r;
			$this->errormes=print_r($s,true);
			$this->errorFunct(2);
			return false;
		}  else {
			for($i=0;$i<count($r);$i++) {
				//if (preg_match("#^(Manufacturer|Model|Firmware|IMEI|Product code|SIM IMSI).+:(.+)#",$r[$i],$s)) {
				if (preg_match("#^(.+):(.+)#",$r[$i],$s)) {
					if (trim($s[1]) and trim($s[2])) { $response[str_replace(" ","_",trim($s[1]))]=trim($s[2]); }
				}
			}
			$r = $this->gammu_exec('--monitor 1');
			for($i=0;$i<count($r);$i++) {
				if (preg_match("#^(.+):(.+)#",$r[$i],$s)) {
					if (trim($s[1]) and trim($s[2])) { $response[str_replace(" ","_",trim($s[1]))]=trim($s[2]); }
				}
			}
			return true;
		}
	}
	
	public function Get()
	{
		$r=$this->gammu_exec('--geteachsms 1',1);
		if(eregi("Error",$r[0])){$this->errorFunct(3); $tt=$this->Identify(); if($tt){$this->errorFunct(4);}return false;}
		else if(eregi("0 SMS parts in 0 SMS sequences",$r[1])){}
		else if(eregi("No response in specified timeout.",$r[0])){}
		//Everything below here is specifically designed to format and send message to an external email in an expected format for further parsing by a separate function.  Modify to do what you need to do with the messages...
		else{
			$data=array();$y=0;
			for($i=0;$i<count($r);$i++){
				$x=$i%11;
				if($x==0){$message=array();}
				$message[$x]=$r[$i];
				if($x==10){$data[$y]=$message;$y++;}
			}
			$takeme=count($data)/2;
			for($i=0;$i<$takeme;$i++){
				for($j=0;$j<11;$j++){
					if($j==0){$message=array();}
					if($j==0){
						list($badinfo,)=split(",",$data[$i][$j]);
						list(,$mesnum)=split(" ",$badinfo);
						$message["mesnum"]=$mesnum;
					}
					else if($j==3){
						list(,$badinfo)=split(" : ",$data[$i][$j]);
						$message["frec"]=trim($badinfo);
						$timerec=substr(trim($badinfo),0,27);
						$timegot=date("Y-m-d H:i:s",mktime(date("H",strtotime($timerec))+4,date("i",strtotime($timerec)),date("s",strtotime($timerec)),date("m",strtotime($timerec)),date("d",strtotime($timerec)),date("Y",strtotime($timerec))));
						$message["rec"]=$timegot;
					}
					else if($j==6){
						list(,$badinfo)=split(":",$data[$i][$j]);
						$message["phone"]=(substr(str_replace("\"","",trim($badinfo)),2));
					}
					else if($j==9){
						$message["message"]=trim($data[$i][$j]);
					}
					else if($j==10){$this->data[$i]=$message;}
				}
			}
			return $this->forwardMessages();
		}
	}
	
	public function Send($number,$text){
		list($phone,)=explode('@',$number);$len=strlen($text);
		$respon = $this->gammu_exec("--sendsms TEXT {$phone} -len {$len} -text '{$text}'");
		if (eregi("OK",$respon)){return true;}else{return false;$this->errorFunct(5);}
	}
	
	private function deleteMessages($exp,&$response){
		$r=array();
		if($exp=="all"){
			for($i=0;$i<count($this->data);$i++){
				$r = $this->gammu_exec("--deletesms 4 {$this->data[$i]["mesnum"]} {$this->data[$i]["mesnum"]}",1);
				if(!empty($r)){$this->errorFunct(8,$r);}
			}
		}
		else{
			$r = $this->gammu_exec("--deletesms 4 {$exp} {$exp}",1);
			if(!empty($r)){$this->errorFunct(8,$r);}
		}
	}
	
	private function forwardMessages(){
		$GM=new gatewayMail();
		if(!$GM){$this->errorFunct(6);}
		else{
			for($i=0;$i<count($this->data);$i++){
				if($GM->send_mail($this->data[$i])){$this->deleteMessages($this->data[$i]["mesnum"],$response);}
				else{$this->errorFunct(7);}
			}
		}
		if(count($this->data)>0){return true;}else{return false;}
	}
	
	private function errorFunct(/*.int.*/$type,$crap)
		{
			if(empty($type)){return false;}
			else //Production
			{
				$errorMessage=new serverAdminMail(); //need to have class.mail.php included or error occurs here.
				if($type==1){$errorMessage->which_mail_client(2,"Cannot find {$this->gammu} or Gammu is not installed");}
				else if($type==2){$errorMessage->which_mail_client(2,"Failed to connect to device: "," {$this->errormes}");}
				else if($type==3){$errorMessage->which_mail_client(2,"Failed to get message from phone, running identify now to see if it is running!");}
				else if($type==4){$errorMessage->which_mail_client(2,"Identify function returned true, phone exists and is accessible, weird problem.");}
				else if($type==5){$errorMessage->which_mail_client(2,"Text Message failed to send through gateway, failing over to google voice.");}
				else if($type==6){$errorMessage->which_mail_client(2,"Could not connect to the gmail client, messages from phone are not forwarding or deleting at this time.  This means that the messages will not be parsed by the program and routed appropriately, for now.");}
				else if($type==7){$errorMessage->which_mail_client(2,"Messages did not forward correctly.  Not deleting from phone, will try again.");}
				else if($type==8){$errorMessage->which_mail_client(2,"Message not deleted from phone properly... Duplicate submissions likely.","{$crap}");}
			}
		}
}

class contactGammu extends gammu{
	private static $gammu_bin="/usr/bin/gammu";private static $gammu_config='/etc/gammurc/config';private static $gammu_config_section='0';
	
	public function __construct(){
		parent::__construct(self::$gammu_bin,self::$gammu_config,self::$gammu_config_section);
	}
	
	public function Send($number,$text){
		return parent::Send($number,$text);
	}
}

/*-----------------------------------------
|
| :::::::::::: EXAMPLE ::::::::::::::::::
|
-----------------------------------------*/

//$response=array();
//$sms=new contactGammu();

/* Sending SMS */
/*$response=$sms->Send('your_phone_here','test_message_here');

/*print response...*/
//print_r($response); 

?>
