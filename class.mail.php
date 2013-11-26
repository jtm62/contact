<?php
	/*.require_module 'standard';.*/
	class textMessageSplit
	{
		private $splitlimit=121;private $mesoffset=0;private $message="";private $meslen=0; private $mesmod=0;private $nummes=0;private $i=0;private $messub="";public $Message=array();private $meshead="";
		public function splitMessage(/*.string.*/$message)
		{
			$this->message=$message;
			$this->meslen=strlen($this->message);
			if($this->meslen<=160){$this->nummes=1;}
			else{
				$this->mesmod=$this->meslen%$this->splitlimit;
				if($this->mesmod==0){$this->nummes=intval($this->meslen/$this->splitlimit);}
				else{$this->nummes=intval($this->meslen/$this->splitlimit)+1;}
			}
			do{
				if($this->nummes==1){$this->messub=(substr($this->message,$this->mesoffset,160));$this->Message[$this->i]=$this->messub;}
				else{$this->messub=(substr($this->message,$this->mesoffset,$this->splitlimit));$this->meshead=($this->i+1)."/".$this->nummes." ";$this->Message[$this->i]=$this->meshead.$this->messub;}
				$this->i++;$this->mesoffset+=$this->splitlimit;
			}while($this->i!=$this->nummes);
			return $this->Message;
		}
	}

	abstract class textMailer
	{
		private $to="";private $subject="";private $message="";private $headers="";private $tMessage=array();private $nummes=0;private $j=0;

		public function setSubject(/*.string.*/$subject){$this->subject=$subject;}
		public function setTo(/*.string.*/$to){$this->to=$to;}
		public function setMessage(/*.string.*/$message){$this->message=$message;}
		public function setHeaders(/*.string.*/$headers){$this->headers=$headers;}
		public function getSubject(){return $this->subject;}
		public function getTo(){return $this->to;}
		public function getMessage(){return $this->message;}
		public function getHeaders(){return $this->headers;}

		public function send_mail_email(/*.string.*/$to,/*.string.*/$subject,/*.string.*/$message,/*.string.*/$headers)
		{
			$this->to=$to;$this->subject=$subject;$this->message=$message;$this->headers=$headers;
			if(mail($this->to,$this->subject,$this->message,$this->headers)){return true;}else{return false;}
		}
		
		public function send_mail_text(/*.string.*/$to,/*.string.*/$subject,/*.string.*/$message,/*.string.*/$headers)
		{
			$this->message=$subject." ".$message;
			$this->to=$to;
			$this->headers=$headers;
			mail($this->to,"",$this->message,$this->headers);
		}
	}
	
abstract class GoogleVoice
{
    private static $username;
    private static $password;
    public $status;
    private $lastURL;
    private $login_auth;
    private $inboxURL = 'https://www.google.com/voice/b/0/m/';
    private $loginURL = 'https://www.google.com/accounts/ClientLogin';
    private $smsURL = 'https://www.google.com/voice/m/sendsms';
	private $callURL = 'https://www.google.com/voice/call/connect/';

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getLoginAuth()
    {
        $login_param = "accountType=GOOGLE&Email={$this->username}&Passwd={$this->password}&service=grandcentral&source=com.lostleon.GoogleVoiceTool";
        $ch = curl_init($this->loginURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_2_1 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5H11 Safari/525.20");
        curl_setopt($ch, CURLOPT_REFERER, $this->lastURL);
        curl_setopt($ch, CURLOPT_POST, "application/x-www-form-urlencoded");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $login_param);
        $html = curl_exec($ch);
        $this->lastURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        $this->login_auth = $this->match('/Auth=([A-z0-9_-]+)/', $html, 1);
        return $this->login_auth;
    }

    public function get_rnr_se()
    {
        $this->getLoginAuth();
        $ch = curl_init($this->inboxURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = array("Authorization: GoogleLogin auth=".$this->login_auth, 'User-Agent: Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_2_1 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5H11 Safari/525.20');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $html = curl_exec($ch);
        $this->lastURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        $_rnr_se = $this->match('!<input.*?name="_rnr_se".*?value="(.*?)"!ms', $html, 1);
        return $_rnr_se;
    }

    public function sms($to_phonenumber, $smstxt)
    {
        $_rnr_se = $this->get_rnr_se();
		list($phone,)=explode('@',$to_phonenumber);
        $sms_param = "id=&c=&number=".urlencode($phone)."&smstext=".urlencode($smstxt)."&_rnr_se=".urlencode($_rnr_se);
        $ch = curl_init($this->smsURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = array("Authorization: GoogleLogin auth=".$this->login_auth, 'User-Agent: Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_2_1 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5H11 Safari/525.20');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_REFERER, $this->lastURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sms_param);      
        $this->status = curl_exec($ch);
        //$this->lastURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
        if($this->status==false||!$this->status){return false;}else{return true;}
    }
	
	public function callNumber($number,$from_number)
	{
		$_rnr_se = $this->get_rnr_se();
        // Send HTTP POST request.
		$ch = curl_init($this->callURL);
		curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = array("Authorization: GoogleLogin auth=".$this->login_auth, 'User-Agent: Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_2_1 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5H11 Safari/525.20');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_REFERER, $this->lastURL);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'_rnr_se' => $_rnr_se,
			'forwardingNumber' => '+1'.$from_number,
			'outgoingNumber' => $number,
			'phoneType' => 2,
			'remember' => 0,
			'subscriberNumber' => 'undefined'
		));
		$this->status = curl_exec($ch);
		curl_close($ch);
        if($this->status==false||!$this->status){return false;}else{return true;}
	}

    private function match($regex,$str,$out_ary=0){return preg_match($regex,$str,$match)==1?$match[$out_ary]:false;}
}

class googleVoiceTexterContactForm extends GoogleVoice{
	private static $username="";private static $password="";
	
	public function __construct(){parent::__construct(self::$username,self::$password);}
	
	public function Send($to_phonenumber,$smstxt){return parent::sms($to_phonenumber,$smstxt);}
}

	class callContactForm extends GoogleVoice{
		private static $username="";private static $password=""; private static $phone="";
		
		public function __construct(){parent::__construct(self::$username,self::$password);}
		
		public function call($number){return parent::callNumber($number,self::$phone);}
	}

	class serverAdminMail extends textMailer
	{
		private $client=0;
		private $adminMail="";
		private $adminPhone="";
		private $mailHeaders="\r\nBCC: \r\n";
		private $phoneHeaders="From: \r\nBCC: \r\n";

		public function which_mail_client(/*.int.*/$client,/*.string.*/$subject,/*.string.*/$message)
		{
			$this->client=$client;
			parent::setSubject($subject);
			parent::setMessage($message);
			if($this->client==1){parent::send_mail_email($this->adminMail,parent::getSubject(),parent::getMessage(),$this->mailHeaders);}
			else{parent::send_mail_email($this->adminMail,parent::getSubject(),parent::getMessage(),$this->mailHeaders);parent::send_mail_text($this->adminPhone,parent::getSubject(),parent::getMessage(),$this->phoneHeaders);}
		}
	}
	
	class contactFormMail extends textMailer
	{
		private $mailHeaders="From: \r\nBCC: \r\n";

		public function send_mail(/*.int.*/$client,/*.string.*/$message,/*.string.*/$to)
		{
			$this->client=$client;parent::setTo($to);
			if($this->client==1){parent::setSubject("Contact Form Email.");parent::send_mail_email(parent::getTo(),parent::getSubject(),$message,$this->mailHeaders);}
			else{
				$SM=new textMessageSplit();$TG=new contactGammu();$gs=true;$gvs=true;$GVT=new googleVoiceTexterContactForm();
				$messages=$SM->splitMessage($message);
				$nummes=sizeof($messages);
				if($nummes>4){$nummes=4;}
				for($j=0;$j<$nummes;$j++){
					if($this->client==7){if($TG->Send(parent::getTo(),$messages[$j])){return true;}else{$gs=false;}}
					if(!$gs||$this->client==5){if($GVT->Send(parent::getTo(),$messages[$j])){return true;}else{$gvs=false;}}
					if((!$gvs&&!$gs)||($this->client!=7&&$this->client!=5)){if(parent::send_mail_text(parent::getTo(),"",$messages[$j],$this->mailHeaders)){return true;}else{return false;}}
				}
			}
		}
	}

/*-----------------------------------------
|
| :::::::::::: EXAMPLE ::::::::::::::::::
|
-----------------------------------------*/
/*$dp="";include("{$dp}class.gammu.php");
$call=new contactFormMail();
$response=$call->send_mail(7,'checking','enter phone here');

/* Sending SMS */
/*$success=$sms->Send('enter phone here','Rearranged class setup, the get function automatically forwards and deletes the messages now!');
print_r($response);*/
	
?>
