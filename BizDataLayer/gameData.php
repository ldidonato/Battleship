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
    function addGameInfoData($player,$gid){
        global $mysqli;
        $sql="INSERT INTO `BattleshipGameInfo`(`GameID`, `turn`, `p1boat1Hits`, `p2boat1Hits`, `p1boat2Hits`, `p2boat2Hits`, `started`, `p1Ready`, `p2Ready`) VALUES (?,?,2,2,3,3,'no','no','no')";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("is",$gid,$player);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while creating adding game data");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
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
    }
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
    function checkifStartedData($gid){
        global $mysqli;
        $sql="SELECT `started` FROM `BattleshipGameInfo` WHERE `GameID` = ?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
                $data=returnJson($stmt);
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while checking if started");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function readyPlayer1($gid){
        global $mysqli;
        $sql="UPDATE `BattleshipGameInfo` SET `p1Ready`='yes' WHERE `GameID`=?";
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
    function readyPlayer2($gid){
        global $mysqli;
        $sql="UPDATE `BattleshipGameInfo` SET `p2Ready`='yes' WHERE `GameID`=?";
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
    function getGameByInfoID($gid){
        global $mysqli;
        $sql="SELECT * FROM BattleshipGameInfo WHERE GameID=?";
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
    function allReadyData($gid){
        global $mysqli;
        $sql="SELECT * FROM `BattleshipGameInfo` WHERE `p1Ready`='yes' AND `p2Ready`='yes' AND `GameID`=?";
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
    function startedYesData($gid){
        global $mysqli;
        $sql="UPDATE `BattleshipGameInfo` SET `started`='yes' WHERE `GameID`=?";
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
    function submitDefenseP1Data($a,$b,$c,$d,$e,$gid){
        global $mysqli;
        $sql="INSERT INTO `BattleshipP1DefenseBoard`(`GameID`,".$a.",".$b.",".$c.",".$d.",".$e.") VALUES (?,'full','full','full','full','full')";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while submitting p1 defense data");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function submitDefenseP2Data($a,$b,$c,$d,$e,$gid){
        global $mysqli;
        $sql="INSERT INTO `BattleshipP2DefenseBoard`(`GameID`,".$a.",".$b.",".$c.",".$d.",".$e.") VALUES (?,'full','full','full','full','full')";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while submitting p2 defense data");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function submitAttackP1Data($gid){
        global $mysqli;
        $sql="INSERT INTO `BattleshipP1AttackBoard`(`GameID`) VALUES (?);";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while submitting p2 defense data");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function submitAttackP2Data($gid){
        global $mysqli;
        $sql="INSERT INTO `BattleshipP2AttackBoard`(`GameID`) VALUES (?);";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while submitting p2 defense data");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function getP1DefenseByID($id,$gid){
        global $mysqli;
        $sql="SELECT ".$id." FROM `BattleshipP2DefenseBoard` WHERE `GameID`=?";
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
    function getP2DefenseByID($id,$gid){
        global $mysqli;
        $sql="SELECT ".$id." FROM `BattleshipP1DefenseBoard` WHERE `GameID`=?";
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
    function switchTurnData($d,$gid){
        global $mysqli;
        $sql="UPDATE `BattleshipGameInfo` SET `turn`=? WHERE `GameID`=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("si",$d,$gid);
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
    function submitHitP1Data($id,$gid){
        //update p1 attack board
        global $mysqli;
        $sql="UPDATE `BattleshipP1AttackBoard` SET `".$id."`='hit' WHERE `GameID`=?";
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
    function submitHitP2Data($id,$gid){
        //update p1 attack board
        global $mysqli;
        $sql="UPDATE `BattleshipP2AttackBoard` SET `".$id."`='hit' WHERE `GameID`=?";
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
    function submitDamageP2Data($id,$gid){
        //update p2 defense board
        global $mysqli;
        $sql="UPDATE `BattleshipP2DefenseBoard` SET `".$id."`='hit' WHERE `GameID`=?";
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
    function submitDamageP1Data($id,$gid){
        //update p2 defense board
        global $mysqli;
        $sql="UPDATE `BattleshipP1DefenseBoard` SET `".$id."`='hit' WHERE `GameID`=?";
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
    function submitMissP1Data($id,$gid){
        //update p1 attack board
        global $mysqli;
        $sql="UPDATE `BattleshipP1AttackBoard` SET `".$id."`='miss' WHERE `GameID`=?";
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
    function submitMissP2Data($id,$gid){
        //update p1 attack board
        global $mysqli;
        $sql="UPDATE `BattleshipP2AttackBoard` SET `".$id."`='miss' WHERE `GameID`=?";
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
    function submitNoDamageP2Data($id,$gid){
        //update p2 defense board
        global $mysqli;
        $sql="UPDATE `BattleshipP2DefenseBoard` SET `".$id."`='miss' WHERE `GameID`=?";
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
    function submitNoDamageP1Data($id,$gid){
        //update p2 defense board
        global $mysqli;
        $sql="UPDATE `BattleshipP1DefenseBoard` SET `".$id."`='miss' WHERE `GameID`=?";
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
/************i made too many functions*******/

    function getP1AttackBoardData($gid){
        global $mysqli;
        $sql="SELECT * FROM `BattleshipP1AttackBoard` WHERE `GameID`=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
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
    function getP2AttackBoardData($gid){
        global $mysqli;
        $sql="SELECT * FROM `BattleshipP2AttackBoard` WHERE `GameID`=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
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
    function getP1DefenseBoardData($gid){
        global $mysqli;
        $sql="SELECT * FROM `BattleshipP1DefenseBoard` WHERE `GameID`=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
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
    function getP2DefenseBoardData($gid){
        global $mysqli;
        $sql="SELECT * FROM `BattleshipP2DefenseBoard` WHERE `GameID`=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("i",$gid);
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