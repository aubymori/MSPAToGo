// JavaScript Document// firefly canvas display engine...  LGL
// --------------------------------- DRY method (Do not Repeat Yourself codewise
//
//  RANDOM NUMBER GENERATOR--	results = whole number no decimals
//		The (nmod) provides a modifier that adds an extra amount to the outcome.
//			nmin = minimum number
//			nmax = maximum number
//				EXAMPLE:	randnum (0, 10, 0) = random number between 0 and 10
//				EXAMPLE:	randnum (0, 50, 2) = random number between 0 and 50...  adds +2 to the returned result
function randnum(nmin, nmax, nmod) {
	return ((Math.floor(Math.random() * (nmax - nmin + 1)) + nmin) + nmod);
}

// IMAGE LOADER function - need to load the image to the canvas block so it will be seen
//		IMG Loader = handles setting up both 'Right' facing and 'Let' facing image animates for each canvas block
//		IMG not loaded = you will not see anything.
function make_img(c)
{
  beer[c] = new Image();
  beel[c] = new Image();
  beer[c].src = sbeer[c];
  beel[c].src = sbeel[c];
  beer[c].onload = function(){
    cobj[c].drawImage(beer[c], 0, 0);
  }
  beel[c].onload = function(){
    cobj[c].drawImage(beel[c], 0, 0);
  }
}

//	VARIABLE and OBJECT ITEM code below
//	FIXED Width and Height sizes  (200 x 150)
var w =200,		//  width of a canvas box
	h =150,		//	height of a canvas box
	fmax = 24	//	the maximum number of frames in the animated .gif file
	radius =60,	//	collision size to check hitting box edge.  Smaller= closer to edge, Larger = further away from edges
	bounceFactor =1,	//	smooth animation... movement moves at 1 point a second.  Increasing will make item move faster
	xx = w - 60,	// x adjustment for placing firefly xcoord so it is not outside box
	yy = h - 60,	// y adjust for placing firefly ycoord so it is not outside box
	fpsrate = 15,	//  Change Frames Per Second Rate.  Determines whole screen refreshing rate (lower = slower, higher = faster)
	boxnum = 4;	//	IMPORTANT ==== Contains the number of <canvas> animation boxes you want on screen.
					//  boxnum was equal the same number of <canvas id="fbox_1", "fbox_2", etc..> boxes you have in your HTML page.
					//	Any missing boxes or skipping numbers will break the display.
	
//  OBJECTS for animation
var cobj =[];	//	object storing the 'canvas' playground box
var beer =[];	//  Bee Right-	facing image container for a <CANVAS>
var beel =[];	//	Bee Left-	facing image container for a <CANVAS>
var sbeer =[];	//	Bee Right-	Storage container to hold IMG source name for a <CANVAS> - to transfer info to object
var sbeel =[];	//	Bee Left-	Storage container to hold IMG source name for a <CANVAS> - to transfer info to object

//  BUILD object to hold all 'firefly' instances
var firefly = {};

//	START BUILDING ALL NEEDED firefly objects
for (var x = 0; x < boxnum; x++) {
	var sname = document.getElementById('fbox_' + x);		//	Get the ID or name of canvas object
	sname.width = w;		//  FORCE canvas block to be a specific width size
	sname.height = h;		//	FORCE canvas block to be a specific height size

	var tname = document.getElementById('fbox_' + x).getContext('2d');	//	Attach the full 'canvas' controls to make it work
	cobj.push(tname)	// The Object ID container for a specific <canvas> box

//	Create needed images! - R and L facing types (BEER - right, BEEL - left)
    sbeer.push("/assets/img/firefly_sprite_opt.png");		//	firefly facing right sprite sheet load  - img source must be found here
    sbeel.push("/assets/img/firefly_sprite_left_opt.png");	//	firefly facing left sprite sheet load	- img source must be found here
	make_img(x);	//	Call IMAGE CREATOR function for a <canvas> box

//	CREATE a FIREFLY object to store its personality or specifics -  IMPORTANT.
//		This OBJECT allows to control and maintain large numbers of entities and makes programming simpler to maintain	
	firefly[x] = {
		dir: randnum(0, 1, 0),	//	random num from 0 to 1 (0 = move left, 1 = move right)
		x: randnum(0, xx, 0),	//	place img random x coord
		y: randnum(0, yy, 0),	//	place img random y coord
		xvel: randnum(0, 3, 1),	//	xmovement velocity
		yvel: randnum(0, 3, 1),	//	ymovement velocity
		imgw: 60,	//	img width
		imgh: 60,	//	img height
		frame: randnum(0,fmax,0),	//	animation frame to start from- usually 0
		tick: randnum(0,fmax,0),	//	anim frame counter... each number is a frame in img animation
		tx: 0,		//	temporary x value to track which anim frame is viewed
		framemax: 12,	//	max number of animation frames in img (firefly = 24 frames).  Stack always starts at 0 so minus 1!
		delay :0,	//	delay flag to stop gif/sprite animation before repeating cycle
		delaytick :0	// stores the delay counter
	}
}

// FUNCTION - CLEAR canvas screen.
//	Erases content of box before new updated motions are 'drawn' - IMPORTANT do not erase
function clearCanvas(i) {
	cobj[i].clearRect(0, 0, w, h);
}

//  ANIMATION function - go move RIGHT
function drawr(i){
		cobj[i].save();
//		firefly[i].frame = firefly[i].tick % firefly[i].framemax;	//	frame counter stays between 0 and maxframes (23)
//		firefly[i].frame ++;
		if (firefly[i].frame > firefly[i].framemax) {
			firefly[i].frame = 0;
			firefly[i].delay = 1;
			firefly[i].delaytick = 0;			
		}
		firefly[i].tx = firefly[i].frame * firefly[i].imgw;			//	moves animation block to next frame on sprite sheet
		cobj[i].drawImage(beer[i],firefly[i].tx,0,firefly[i].imgw,firefly[i].imgh,	//  DISPLAY Bee Right image anim
		firefly[i].x,firefly[i].y,firefly[i].imgw,firefly[i].imgh);
		if (firefly[i].delay){
			firefly[i].delaytick ++;
			if (firefly[i].delaytick > fpsrate){
				firefly[i].delay = 0;
				firefly[i].delaytick = 0;
			}
		} else {
			firefly[i].frame++	//	move to next animation frame
		}
		cobj[i].restore();	//	done updating the canvas-  close task!
}
//  ANIMATION function - go move LEFT
function drawl(i){
		cobj[i].save();
//		firefly[i].frame = firefly[i].tick % firefly[i].framemax;	//	frame counter stays between 0 and maxframes (23)
//		firefly[i].frame ++;
		if (firefly[i].frame > firefly[i].framemax) {
			firefly[i].frame = 0;
			firefly[i].delay = 1;
			firefly[i].delaytick = 0;			
		}
		firefly[i].tx = firefly[i].frame * firefly[i].imgw;			//	moves animation block to next frame on sprite sheet
		cobj[i].drawImage(beel[i],firefly[i].tx,0,firefly[i].imgw,firefly[i].imgh,	//  DISPLAY Bee Right image anim
		firefly[i].x,firefly[i].y,firefly[i].imgw,firefly[i].imgh);
		if (firefly[i].delay){
			firefly[i].delaytick ++;
			if (firefly[i].delaytick > fpsrate){
				firefly[i].delay = 0;
				firefly[i].delaytick = 0;
			}
		} else {
			firefly[i].frame++	//	move to next animation frame
		}
		cobj[i].restore();	//	done updating the canvas-  close task!
}

//  ANIMATE all the FIREFLY boxes function
function update(){
	for (var i = 0; i < boxnum; i++) {		//	CYCLE through all canvas boxes you want - use BOXNUM variable
		clearCanvas(i);						//  Clear canvas screen before showing new animation content
		//	DISPLAY correct facing animation (LEFT or RIGHT)
		if (firefly[i].dir) {
			drawr(i);		// moving to Right - firefly face right
		} else {
			drawl(i);		// moving to Left - firefly face left
		}
		// Now, lets make the firefly move to a new position
		firefly[i].y += firefly[i].yvel;
		firefly[i].x += firefly[i].xvel;

		// EDGE collision check - BOTTOM BOX COLLISION
		if(firefly[i].y + radius > h) {
			firefly[i].y = h - radius;
			firefly[i].yvel = randnum(1,2,1);	//	random ymove rate!  random (2) + 1
			firefly[i].yvel *= -bounceFactor;	//	change direction
		}
		// EDGE collision check - TOP BOX COLLISION
		if(firefly[i].y - radius < -60) {
			firefly[i].yvel *= -bounceFactor;	//	change direction
			firefly[i].y += firefly[i].yvel;
			firefly[i].yvel = randnum(1,2,1);	//	random ymove rate!  random (2) + 1
		}
		// EDGE collision chek - RIGHT BOX COLLISION
		if (firefly[i].x + radius > w) {
			firefly[i].dir = 0;	// change firefly facing direction
			firefly[i].x = w - radius;
			firefly[i].xvel = randnum(1,2,1);	//	random ymove rate!  random (2) + 1
			firefly[i].xvel *= -bounceFactor;
		}
		// EDGE collision chek - LEFT BOX COLLISION
		if (firefly[i].x - radius < -60) {
			firefly[i].dir = 1;	// change firefly facing direction
			firefly[i].xvel *= -bounceFactor;
			firefly[i].x += firefly[i].xvel;
			firefly[i].xvel = randnum(1,2,1);	//	random ymove rate!  random (2) + 1
		}
    }
}
setInterval(update, 1000/fpsrate);	// change FPSRATE to adjust canvas refreshing rate - reg speed is 24 fps. Higher = more demanding
