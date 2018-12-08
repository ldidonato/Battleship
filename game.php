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
        getLocalChat();
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
                    getLocalChat();
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
    
        //Chat stuff
    function getLocalChat(){
        MyXHR('get',{method:'getLocalChat',a:'chat'}).done(function(json){
            var data = JSON.parse(json);
            var text="";
            if(data !==  null){
                for(var i=0; i<data.length; i++){
                    text += "<p><b>"+data[i].email+": </b> </b>"+data[i].message+"</p>";
                }
            }
            document.getElementById('localChatBody').innerHTML=text;
        });//XHR
    }
        
    function sendLocalChat(){
        var message = document.getElementById("localInput").value;
        MyXHR('get',{method:'sendLocalChat',a:'chat',data:message}).done(function(json){
             document.getElementById("localInput").value = "";
        });//XHR sendGlobalChat
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
	 //board stuff
	 ///////////////////////////////
     function init(){
         var svgns = "http://www.w3.org/2000/svg";
         var boardnum = 5;
         //make top
         
         //make pegs for attack board
         for(var c =0; c<boardnum; c++){ //0-3
             for(var r=0; r<boardnum; r++){ //A-D- 0-3
                 var ele = makePeg(50+(50*r),50+(50*c),"a_"+c+"_"+r, "peg attack")
                 document.getElementById("board").appendChild(ele);
             }//r
         }//c
         
         //make peg for defense board
         for(var c =0; c<boardnum; c++){ //0-3
             for(var r=0; r<boardnum; r++){ //A-D- 0-3
                 var ele = makePeg(50+(50*r),((boardnum+2)*50)+(50*c),"d_"+c+"_"+r, "peg defend")
                 document.getElementById("board").appendChild(ele);
             }//r
         }//c
         
         //make boats
         //w,h,x,y
         var boat = makeBoat(50,40,400,400,'boat1',2);
         document.getElementById("board").appendChild(boat);
         var boat2 = makeBoat(50,40,400,500,'boat1',3);
         document.getElementById("board").appendChild(boat2);
        
         
         
     }//init
        
    function makePeg(cx, cy, id, cla){
         var svgns = "http://www.w3.org/2000/svg";
         var ele=document.createElementNS(svgns,'rect');
         ele.setAttributeNS(null,'id','pId_'+id);
         ele.setAttributeNS(null,'x',cx);
         ele.setAttributeNS(null,'y',cy);
         ele.setAttributeNS(null,'width','48');
         ele.setAttributeNS(null,'height','48');
         ele.setAttributeNS(null,'stroke','#333');
         ele.setAttributeNS(null,'stroke-width','3');
         ele.setAttributeNS(null,'fill','#d9edf7');
         ele.setAttributeNS(null,'class',cla);
         ele.onclick = function () {
            alert(this.id);
         };
         return ele;
    }
    function makeBoat(w, h, x, y, id, size){
         var svgns = "http://www.w3.org/2000/svg";
         var ele=document.createElementNS(svgns,'rect');
         ele.setAttributeNS(null,'id',id);
         ele.setAttributeNS(null,'x',x);
         ele.setAttributeNS(null,'y',y);
         ele.setAttributeNS(null,'class','boat draggable');
         ele.setAttributeNS(null,'width',w*size);
         ele.setAttributeNS(null,'height',h);
         ele.setAttributeNS(null,'rx',20);
         ele.setAttributeNS(null,'ry',20);
         ele.setAttributeNS(null,'stroke','#333');
         ele.setAttributeNS(null,'stroke-width','1');
         ele.setAttributeNS(null,'fill','#ddd');
         ele.addEventListener('mousedown', startDrag);
         ele.addEventListener('mousemove', drag);
         ele.addEventListener('mouseup', endDrag);
         ele.addEventListener('mouseleave', endDrag);
        
        
        
         return ele;
    }
    function startDrag(){
        
    }
    function drag(){
        
    }
    function endDrag(){

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
                  <div class="panel-body" style="height:86%;" id="">
                      <svg width="100%" height="100%" onload="init(evt)" id="board">
			              
		              
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      </svg>
                      
                      
                  </div>
                </div><!-- match panel-->
            </div><!--sidebar-->
            <div class="col-sm-4" id="chatbox">
                <div class="panel panel-info">
                    <div class="panel-heading" id="vsBox"> P1 VS P2</div>
                  <div class="panel-body" id="">
                      <button type="button" onclick="" class="btn btn-warning">End Turn</button>
                      <button type="button" onclick="giveUp()" class="btn btn-danger" style="float:right;">Forfeit</button>
                      
                  </div>
                </div><!-- game panel-->
                <div class="panel panel-default">
                    <div class="panel-heading"><span class="glyphicon glyphicon-bullhorn"></span> Chat</div>
                    <div class="panel-body" id="localChatBody">
                        
                        
                        
                    </div>
                    <div class="panel-footer">
                        <form>
                          <div class="input-group">
                            <input id="localInput" type="text" class="form-control" placeholder="Say something...">
                            <div class="input-group-btn">
                              <div onclick="sendLocalChat()" id="" class="btn btn-default" style="padding-top:9px;padding-bottom:9px;">
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









