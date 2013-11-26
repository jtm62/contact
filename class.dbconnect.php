<?php
	/*.require_module 'mysqli';.*/
	/*.require_module 'standard';.*/
	abstract class InnoDBconnector
	{
		private $host="";private $user="";private $pass="";private $needdb="";private /*.object.*/ $db=null;private $goodconnect=false;
		private $db_link=false;private $conn=false;private $myerror=true;private $type=0;private $sslreq=false;private $acreq=false;private $optset=false;
		private $ssldb=true;private static $sslkey="/etc/mysql/newcerts/client-key.pem";private static $sslcert="/etc/mysql/newcerts/client-cert.pem";
		private static $sslca="/etc/mysql/newcerts/ca-cert.pem";private /*.string.*/ $sslcapath=null;private /*.string.*/ $sslcipher=null;		
		
		public function setHost(/*.string.*/$host){$this->host=$host;}
		public function setUser(/*.string.*/$user){$this->user=$user;}
		public function setPass(/*.string.*/$pass){$this->pass=$pass;}
		public function setDB(/*.string.*/$db){$this->needdb=$db;}
		public function getHost(){return $this->host;}
		public function getUser(){return $this->user;}
		public function getPass(){return $this->pass;}
		public function getDB(){return $this->db;}
		
		public function connect(/*.string.*/$host,/*.string.*/$user,/*.string.*/$pass,/*.string.*/$needdb,/*.boolean.*/$sslreq,/*.boolean.*/$acreq)
		{
			$this->host=$host;$this->user=$user;$this->pass=$pass;$this->needdb=$needdb;$this->sslreq=$sslreq;$this->acreq=$acreq;
			$this->db=@mysqli_init();
			if(!$this->db)
			{
				if($this->myerror)
				{
					$this->errorFunct($this->type=1);
				}
				return false;
			}
			else
			{
				if($this->sslreq){$this->ssldb=@mysqli_ssl_set($this->db,self::$sslkey,self::$sslcert,self::$sslca,$this->sslcapath,$this->sslcipher);}
				if(!$this->ssldb)
				{
					if($this->myerror)
					{
						$this->errorFunct($this->type=3);
					}
				}
				else
				{
					if($this->est_con()){return $this->db;}
					else{return false;}
				}
			}
		}
		
		public function close()
		{
			if($this->conn){@mysqli_close($this->db);$this->conn=false;}
			else{if($this->myerror){return $this->errorFunct($this->type=5);}}
			return true;
		}
		
		private function est_con()
		{
			if($this->sslreq){$this->db_link=@mysqli_real_connect($this->db,$this->host,$this->user,$this->pass,$this->needdb,null,null,MYSQLI_CLIENT_SSL);}
			else{$this->db_link=@mysqli_real_connect($this->db,$this->host,$this->user,$this->pass,$this->needdb);}
			if(!$this->db_link)
			{
				if($this->myerror){$this->errorFunct($this->type=4);}
			}
			else
			{
				$this->conn=true;
				if($this->acreq)
				{
					if(@mysqli_autocommit($this->db,false)){return $this->db_link;}
					else{if($this->myerror){$this->errorFunct($this->type=2);}}
				}
				else{return $this->db_link;}
			}
		}
		
		private function errorFunct(/*.int.*/$type)
		{
			if(empty($type)){return false;}
			/*else //Production
			{
				$errorMessage=new serverAdminMail();
				if($type==1){$errorMessage->which_mail_client(2,"No MySQLi Session!","MySQLi could not be initialized.");}
				else if($type==2){$errorMessage->which_mail_client(2,"Autocommit not disabled","Unable to turn autocommit off, please check.");}
				else if($type==3){$errorMessage->which_mail_client(2,"No SSL Certificates!","Provided SSL Certs are invalid. Fix it!");}
				else if($type==4){$errorMessage->which_mail_client(2,"Database not connected","Connect Error Number: ".mysqli_connect_errno()."\n\nError Information: ".mysqli_connect_error());}
				else if($type==5){$errorMessage->which_mail_client(2,"Cannot close database!","Database connection not established. Ending process!");}
			}*/
			else //Test
			{
				echo "error type: {$type}\n";
				if($type==1){echo "No MySQLi Session!","MySQLi could not be initialized.\n";}
				else if($type==2){echo "Autocommit not disabled","Unable to turn autocommit off, please check.\n";}
				else if($type==3){echo "No SSL Certificates!","Provided SSL Certs are invalid. Fix it!\n";}
				else if($type==4){echo "Database not connected","Connect Error Number: ".mysqli_connect_errno()."\n\nError Information: ".mysqli_connect_error()."\n";}
				else if($type==5){echo "Cannot close database!","Database connection not established. Ending process!\n";}
			}
			return true;
		}
	}
	
	
	class ConnectContact extends InnoDBconnector
	{
		private static $host="";private static $user="";private static $pass="";private static $db="messaging_logger";
		private $sslreq=false;private $acreq=false;
		
		public function make_connect(/*.boolean.*/$sslreq,/*.boolean.*/$acreq)
		{
			$this->sslreq=$sslreq;$this->acreq=$acreq;
			$connect_var=parent::connect(self::$host,self::$user,self::$pass,self::$db,$this->sslreq,$this->acreq); return $connect_var;
		}
		public function close_connect(){parent::close();}
		
		public function __destruct(){}
	}
	
	class SessionLog extends InnoDBconnector
	{
		private static $host="";private static $user="";private static $pass="";private static $db="visitor_logs";
		private $sslreq=false;private $acreq=false;
		
		public function make_connect(/*.boolean.*/$sslreq,/*.boolean.*/$acreq)
		{
			$this->sslreq=$sslreq;$this->acreq=$acreq;
			$connect_var=parent::connect(self::$host,self::$user,self::$pass,self::$db,$this->sslreq,$this->acreq); return $connect_var;
		}
		public function close_connect(){parent::close();}
		
		public function __destruct(){}
	}

?>
