<?php
App::uses('AppController', 'Controller');
/**
 * Slices Controller
 *
 * @property Slice $Slice
 */
class SlicesController extends AppController {



/**
 * Adds a new game slice entry into the data base
 *
 * @param 	json 	via POST: {game_id, shots, enemies_shot, enemies_avoided, rocks_shot, rocks_avoided}
 * @return 	void
 */
	public function add() {
		date_default_timezone_set("America/Caracas");
		$this->layout = 'ajax';
		$this->autoRender = false;
		$x = 0;
		if ($this->request->is('post')) {
			$this->request->data['posteddate'] = date("Y-m-d H:i:s");
			$this->Slice->create();
			for ($x = 3; $x != 0; $x -= 1) {
				if ($this->Slice->save($this->request->data)) {
					break;
				}
			}
		}
		if ($x > 0) {
			echo "ok";
		}
		else {
			echo "error";
		}
	}


/**
 * Executes the k-means algorithm over the game slices entries.
 * @author Ian Barber. ianbarber@gmail.com
 */
	public function kmeans() {
		$this->layout = 'ajax';
		$this->autoRender = false;
		$this->loadModel('Centroid');

		$db_data = $this->Slice->find('all', array('fields' => array('Slice.id', 'Slice.enemies_shot', 'Slice.rocks_shot', 'Slice.centroid_id')));
		$data = array();
		foreach ($db_data as $d) {
			$row = array($d['Slice']['enemies_shot'],
				          $d['Slice']['rocks_shot']);
			array_push($data, $row);
		}
		// Normalise input data
		$x = 10;
		foreach ($data as $key => $d) {
			$data[$key] = $this->normalise_value($d,  sqrt($d[0]*$d[0] + $d[1] * $d[1]));
		}

		// K-Means beginning
		$k = 3; // because we're looking for 3 levels
		$centroids = $this->initialise_centroids($data, $k);
		$mapping = array();
		while (true) {
			$new_mapping = $this->assign_centroids($data, $centroids);
			$changed = false;
			foreach ($new_mapping as $document_id => $centroid_id) {
				if (!isset($mapping[$document_id]) || $centroid_id != $mapping[$document_id]) {
					$mapping = $new_mapping;
					$changed = true;
					break;
				}
			}
			if (!$changed) {
				break;
			}
			$centroids = $this->update_centroids($mapping, $data, $k);
		}
		// K-Means end
		$distance = array();
		$x = 0;
		foreach ($centroids as $c) {
			$d = $this->distance($c[0], 0, $c[1], 0);
			$d = array($x, $d);
			array_push($distance, $d);
			$x++;
		}
		// sort distance ascending
		for ($i = 0; $i < 2; $i++) {
			for ($j = $i + 1; $j < 3; $j++) {
				if ($centroids[$i][0] > $centroids[$j][0]) {
					$aux = $distance[$i];
					$distance[$i] = $distance[$j];
					$distance[$j] = $aux;
				}
			}
		}
		$my_centroids = array();
		$my_centroids['Centroid'][0]['id'] = $distance[0][0];
		$my_centroids['Centroid'][0]['x'] = $centroids[$distance[0][0]][0];
		$my_centroids['Centroid'][0]['y'] = $centroids[0][1];
		$my_centroids['Centroid'][0]['level'] = 0;
		$my_centroids['Centroid'][1]['id'] = $distance[1][0];
		$my_centroids['Centroid'][1]['x'] = $centroids[$distance[1][0]][0];
		$my_centroids['Centroid'][1]['y'] = $centroids[$distance[1][0]][1];
		$my_centroids['Centroid'][1]['level'] = 1;
		$my_centroids['Centroid'][2]['id'] = $distance[2][0];
		$my_centroids['Centroid'][2]['x'] = $centroids[$distance[2][0]][0];
		$my_centroids['Centroid'][2]['y'] = $centroids[$distance[2][0]][1];
		$my_centroids['Centroid'][2]['level'] = 2;
		$this->Centroid->query('TRUNCATE centroids;');	
		$this->Centroid->create();
		$this->Centroid->saveMany($my_centroids['Centroid']);
		
		
		$x = 0;
		// Update records centroid_id (class)
		$data = array();
		foreach ($db_data as $d) {
			$d['Slice']['centroid_id'] = $mapping[$x];
			$data[$x] = array('id' => $d['Slice']['id'],
				              'centroid_id' => $mapping[$x]);
			$x++;
		}
		$this->Slice->saveAll($data);
		echo "success";
	}

/**
 * Initialises the centroids randomly
 * @author Ian Barber. ianbarber@gmail.com
 *
 * @param array 	the 2d data array
 * @param int 		number of clusters to be found
 * @return array 	centroids (2d points)
 */
	private function initialise_centroids (array $data, $k) {
		$dimensions = count($data[0]);
		$centroids = array();
		$dimmax = array();
		$dimmin = array();
		foreach ($data as $document) {
			foreach ($document as $dim => $val) {
				if (!isset($dimmax[$dim]) || $val > $dimmax[$dim]) {
					$dimmax[$dim] = $val;
				}
				if (!isset($dimmin[$dim]) || $val > $dimmin[$dim]) {
					$dimmin[$dim] = $val;
				}
			}
		}
		for ($i = 0; $i < $k; $i++) {
			$centroids[$i] = $this->initialise_centroid($dimensions, $dimmax, $dimmin);
		}
		return $centroids;
	}

/**
 * Initialise a centroid
 * @author Ian Barber. ianbarber@gmail.com
 *
 * @param array 	the data
 * @param int 		number of clusters
 * @return array 	point (x,y)
 */
	private function initialise_centroid ($dimensions, $dimmax, $dimmin) {
		$total = 0;
		$centroid = array();
		for ($j = 0; $j < $dimensions; $j++) {
			$centroid[$j] = (rand($dimmin[$j] * 1000, $dimmax[$j] * 1000));
			$total += $centroid[$j] * $centroid[$j];
		}
		$centroid = $this->normalise_value($centroid, sqrt($total));
		return $centroid;
	}

/**
 * @author Ian Barber. ianbarber@gmail.com
 *
 * @param 	array 	the data
 * @param 	array 	centroids
 * @return 	array 	mapping: data id assigned to centroids
 */
	private function assign_centroids($data, $centroids) {
		$mapping = array();
		foreach ($data as $document_id => $document) {
			// wired initialisation
			//$document = array(0,0);
			$min_dist = 100;
			$min_centroid = null;
			foreach ($centroids as $centroid_id => $centroid) {
				$dist = 0;
				foreach ($centroid as $dim => $value) {
					$dist += abs($value - $document[$dim]);
				}
				if ($dist < $min_dist) {
					$min_dist = $dist;
					$min_centroid = $centroid_id;
				}
			}
			$mapping[$document_id] = $min_centroid;
		}
		return $mapping;
	}

/**
 * Updates the centroids coordinates accordint to the training
 * @author Ian Barber. ianbarber@gmail.com
 *
 * @param 	array 	mapping
 * @param 	array 	data
 * @return 	int 	number of clusters
 */
	private function update_centroids($mapping, $data, $k) {
		$centroids = array();
		$counts = array_count_values($mapping);
		foreach ($mapping as $document_id => $centroid_id) {
			// wired initialisation
			//$centroids[$centroid_id] = array(0,0,0);
			foreach ($data[$document_id] as $dim => $value) {
				error_reporting(0);
				$centroids[$centroid_id][$dim] += ($value/$counts[$centroid_id]);
			}
		}
		if (count($centroids) < $k) {
			$centroids = array_merge($centroids, $this->initialise_centroids($data, $k - count($centroids)));
		}
		return $centroids;
	}

/**
 * Shows the data in a readable-friendly way.
 */
	private function format_results($mapping, $data, $centroids) {
		$result = array();
		$result['centroids'] = $centroids;
		foreach ($mapping as $document_id => $centroid_id) {
			$result[$centroid_id][] = implode(',', $data[$document_id]);
		}
		return $result;
	}

/**
 * Normalise the input values of k-Means.
 * @author Ian Barber. ianbarber@gmail.com
 *
 * @param array
 * @param number
 * @return array
 */
	private function normalise_value(array $vector, $total) {
		foreach ($vector as &$value) {
			if ($total == 0)
				$total = 0.001;
			$value = (float)($value/$total);
		}
		return $vector;
	}

	private function distance ($x1, $y1, $x2, $y2) {
		$result = ($x1 - $x2) * ($x1 - $x2) + ($y1 - $y2) * ($y1 - $y2);
		return sqrt($result);
	}

}// end of class
