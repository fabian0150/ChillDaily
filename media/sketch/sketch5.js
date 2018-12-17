var totalPts = 300;
var steps = totalPts + 1;
  
function setup() {
  var myCanvas = createCanvas(windowWidth, windowHeight);
  myCanvas.parent("canvas"); 
  stroke(255);
  frameRate(1);
} 

function draw() {
 clear();
  var rand = 0;
  for  (var i = 1; i < steps; i++) {
    point( (width/steps) * i, (height/2) + random(-rand, rand) );
    rand += random(-5, 5);
  }
}