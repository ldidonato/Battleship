<?php

    //go to the data layer and actually get the data I want
    require "./BizDataLayer/loginData.php";

    $mysqli=new mysqli("localhost","lad4284","withkentucky",'lad4284');             
    if(mysqli_connect_errno()){
        printf("connection failed: ",mysqli_connect_errno());
        exit();
    }

    function doLogin($d){
        //should they be here?
        ///email|password
        $dataArr = explode("|",$d);
        $response = getLoginData($dataArr[0],$dataArr[1]);
        
        if($response === 'null'){
            echo("Login Fail");
        }else{
            echo("Login Success");
        }
        
    }
    function doRegister($d){
        ///email|password
        $dataArr = explode("|",$d);
        $response = createAccount($dataArr[0],$dataArr[1]);
        echo($response);
    }
    
    ///////////////////////////////
    //online stuff
    ///////////////////////////////   
    function goOnline($d){
        ///$d = email
        addOnline($d);
    }
    function getEmail(){
        session_start();
        echo($_SESSION["id"]);
    }
    function goOffline(){
        ///$d = email
        session_start();
        $email = $_SESSION["id"];
        removeOnline($email);
    }

    

     ///////////////////////////////
	 //session stuff
	 ///////////////////////////////   
    function startSession($d){
        session_start();
        $_SESSION["id"] = $d;
        echo($_SESSION["id"]);
    }

    function checkSession(){
        session_start();
        if(isset($_SESSION["id"])) {
            echo("true");
        }else{
            echo("false");
        }
    }
    
    function endSession(){
        session_start();
        $_SESSION["id"] = null;
        session_destroy();
    }


?>