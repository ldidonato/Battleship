<?php
	//include dbInfo
	//require_once("DB.class.php");
	//include exceptions
	require_once('./BizDataLayer/exception.php');
	
	function getLoginData($email,$pass){
        global $mysqli;
        $sql="SELECT * FROM BattleshipLogin WHERE email=? AND password=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$email,$pass);
                $data=returnJson($stmt);
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while getting login data");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }

    function createAccount($email, $pass){
        global $mysqli;
        $sql="INSERT INTO BattleshipLogin (email, password) VALUES (?,?)";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss",$email,$pass);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while creating the account");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
        
    }
    function addOnline($email){
        global $mysqli;
        $sql="INSERT INTO BattleshipOnline (email) VALUES (?)";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("s",$email);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while creating the account");
            }
        }catch (Exception $e) {
            log_error($e, $sql, null);
            return false;
        }
    }
    function removeOnline($email){
        global $mysqli;
        $sql="DELETE FROM `BattleshipOnline` WHERE email=?";
        try{
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("s",$email);
                $stmt->execute();
                $mysqli->close();
                return $data;
            }else{
                throw new Exception("An error occurred while creating the account");
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