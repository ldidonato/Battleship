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
            echo("Login Failed");
        }else{
            echo("Login Success");
        }
       
        
    }


?>