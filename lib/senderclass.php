<?php

/**
* @author: Pasindu De Silva <ppasindu@live.com>
* @license GNU Affero General Public License http://www.gnu.org/licenses/
*
*This is a send api for appzone this uses php function file_get_content
*
*/
class smssender
{
	private $address;
	private $message;
	private $correlator;

	public function __construct($server,$username,$password){
		$this->server=$server;
		$this->username=$username;
		$this->password=$password;
	}


	private function getAuthHeader(){
		$auth=$this->username . ':' . $this->password;
		$auth=base64_encode($auth);
		return 'Authorization: Basic ' . $auth;
	}

	public function broadcast($message)
	{
		if ($message!="") {
			$postdata="version=1.0&address=list:all_registered&message=".urlencode($message);

			return $this->sendmsg($postdata);
		}
		else{
		
					return false;
				}
		
	}

	public function sms($message,$address)
	{

		if (is_string($address) && trim($address)!="" && is_string($message) && trim($message)!="") {
			$postdata="version=1.0&address=tel:". urlencode($address) ."&message=".urlencode($message);
		return	$this->sendmsg($postdata);
		}

	}

	public function smsmany($message,$test= array())
	{
				foreach ($test as $value){
					$this->sms($message,$value);
				}
	}


	public function sendmsg($postdata)
	{
		
		$http_request = array(
				  'http'=>array(
					    'method'=>"POST",
					    'header'=>"Accept: text/xml\r\n" .
					              "Content-type: application/x-www-form-urlencoded\r\n".
					               $this->getAuthHeader()."\r\n",
					    'content'=>$postdata   
				  	)

				);

		$context = stream_context_create($http_request);

		$response = @file_get_contents($this->server, 0, $context);
	
		return $this->status_code_handle($response);
		
	}


	
		
	private function status_code_handle($response)
	{

		$respObj=simplexml_load_string($response);

		if ($respObj=="") {
			throw new AppZoneException ("Server URL is invalid",'500');
		}else
			{

				$status_code=$respObj->status_code;

				$retryCodes=array
							(
								'SBL-SMS-MT-5000',
								'SBL-SMS-MT-5001',
								'SBL-SMS-MT-5004',
							);

				if ($status_code=="SBL-SMS-MT-2000") {
					// Msg is successfully sent
					return true;
				}
				elseif ($status_code=="CORE-SMS-MT-4037") {
					// Message size exceeded limit 
					throw new AppZoneException ("Message excedding the limt",'500');
				}


				if ($status_code=="SBL-SMS-MT-5002") {
					// Too same msg has been retried till max limit
					throw new AppZoneException ("Too many retries",'SBL-SMS-MT-5002');
				}
			 	/*
			 	else
					{
						// Checks wheather the msg should be retried and retries
						// This is optional
						foreach ($retryCodes as $value){	

								if ($retryCodes==$value) {
									$this->sendmsg($postdata);
									return true;
								}
						
						}
					}*/
			}
	}


		

	public function WriteLog($logStream)
	{ // This loges all events happening in the system
			$_LOGFILE = 'LogData.log';
		
			$file = fopen($_LOGFILE, 'a');
			fwrite($file, '['.date(' M j G:i:s Y').'] '.$logStream.'\n');
			fclose($file);
	}


}

class AppZoneException extends Exception
{
	private $response_code, $respose_msg;

	
	public function __construct($state_code,$state_msg)
	{	
		$this->response_code=$state_code;
		$this->respose_msg=$state_msg;
	}

	public function getstatusCode()
	{
		$response_code= $this->response_code;
		return $response_code;
	}

	public function getstatusMsg()
	{	
		$respose_msg= $this->respose_msg;
		return $respose_msg;;
	}
}

?>
