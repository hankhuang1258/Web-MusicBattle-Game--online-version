<?php session_start(); ?>
<?php
require_once('conf.php');
include("mysql_music_connect.php");
?>


<html>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<head>
		<script src="jquery-1.11.3.min.js"></script>
		<link rel="stylesheet" type="text/css" href="styleindex.css">
	</head>
	<body>
	      
	
	
	<script>
	function PrintScore(){
				document.getElementById("score").innerHTML=score;
				document.getElementById('post_score').value = score;
				if(score>0){
					sendScore();
				}
				//setTimeout("PrintScore()", 100);
			}
	function sendScore(){
	//alert($("#post_score").val());
        $.post(
            "./score_post.php",
            {post_name:$("#post_name").val(), post_room:$("#post_room").val(), post_score:$("#post_score").val()}
        );
    }
	function showMsg(t){
        $.post(
            "./longpoll.php",
            {'lastMsgTime':t}
        ).done(function(data){
            $("#showMsgHere").val(data);
            setTimeout("showMsg(1);", 1000);
        });
    }		
	
	</script>
		<?php
			//$sql="select * from music where NO='9'";
			//$result = mysql_query($sql);
			//$row = @mysql_fetch_row($result);
			/*yuan: GET值*/

				$NO = $_GET['NO'];
				$room_id = $_GET['roomid'];
				$cur_name = $_SESSION['username'];
				$_SESSION['room_id'] = $room_id;
				$_SESSION['NO'] = $NO;
				
				$sql = "SELECT * from music WHERE NO = $NO";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				mysql_query("UPDATE member_table SET cur_score ='0' WHERE username = '$cur_name'") or die(mysql_error());
				
				mysql_query("UPDATE member_table SET at_which_room ='$room_id' WHERE username = '$cur_name'") or die(mysql_error());
				
				
				
		?>
		
		

		<div id="topbar">
			<input type="button" value=" Room " class="button_type1"  onclick="window.location.href ='view_room.php'">
			<input type="button" value=" Music " class="button_type1"  onclick="window.location.href ='music_select.php'">
			<?php echo $row['name'];?>
            <audio id="audioCtrl">controls></audio>
			<button id="playBtn" class="button_type1" onclick="window.location.href ='start_game.php'">Let's Battle!!</button>
		</div>
		
		
		
		
		<div id="waiting">
			loading...
		 </div>
		<div id = "left" align="center">
		
		<tr align="center" height="90%">
			<td>
				<textarea  style="border:none; color:white;" rows="10" cols="25" id="showMsgHere" disabled="disabled" class="userlog"></textarea>
			</td>
		</tr>
    
		
		<!--
		<div id="holder">
			<div id="msg">
				<h3>Grade:</h3><br />	 
				<div id="messages">
		
				</div>  
			</div>	

		</div>
		-->
		
		<!--
		傳送所在房間/ID/分數給score_post.php
		-->
		<!--
		<iframe id="myIframe" name="myIframe" width="0" height="0"  type = "hidden"></iframe>
		-->
		
	
		<form name="score_form"  id ="score_form"  type = "hidden">
			<input name="post_score" id = "post_score" type="hidden" value="" />
			<input name="post_name" id = "post_name" type="hidden" value="<?php echo $_SESSION['username']; ?>" />
			<input name="post_room" id = "post_room" type="hidden" value="<?php echo $_GET['roomid']; ?>" />	
		</form>
		</div>
		<div id="right">
		<div class="name" align="center">
			<table class="table" align="center">
			<?php
				include("mysql_music_connect.php");
				$id = $_SESSION['username'];
				$sql1="select * from member_table where username='$id'";
				$result1 = mysql_query($sql1);
				$row1 = @mysql_fetch_row($result1); 		
			?>
			
				<?php
					include("mysql_music_connect.php");
					
					//此判斷為判定觀看此頁有沒有權限
					//說不定是路人或不相關的使用者
					//因此要給予排除
					
						if($_SESSION['username'] != null)
						{
								
								echo "Hi,    ".$_SESSION['username'];
								echo '</br>';
								/*echo '<input type="button" value="新增" class="button_type1"  onclick="window.location.href =\'music_register.php\'">&nbsp;&nbsp;';*/
								echo '<input type="button" value="修改" class="button_type1"  onclick="window.location.href =\'music_update.php\'">&nbsp;&nbsp;';
								echo '<input type="button" value="刪除" class="button_type1"  onclick="window.location.href =\'music_delete.php\'">&nbsp;&nbsp;';
								//echo '<a href="photo.php" target=\"_blank\">新增大頭貼</a>';
						}
						else
						{
								echo '您無權限觀看此頁面!';
								echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
						}
						echo '<input type="button" value="登出" class="button_type1"  onclick="window.location.href =\'music_logout.php\'">';
				?>
			</table>
		</form>
		</div>
		</div>
		
		<div id="score">
			<script>
				PrintScore();
			</script>
		</div>
		<div id="canvas-container">
			<canvas id="canvas"></canvas>
			<canvas id="myCanvas"></canvas>
      <canvas id="canvas-copy"></canvas>
			<canvas id="combocanvas"></canvas>
			<div id="instruction">
				顯示GOOD，EXCELLENT
			</div>
			
		</div>
		
        <!--<canvas id="myCanvas"></canvas>
		-->
		<script>
		var isStart;
		var source;
		(function() {
  var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
                              window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
  window.requestAnimationFrame = requestAnimationFrame;
})();

/*window.onload = function() {
  var element = document.getElementById('container')
  dropAndLoad(element, init, "ArrayBuffer")
}*/

 

function init2(arrayBuffer) {
  console.log("YA");
  //document.getElementById('instructions').innerHTML = 'Loading ...'
  // Create a new `audioContext` and its `analyser`
  window.audioCtx = new AudioContext()
  window.analyser = audioCtx.createAnalyser()
  // If a sound is still playing, stop it.
  if (window.source)
	source.noteOff(0)
 // Decode the data in our array into an audio buffer
	audioCtx.decodeAudioData(arrayBuffer, function(buffer) {
    // Use the audio buffer with as our audio source
    window.source = audioCtx.createBufferSource()   
    source.buffer = buffer
    // Connect to the analyser ...
    source.connect(analyser)
    // and back to the destination, to play the sound after the analysis.
    analyser.connect(audioCtx.destination)
    // Start playing the buffer.
    source.start(0)
    // Initialize a visualizer object
    var viz = new simpleViz()
    // Finally, initialize the visualizer.
    new visualizer(viz['update'], analyser)
    //document.getElementById('instructions').innerHTML = ''
  })
}

function visualizer(visualization, analyser) {
  var self = this
  this.visualization = visualization  
  var last = Date.now()
  var loop = function() {
    var dt = Date.now() - last
    // we get the current byteFreq data from our analyser
    var byteFreq = new Uint8Array(analyser.frequencyBinCount)
    analyser.getByteFrequencyData(byteFreq)
    last = Date.now()
    // We might want to use a delta time (`dt`) too for our visualization.
    self.visualization(byteFreq, dt)
    requestAnimationFrame(loop)
  }
  requestAnimationFrame(loop)
}
var color; 
var saturation ; //saturation
var brightness ; 
var ctime;
function wait(){
   color=Math.random()*360;
   ctime=Math.random()*5000;
   saturation=Math.random()*100+60;
   brightness=Math.random()*75+20;
   setTimeout("wait()",ctime);
}
// A simple visualization. Its update function illustrates how to use 
// the byte frequency data from an audioContext analyser.
function simpleViz(canvas) {
  var self = this
  this.canvas = document.getElementById('canvas')
  this.ctx = this.canvas.getContext("2d")
  this.copyCtx = document.getElementById('canvas-copy').getContext("2d")
  this.ctx.fillStyle = '#fff' 
  this.barWidth = 10
  this.barGap = 4
  this.which=Math.floor(Math.random()*4);
  //this.canvas.width = window.innerWidth-200;
  //this.canvas.height = window.innerHeight-10;
  // We get the total number of bars to display
  this.bars = Math.floor(this.canvas.width / (this.barWidth + this.barGap))
  // This function is launched for each frame, together with the byte frequency data.
  setTimeout("wait()",200);
  this.update = function(byteFreq) {
    self.ctx.clearRect(0, 0, self.canvas.width, self.canvas.height)
    // We take an element from the byteFreq array for each of the bars.
    // Let's pretend our byteFreq contains 20 elements, and we have five bars...
    var step = Math.floor(byteFreq.length / self.bars)
    // `||||||||||||||||||||` elements
    // `|   |   |   |   |   ` elements we'll use for our bars
   /*!!!!!!!!!!!!!!!!!!!!!!1*/
     //self.ctx.fillStyle="white";
    //self.ctx.fillRect(0,0,self.canvas.width,self.canvas.height)
      //self.canvas.width=800;
	  //self.canvas.height=800;
       if(self.which<0){
      for(var i=1;i<5;i++){
      var r=5+Math.floor(byteFreq[i*step]/2);
      var c=Math.floor(color);
      var s=Math.floor(saturation);
      var b=Math.floor(brightness);
      self.ctx.fillStyle='hsl('+c+i*10+','+s+'%,'+b+'%)';

      self.ctx.beginPath();
      
      self.ctx.arc(self.canvas.width/2,self.canvas.height/2,r,0,2*Math.PI,false);
       
      self.ctx.fill();
      
      self.copyCtx.clearRect(0, 0, self.canvas.width, self.canvas.height)
      self.copyCtx.drawImage(self.canvas, 0, 0)
     }
   }else if(self.which>=0){
    
    for (var i = 0; i < self.bars; i ++) {
      // Draw each bar
      var barHeight = byteFreq[i*step]*0.2;
      var c=Math.floor(color);
      var s=Math.floor(saturation);
      var b=Math.floor(brightness);
      if(b<50){
        b=50;
      }
      var k=Math.floor(Math.floor(self.canvas.height - barHeight)*1.5);
       

      self.ctx.fillStyle='hsl('+c+i*10+','+s+'%,'+b+'%)';
      self.ctx.fillRect(
        i * (self.barWidth + self.barGap)+50, 
        self.canvas.height - barHeight*2, 
        self.barWidth/2, 
        barHeight/2)
      self.copyCtx.clearRect(0, 0, self.canvas.width, self.canvas.height)
      self.copyCtx.drawImage(self.canvas, 0, 0)
    }
  }
  }
}
		
		
		
		
		
		/*yuan:接收到訊息就呼叫 onMessage*/
			function Init () {
			isStart = 0;
            if (window.addEventListener) {  // Firefox, Opera, Google Chrome and Safari
					window.addEventListener ("message", OnMessage, false);
				}
				else {
					if (window.attachEvent) {   // Internet Explorer
						
						window.attachEvent("onmessage", OnMessage);
					}
				}
			}

		/*yuan: 分析message，判斷是否在相同房間並更改HTML顯示的分數*/
        function OnMessage (event) {
            var message = event.data.split ("|||");
            //var arr = message.split ("|||");
			//console.log('room ==== ".$WTFroom[0]."');
				for(var i in message){
					//alert(message[i]);
					var WTFmessage = message[i].split("<br>")
					messagesDiv = document.getElementById('messages');
					newMess = document.createElement('p');
					//newMess.innerHTML = message[i];
					newMess.innerHTML = "";
					for(var j in WTFmessage){
						var WTFroom = WTFmessage[j].split(" ==== room ");
						if(WTFroom[0]==<?php echo $_GET['roomid']?>){
							newMess.innerHTML=newMess.innerHTML+WTFroom[1]+"<br>";
							//alert('yayayaya');
						}
					}
					messagesDiv.appendChild(newMess);
					if(messagesDiv.childElementCount>1){
							messagesDiv.removeChild(messagesDiv.firstChild);
					}
					messagesDiv.scrollTop = messagesDiv.scrollHeight;
				}	
            
        }

		/*
        function addiframe(){
        	ifrm = document.createElement('iframe');
        	ifrm.setAttribute("src","http://<?php echo CON_IP.':'.CON_PORT; ?>");
        	ifrm.setAttribute("id","messagesiframe");
			ifrm.setAttribute("type","hidden");
        	divto = document.getElementById('holder');
        	divto.appendChild(ifrm);
        }*/
		
		window.onload = pageload;
		function pageload(){
			Init();
			judgegame();
			//setTimeout("addiframe()",2500);
		}
			
			var audioCtrl = document.querySelector ("#audioCtrl"),
				fileInput = document.querySelector ("#fileInput"),
				playBtn = document.querySelector ("#playBtn"),
				canvas = document.querySelector ("#myCanvas");
				canvas2 = document.querySelector ("#myCanvas2");
				waiting = document.querySelector ("#waiting");
				instruction = document.querySelector ("#instruction");
	
				
			//var test = document.querySelector ("#test");

			var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
			var source;
			var peaks;
			var startTime;
			var lastTime = 0;
			var score=0;
			var canvasCtx = canvas.getContext ("2d");
			var arrowSize = 90;
			var bigArrows = [new arrow (0, -150, 100, arrowSize, "bigArrowL.png"),
							 new arrow (1, -50, 100, arrowSize, "bigArrowD.png"),
							 new arrow (2, 50, 100, arrowSize, "bigArrowU.png"),
							 new arrow (3, 150, 100, arrowSize, "bigArrowR.png")];
			var bigArrows2 = [new arrow (0, -150, 100, arrowSize, "bigArrowL_R.png"),
							 new arrow (1, -50, 100, arrowSize, "bigArrowD_R.png"),
							 new arrow (2, 50, 100, arrowSize, "bigArrowU_R.png"),
							 new arrow (3, 150, 100, arrowSize, "bigArrowR_R.png")];
			var bigArrows3 = [new arrow (0, -150, 100, arrowSize, "bigArrowL.png"),
							 new arrow (1, -50, 100, arrowSize, "bigArrowD.png"),
							 new arrow (2, 50, 100, arrowSize, "bigArrowU.png"),
							 new arrow (3, 150, 100, arrowSize, "bigArrowR.png")];
			var loadedCnt = 0;
			for (var i=0; i<bigArrows.length; i++) {
			  bigArrows[i].img.onload = function () {
				loadedCnt++;
				if (loadedCnt == bigArrows.length)
				  drawCanvas ();
			  }
			}
			var speed = 800;
			var arrows = new Array ();
			var arrowSrcArray = ["bigArrowL.png", "bigArrowD.png", "bigArrowU.png", "bigArrowR.png"];
			var delayTime = 3;

			audioCtrl.style.display = 'none';
			
			
			<?php
			include("mysql_music_connect.php");
			$room_id = $_GET['roomid'];
			$sql_temp = "SELECT * from room_table WHERE room_no = $room_id";
			$result_temp = mysql_query($sql_temp)or die(mysql_error());
			$row_temp = mysql_fetch_assoc($result_temp);
			$room_creator =  $row_temp['creator_id'];
			if($room_creator == $_SESSION['username'])
				$hide = false;
			else
				$hide = true;
			
			if($hide==true){
				echo "playBtn.style.display='none';";
			}
			else{
				echo "playBtn.style.display='inline';";
			}
			?>
				
			
			

			waiting.style.display= 'none';
			instruction.style.display='none';
			
			function getisStart(t){
				$.post(
					"./check_start.php",
					{'attr':t, room_id:<?php echo $room_id;?>}
				).done(function(data){
					if(data==0){
						//console.log("1");
						//alert(isStart);
						setTimeout("getisStart(1);", 1000);
					}
					else{
						isStart = data;
						//alert(isStart);
						judgegame();
					}
				});
			}

			function judgegame(){
				if(isStart==0){  // not to start
					getisStart(1);
				}
				else{ //start
					StartGame();
				}
			}
			
			function StartGame(){
				if(isStart==1){
			showMsg(0);
			waiting.style.display= 'inline';
			  var file='<?php echo $row[1]?>';
			  console.log (file);
			  window.audioCtx = new AudioContext()
			  window.analyser = audioCtx.createAnalyser()
  
			  //var myBlob = new Blob(file, {type : "plain/text"});
			  //console.log (myBlob);
			  //var file = e.target.files[0];
			 // var objectURL = (URL || webkitURL).createObjectURL (myBlob);
			  //console.log (objectURL);
			  audioCtrl.src = file;

			  //source = audioCtx.createMediaElementSource (audioCtrl);
			  //source.connect (audioCtx.destination);

			  var request = new XMLHttpRequest();
			  request.open('GET', file, true);
			  request.responseType = 'arraybuffer';
			  request.onload = function() {
				var audioData = request.response;
				audioCtx.decodeAudioData(audioData, function(buffer) {
				  var offlineContext = new OfflineAudioContext(1, buffer.length, buffer.sampleRate);
				  source = offlineContext.createBufferSource();
				  source.buffer = buffer;

				  var filter = offlineContext.createBiquadFilter();
				  filter.frequency =400;
				  //filter.Q.value =50;
				  filter.type = "allpass";
				  source.connect(filter);
				  filter.connect(offlineContext.destination);

				  source.start(0);
				  offlineContext.startRendering();

				  offlineContext.oncomplete = function(e) {
					var initialThresold = 0.9,
						thresold = initialThresold,
						minThresold = 0.3,
						minPeaks = 600;

					/*do {
					  peaks = getPeaksAtThreshold(e.renderedBuffer.getChannelData(0), thresold);
					  thresold -= 0.05;
					} while (peaks.length < minPeaks && thresold >= minThresold);*/
					peaks = getPeaksAtThreshold(waveformApproximation(e.renderedBuffer.getChannelData(0)), thresold);
					peaks.sort (function (a,b){return a - b});
					console.log (peaks);
					console.log (1000/audioCtx.sampleRate*peaks[0]);

					source = audioCtx.createBufferSource ();
					source.buffer = buffer;
					filter = audioCtx.createBiquadFilter ();
					filter.frequency =200;
					//filter.Q.value =10;
					filter.type = "allpass";
					var delay = audioCtx.createDelay(3);
					delay.delayTime.value = 3;
					source.connect (filter);
					filter.connect (delay);
					delay.connect (audioCtx.destination);

					//playBtn.style.display = 'block';
					window.source = audioCtx.createBufferSource()  ; 
					source.buffer = buffer;
					// Connect to the analyser ...
					source.connect(analyser);
					// and back to the destination, to play the sound after the analysis.
					analyser.connect(audioCtx.destination);
						<?php
						include("mysql_music_connect.php");
						$room_id = $_GET['roomid'];
						$sql_temp = "SELECT * from room_table WHERE room_no = $room_id";
						$result_temp = mysql_query($sql_temp)or die(mysql_error());
						$row_temp = mysql_fetch_assoc($result_temp);
						$room_creator =  $row_temp['creator_id'];
						if($room_creator == $_SESSION['username'])
							$hide = false;
						else
							$hide = true;
						if($hide==false){
							echo  "playBtn.style.display = 'inline';";
						}
						?>
						waiting.style.display = 'none';
					//start game
						console.log("1");
						startTime = Date.now() - lastTime;
			  source.start (0, lastTime/1000);
			  showCombo();
			  draw ();
			  var viz = new simpleViz()
			// Finally, initialize the visualizer.
			new visualizer(viz['update'], analyser)
					}
				},
				function(e){"Error with decoding audio data" + e.err});
			  }
			  
			  request.send();
			  PrintScore();
			  }
			}

		

			audioCtrl.addEventListener ("play", function () {
			  //test ();
			});

			function waveformApproximation (data) {
			  var p=0;
			  for (var i=1; i<data.length-1; i++) {
				if (Math.abs(data[i])>Math.abs(data[i-1]) && Math.abs(data[i])>Math.abs(data[i+1])) {
				  p=data[i];
				}
				data[i-1]=p;
			  }
			  return data;
			}

			function getPeaksAtThreshold(data, threshold) {
			  var peaksArray = [];
			  var length = data.length;
			  var flag = 0.1;
			  
			  var range = 10000, srange=400;
			  var avg = average (data, 0, Math.min(length, range));
			  var savg = average (data, 0, Math.min(length, srange));

			  for(var i = 0; i < length; i++) {
				//avg = (avg*range*2-(i-range<0?avg:Math.abs(data[i-range]))+(i+range>=length?avg:Math.abs(data[i+range])))/(range*2);
				//savg = (savg*srange*2-(i-srange<0?savg:Math.abs(data[i-srange]))+(i+srange>=length?savg:Math.abs(data[i+srange])))/(srange*2);
				savg = average (data, Math.max(0, i-srange), Math.min(length, i+srange));
				//savg = savg-(i-srange<0?savg:Math.abs(data[i-srange]))/(srange*2)+(i+srange>=length?savg:Math.abs(data[i+srange]))/(srange*2);
				//avg = Math.sqrt((Math.pow(avg,2)*range*2-(i-range<0?Math.pow(avg,2):Math.pow(data[i-range],2))+(i+range>=length?Math.pow(avg,2):Math.pow(data[i+range],2)))/(range*2));
				//savg = Math.sqrt((Math.pow(savg,2)*srange*2-(i-srange<0?Math.pow(savg,2):Math.pow(data[i-srange],2))+(i+srange>=length?Math.pow(savg,2):Math.pow(data[i+srange],2)))/(srange*2));
				/*if (flag!=0) {
				  if (savg < avg)
					flag = 0;
				}
				else */if (savg > flag*1.95+0.035) {
				  peaksArray.push(i);
				  flag = savg;
				  i += 8000;
				  savg = average (data, Math.max(0,i-srange), Math.min(length,i+srange));
				}
				else {
				  //savg = Math.max(0.1, savg);
				  flag = Math.min(flag, savg);
				}
			  }
			  return peaksArray;
			}

			/*function test () {
			  if (audioCtrl.paused) return;

			  requestAnimationFrame(test);
			  if (audioCtrl.currentTime >= 1/audioCtx.sampleRate*peaks[0]) {
				test.style.display = 'block';
				peaks.shift ();
			  }
			  else
				test.style.display = 'none';

			  lastTime = audioCtrl.currentTime;
			}*/

			function draw () {
			source.onended = function() {
			//音樂撥放完之後
				isStart=0;
			  console.log('Your audio has finished playing');
              window.location.href = 'http://61.231.125.6/newBattle/end_game.php';
			}
			  if (false) return;
			  requestAnimationFrame(draw);
			  var audioTime = Date.now() - startTime;
			  var deltaTime = audioTime - lastTime;
			  //console.log (audioCtrl.paused);
			  //console.log (source.buffer.getChannelData(0)[Math.floor(audioCtx.sampleRate/1000*audioTime)]);
			  for (var i=0; i<arrows.length; i++)
				arrows[i].y-=speed*deltaTime/1000;
			  while (arrows.length>0 && arrows[0].y < 0-arrows[0].size/2){
				  arrows.shift();
				  console.log ("Miss");
				  scorex();
				  console.log (score);
				  combo=0;
				  console.log (combo);
				  combox();
				  document.getElementById("instruction").innerHTML='MISS';
				  instruction.style.display = 'block';
				  setTimeout("noShowMsg()",500); 
  
			  }
				

			  if (audioTime >= 1000/audioCtx.sampleRate*peaks[0]) {
				var ran = Math.floor(Math.random()*4);
				arrows.push(new arrow(ran, bigArrows[ran].x, bigArrows[ran].y+speed*delayTime, arrowSize, arrowSrcArray[ran]));
				peaks.shift();
			  }
			  lastTime = audioTime;

			  canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
			  for (var i=0; i<4; i++)
				canvasCtx.drawImage (bigArrows[i].img, canvas.width /2+bigArrows[i].x-bigArrows[i].size/2, bigArrows[i].y-bigArrows[i].size/2, bigArrows[i].size, bigArrows[i].size);
			  for (var i=0; i<arrows.length; i++) {
				canvasCtx.drawImage (arrows[i].img, canvas.width /2+arrows[i].x-arrows[i].size/2, arrows[i].y-arrows[i].size/2, arrows[i].size, arrows[i].size);
			  } 
			}

			function average (array, start, end) {
			  var total=0;;
			  for (var i=start; i<end; i++)
				total+=Math.abs(array[i]);
				//total+=Math.pow(array[i],2);
			  return total/(end-start);
			}

			function drawCanvas () {
			  canvas.width = window.innerWidth-200;
			  canvas.height = window.innerHeight-10;
			  for (var i=0; i<4; i++)
				canvasCtx.drawImage (bigArrows[i].img, canvas.width /2+bigArrows[i].x-bigArrows[i].size/2, bigArrows[i].y-bigArrows[i].size/2, bigArrows[i].size, bigArrows[i].size);
			   // canvasCtx.drawImage (bigArrows2[i].img, canvas.width /2+bigArrows2[i].x-bigArrows2[i].size/2, bigArrows2[i].y-bigArrows2[i].size/2, bigArrows2[i].size, bigArrows2[i].size);
			}

			function arrow (dir, x, y, size, src) {
			  this.dir = dir;
			  this.x = x;
			  this.y = y;
			  this.size = size;
			  this.img = new Image();
			  this.img.src = src;
			}

			var combo=0;

			document.addEventListener ('keyup', function (e) {
			  e = e || window.event;
			  if (e.keyCode == '37'){
				 ArrowKeyUp (0);
				 //touch(0);
			  }
				
			  else if (e.keyCode == '40'){
				  ArrowKeyUp (1);
				  //touch(1);
			  }
				
			  else if (e.keyCode == '38'){
				 ArrowKeyUp (2);
				 //touch(2);
			  }
				
			  else if (e.keyCode == '39'){
				ArrowKeyUp (3);
				//touch(3);
			  }
				
			  if (e.keyCode >= '37' && e.keyCode <= '40')
				e.preventDefault();
			});

			document.addEventListener ('keydown', function (e) {
			  e = e || window.event;
			  if (e.keyCode == '37'){
				 pressArrowKey (0);
				 //touch(0);
			  }
				
			  else if (e.keyCode == '40'){
				  pressArrowKey (1);
				  //touch(1);
			  }
				
			  else if (e.keyCode == '38'){
				 pressArrowKey (2);
				 //touch(2);
			  }
				
			  else if (e.keyCode == '39'){
				pressArrowKey (3);
				//touch(3);
			  }
				
			  if (e.keyCode >= '37' && e.keyCode <= '40')
				e.preventDefault();
			});
			function showCombo(canvas){
	
				var self = this;
				
				this.combocanvas=document.getElementById("combocanvas");
				this.comboctx=combocanvas.getContext("2d");
				
				setTimeout(function(){
					requestAnimationFrame(showCombo);
					console.log("RRRR"+combo);
					self.comboctx.width = window.innerWidth;
					self.comboctx.height = window.innerHeight;
					self.comboctx.clearRect(0,0,combocanvas.width,combocanvas.height);
					self.comboctx.font = "bold 40px sans-serif";
					self.comboctx.fillStyle = 'white';
					self.comboctx.fillText(combo,combocanvas.width/2,combocanvas.height/2);
					
				},1000/30);
			}
			function drawGood(){
				document.getElementById("instruction").innerHTML="Good";
				instruction.style.display = 'block';
				setTimeout("drawGood()", 100);
			}
			function printWait()
			{
				document.getElementById("score").innerHTML='waiting';
			}
			function drawCombo(){
				document.getElementById("combo").innerHTML=combo;
				setTimeout("drawCombo()", 100);
			}
			function combox(){
				return combo;
			}
			function scorex(){
				
				return score;
			}
			
			function noShowMsg(){
				instruction.style.display = 'none';
			}
			function touch(dir){
				canvasCtx.drawImage (bigArrows2[dir].img, canvas.width /2+bigArrows2[dir].x-bigArrows2[dir].size/2, bigArrows2[dir].y-bigArrows2[dir].size/2, bigArrows2[dir].size, bigArrows2[dir].size);

			}
			function pressArrowKey (dir) {
			  var minDist=Number.MAX_VALUE, minIndex;
			  bigArrows[dir].img = bigArrows2[dir].img;
			  drawCanvas ();
			  for (var i=0; i<arrows.length; i++) {
				if (arrows[i].dir==dir && Math.abs(arrows[i].y-bigArrows[dir].y)<minDist) {
				  minDist = Math.abs(arrows[i].y-bigArrows[dir].y);
				  minIndex = i;
				}
			  }
			  if (minIndex!=undefined) {
				  if(minDist<arrowSize && combo>=5){
					 // setTimeout("canvasCtx.drawImage (bigArrows2[dir].img, canvas.width /2+bigArrows2[dir].x-bigArrows2[dir].size/2, bigArrows2[dir].y-bigArrows2[dir].size/2, bigArrows2[dir].size, bigArrows2[dir].size)", 10);
					  
					  document.getElementById("instruction").innerHTML='EXCELLENT';
					  instruction.style.display = 'block';
					  setTimeout("noShowMsg()",500); 
					  arrows.splice(minIndex, 1);
					  console.log ("EXCELLENT");
					  score=score+100;
					  PrintScore();
					  scorex();
					  console.log (score);
					  combo++;
					  console.log (combo);
					  combox();
				  }
				  else if(minDist<arrowSize*1.25 && combo>=5){
					  //canvasCtx.drawImage (bigArrows2[dir].img, canvas.width /2+bigArrows2[dir].x-bigArrows2[dir].size/2, bigArrows2[dir].y-bigArrows2[dir].size/2, bigArrows2[dir].size, bigArrows2[dir].size);
					  document.getElementById("instruction").innerHTML='GOOD';
					  instruction.style.display = 'block';
					    setTimeout("noShowMsg()",500); 
					  arrows.splice(minIndex, 1);
					  console.log ("Badx");
					  score=score+50;
					  PrintScore();
					  scorex();
					  console.log (score);
					  combo++;
					  console.log (combo);
					  combox();
				}
				else if (minDist<arrowSize) {
				  //canvasCtx.drawImage (bigArrows2[dir].img, canvas.width /2+bigArrows2[dir].x-bigArrows2[dir].size/2, bigArrows2[dir].y-bigArrows2[dir].size/2, bigArrows2[dir].size, bigArrows2[dir].size);
				  document.getElementById("instruction").innerHTML='GOOD';
				  instruction.style.display = 'block';
				    setTimeout("noShowMsg()",500); 
				  arrows.splice(minIndex, 1);
				  console.log ("Good");
				  score=score+10;
				  PrintScore();
				  scorex();
				  console.log (score);
				  combo++;
				  console.log (combo);
				  combox(); 
				}
				
				else if (minDist<arrowSize*1.25) {
				  //canvasCtx.drawImage (bigArrows2[dir].img, canvas.width /2+bigArrows2[dir].x-bigArrows2[dir].size/2, bigArrows2[dir].y-bigArrows2[dir].size/2, bigArrows2[dir].size, bigArrows2[dir].size);
				  document.getElementById("instruction").innerHTML='BAD';
				  instruction.style.display = 'block';
				    setTimeout("noShowMsg()",500); 
				  arrows.splice(minIndex, 1);
				  console.log ("Bad");
				  score=score+1;
				  PrintScore();
				  scorex();
				  console.log (score);
				  combo++;
				  console.log (combo);
				  combox();
				}
				
				else {
				  document.getElementById("instruction").innerHTML='MISS';
				  instruction.style.display = 'block';
				    setTimeout("noShowMsg()",500); 
				  console.log ("Miss");
				  scorex();
				  console.log (score);
				  combo=0;
				  console.log (combo);
				  combox();
				}
			  }
			  else {
				document.getElementById("instruction").innerHTML='MISS';
				instruction.style.display = 'block';
				  setTimeout("noShowMsg()",500); 
				console.log ("Miss");
				scorex();
				console.log (score);
				combo=0;
				console.log (combo);
				combox();
			  }
			}
			function ArrowKeyUp (dir) {
			  bigArrows[dir].img = bigArrows3[dir].img;
			  drawCanvas ();
			}	
			
		</script>

		<div id="score" name = "score">
			<script>
				PrintScore();
			</script>
		</div>

		
		
  </body>
</html>

	
