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
        checkifStarted();
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
            if(data !== null){
                if(data[0].winner !== ""){
                    alert(data[0].winner+" has won Battleship!");
                    MyXHR('get',{method:'endGame',a:'game'}).done(function(json){
                        console.log("game has ended");
                         window.location.href = "/~lad4284/442/Battleship/index.html";
                    });
                }
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
     function init(evt){
         makeDraggable(evt);
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
         var boat = makeBoat(45,30,400,400,'boat1',2);
         document.getElementById("board").appendChild(boat);
         var boat2 = makeBoat(45,30,400,500,'boat2',3);
         document.getElementById("board").appendChild(boat2);
        
         
         
     }//init
    function setCharAt(str,index,chr) {
        if(index > str.length-1) return str;
        return str.substr(0,index) + chr + str.substr(index+1);
    }
    function fire(id){
         MyXHR('get',{method:'getGame',a:'game'}).done(function(game){
             var gameData = JSON.parse(game);
             var you = document.getElementById("userBox").innerHTML;
             if(you == gameData[0].Player1){
                 var dID = setCharAt(id,4,'d');
                 MyXHR('get',{method:'P1Fire',a:'game',data:dID}).done(function(json){
                     //var hitData = JSON.parse(json);
                     json = json.replace(/^\s+|\s+$/gm,'');
                     //If sucessful hit
                     if(json == "[{\""+dID+"\":\"full\"}]"){
                         console.log("hit");
                         MyXHR('get',{method:'submitHitP1',a:'game',data:dID}).done(function(json){
                             console.log(json);
                         });
                         MyXHR('get',{method:'submitDamageP1',a:'game',data:dID}).done(function(json){
                             console.log(json);
                         });
                     }else{
                         console.log("miss");
                         MyXHR('get',{method:'submitMissP1',a:'game',data:dID}).done(function(json){
                             console.log(json);
                         });
                         MyXHR('get',{method:'submitNoDamageP1',a:'game',data:dID}).done(function(json){
                             console.log(json);
                         });
                     }
                 });
                 //switch turns
                 var turnswitch = gameData[0].Player2;
                 MyXHR('get',{method:'switchTurn',a:'game',data:turnswitch}).done(function(json){

                 });
             }else{
                 //same thing but player 2
                 var dID = setCharAt(id,4,'d');
                 MyXHR('get',{method:'P2Fire',a:'game',data:dID}).done(function(json){
                     //var hitData = JSON.parse(json);
                     json = json.replace(/^\s+|\s+$/gm,'');
                     //If sucessful hit
                     if(json == "[{\""+dID+"\":\"full\"}]"){
                         console.log("hit");
                         MyXHR('get',{method:'submitHitP2',a:'game',data:dID}).done(function(json){
                             console.log(json);
                         });
                         MyXHR('get',{method:'submitDamageP2',a:'game',data:dID}).done(function(json){
                             console.log(json);
                         });
                     }else{
                         console.log("miss");
                         MyXHR('get',{method:'submitMissP2',a:'game',data:dID}).done(function(json){
                             console.log(json);
                         });
                         MyXHR('get',{method:'submitNoDamageP2',a:'game',data:dID}).done(function(json){
                             console.log(json);
                         });
                     }
                 });


                //switch turns
                 var turnswitch = gameData[0].Player1;
                 MyXHR('get',{method:'switchTurn',a:'game',data:turnswitch}).done(function(json){

                 });
             }
             
             
         });
    }
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
               MyXHR('get',{method:'checkifStarted',a:'game'}).done(function(json){
                  var data = JSON.parse(json);
                    if(data[0].started == 'yes'){
                        if(!this.classList.contains("noattack") && this.classList.contains("attack")){
                            fire(this.id);
                        }//top peg and your turn
                    }//has game started
              }.bind(this));
             
             
             
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
         return ele;
    }
    function makeDraggable(evt) {
          var svg = evt.target;
          svg.addEventListener('mousedown', startDrag);
          svg.addEventListener('mousemove', drag);
          svg.addEventListener('mouseup', endDrag);
          svg.addEventListener('mouseleave', endDrag);
          var selectedElement = false;
          var selectedElement, offset;
          function startDrag(evt) {
                if (evt.target.classList.contains('draggable')) {
                    selectedElement = evt.target;
                    offset = getMousePosition(evt);
                    offset.x -= parseFloat(selectedElement.getAttributeNS(null, "x"));
                    offset.y -= parseFloat(selectedElement.getAttributeNS(null, "y"));
                }
          }
          function drag(evt) {
                if (selectedElement) {
                    evt.preventDefault();
                    var coord = getMousePosition(evt);
                    selectedElement.setAttributeNS(null, "x", coord.x - offset.x);
                    selectedElement.setAttributeNS(null, "y", coord.y - offset.y);
                    
                    //light up board
                    lightUpBoard(selectedElement);
                  
                }//if
          }
          function endDrag(evt) {
              if(selectedElement){
                    if(selectedElement.id == "boat1"){
                        var otherboat = "boat2"
                    }else{
                        var otherboat = "boat1"
                    }
                  
                   //add new green tiles
                   var tilelist = document.getElementsByClassName("defend");
                   for(var i=0;i<tilelist.length;i++){
                       if(tilelist[i].style.fill == "rgb(92, 184, 92)" && !tilelist[i].classList.contains(otherboat)){
                           tilelist[i].classList.add(selectedElement.id);
                       }else{
                          tilelist[i].classList.remove(selectedElement.id);
                       }
                   }//tilelist
               }//if
               selectedElement = null;
          }
          function getMousePosition(evt) {
              var CTM = svg.getScreenCTM();
              return {
                x: (evt.clientX - CTM.e) / CTM.a,
                y: (evt.clientY - CTM.f) / CTM.d
              };
          }
        function lightUpBoard(selectedElement){
            var tilelist = document.getElementsByClassName("defend");
            var bx = selectedElement.getBBox().x;
            var by = selectedElement.getBBox().y;
            var bw = selectedElement.getBBox().width;
            var bh = selectedElement.getBBox().height;
            var bULC = [bx, by];
            var bURC = [bx+bw, by];
            var bLLC = [bx, by+bh];
            var bLRC = [bx+bw, by+bh];
            for(var i=0;i<tilelist.length;i++){
                var tx = tilelist[i].getBBox().x;
                var ty =  tilelist[i].getBBox().y;
                var tw =  tilelist[i].getBBox().width;
                var th =  tilelist[i].getBBox().height;
                var tULC = [tx, ty];
                var tURC = [tx+tw, ty];
                var tLLC = [tx, ty+th];
                var tLRC = [tx+tw, ty+th];
                var nexttile;
                var nexttile2;
                //500 < windowsize && windowsize < 600
                //[0] = x and [1] = y
                if((tULC[0] < bULC[0] && bULC[0] < tURC[0])&&(tULC[1] < bULC[1] && bULC[1] < tLLC[1])){
                    tilelist[i].style.fill = "#5cb85c";
                    var tileid = tilelist[i].id;
                    
                    var nextcol = parseInt(tileid[8])+1;
                    if(nextcol == 5 || nextcol == 6){
                      //  tilelist[i].style.fill = "#f0ad4e";
                    }else{
                        nexttile = document.getElementById("pId_d_"+tileid[6]+"_"+nextcol);
                        nexttile.style.fill = "#5cb85c";
                        if(bw/45 == 3){
                          nextcol++;
                          nexttile2 = document.getElementById("pId_d_"+tileid[6]+"_"+nextcol);
                            if(nexttile2 != null){
                                nexttile2.style.fill = "#5cb85c";
                            }
                        }
                    }

                }else{
                    if(selectedElement.id == "boat1"){
                        var otherboat = "boat2"
                    }else{
                        var otherboat = "boat1"
                    }
                    
                    if(tilelist[i].classList.contains(otherboat)){
                        tilelist[i].style.fill = "#5cb85c";
                    }else if(tilelist[i] != nexttile && tilelist[i] != nexttile2){
                        tilelist[i].style.fill = "#d9edf7";
                    }
                }
            }//for
        }
    }//make draggable
        
     ///////////////////////////////
	 //play the actual game stuff
	 ///////////////////////////////
     function startGame(){
         var tilelist = document.getElementsByClassName("defend");
         var count = 0;
         for(var i=0;i<tilelist.length;i++){
             if(tilelist[i].style.fill == "rgb(92, 184, 92)"){
                 count++;
             }
         }
         //make sure boats are valid
         if(count != 5){
             alert("Invalid boat setup, please make sure there are 5 green tiles.");
         }else{
              disableStartbtn();
              //tell server you are ready
              MyXHR('get',{method:'getGame',a:'game'}).done(function(json){
			     var data = JSON.parse(json);
                 var send = data[0].Player1+"|"+data[0].Player2;
                  MyXHR('get',{method:'startGame',a:'game',data:send}).done(function(json2){
                     MyXHR('get',{method:'allReady',a:'game'}).done(function(json3){
                        //if both players are ready
                        var data3 = JSON.parse(json3);
                        if(data3 != null){
                            MyXHR('get',{method:'startedYes',a:'game'}).done(function(json3){
                                console.log("started yup");
                                
                            });
                        }
                     });
                      
                  });//startgame
           
		      });//getgame

             //submit defense board
             MyXHR('get',{method:'getGame',a:'game'}).done(function(game){
			     var gameData = JSON.parse(game);
                 var you = document.getElementById("userBox").innerHTML;
                 
                 //get green tiles
                 var greentiles = "";
                   for(var i=0;i<tilelist.length;i++){
                       if(tilelist[i].style.fill == "rgb(92, 184, 92)"){
                           greentiles += tilelist[i].id+"|";
                       }
                   }
                 if(you == gameData[0].Player1){
                     MyXHR('get',{method:'submitDefenseP1',a:'game',data:greentiles}).done(function(json){
                         console.log("submit p1");
                         console.log(json);
                     });
                      MyXHR('get',{method:'submitAttackP1',a:'game',data:greentiles}).done(function(json){
                        });
                 }else{
                     MyXHR('get',{method:'submitDefenseP2',a:'game',data:greentiles}).done(function(json){
                         console.log("submit p2");
                         console.log(json);
                     });
                     MyXHR('get',{method:'submitAttackP2',a:'game',data:greentiles}).done(function(json){
                        });
                 }
                 
             });
             
             
             
         }//boat validate
     }
     function disableStartbtn(){
         document.getElementById("startDiv").innerHTML = "<button type='button' class='btn btn-success' disabled>Start Game</button>";
     }
     function checkifStarted(){
         MyXHR('get',{method:'checkifStarted',a:'game'}).done(function(json){
			var data = JSON.parse(json);
             if(data[0].started == 'yes'){
                 disableStartbtn();
                 //make the ships not draggable
                 for(var i=0;i<document.getElementsByClassName('draggable').length;i++){
                     document.getElementsByClassName('draggable')[i].classList.add("nodraggable");
                     document.getElementsByClassName('draggable')[i].classList.remove("draggable");
                 }
                 updateWidget();
                 //this is where all the mush goes for playing
                 
                 
                 
             }    
		});//check if started
     }
     function updateWidget(){
         MyXHR('get',{method:'getGameInfo',a:'game'}).done(function(info){
             var infoData = JSON.parse(info);
             MyXHR('get',{method:'getGame',a:'game'}).done(function(game){
			     var gameData = JSON.parse(game);
                 var you = document.getElementById("userBox").innerHTML;
                 //determine enemy boat count and win system
                 var enemyboatcount=2;
                 var yourboatcount=2;
                 if(you == gameData[0].Player1){
                     //you are player 1
                     if(infoData[0].p2boat1Hits == 0){
                         enemyboatcount--;
                     }
                     if(infoData[0].p2boat2Hits == 0){
                         enemyboatcount--;
                     }
                     if(infoData[0].p1boat1Hits == 0){
                         yourboatcount--;
                     }
                     if(infoData[0].p1boat2Hits == 0){
                         yourboatcount--;
                     }
                 }else{
                     //you are player 2
                     if(infoData[0].p1boat1Hits == 0){
                         enemyboatcount--;
                     }
                     if(infoData[0].p1boat2Hits == 0){
                         enemyboatcount--;
                     }
                     if(infoData[0].p2boat1Hits == 0){
                         yourboatcount--;
                     }
                     if(infoData[0].p2boat2Hits == 0){
                         yourboatcount--;
                     }
                 }
                 document.getElementById("enemyDiv").innerHTML="<span style='float:right'><span class='label label-primary'>"+enemyboatcount+"</span> Enemy Boats Remaining </span>";
                 if(yourboatcount == 0){
                     giveUp();
                 }else{
                     var attackpegs = document.getElementsByClassName("attack");
                     if(you == infoData[0].turn){
                        document.getElementById("turnDiv").innerHTML ="Turn <span class='label label-success'>"+infoData[0].turn+"</span>";
                         
                         //turn on fireing mode
                         for(var i=0;i<attackpegs.length;i++){
                             attackpegs[i].classList.remove("noattack");
                         }
                     }else{
                         //if it is NOT your turn
                         document.getElementById("turnDiv").innerHTML ="Turn <span class='label label-danger'>"+infoData[0].turn+"</span>";
                         
                         //turn off fireing mode
                         for(var i=0;i<attackpegs.length;i++){
                             attackpegs[i].classList.add("noattack");
                         }
                     }
                 }
                 
                 //update board colors
                 updateBoardColors(you, gameData[0].Player1);
                 
                 
                 
            });//getgame
         });//getinfo
     }
     
     function updateBoardColors(you, player1){
         
                //ATTACK BOARD
                 var attacktiles = document.getElementsByClassName("attack");  
                 if(you == player1){
                     MyXHR('get',{method:'getP1AttackBoard',a:'game'}).done(function(json){
                         for(var i=0; i<attacktiles.length;i++){
                             var id = attacktiles[i].id;
                             id = setCharAt(id,4,'d');
                             var data = JSON.parse(json)[0][id];
                             if(data=="miss"){
                                 attacktiles[i].style.fill= "white";
                             }else if(data== "hit"){
                                 attacktiles[i].style.fill= "#d43f3a";
                             }
                         }//for

                     });
                 }else{
                     MyXHR('get',{method:'getP2AttackBoard',a:'game'}).done(function(json){
                         for(var i=0; i<attacktiles.length;i++){
                             var id = attacktiles[i].id;
                             id = setCharAt(id,4,'d');
                             var data = JSON.parse(json)[0][id];
                             if(data=="miss"){
                                 attacktiles[i].style.fill= "white";
                             }else if(data== "hit"){
                                 attacktiles[i].style.fill= "#d43f3a";
                             }
                         }//for

                     });
                 }//ifelse
         
                 //DEFENSE BOARD
                 var defensetiles = document.getElementsByClassName("defend");  
                 if(you == player1){
                     MyXHR('get',{method:'getP1DefenseBoard',a:'game'}).done(function(json){
                         for(var i=0; i<defensetiles.length;i++){
                             var id = defensetiles[i].id;
                             id = setCharAt(id,4,'d');
                             var data = JSON.parse(json)[0][id];
                             if(data=="miss"){
                                 defensetiles[i].style.fill= "white";
                             }else if(data== "hit"){
                                 defensetiles[i].style.fill= "#d43f3a";
                             }else if(data== "full"){
                                 defensetiles[i].style.fill= "rgb(92, 184, 92)";
                             }
                         }//for

                     });
                 }else{
                     MyXHR('get',{method:'getP2DefenseBoard',a:'game'}).done(function(json){
                         for(var i=0; i<defensetiles.length;i++){
                             var id = defensetiles[i].id;
                             id = setCharAt(id,4,'d');
                             var data = JSON.parse(json)[0][id];
                             if(data=="miss"){
                                 defensetiles[i].style.fill= "white";
                             }else if(data== "hit"){
                                 defensetiles[i].style.fill= "#d43f3a";
                             }else if(data== "full"){
                                 defensetiles[i].style.fill= "rgb(92, 184, 92)";
                             }
                         }//for

                     });
                 }//ifelse
                 
         
     }//update

	 
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
                      <span id="turnDiv">Turn <span class="label label-warning">Setup</span></span> 
                      
                      <span id="enemyDiv"><span style="float:right"><span class="label label-primary">2</span> Enemy Boats Remaining </span></span><br><br>
                      <hr>
                      <span id="startDiv">
                      <button type="button" onclick="startGame()" class="btn btn-success">Start Game</button>
                          </span>
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









