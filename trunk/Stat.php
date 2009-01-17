<?php
/**
 * @file the Statistics class
 * @author Rune Kaagaard
 * @license GPL2
 */

/**
 * Stat class, has one public method get() that returns an object with stats 
 * about the board
 */
class Stat {
	/**
	 * get all available statistics about board
	 * 
	 * @param $board array
	 * @return obj
	 */
	public function get($board) {
		$stat = new stdClass();
		$stat->maxHeight = $this->maxHeight($board);
		$stat->holes = $this->holes($board);
		return $stat;
	}
	
	/**
	 * the maximum height allocated by a piece
	 * 
	 * @param $b board
	 * @return int
	 */
	private function maxHeight($b) {
		$max = 0;
		foreach (range($b->dimX, 0) as $x) {
			foreach (range($b->dimY,0) as $y) {
				if ($b->board[$x][$y]) {
					$max = max($max, $y);
					break;
				}
			}
		}
		return $max;
	}
	
	/**
	 * counts holes in board
	 * 
	 * @param $b board
	 * @return int
	 */
	private function holes($b) {
		$holes = 0;
		foreach (range($b->dimX, 0) as $x) {
			foreach (range($b->dimY,0) as $y) {
				if ($b->board[$x][$y]) {
					if ($y < 1) break;
					$maxY = $y;
					foreach (range($maxY-1,0) as $y) {
						if (!$b->board[$x][$y]) $holes++;
					}
					break;
				}
			}
		}
		return $holes;
	}
	
	/**
	 * render the statistics as text
	 * 
	 * @param $stat the object that get() has returned
	 */
	public function render($stat) {
		echo "\n### Stat ###\n";
		foreach ($stat as $k=>$v) {
			echo "$k: $v\n";
		}
	}
}
?>