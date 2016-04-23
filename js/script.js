
var audioCtrl = document.querySelector ("#audioCtrl"),
    fileInput = document.querySelector ("#fileInput"),
    playBtn = document.querySelector ("#playBtn"),
    canvas = document.querySelector ("#myCanvas");
	canvas2 = document.querySelector ("#myCanvas2");
	
var test = document.querySelector ("#test");

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
playBtn.style.display = 'none';
test.style.display = 'none';

fileInput.addEventListener ("change", function (e) {
  var file="<?php echo $row[0]?>"";
 // var file = e.target.files[0];
  var objectURL = (URL || webkitURL).createObjectURL (file);
  audioCtrl.src = objectURL;

  //source = audioCtx.createMediaElementSource (audioCtrl);
  //source.connect (audioCtx.destination);

  var request = new XMLHttpRequest();
  request.open('GET', objectURL, true);
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

        playBtn.style.display = 'block';
      }
    },
    function(e){"Error with decoding audio data" + e.err});
  }
  request.send();
});

playBtn.addEventListener ("click", function () {
  startTime = Date.now() - lastTime;
  source.start (0, lastTime/1000);
  draw ();
});

audioCtrl.addEventListener ("play", function () {
  test ();
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

function test () {
  if (audioCtrl.paused) return;

  requestAnimationFrame(test);
  if (audioCtrl.currentTime >= 1/audioCtx.sampleRate*peaks[0]) {
    test.style.display = 'block';
    peaks.shift ();
  }
  else
    test.style.display = 'none';

  lastTime = audioCtrl.currentTime;
}

function draw () {
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
function PrintScore(){
	document.getElementById("score").innerHTML=score;
	setTimeout("PrintScore()", 100);
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
		  
		  arrows.splice(minIndex, 1);
		  console.log ("Goodx");
		  score=score+100;
		  scorex();
		  console.log (score);
		  combo++;
		  console.log (combo);
		  combox();
	  }
	  else if(minDist<arrowSize*1.25 && combo>=5){
		  //canvasCtx.drawImage (bigArrows2[dir].img, canvas.width /2+bigArrows2[dir].x-bigArrows2[dir].size/2, bigArrows2[dir].y-bigArrows2[dir].size/2, bigArrows2[dir].size, bigArrows2[dir].size);
		  arrows.splice(minIndex, 1);
		  console.log ("Badx");
		  score=score+5;
		  scorex();
		  console.log (score);
		  combo++;
		  console.log (combo);
		  combox();
	}
    else if (minDist<arrowSize) {
      //canvasCtx.drawImage (bigArrows2[dir].img, canvas.width /2+bigArrows2[dir].x-bigArrows2[dir].size/2, bigArrows2[dir].y-bigArrows2[dir].size/2, bigArrows2[dir].size, bigArrows2[dir].size);
	  arrows.splice(minIndex, 1);
      console.log ("Good");
	  score=score+10;
	  scorex();
	  console.log (score);
	  combo++;
	  console.log (combo);
	  combox(); 
    }
	
    else if (minDist<arrowSize*1.25) {
	  //canvasCtx.drawImage (bigArrows2[dir].img, canvas.width /2+bigArrows2[dir].x-bigArrows2[dir].size/2, bigArrows2[dir].y-bigArrows2[dir].size/2, bigArrows2[dir].size, bigArrows2[dir].size);
	  arrows.splice(minIndex, 1);
      console.log ("Bad");
	  score=score+1;
	  scorex();
	  console.log (score);
	  combo++;
	  console.log (combo);
      combox();
    }
	
    else {
      console.log ("Miss");
	  scorex();
	  console.log (score);
	  combo=0;
	  console.log (combo);
	  combox();
    }
  }
  else {
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
