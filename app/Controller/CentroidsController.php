<?php
App::uses('AppController', 'Controller');
/**
 * Centroids Controller
 *
 * @property Centroid $Centroid
 */
class CentroidsController extends AppController {

/**
 * Gets the cluster level from a given tuple
 *
 * @param 	int 	$enemies_shot
 * @param 	int 	$rocks_shot
 * @return int
 */
	public function get_centroid ($enemies_shot, $rocks_shot) {
		$this->layout = 'ajax';
		$this->autoRender = false;	
		// Normalise values
		$total = ($enemies_shot * $enemies_shot) + ($rocks_shot * $rocks_shot);
		$total = sqrt($total);
		if ($total == 0)
			$total = 0.001;
		$x = $enemies_shot/$total;
		$y =  $rocks_shot/$total;
		//pr("x: ".$x."   y: ".$y);
		$centroids = $this->Centroid->find('all');

		for ($i = 0; $i < 3; $i++) {
			$cX = $centroids[$i]['Centroid']['x'];
			$cY = $centroids[$i]['Centroid']['y'];
			$centroids[$i]['Centroid']['distance'] = $this->distance($x, $y, $cX, $cY);
		}
		//pr($centroids);
		for ($i = 0; $i < 2; $i++) {
			for ($j = $i+1; $j < 3; $j++) {
				if ($centroids[$j]['Centroid']['distance'] < $centroids[$i]['Centroid']['distance']) {
					$aux = $centroids[$i];
					$centroids[$i] = $centroids[$j];
					$centroids[$j] = $aux;
				}
			}
		}
		//pr($centroids);
		echo $centroids[0]['Centroid']['level'];
	}


/**
 * Calculates the euclidean distance squared between two points
 * 
 * @param 	int 	$x1
 * @param 	int 	$y1
 * @param 	int 	$x2
 * @param 	int 	$y2
 * @return 	int
 */
	private function distance ($x1, $y1, $x2, $y2) {
		$result = ($x1 - $x2) * ($x1 - $x2) + ($y1 - $y2) * ($y1 - $y2);
		return $result;
	}
}
