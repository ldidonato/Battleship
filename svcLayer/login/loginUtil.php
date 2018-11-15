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
        //no data to prep

        echo(getLoginData($d));
    }


?>