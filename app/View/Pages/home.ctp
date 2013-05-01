<div id="enchant-stage"></div>
<div id="credits">
	<h3>Extra credits</h3>
	<p><span class="bold">Music:</span> <a href="http://www.freesound.org/people/headphonesvocal/sounds/185274/">Headphones Vocal</a>
	<span class="bold">Sound FX: </span>
		<a href="http://www.freesound.org/people/kantouth/sounds/104398/">kantouth</a>,
		<a href="http://www.freesound.org/people/smcameron/sounds/51464/">smcameron</a>,
		<a href="http://www.freesound.org/people/Nbs%20Dark/sounds/83560/">Nbs Dark</a>
	</p>
</div>
<style type="text/css">
	body {
		margin: 0;
		padding: 0;
		background-color: #000;
	}
	#container {
		width: 100%;
		height: 100%;

	}
	#enchant-stage {
		margin: 0 auto 0;
	}
	.bold {
		font-weight: bold;
	}
	#credits {
		font-family: sans-serif;
		color: #FFF;
		background-color: transparent;
		width: 760px;
		margin: 0 auto 0;
	}
	#credits h3 {
		text-decoration: underline;
	}
</style>

<script type="text/javascript">

/**
 * Fly Robin, Fly! is a 2D side-scrolling shoot'em up that
 * adapts its difficulty according to the player's profile
 * using the k-means clustering algorithm.
 *
 * This part of the code is only concerning to the game's logic
 * and the data measurement to store it in the data base.
 *
 * @author		Jorge Palacios	http://jorge.palacios.co/
 */

/**
 * Function for extending the Number class
 * in order to add '0' to the left.
 */
	Number.prototype.pad = function(size){
		if(typeof(size) !== "number"){size = 2;}
		var s = String(this);
		while (s.length < size) s = "0" + s;
		return s;

	}

	enchant();
	var game = null;
	var g_GameWidth = 760;
	var g_GameHeight = 450;
	var g_TopBorder = 40;
	var gameFps = 60;
	var gameScale = 1;
	var g_UrlSlice = "<?php echo $this->Html->url(array('controller' => 'slices', 'action' => 'add')); ?>";
	var g_UrlGame = "<?php echo $this->Html->url(array('controller' => 'games', 'action' => 'update')); ?>";
	var g_UrlNext = "<?php echo $this->Html->url(array('controller' => 'games', 'action' => 'new_game')); ?>";
	var g_UrlLevel = "<?php echo $this->Html->url(array('controller' => 'centroids', 'action' => 'get_centroid')); ?>";
	var imgRoot = "<?php echo $this->webroot; ?>/img/";
	var sndRoot = "<?php echo $this->webroot; ?>/snd/";
	var imgPlayer = imgRoot + "player.png";
	var imgBackground = imgRoot + "background.png";
	var imgScore = imgRoot + "score.png";
	var imgTitle = imgRoot + "title.png";
	var imgHow = imgRoot + "how.png";
	var imgBtnPlay = imgRoot + "btn_play.png";
	var imgBtnHow = imgRoot + "btn_how.png";
	var imgBtnAgain = imgRoot + "btn_again.png";
	var imgLaserPlayer = imgRoot + "lazer_blue.png";
	var imgLaserEnemy= imgRoot + "lazer_red.png";
	var imgRock = imgRoot + "asteroid.png";
	var imgEnemyShip = imgRoot + "enemy.png";

	var sndLaser1 = sndRoot + "laser.wav";
	var sndLaser2 = sndRoot + "laser2.wav";
	var sndExplosion1 = sndRoot + "explosion.wav";
	var sndExplosion2 = sndRoot + "explosion2.wav";
	var sndSong = sndRoot + "busybody-loop.ogg";

	var g_MousePrev = {x:0,y:0};
	var g_MouseCurr = {x:0,y:0};

	var g_LevelText = "LEVEL: standard";
	var g_LevelColor = "#99cc33";
	var g_LevelValue = -1;


	window.onload = function () {
		game = new Game(g_GameWidth, g_GameHeight);
		game.fps = gameFps;
		game.scale = gameScale;

		game.preload(imgBackground,imgScore,imgTitle,imgHow,imgBtnPlay,imgBtnHow,imgBtnAgain,imgPlayer,imgLaserPlayer,imgLaserEnemy,imgRock,imgEnemyShip,sndLaser1,sndLaser2,sndExplosion1,sndExplosion2,sndSong);
		game.onload = function () {	
			goToScene("title", game);
		};
		game.start();
	};

/**
 * Funtion for handling the transitions betweetn scenes
 */
	function goToScene(scene, gameObj) {
		var newScene;
		switch (scene) {
			default:
			case "title":
				newScene = new SceneTitle(gameObj);
				break;
			case "main":
				newScene = new SceneMain(gameObj);
				break;
			case "how":
				newScene = new SceneHow(gameObj);
				break;
			case "over":
				newScene = new SceneOver(gameObj);
				break;
		}
		gameObj.replaceScene(newScene);
	}

/**
 * The Monitor singleton class is in charge of storing the game values
 * to be measured.
 */
	var Monitor = {
		m_GameId: 0,
		m_GameTime: 0,
		m_Energy: 0,
		m_Shots: 0,
		m_EnemiesShot: 0,
		m_EnemiesAvoided: 0,
		m_RocksShot: 0,
		m_RocksAvoided: 0,
		m_TotalShots: 0,
		m_TotalEnemiesShot: 0,
		m_TotalEnemiesAvoided: 0,
		m_TotalRocksShot: 0,
		m_TotalRocksAvoided: 0,
		/**
		 * Resets the measurement in the current
		 * time step (slice)
		 *
		 * @return	void
		 */
		resetSlice: function () {
			this.m_TotalShots += this.m_Shots;
			this.m_TotalEnemiesShot += this.m_EnemiesShot;
			this.m_TotalEnemiesAvoided += this.m_EnemiesAvoided;
			this.m_TotalRocksShot += this.m_RocksShot;
			this.m_TotalRocksAvoided += this.m_RocksAvoided;
			this.m_Shots = 0;
			this.m_EnemiesShot = 0;
			this.m_EnemiesAvoided = 0;
			this.m_RocksShot = 0;
			this.m_RocksAvoided = 0;
		},
		/**
		 * Resets all the values to 0.
		 *
		 * @return	void
		 */
		reset: function () {
			this.m_TotalShots = 0;
			this.m_TotalEnemiesShot = 0;
			this.m_TotalEnemiesAvoided = 0;
			this.m_TotalRocksShot = 0;
			this.m_TotalRocksAvoided = 0;
			this.m_Shots = 0;
			this.m_EnemiesShot = 0;
			this.m_EnemiesAvoided = 0;
			this.m_RocksShot = 0;
			this.m_RocksAvoided = 0;	
		}
	};

/**
 * Class for creating the lasers shot in the game
 * either from the player and from the enemy ships.
 */
	var Laser = Class.create(Sprite, {
		/**
		 * Constructor
		 *
		 * @param	gameObj 	the singleton game object
		 * @param	x 			x-axis position
		 * @param	y 			y-axis position
		 * @param	type 		string of laser type (player,laser)
		 * @return	void
		 */
		initialize: function (gameObj, x, y, type) {
			Sprite.call(this, 32, 8);
			this.m_Type = "player";
			this.image = gameObj.assets[imgLaserPlayer];
			this.x = x;
			this.y = y - (this.height/2);
			this.m_Speed = 500;
			this.m_Alive = true;
			this.addEventListener(Event.ENTER_FRAME, this.update);
			if (type == "laser") {
				this.image = gameObj.assets[imgLaserEnemy];
				this.m_Speed = -370;
			}
		},
		/**
		 * Update function called every single frame
		 *
		 * @param	evt 	event object
		 * @return	void
		 */
		update: function (evt) {
			this.x += this.m_Speed * evt.elapsed / 1000;
			if (this.x > g_GameWidth) {
				this.m_Alive = false;
			}
		},
		isAlive: function () {
			return this.m_Alive;
		},
		kill: function () {
			this.m_Alive = false;
		}
	});

/**
 * Abstract class for enemies (obstacle and enemy ships)
 */
	var Enemy = Class.create(Sprite, {
		/**
		 * Constructor
		 *
		 * @param	gameObj 	singleton game object
		 * @param	x 			x-axis position
		 * @param	y 			y-axis position
		 * @return	void
		 */
		initialize: function (gameObj, x, y) {
			this.init(gameObj, x, y, 0, 0, "enemy");
		},
		/**
		 * Initialization function
		 *
		 * @param	gameObj 	singleton game object
		 * @param	x 			x-axis position
		 * @param	y 			y-axis position
		 * @param	w 			width
		 * @param 	h 			height
		 * @param	type 		string, type of enemy (ship, rock)
		 * @return	void
		 */
		init: function (gameObj, x, y, w, h, type) {
			Sprite.call(this, w, h);
			this.x = x;
			this.y = y;
			this.m_Speed = 0;
			this.m_Alive = true;
			this.m_Type = type;
			this.addEventListener(Event.ENTER_FRAME, this.update);
		},
		/**
		 * Update function called every single frame
		 *
		 * @param	evt 	event object
		 * @return	void
		 */
		update: function (evt) {
			this.x -= this.m_Speed * evt.elapsed / 1000;
			if (this.x + this.width < 0) {
				this.m_Alive = false;
				if (this.m_Type == "ship") {
					Monitor.m_EnemiesAvoided++;
				}
				else if (this.m_Type == "rock") {
					Monitor.m_RocksAvoided++;
				}
			}
		},
		/**
		 * Checks if the alive value is true or false
		 *
		 * @return	bool
		 */
		isAlive: function () {
			return this.m_Alive;
		},
		/**
		 * Sets the alive value to false
		 *
		 * @return	void
		 */
		kill: function () {
			this.m_Alive = false;
		}
	});

/**
 * Class for defining the rocks (asteroids)
 */
	var Rock = Class.create(Enemy, {
		/**
		 * Constructor
		 *
		 * @param	gameObj 	singleton game object
		 * @param	x 			x-axis position
		 * @param 	y 			y-axis position
		 * @return 	void
		 */
		initialize: function (gameObj, x, y) {
			this.m_GameObj = gameObj;
			var w = 80, h = 70;
			this.init(gameObj, x, y, w, h, "rock");
			this.image = gameObj.assets[imgRock];
			this.m_Speed = 100;
			this.m_RotationSpeed = Math.random() * 100 - 50;
			this.addEventListener(Event.ENTER_FRAME, this.rotate);
		},
		/**
		 * Called every single frame, rotates the asteroid using
		 * the defined rotation speed.
		 *
		 * @return void
		 */
		rotate: function (evt) {
			this.rotation += this.m_RotationSpeed * evt.elapsed / 1000;
		}
	});

	var EnemyShip = Class.create(Enemy, {
		/**
		 * Constructor
		 *
		 * @param	gameObj 	singleton game object
		 * @param	x 			x-axis position
		 * @param 	y 			y-axis position
		 * @return 	void
		 */
		initialize: function (gameObj, x, y) {
			this.m_GameObj = gameObj;
			this.m_ShootTimer = 0;
			this.m_ShootTime = 1200;
			var w = 90, h = 60;
			this.init(gameObj, x, y, w, h, "ship");
			this.image = gameObj.assets[imgEnemyShip];
			this.m_Speed = 140;
			this.addEventListener(Event.ENTER_FRAME, this.shoot);
		},
		/**
		 * Creates a new laser
		 *
		 * @param	evt 		event object
		 * @return void
		 */
		shoot: function (evt) {
			this.m_ShootTimer += evt.elapsed;
			if (this.m_ShootTimer >= this.m_ShootTime) {
				this.m_GameObj.assets[sndLaser2].play();
				var laserY = this.height/2 + this.y;
				var laserX = this.x;
				this.scene.newLaser(laserX,laserY, "laser");
				this.m_ShootTimer -= this.m_ShootTime;
			}
		}
	});

/**
 * Ship controlled by the player
 */
	var Player = Class.create(Sprite, {
		/**
		 * Constructor
		 * 
		 * @param	gameObj 	singleton game object
		 */
		initialize: function (gameObj) {
			Sprite.call(this, 90, 60);
			this.image = gameObj.assets[imgPlayer];
			this.m_ShootTime = 500;
			this.m_ShootTimer = 0;
			this.m_Energy = 100;
			this.m_GameObj = gameObj;
			this.m_GameObj.keybind(32, 'space');
			this.x = 50;
			this.y = g_GameHeight/2 - this.width/2;
			this.m_Speed = 450;
			this.m_ShootPrev = false;
			this.m_ShootCurr = false;
			this.addEventListener(Event.TOUCH_START, this.touchStart);
			this.addEventListener(Event.TOUCH_MOVE, this.touchMove);
			this.addEventListener(Event.ENTER_FRAME, this.update);
		},
		/**
		 * Creates a new laser
		 *
		 * @return	void
		 */
		shoot: function () {
			this.m_GameObj.assets[sndLaser2].play();
			var laserY = this.height/2 + this.y;
			var laserX = this.x + this.width;
			this.scene.newLaser(laserX,laserY, "player");
			Monitor.m_Shots++;
		},
		/**
		 * Handles the touch on the ship
		 *
		 * @param	evt 	event object
		 * @return	void
		 */
		touchStart: function (evt) {
			g_MouseCurr = {x:evt.x,y:evt.y};
			if (this.m_ShootTimer > 0) {
				this.shoot();
			}
			this.m_ShootTimer = this.m_ShootTime;
		},
		/**
		 * Handles the touch drag on the ship
		 *
		 * @param	evt 	event object
		 * @return	void
		 */
		touchMove: function (evt) {
			g_MousePrev = {x:g_MouseCurr.x, y:g_MouseCurr.y};
			g_MouseCurr = g_MouseCurr = {x:evt.x,y:evt.y};
			this.x += (g_MouseCurr.x - g_MousePrev.x);
			this.y += (g_MouseCurr.y - g_MousePrev.y);
			
		},
		/**
		 * Update function on every frame
		 *
		 * @param	evt 	event object
		 * @return	void
		 */
		update: function (evt) {
			var input = this.m_GameObj.input;
			
			this.m_ShootPrev = this.m_ShootCurr;
			this.m_ShootCurr = input.space;

			if (input.up && !input.down)
				this.y -= this.m_Speed * evt.elapsed / 1000;
			if (input.down && !input.up)
				this.y += this.m_Speed * evt.elapsed / 1000;
			if (input.left && !input.right)
				this.x -= this.m_Speed * evt.elapsed / 1000;
			if (input.right && !input.left)
				this.x += this.m_Speed * evt.elapsed / 1000;
			if (this.m_ShootCurr && !this.m_ShootPrev)
				this.shoot();

			if (this.m_ShootTimer > 0) {
				this.m_ShootTimer -= evt.elapsed;
			}

			if (this.y < g_TopBorder)
				this.y = g_TopBorder;
			if (this.y + this.height > g_GameHeight)
				this.y = g_GameHeight - this.height;
			if (this.x < 0)
				this.x = 0;
			if (this.x > 300)
				this.x = 300;
		},
		/**
		 * Gets the player's current  health.
		 *
		 * @return	int
		 */
		getEnergy: function () {
			return this.m_Energy;
		},
		/**
		 * Sets a dama quantity to the player.
		 *
		 * @param	damage 	int
		 * @return	void
		 */
		setDamage: function (damage) {
				this.m_Energy -= damage;
				if (this.m_Energy < 0)
					this.m_Energy = 0;
		}
	});


/**
 * Title screen
 */
	
	var SceneTitle = Class.create(Scene, {
		/**
		 * Constructor
		 * 
		 * @param	gameObj 	singleton game object
		 */
		initialize: function (gameObj) {
			Scene.apply(this);
			this.m_GameObj = gameObj;
			this.m_NextScene = "main";
			var background = new Sprite(g_GameWidth, g_GameHeight);
			background.image = this.m_GameObj.assets[imgTitle];
			this.addChild(background);
			var btnPlay = new Sprite(215, 91);
			btnPlay.image = gameObj.assets[imgBtnPlay];
			btnPlay.x = 273;
			btnPlay.y = 160;
			btnPlay.addEventListener(Event.TOUCH_START, function (evt) {
				btnPlay.scaleX = 1.2;
				btnPlay.scaleY = 1.2;				
			});
			btnPlay.addEventListener(Event.TOUCH_END, function (evt) {
				btnPlay.scaleX = 1.0;
				btnPlay.scaleY = 1.0;
				goToScene("main", this.parentNode.m_GameObj);
			});
			this.addChild(btnPlay);
			var btnHow = new Sprite(215, 91);
			btnHow.image = gameObj.assets[imgBtnHow];
			btnHow.x = 273;
			btnHow.y = 280;
			btnHow.addEventListener(Event.TOUCH_START, function (evt) {
				btnHow.scaleX = 1.2;
				btnHow.scaleY = 1.2;
			});
			btnHow.addEventListener(Event.TOUCH_END, function (evt) {
				btnHow.scaleX = 1.0;
				btnHow.scaleY = 1.0;
				goToScene("how", this.parentNode.m_GameObj);
			});
			this.addChild(btnHow);
			
		}
	});

/**
 * Instructions screen
 */
	var SceneHow = Class.create(Scene, {
		/**
		 * Constructor
		 * 
		 * @param	gameObj 	singleton game object
		 */
		initialize: function (gameObj) {
			Scene.apply(this);
			this.m_GameObj = gameObj;
			var background = new Sprite(g_GameWidth, g_GameHeight);
			background.image = this.m_GameObj.assets[imgHow];
			this.addChild(background);
			var btnPlay = new Sprite(215, 91);
			btnPlay.image = gameObj.assets[imgBtnPlay];
			btnPlay.x = 273;
			btnPlay.y = 320;
			btnPlay.addEventListener(Event.TOUCH_START, function (evt) {
				btnPlay.scaleX = 1.2;
				btnPlay.scaleY = 1.2;				
			});
			btnPlay.addEventListener(Event.TOUCH_END, function (evt) {
				btnPlay.scaleX = 1.0;
				btnPlay.scaleY = 1.0;
				goToScene("main", this.parentNode.m_GameObj);
			});
			this.addChild(btnPlay);
		}
	});


/**
 * Game Over screen
 */
	var SceneOver = Class.create(Scene, {
		/**
		 * Constructor
		 * 
		 * @param	gameObj 	singleton game object
		 */
		initialize: function (gameObj) {
			Scene.apply(this);
			this.m_GameObj = gameObj;
			var background = new Sprite(g_GameWidth, g_GameHeight);
			background.image = this.m_GameObj.assets[imgScore];
			this.addChild(background);
			
			var labels = new Array(new Label("health: " + Monitor.m_Energy),
				                   new Label("time: " + Monitor.m_GameTime),
				                   new Label("shots: " + Monitor.m_TotalShots),
				                   new Label("enemies shot: " + Monitor.m_TotalEnemiesShot),
				                   new Label("enemies avoided: " + Monitor.m_TotalRocksAvoided),
				                   new Label("rocks shot: " + Monitor.m_TotalRocksShot),
				                   new Label("rocks avoided: " + Monitor.m_TotalRocksAvoided));
			var labelsSize = labels.length;
			var labelsOrigin = {x:120,y:150};
			var labelsFontSize = 18;
			var labelsFont = labelsFontSize+"px sans-serif";
			for (i = 0; i < labelsSize; i++) {
				labels[i].x = labelsOrigin.x;
				labels[i].y = labelsOrigin.y + (labelsFontSize*i);
				labels[i].font = labelsFont;
				labels[i].color = "#FFF";
				labels[i].textAlign = "right";
				this.addChild(labels[i]);
			}
			var btnPlay = new Sprite(215, 91);
			btnPlay.image = gameObj.assets[imgBtnAgain];
			btnPlay.x = 273;
			btnPlay.y = 320;
			btnPlay.addEventListener(Event.TOUCH_START, function (evt) {
				btnPlay.scaleX = 1.2;
				btnPlay.scaleY = 1.2;				
			});
			btnPlay.addEventListener(Event.TOUCH_END, function (evt) {
				btnPlay.scaleX = 1.0;
				btnPlay.scaleY = 1.0;
				goToScene("main", this.parentNode.m_GameObj);
			});
			this.addChild(btnPlay);
		}
	});

/**
 * Main game screen
 */
	var SceneMain = Class.create(Scene, {
		/**
		 * Constructor
		 * 
		 * @param	gameObj 	singleton game object
		 */
		initialize: function (gameObj) {
			Scene.apply(this);
			Monitor.reset();
			this.m_Slices = new Array();
			this.m_LevelRequest = true;
			$.ajax({
				type: "POST",
				url: g_UrlNext,
				success: function (result) {
					Monitor.m_GameId = parseInt(result);
				}
			});
			this.m_LaserList = [];
			this.m_EnemyList = [];
			this.m_GameObj = gameObj;
			this.m_GameTimeLimit = 90000;
			this.m_GameTime = this.m_GameTimeLimit;
			this.m_RockTimer = 0;
			this.m_RockTime = 1500;
			this.m_EnemyShipTimer = 0;
			this.m_EnemyShipTime = 2500;
			this.m_DamageShip = 12;
			this.m_DamageLaser = 6;
			this.m_DamageRock = 8;
			this.m_SliceTimer = 0;
			this.m_SliceTime = 5;
			this.m_SliceTime *= 1000;
			this.m_Player = new Player(this.m_GameObj);
			var background = new Sprite(g_GameWidth, g_GameHeight);
			background.image = this.m_GameObj.assets[imgBackground];
			this.addChild(background);
			this.addChild(this.m_Player);
			this.addEventListener(Event.ENTER_FRAME, this.update);
			this.m_LabelEnergy = new Label("HEALTH: ");
			this.m_LabelEnergy.x = 5;
			this.m_LabelEnergy.color = "#FFF";
			this.m_LabelEnergy.font = "20px sans-serif";
			this.addChild(this.m_LabelEnergy);
			this.m_LabelTime = new Label("TIME: ");
			this.m_LabelTime.x = 290;
			this.m_LabelTime.color = "#FFF";
			this.m_LabelTime.font = "20px sans-serif";
			this.addChild(this.m_LabelTime);
			this.m_LabelLevel = new Label("LEVEL: standard");
			this.m_LabelLevel.x = 560;
			this.m_LabelLevel.font = "20px sans-serif";
			this.m_LabelLevel.color = "#FFF";
			this.m_LabelLevel.align = "right";
			this.addChild(this.m_LabelLevel);
			g_LevelValue = -1;
			this.m_GameObj.assets[sndSong].play();
			Monitor.reset();
		},
		/**
		 * Sends the final report to the server, resets counter values
		 * and transitions to the game over screen.
		 *
		 * @return	void
		 */
		endGame: function () {
			this.m_GameObj.assets[sndSong].stop();
			Monitor.resetSlice();
			if (Monitor.m_GameTime > this.m_GameTimeLimit) {
				Monitor.m_GameTime = this.m_GameTimeLimit;
			}
			Monitor.m_GameTime = Math.floor(Monitor.m_GameTime/1000);
			Monitor.m_Energy = this.m_Player.getEnergy();
			var report = {
				id: Monitor.m_GameId,
				time: Monitor.m_GameTime,
				energy: Monitor.m_Energy,
				shots: Monitor.m_TotalShots,
				enemies_shot: Monitor.m_TotalEnemiesShot,
				enemies_avoided: Monitor.m_TotalEnemiesAvoided,
				rocks_shot: Monitor.m_TotalRocksShot,
				rocks_avoided: Monitor.m_TotalRocksAvoided
			};
			$.ajax({
				type: "POST",
				url: g_UrlGame,
				data: report,
				dataType: "json",
				success: function (result) {
					console.log("game: " + result);
				}
			});
			this.m_GameObj.replaceScene(new SceneOver(this.m_GameObj));
		},
		/**
		 * Game's logic updated every single frame.
		 *
		 * @param	evt 	event object
		 * @return	void
		 */
		update: function (evt) {

			// value adjustment according to player's profile
			switch (g_LevelValue) {
				default:
				case 0:
					this.m_EnemyShipTime = 2500;
					this.m_RockTime = 1500;
					break;
				case 1:
					this.m_EnemyShipTime = 1500;
					this.m_RockTime = 2500;
					break;
				case 2:
					this.m_EnemyShipTime = 3000;
					this.m_RockTime = 800;
					break;
			}
			
			this.m_GameTime -= evt.elapsed;
			Monitor.m_GameTime += evt.elapsed;
			this.m_LabelLevel.text = g_LevelText;
			this.m_LabelLevel.color = g_LevelColor;

			// Sends information about player's performance and retrieves
			// player's profile according to the information.
			if (this.m_LevelRequest == true && Monitor.m_GameTime > 35000) {
				this.m_LevelRequest = false;
				var numSlices = this.m_Slices.length;
				var enemies_shot = 0;
				var rocks_shot = 0;
				for (i = 0; i < numSlices; i++) {
					enemies_shot += this.m_Slices[i].enemies_shot;
					rocks_shot += this.m_Slices[i].rocks_shot;
				}
				enemies_shot /= numSlices;
				rocks_shot /= numSlices;
				var my_url = g_UrlLevel + "/" + enemies_shot + "/" + rocks_shot;
				var level = -1;
				$.get(my_url, function (response) {
					level = parseInt(response);
					g_LevelValue = level;
					switch (level) {
						default:
						case 0:
							g_LevelColor = "#99cc33";
							g_LevelText = "LEVEL: standard";
							break;
						case 1:
							g_LevelColor = "#3366cc";
							g_LevelText = "LEVEL: rock crasher";
							break;
						case 2:
							g_LevelColor = "#cc3333";
							g_LevelText = "LEVEL: enemy blaster";
							break;
					}
				});
				
			}

			// Measurement sending to the server when time is right
			this.m_SliceTimer += evt.elapsed;
			if (this.m_SliceTimer >= this.m_SliceTime) {
				this.m_SliceTimer -= this.m_SliceTime;
				slice = {
					game_id: Monitor.m_GameId,
					shots: Monitor.m_Shots,
					enemies_shot: Monitor.m_EnemiesShot,
					enemies_avoided: Monitor.m_EnemiesAvoided,
					rocks_shot: Monitor.m_RocksShot,
					rocks_avoided: Monitor.m_RocksAvoided
				};
				this.m_Slices.push(slice);
				$.ajax({
					type: "POST",
					url: g_UrlSlice,
					data: slice,
					dataType: "json",
					success: function (result) {
						console.log(result);
					}
				});
				
				Monitor.resetSlice();
			}

			if (this.m_GameTime <= 0 || this.m_Player.getEnergy() <= 0) {
				this.endGame();
			}
			
			// main game logic and collisions
			var minutes = Math.floor(this.m_GameTime / 1000 / 60);
			var seconds = Math.floor(this.m_GameTime / 1000 % 60);
			this.m_LabelTime.text = "TIME: " + minutes.pad(2) + ":" + seconds.pad(2);
			var energy = this.m_Player.getEnergy();
			this.m_LabelEnergy.text = "HEALTH: " + energy.pad(3);

			this.m_RockTimer -= evt.elapsed;
			if (this.m_RockTimer < 0) {
				this.m_RockTimer = this.m_RockTime;
				this.newRock();
			}
			this.m_EnemyShipTimer -= evt.elapsed;
			if (this.m_EnemyShipTimer < 0) {
				this.m_EnemyShipTimer = this.m_EnemyShipTime;
				this.newEnemyShip();
			}

			var numLasers = this.m_LaserList.length;
			var numEnemies = this.m_EnemyList.length;
			player = this.m_Player;
			// Check collisions
			for (i = 0; i < numEnemies; i++) {
				enemy = this.m_EnemyList[i];
				for (j= 0; j < numLasers; j++) {
					laser = this.m_LaserList[j];

					if (enemy.m_Type == "ship" || enemy.m_Type == "rock") {
						if (enemy.within(laser)) {
							laser.kill();
							enemy.kill();
							if (enemy.m_Type == "ship") {
								Monitor.m_EnemiesShot++;
								this.m_GameObj.assets[sndExplosion1].play();
							}
							else {
								Monitor.m_RocksShot++;
								this.m_GameObj.assets[sndExplosion2].play();
							}
						}
					}
					else {
						// laser vs laser
						if (enemy.intersect(laser)) {
							laser.kill();
							enemy.kill();
						}
					}
				}

				if (player.intersect(enemy)) {
					var damage = 0;
					switch (enemy.m_Type) {
						default:
						case "laser":
							damage = this.m_DamageLaser;
							break;
						case "ship":
							damage = this.m_DamageShip;
							break;
						case "rock":
							damage = this.m_DamageRock;
							break;
					}
					player.setDamage(damage);
					enemy.kill();
				}
			}

			// 'dead' entities disposal
			var listSize = this.m_LaserList.length;
			for (i = 0; i < listSize; i++) {
				if (this.m_LaserList[i].isAlive() == false) {
					this.removeChild(this.m_LaserList[i]);
					this.m_LaserList.splice(i,1);
					i--;
					listSize--;
				}
			}
			listSize = this.m_EnemyList.length;
			for (i = 0; i < listSize; i++) {
				if (this.m_EnemyList[i].isAlive() == false) {
					this.removeChild(this.m_EnemyList[i]);
					this.m_EnemyList.splice(i,1);
					i--;
					listSize--;
				}
			}
		},
		/**
		 * Adds a new laser to the game accordint to its type
		 *
		 * @param	x 		x-axis position
		 * @param 	y 		y-axis position
		 * @param	type 	string
		 * @return	void
		 */
		newLaser: function (x, y, type) {
			var laser = new Laser(this.m_GameObj, x, y, type);
			if (type == "player") {
				this.m_LaserList.push(laser);
			}
			else {
				this.m_EnemyList.push(laser);
			}
			this.addChild(laser);
		},
		/**
		 * Adds a new asteroid to the game
		 *
		 * @return	void
		 */
		newRock: function () {
			var rock = new Rock(this.m_GameObj, g_GameWidth, g_GameHeight);
			rock.y = Math.random() * (g_GameHeight - rock.height - g_TopBorder) + g_TopBorder;
			this.m_EnemyList.push(rock);
			this.addChild(rock);
		},
		/**
		 * Adds a new enemy ship to the game
		 *
		 * @return	void
		 */
		newEnemyShip: function () {
			var enemyShip = new EnemyShip(this.m_GameObj, g_GameWidth, g_GameHeight);
			enemyShip.y = Math.random() * (g_GameHeight - enemyShip.height - g_TopBorder) + g_TopBorder;
			this.m_EnemyList.push(enemyShip);
			this.addChild(enemyShip);
		}
	});
</script>