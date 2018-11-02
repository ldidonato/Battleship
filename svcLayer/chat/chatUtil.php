<?php

//go to the data layer and actually get the data I want
include('BizDataLayer/chatData.php');

	function getChat(){
		//should they be here?
		//no data to prep
		
		echo(getChatData());
	}

?>