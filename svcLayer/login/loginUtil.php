<?php

//go to the data layer and actually get the data I want
include('BizDataLayer/loginData.php');

	function doLogin(){
		//should they be here?
		//no data to prep
		
		echo(getLoginData());
	}


?>