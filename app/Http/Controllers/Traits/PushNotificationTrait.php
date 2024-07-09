<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait PushNotificationTrait {

	function sendNotification(){
		//print_r('expression'); die;
	    $friendToken = [];
	   // $usernames = $request->all()['friend_usernames'];
	  //  $dialog_id = $request->all()['dialog_id'];
	    //foreach ($usernames as $username) {
	        $friendToken[] = 'e-GWEAl82YA:APA91bH3O3pAGCAW5wBvF6L9rBmfs8HLhSeJebZpivaBk12ninPBMnRTbU0Var-X5THgmrU0Md4OlEX9r-gWou_d429r6ysmyT-wOfmfFWRuRaQHS8uY1A4KqoWbpJjXz7yihkx8_gTk';
	  //  }

	    $url = 'https://fcm.googleapis.com/fcm/send';
	   // foreach ($friendToken as $tok) {
	        $fields = array(
	            'to' => 'e-GWEAl82YA:APA91bH3O3pAGCAW5wBvF6L9rBmfs8HLhSeJebZpivaBk12ninPBMnRTbU0Var-X5THgmrU0Md4OlEX9r-gWou_d429r6ysmyT-wOfmfFWRuRaQHS8uY1A4KqoWbpJjXz7yihkx8_gTk',
	            'data' => $message = array(
	                "message" => 'hello',
	               // "dialog_id" => $dialog_id
	            )
	        );
	        $headers = array(
	            'Authorization: AIzaSyAXDvwrxwYqc2AgyErwbh6e46LbQAsHsLU',
	            'Content-type: Application/json'
	        );
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	        curl_exec($ch);
	        curl_close($ch);
	   // }

	    $res = ['error' => null, 'result' => "friends invited"];

	    return $res;
	}


	function sendEmail(){
	    
	}

}