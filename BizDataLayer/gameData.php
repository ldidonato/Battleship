<?php
	//include dbInfo
	//require_once("DB.class.php");
	//include exceptions
	require_once('./BizDataLayer/exception.php');
	
	function getOUsers(){
        global $mysqli;
        $sql="SELECT * FROM BattleshipOnline";
        try{
            if($stmt=$mysqli->prepare($sql)){
                //$stmt->bind_param("ss",$email,$pass);
                $data=returnJson($stmt);
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while getting online users");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }

/*********************************Challenge Info****************************/
	function getChallengeTable($email){
        global $mysqli;
        $sql="SELECT * FROM BattleshipChallenges WHERE Opponent=? OR Challenger=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$email,$email);
                $data=returnJson($stmt);
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while getting challenge info");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }

    function sendChallengeData($challenger,$opponent){
        global $mysqli;
        $sql="INSERT INTO BattleshipChallenges (Challenger, Opponent, status) VALUES (?,?,'pending')";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$challenger,$opponent);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while challenging someone");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
        
    }
    
    function denyChallengeData($you,$challenger){
        global $mysqli;
        $sql="UPDATE BattleshipChallenges SET status='denied' WHERE Opponent = ? AND Challenger = ?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$you,$challenger);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while denying someone");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function removeChallengeData($you,$opponent){
        global $mysqli;
        $sql="DELETE FROM BattleshipChallenges WHERE Challenger = ? AND Opponent = ?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$you,$opponent);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while denying someone");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function createGame($you,$opponent){
        global $mysqli;
        $sql="INSERT INTO `BattleshipGameList`(`Player1`, `Player2`) VALUES (?,?)";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$you,$opponent);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while creating game part1");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    //NOT USED
/*
    function getLatestGameID($Player1, $Player2){
        global $mysqli;
        $sql="SELECT GameID FROM BattleshipGameList WHERE Player1=? AND Player2=? ORDER BY GameID DESC LIMIT 0,1";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$Player1,$Player2);
                $data=returnJson($stmt);
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while getting lastest game ID");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }*/
    function getGameByID($gid){
        global $mysqli;
        $sql="SELECT * FROM BattleshipGameList WHERE GameID=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
                $data=returnJson($stmt);
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while getting game by id");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function getGamesData($email){
        global $mysqli;
        $sql="SELECT * FROM `BattleshipGameList` WHERE `Player1` = ? OR `Player2` = ?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$email,$email);
                $data=returnJson($stmt);
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while getting games data");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
/*********************************Game Info****************************/

   function removeIDfromGameList($gid){
        global $mysqli;
        $sql="DELETE FROM BattleshipGameList WHERE GameID=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while denying someone");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function loseGameData($you,$gid){
        global $mysqli;
        $sql="UPDATE `BattleshipGameList` SET `winner`=? WHERE GameID=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("si",$you,$gid);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while denying someone");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }







/*********************************Utilities*********************************/
/*************************
	returnJson
	takes: prepared statement
		-parameters already bound
	returns: json encoded multi-dimensional associative array
*/
function returnJson ($stmt){
	$stmt->execute();
	$stmt->store_result();
 	$meta = $stmt->result_metadata();
    $bindVarsArray = array();
	//using the stmt, get it's metadata (so we can get the name of the name=val pair for the associate array)!
	while ($column = $meta->fetch_field()) {
    	$bindVarsArray[] = &$results[$column->name];
    }
	//bind it!
	call_user_func_array(array($stmt, 'bind_result'), $bindVarsArray);
	//now, go through each row returned,
	while($stmt->fetch()) {
    	$clone = array();
        foreach ($results as $k => $v) {
        	$clone[$k] = $v;
        }
        $data[] = $clone;
    }
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	//MUST change the content-type
	header("Content-Type:text/plain");
	// This will become the response value for the XMLHttpRequest object
    return json_encode($data);
}
?>