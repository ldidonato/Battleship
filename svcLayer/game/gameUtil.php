<?php
//ALL game goes in this folder
	//service layer
	//- wtf does that mean???
		//check security
			//should the person who is making this call be able to do this????
		//prep the data...
		
		//checkTurn(myData)
		//myData looks like: data:"44|55"
		function checkTurn($myData){
			//security?
			//fail - return{"response":"you suck"};
			
			//prep the data...
			$h=explode('|',$myData);
			$gameId=$h[0];
			$userId=$h[1];
			
			//call down the chain to the BizData Layer
			//all calls come from mid.php, so path is from there...
			include_once("BizDataLayer/checkTurn.php");
			echo(checkTurnData($gameId,$userId));
		}
?>