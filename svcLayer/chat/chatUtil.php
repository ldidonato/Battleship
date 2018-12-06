<?php

    //go to the data layer and actually get the data I want
    require "./BizDataLayer/chatData.php";

    $mysqli=new mysqli("localhost","lad4284","withkentucky",'lad4284');             
    if(mysqli_connect_errno()){
        printf("connection failed: ",mysqli_connect_errno());
        exit();
    }

    function getGlobalChat(){
        //should they be here?
        ///email|password
        $response = getGlobal();
        echo($response);
        
    }
    function sendGlobalChat($d){
        //$d = message
        session_start();
        $email = $_SESSION["id"];
        sendGChat($email,$d);
    }

    function getLocalChat(){
        session_start();
        $gid = $_SESSION["GameID"];
        $response = getLocal($gid);
        echo($response);
    }
    function sendLocalChat($d){
        //$d = message
        session_start();
        $gid = $_SESSION["GameID"];
        $email = $_SESSION["id"];
        sendLChat($gid,$email,$d);
    }

?>