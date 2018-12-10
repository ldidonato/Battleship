<?php
    //go to the data layer and actually get the data I want
    require "./BizDataLayer/gameData.php";

    $mysqli=new mysqli("localhost","lad4284","withkentucky",'lad4284');             
    if(mysqli_connect_errno()){
        printf("connection failed: ",mysqli_connect_errno());
        exit();
    }

///////////////////////////////////////////////////
//index.html functions
///////////////////////////////////////////////////
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
    function removeChallengeA($opponent){
        session_start();
        $you = $_SESSION["id"];
        echo(removeChallengeData($opponent,$you));
    }
    function acceptChallenge($opponent){
        session_start();
        $you = $_SESSION["id"];
        createGame($you,$opponent);  
        //you = player1, opponent = player2
        //Player1 | Player2
        echo($you."|".$opponent);
    }
    function getLastestGID($opponent){
        session_start();
        $you = $_SESSION["id"];
        //you = player1, opponent = player2
        echo(getLatestGameID($you,$opponent));
    }
    function addGameInfo($gid){
        session_start();
        $you = $_SESSION["id"];
        echo(addGameInfoData($you,$gid));
    }
    function getGames(){
        session_start();
        $you = $_SESSION["id"];
        echo(getGamesData($you));
    }
    function playGameCheck($gid){
        session_start();
        $_SESSION["GameID"] = $gid;
        echo(getGameByID($gid));
    }
///////////////////////////////////////////////////
//game.html functions
///////////////////////////////////////////////////
    
    function getGame(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(getGameByID($gid));
    }
    function getGameInfo(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(getGameByInfoID($gid));
    }
    function endGame(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(removeIDfromGameList($gid));
    }
    function loseGame($d){
        session_start();
        $gid = $_SESSION["GameID"];
        $you = $_SESSION["id"];
        $dataArr = explode("|",$d);
        if($dataArr[0] == $you){
            loseGameData($dataArr[1],$gid);
            echo("1");
        }else{
            loseGameData($dataArr[0],$gid);
            echo($d);
        }
    }
    function startGame($d){
        $dataArr = explode("|",$d);
        //player1|player2
        session_start();
        $you = $_SESSION["id"];
        $gid = $_SESSION["GameID"];
        if($you == $dataArr[0] ){
            readyPlayer1($gid);
            echo("ready player 1");
        }else if($you == $dataArr[1]){
            echo("ready player 2");
            readyPlayer2($gid);
        }
    }
    function checkifStarted(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(checkifStartedData($gid));
    }
    function allReady(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(allReadyData($gid));
    }
    function startedYes(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(startedYesData($gid));
    }
    function submitDefenseP1($d){
        session_start();
        $gid = $_SESSION["GameID"];
        $dataArr = explode("|",$d);
        echo(submitDefenseP1Data($dataArr[0],$dataArr[1],$dataArr[2],$dataArr[3],$dataArr[4],$gid));
    }
    function submitDefenseP2($d){
        session_start();
        $gid = $_SESSION["GameID"];
        $dataArr = explode("|",$d);
        echo(submitDefenseP2Data($dataArr[0],$dataArr[1],$dataArr[2],$dataArr[3],$dataArr[4],$gid));
    }
    function submitAttackP1(){
        session_start();
        $gid = $_SESSION["GameID"];
        submitAttackP1Data($gid);
    }
    function submitAttackP2(){
        session_start();
        $gid = $_SESSION["GameID"];
        submitAttackP2Data($gid);
    }
    function switchTurn($d){
        session_start();
        $gid = $_SESSION["GameID"];
        switchTurnData($d,$gid);
    }
    function P1Fire($d){
        //player1 fires on player2 defense board
        //pull defense board at it
        session_start();
        $gid = $_SESSION["GameID"];
        echo(getP1DefenseByID($d,$gid));
    }
    function submitHitP1($id){
        //p1 got a hit update p1's attack board
        session_start();
        $gid = $_SESSION["GameID"];
        echo(submitHitP1Data($id,$gid));
    }
    function submitDamageP1($id){
        //p1 got a hit update p2's defense board
        session_start();
        $gid = $_SESSION["GameID"];
        echo(submitDamageP2Data($id,$gid));
    }
    function submitMissP1($id){
        //p1 got a hit update p1's attack board
        session_start();
        $gid = $_SESSION["GameID"];
        echo(submitMissP1Data($id,$gid));
    }
    function submitNoDamageP1($id){
        //p1 got a hit update p2's defense board
        session_start();
        $gid = $_SESSION["GameID"];
        echo(submitNoDamageP2Data($id,$gid));
    }
    

    function P2Fire($d){
        //player1 fires on player2 defense board
        //pull defense board at it
        session_start();
        $gid = $_SESSION["GameID"];
        echo(getP2DefenseByID($d,$gid));
    }
    function submitHitP2($id){
        //p1 got a hit update p1's attack board
        session_start();
        $gid = $_SESSION["GameID"];
        echo(submitHitP2Data($id,$gid));
    }
    function submitDamageP2($id){
        //p1 got a hit update p2's defense board
        session_start();
        $gid = $_SESSION["GameID"];
        echo(submitDamageP1Data($id,$gid));
    }
    function submitMissP2($id){
        //p1 got a hit update p1's attack board
        session_start();
        $gid = $_SESSION["GameID"];
        echo(submitMissP2Data($id,$gid));
    }
    function submitNoDamageP2($id){
        //p1 got a hit update p2's defense board
        session_start();
        $gid = $_SESSION["GameID"];
        echo(submitNoDamageP1Data($id,$gid));
    }


    function getP1AttackBoard(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(getP1AttackBoardData($gid));
    }

    function getP2AttackBoard(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(getP2AttackBoardData($gid));
    }

    function getP1DefenseBoard(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(getP1DefenseBoardData($gid));
    }

    function getP2DefenseBoard(){
        session_start();
        $gid = $_SESSION["GameID"];
        echo(getP2DefenseBoardData($gid));
    }




?>








