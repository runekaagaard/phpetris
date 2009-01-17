<?php
/**
 * @file Play multiple games of tetris rendered on command line, and
 * keep simple stats
 * @author Rune Kaagaard
 * @license GPL2
 */

error_reporting(E_ALL);
require_once 'Board.php';

$fHoles=5;
$fHeight=2;
$fScore=1;
$fy=2;

$b = new Board;
$br = new Brain;
$s = new Stat;

if (empty($last)) $last=0;
if (empty($max)) $max=0;
if (empty($played)) $played=0;
if (empty($won)) $won=0;
if (empty($factor)) $factor=0;
if (empty($wonPct)) $wonPct=0;

$res->status = true;

$i=0;
while ($res->status && $i<200) {
	$piece = Factory::randomPiece();
	$res = $br->think($b, $piece, $fHoles, $fHeight, $fScore, $fy);
	$b = $res->board;
	$stat = $s->get($b);
	echo `clear`;
	$b->render();
	$s->render($stat);
	echo "\n### Games ###\nbest score: $max\nbest factor: $factor\nlast: $last\nplayed: $played\nwon: $wonPct %";
	$i++;
}

$played++;
if ($res->status) $won++;
$max = max($max, $b->score);
$last = $b->score;
$factor = $max/200;
$wonPct = round($won / $played*100,4); 

include (__FILE__);