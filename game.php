<html>
  <head>
    <title>Play | Battleship</title>
    <script>
	 
	 ///////////////////////////////
	 //chat stuff
	 ///////////////////////////////
    
    //runs every 2 secs
    window.setInterval(function(){
        checkWinner();
    }, 2000);
    //runs every 20 mins
    window.setInterval(function(){
        endSession();
        console.log("20mins has passed you have been logged out");
    }, 20*60000);
     
    
     ///////////////////////////////
	 //session stuff
	 ///////////////////////////////   
     function checkSession(){
         MyXHR('get',{method:'checkSession',a:'login'}).done(function(json){
			if(json === "false"){
                endSession();
                window.location.href = "/~lad4284/442/Battleship/login.html";
                
            }else{
                MyXHR('get',{method:'getEmail',a:'login'}).done(function(json){
                    console.log(json+" is online");
                    document.getElementById("userBox").innerHTML=json;
                    //INITALIZE STUFF
                    fillInfoWidget();
                });//XHR
            }//else
		});//XHR
     }
    function endSession(){
        MyXHR('get',{method:'goOffline',a:'login'}).done(function(json2){
            console.log("user is now offline");
        }); //offline XHR  
        
         MyXHR('get',{method:'endSession',a:'login'}).done(function(json){
			window.location.href = "/~lad4284/442/Battleship/login.html";
		});
     }
    
    function checkWinner(){
        MyXHR('get',{method:'getGame',a:'game'}).done(function(json){
			var data = JSON.parse(json);
            if(data[0].winner !== ""){
                alert(data[0].winner+" has won Battleship!");
                MyXHR('get',{method:'endGame',a:'game'}).done(function(json){
                    console.log("game has ended");
			         window.location.href = "/~lad4284/442/Battleship/index.html";
                });
            }
		});
    }
        
	 ///////////////////////////////
	 //info widget stuff
	 ///////////////////////////////
    function fillInfoWidget(){
        MyXHR('get',{method:'getGame',a:'game'}).done(function(json){
			var data = JSON.parse(json);
            var text="<span class='glyphicon glyphicon-pawn'></span> ";
            document.getElementById("vsBox").innerHTML = text+data[0].Player1+" VS. "+data[0].Player2;
		});
    }
    function giveUp(){
        MyXHR('get',{method:'getGame',a:'game'}).done(function(json){
            var data = JSON.parse(json);
            var info = data[0].Player1+"|"+data[0].Player2;
            //pass players in
            MyXHR('get',{method:'loseGame',a:'game',data:info}).done(function(json2){
                console.log("you have given up"+json2);
            });
        });
    }
 
        
        
	 
	 ///////////////////////////////
	 //utility stuff
	 ///////////////////////////////
	 //MyXHR() - method to call the mid.php file...
	 //		getPost - get or post
	 //		d - data, looks like {name:value;name2:val2;...}
	 //		id - id of the parent for the spinner...
	 function MyXHR(getPost,d,id){
		//ajax shortcut
		return $.ajax({
			type: getPost,
			async: true,
			cache: false,
			url: 'mid.php',
			data:d,
			beforeSend:function(){
				//turn on spinner if I have one 
				if(id){
					$(id).append('<img src="path/spinner.gif" class="awesome"/>');
				}
			}
		}).always(function(){
			//kill spinner
			if(id){
				$(id).find('.awesome').fadeOut(4000,function(){
					$(this).remove();
				});
			}
		}).fail(function(err){
            console.log("from error in index.html");
			console.log(err);
		});
	 }
     
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body onload="checkSession()" >
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" id="header">BATTLESHIP</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li><a style="color:white;cursor:default;"><span class="glyphicon glyphicon-user"></span> <span id="userBox">User</span></a></li>
            <li><a href="index.html"><span class="glyphicon glyphicon-home"></span> <span >Home</span></a></li>
          <li><a onclick="endSession()" style="cursor: pointer;"><span class="glyphicon glyphicon-log-in"></span> Logout</a>
        </ul>
      </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
              <div class="col-sm-8">
                

                <div class="panel panel-primary">
                    <div class="panel-heading"><span class="glyphicon glyphicon-pawn"></span> Games</div>
                  <div class="panel-body" id="">
                      
                  </div>
                </div><!-- match panel-->
            </div><!--sidebar-->
            <div class="col-sm-4" id="chatbox">
                <div class="panel panel-info">
                    <div class="panel-heading" id="vsBox"> P1 VS P2</div>
                  <div class="panel-body" id="">
                      <button type="button" onclick="giveUp()" class="btn btn-danger">Forfeit</button>
                  </div>
                </div><!-- game panel-->
                <div class="panel panel-default">
                    <div class="panel-heading"><span class="glyphicon glyphicon-bullhorn"></span> Chat</div>
                    <div class="panel-body" id="localChatBody">
                        
                        
                        
                    </div>
                    <div class="panel-footer">
                        <form>
                          <div class="input-group">
                            <input id="" type="text" class="form-control" placeholder="Say something...">
                            <div class="input-group-btn">
                              <div onclick="" id="" class="btn btn-default" style="padding-top:9px;padding-bottom:9px;">
                                <i class="glyphicon glyphicon-send"></i>
                              </div>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- body row-->
    </div><!-- body container-->
  </body>
</html>









