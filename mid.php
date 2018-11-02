<?php
	//this is the entry point to our architecture.  All goes through this...
	// input
	//	a - for area (ex: chat, game, login, x)
	//	method - which method to call in the area...
	//	data (optional) - if there is data, send it along...
	
	
	if(isset($_GET['method']) || isset($_POST['method'])){
		foreach( glob("./svcLayer/".$_REQUEST['a']."*.php") as $filename){
			require $filename;
		}
		//I have loaded all of the scripts in the 'a' (area)
		//call_user_func(methodToCall, arg, arg, arg...);
		$request=@call_user_func($_REQUEST['method'],$_REQUEST['data'],$_SERVER['REMOTE_ADDR'],$_COOKIE['token']);
		//getTurn({},ip,token)
		if($request){
			//throw the json back up!
			header('Content-Type:text/plain');
			echo $result;
		}
	}
?>