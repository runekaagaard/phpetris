<?php
/**
 * @file The challenge class 
 * @author Rune Kaagaard
 * @license GPL2
 */
error_reporting(E_ALL);

//requires
require_once 'Board.php';

/**
 * processes input from http://tetrisapp.appspot.com and renders answer to page
 */
class Challenge {
	/**
	 * constructor. Executes commands.
	 */
	public function __construct() {
		$board = new Board($this->parseBoard($_GET));
		$piece = new $_GET['piece'];
		$brain = new Brain;
		$result = $brain->think($board, $piece);
		if (empty($result)) die ('GAME OVER');
		$this->render($result);
	}
	
	/**
	 * parse the string given from tetrisapp into an array
	 * @param $get
	 * @return array
	 */
	private function parseBoard($get) {
		$board = array();
		$rows = explode(' ', $get['board']);
		$y = count($rows)-1;
		foreach($rows as $row) {
			$cols = str_split($row);
			$x = 0;
			foreach ($cols as $col) {
				if ($col == '.') $col = 0;
				$board[$x][$y] = $col;
				$x++;
			}
			$y--;
		}
		return $board;
	}
	
	/**
	 * render result for tetrisapp
	 * @param $result object
	 */
	private function render($result) {
		$d = $result->rot * 90;
		exit ("position=$result->x&degrees=$d");
	}
}

//run challenge
new Challenge;