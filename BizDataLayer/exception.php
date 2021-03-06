<?php
function log_error($e, $sqlst, $params){
    $myFile = "/dataerror.log";
	$fh = fopen($myFile, 'a+') or die("can't open file: ".$e." \n ".$sqlst." \n ".$params);
	try{
   		fwrite($fh, "Exception caught @".date("H:i:s m.d.y")."\n"); 
		fwrite($fh, "    Message: ".$e->getMessage()."\n");
        fwrite($fh, "    SQL: ".$sqlst."\n"); 
		if (is_array($params))
            fwrite($fh, "    Params: ".implode(",",$params)."\n");
        fwrite($fh, "    File: ".$e->getFile()."\n");
        fwrite($fh, "    Line: ".$e->getLine()."\n");
        fwrite($fh, "    Trace: ".$e->getTraceAsString()."\n");
        fclose($fh);
	}catch (Exception $e) {
		echo 'error';
    }
}
?>