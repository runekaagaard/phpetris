<?php
/**
 * @file Play a single game of tetris rendered on command line
 * @author Rune Kaagaard
 * @license GPL2
 */

error_reporting(E_ALL);
require_once 'Board.php';

$b = new Board;
$br = new Brain;
$s = new Stat;

$sleep = (!empty($_SERVER['argv'][1])) ? (float)($_SERVER['argv'][1]) * 1000000 : 0;

$i=0;
$res->status = true;
while ($res->status && $i<200) {
	$piece = Factory::randomPiece();
	$res = $br->think($b, $piece);
	$b = $res->board;
	$stat = $s->get($b);
	echo `clear`;
	$b->render();
	$s->render($stat);
	usleep($sleep);
	$i++;
}

if ($res->status) {
	$msg = 'You Won!!';
} else {
	$msg = 'Game Over';
}

exit ("\n#############\n# $msg #\n#############\nscore is: $b->score\ncount is: $i\n");