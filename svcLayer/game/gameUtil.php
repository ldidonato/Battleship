<?php
    //go to the data layer and actually get the data I want
    require "./BizDataLayer/gameData.php";

    $mysqli=new mysqli("localhost","lad4284","withkentucky",'lad4284');             
    if(mysqli_connect_errno()){
        printf("connection failed: ",mysqli_connect_errno());
        exit();
    }

    function getOnlineUsers(){
        echo(getOUsers());
    }
    
    function getChallenges(){
        session_start();
        $email = $_SESSION["id"];
        echo(getChallengeTable($email));
    }

    function sendChallenge($d){
        session_start();
        $email = $_SESSION["id"];
        echo(sendChallengeData($email,$d));
    }

    function denyChallenge($challenger){
        session_start();
        $you = $_SESSION["id"];
        echo(denyChallengeData($you,$challenger));
    }

    function removeChallenge($opponent){
        session_start();
        $you = $_SESSION["id"];
        echo(removeChallengeData($you,$opponent));
    }
    function acceptChallenge($opponent){
        session_start();
        $you = $_SESSION["id"];
        //get empty lobbies
        $lobbies = json_decode(getEmptyLobbies(),true);
        if(empty($lobbies)) {
            echo("no lobbies avalible");
        }else{
            //grab first empty lobby
            $lobby = $lobbies[0]["lobbyID"];
            echo($lobby);
        }   
    }
    function enterLobbyHelper($d){
        session_start();
        $you = $_SESSION["id"];
        $dataArr = explode("|",$d);
        //opponent | lobbyid
        $response = enterLobby($you,$dataArr[0],$dataArr[1]);
        echo($reponse);
    }

	
?>








