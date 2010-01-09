<?php
/**
 * @file the brain class
 * @author Rune Kaagaard
 * @license GPL2
 */

/**
 * Thinks about tetris and calculates the best move
 */
class Brain {
	
	/**
	 * return the best move from a given board and a given piece
	 * 
	 * @param $board array the board
	 * @param $piece obj the piece class
	 * @param $fHoles int the weight that minimizing holes should have
	 * @param $fHeight int the weight that keeping height low should have
	 * @param $fScore int the weight that getting points should have
	 * @param $fy int the weight that placing the piece low should have
	 * @return object the best result 
	 */
	public function think($board, $piece, $fHoles=3, $fHeight=2, $fScore=1, $fy=2) {
		//init
		$s = new Stat;
		$sym = $piece->getSym();
		$name = $piece->getName();
		$res = new stdClass();
		//start low
		$res->score = -999999999999999999999;
		$stat = $s->get($board);
		//loop through the available symmetries of the piece
		foreach ($sym as $rot) {
			//loop through the positions on x-axis
			foreach (range(0,$board->dimX) as $x) {
				//clone a board to compare with original
				$tmp = clone $board;
				//add the piece
				$y = $tmp->addPiece($x, $name, $rot);
				//make sure move is legal
				if ($y !== false) {;
					//calc delta stats
					$statTmp = $s->get($tmp);
					$dScore = ($tmp->score - $board->score) / 10;
					$dHoles = $statTmp->holes - $stat->holes;
					$dHeight = $statTmp->maxHeight - $stat->maxHeight;
					$dy = $statTmp->maxHeight - $y;
					//calc score based on weights
					$score = 0.0
						- $dHoles * $fHoles
						- $dHeight * $fHeight
						+ $dScore * $fScore
						+ $dy * $fy
					;
					//update score if new highscore
					if ($score > $res->score) {
						$res->x = $x;
						$res->rot = $rot;
						$res->score = $score;
					}
				}
			}
		}
		//if no legal move was found, return status a false
		if (!isset($res->x)) {
			$res->status = false;
			$res->board = $board;
			return $res;
		//otherwise return the best move found
		} else {
			$res->status = true;
			$board->addPiece($res->x, $name, $res->rot);
			$res->board = $board;
			return $res;
		}
	}
}