<?php
	//this is the end, the BiZData Layer...
	//will always and ONLY be called from tthe service layer...
	
		//$gameId - the game Id
		//$userId - the user Id to check in the game if it is their turn
		function checkTurnData($gameId, $userId){
			//hard coded- will need to change to a db call eventually
			$t = '[{"gameId":55,"turn":"false"}]';
			return $t;
		}



?>