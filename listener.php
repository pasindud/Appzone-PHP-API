<?php

/**
* @author: Pasindu De Silva <ppasindu@live.com>
* @license GNU Affero General Public License http://www.gnu.org/licenses/
*
*This is a send api for appzone this uses php function file_get_content
*@credits: Arunoda Susiripala <arunoda.susiripala@gmail.com>
*/

include_once 'lib/AppZoneReciever.php';
include_once 'lib/pdsender.php';

try{
	//create the receiver
	
	
	$reciever=new AppZoneReciever();

	$content = $reciever->getMessage(); // get the message content
   	$address = $reciever->getAddress(); // get the sender's address
   	$correlationId = $reciever->getCorrelator(); // get the correlation id of the messgae


   	// Setting the listener details
	$send= new smssender("http://127.0.0.1:8000/sms/", "APPID", "password");

	

	$keyword=strtolower($content);

	if($keyword=="onesms"){
			$res=$send->sms("Sms Sent to the address",  $reciever->getAddress());
	}
	elseif($keyword=="many")
		{
					$arrayone = array('fb57bc1434fa37ba3c79e71ee2184c49', 
									  'dhtrjj1434fa37ba3c79e71ee2184c49', 
									  'ft67bc1434fa37ba3c79e71eth184c49'
									  );


					$res=$send->smsmany(" Sms Sent to many addresses",$arrayone);
		}
		elseif($keyword=="broadcast")
			{
						$res=$send->broadcast("Broadcast the message");
			}
	
		
}
catch(AppZoneException $ex){
	//throws when failed sending or receiving the sms
		$send->WriteLog("ERROR: ");
	$send->WriteLog("ERROR: ".$ex->getstatusCode()." | ".$ex->getstatusMsg());

			
}





?>

