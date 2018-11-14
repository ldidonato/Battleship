<?php                  

    //mysqli(server, username, pass(that you logged into phpMyAdmin with), db)

    $conn = new mysqli('serenity.ist.rit.edu','lad4284','withkentucky','lad4284');

    //$conn is holding a object!  $conn->method(), $conn->property

    //let's do a test

    if( mysqli_connect_errno() ){ // if I get in here, something went wrong

                    echo 'connection failed: ' . mysqli_connect_error();

                    exit();

    }else{

                    //echo 'connection good';

    }

?>

















