var num = 2000;
var range = 6;

var ax = [];
var ay = [];


function setup() {
 	var myCanvas = createCanvas(windowWidth, windowHeight);
 	myCanvas.parent("canvas");
 	for ( var i = 0; i < num; i++ ) {
    	ax[i] = width / 2;
		ay[i] = height / 2;
	}
	frameRate(30);
}

function draw() {
	clear();

  // Shift all elements 1 place to the left
  for ( var i = 1; i < num; i++ ) {
    ax[i - 1] = ax[i];
    ay[i - 1] = ay[i];
  }

  // Put a new value at the end of the array
  ax[num - 1] += random(-range, range);
  ay[num - 1] += random(-range, range);

  // Constrain all points to the screen
  ax[num - 1] = constrain(ax[num - 1], 0, width);
  ay[num - 1] = constrain(ay[num - 1], 0, height);

  // Draw a line connecting the points
  for ( var j = 1; j < num; j++ ) {
    var val = j / num * 204.0 + 51;
    stroke(val);
    line(ax[j - 1], ay[j - 1], ax[j], ay[j]);
  }
}