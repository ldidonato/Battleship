<?php

//go to the data layer and actually get the data I want
include('BizDataLayer/chatData.php');

	function getChat(){
		//should they be here?
		//no data to prep
		
		echo(getChatData());
	}

    function sayChat(d){
        //should I be able to say this?
        
        //prep the data
        //split data {username:dan,message:"howdy pard"}
        
        
        echo(sayChatData(d['username'],d['message']));
    }

?>