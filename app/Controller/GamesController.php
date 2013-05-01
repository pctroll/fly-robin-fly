<?php
App::uses('AppController', 'Controller');
/**
 * Games Controller
 *
 * @property Game $Game
 */
class GamesController extends AppController {



/**
 * Creates a new game entry and
 * returns its id for slicing reference.
 *
 * @param 	json 	via POST: 
 * @return 	int
 */	public function new_game() {
		date_default_timezone_set("America/Caracas");
		$this->layout = 'ajax';
		$this->autoRender = false;
		$ipLocal = $_SERVER['SERVER_ADDR'];
		$ipClient= $this->request->clientIp();
		$id = 0;
		if ($this->request->is('post')) {
			$this->request->data['time'] = 0;
			$this->request->data['energy'] = 0;
			$this->request->data['shots'] = 0;
			$this->request->data['enemies_shot'] = 0;
			$this->request->data['enemies_avoided'] = 0;
			$this->request->data['rocks_shot'] = 0;
			$this->request->data['rocks_avoided'] = 0;
			$this->request->data['posteddate'] = date("Y-m-d H:i:s");
			$this->request->data['class'] = "";
			$this->Game->create();
			if ($this->Game->save($this->request->data)) {
					$id = $this->Game->getLastInsertId();
			}
		}
		echo $id;
	}


/**
 * Updates the final game value with the final results of the session
 * @param 	json 	via POST
 * @return 	string
 */

	public function update () {
		date_default_timezone_set("America/Caracas");
		$this->layout = 'ajax';
		$this->autoRender = false;
		$ipLocal = $_SERVER['SERVER_ADDR'];
		$ipClient= $this->request->clientIp();
		$response = "error";
		if ($this->request->is('post')) {
			if ($this->Game->exists($this->request->data['id'])) {
				$this->request->data['posteddate'] = date("Y-m-d H:i:s");
				if ($this->Game->save($this->request->data)) {
					$response = "ok";
				}
			}	
		}
		echo $response;
	}

}
