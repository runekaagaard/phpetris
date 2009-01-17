<?php
/**
 * @file the board class
 * @author Rune Kaagaard
 * @license GPL2
 */

//requires
require_once 'Pieces.php';
require_once 'Stat.php';
require_once 'Brain.php';

/**
 * handles the tetris board, drops pieces and validate moves
 */
class Board {
	/** @var array the board */
	public $board;
	/** @var scored points */
	public $score=0;
	/** @var points assigned to 0,4 completed rows */
	public $points=array(0,10,25,40,55);
	/** @var count of how many pieces has been dropped */
	public $count=0;
	/** @var cols from 0 */
	public $dimX=9;
	/** @var rows from 0 */
	public $dimY=19;
	public function __construct($board=null) {
		if (empty($null)) {
			$this->reset();
		} else {
			$this->board = $board;
		}
	}
	
	/**
	 * generates a clean board
	 */
	public function reset() {
		foreach (range(0,$this->dimX) as $x) {
			foreach (range(0,$this->dimY) as $y) {
				$this->board[$x][$y] = 0;
			}
		}
	}
	
	/**
	 * renders board as in a textual representation
	 */
	public function render() {
		echo "### Board ###";
		foreach (range($this->dimY, 0) as $y) {
			echo "\n".str_pad($y, 2, 0, 'right').' ';
			foreach (range(0,$this->dimX) as $x) {
				echo str_replace(0, '.', $this->board[$x][$y]);
			}
		}
		echo "\n   ";
		foreach (range(0,$this->dimX) as $x) echo substr($x, -1);
		echo "\n\n";
		echo "### Info ###\n";
		$count = ($this->count+1);
		echo "score: $this->score\n";
		echo "count: ".$count."\n";
		echo "factor: ".round($this->score/$count,4)."\n";
	}
	
	/**
	 * adds a piece to the board
	 * @param $x x-axis number from 0
	 * @param $name name of the piece
	 * @param $rot rotation state of piece
	 * @return bool status of piece dropping, will fail if piece wont fit
	 */
	public function addPiece($x, $name, $rot) {
		$piece = new $name($rot);
		$shape = $piece->getShape();
		$y = $this->dropPiece($x, $shape);
		if ($y === false) { 
			//echo "piece: $name does not fit at x: $x\n";
			return false;
		}
		foreach (range(0,$shape['w']) as $dx) {
			foreach (range(0,$shape['h']) as $dy) {
				if ($shape[$dx][$dy]) {
					$this->board[$x+$dx][$y+$dy] = $shape[$dx][$dy]; 
				}
			}
		}
		$this->completedRows();
		$this->count++;
		return $y;
	}
	
	/**
	 * drops a piece on the board
	 * @param $x location on x-axis
	 * @param $shape the shape of the piece
	 * @return status
	 */
	
	private function dropPiece($x, $shape) {
		if ($x+$shape['w'] > $this->dimX) return false;
		foreach (range($this->dimY-$shape['h'],0) as $y) {
			foreach (range(0,$shape['w']) as $dx) {
				foreach (range(0,$shape['h']) as $dy) {
					if ($shape[$dx][$dy] && $this->board[$x+$dx][$y+$dy]) {
						$y++;
						if ($y+$shape['h'] > $this->dimY) return false;
						return $y;
					}
				}
			}
		}
		if ($y+$shape['h'] > $this->dimY) return false;
		if ($y == 0) {
			return 0;
		} else {
			return false;
		}
	}
	
	/**
	 * removes and add points for completed rows
	 */
	private function completedRows() {
		$i = 0;
		foreach (range(0,$this->dimY) as $y) {
			$isComplete = true;
			foreach (range(0,$this->dimX) as $x) {
				if (!$this->board[$x][$y]) {
					$isComplete = false;
				}
			}
			if ($isComplete) {
				$i++;
				foreach (range($y,$this->dimY-1) as $y2) {
					foreach (range(0,$this->dimX) as $x) {
						$this->board[$x][$y2] = $this->board[$x][$y2+1];
					}
				}
				foreach (range(0,$this->dimX) as $x) {
					$this->board[$x][$this->dimY] = 0;
				}
			}
		}
		$this->score += $this->points[$i];
	}
}