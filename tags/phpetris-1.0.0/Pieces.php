<?php
/**
 * @file The master Piece class, the Factory piece builder class and the individual
 * Pieces classes 
 * @author Rune Kaagaard
 * @license GPL2
 */

/**
 * Holds methods common for all pieces
 */
abstract class Piece {
	/** @var int which rotation state from 0 to maximum 3 the piece is in */
	protected $rot = 0;
	/** @var array list of possible rotation states */
	protected $sym = array(0);
	
	/**
	 * constructor
	 * 
	 * @param $rot initialize rotation
	 */
	public function __construct($rot=0) {
		$this->rotate($rot);
	}
	
	/**
	 *  get shape of current rotation state
	 * 
	 * return array
	 */
	public function getShape() {
		 return $this->shapes[$this->rot];
	}
	
	/**
	 * rotate the piece
	 * 
	 * @param $rot int the desired rotation state
	 * @return unknown_type
	 */
	public function rotate($rot) {
		if (!in_array($rot, $this->sym)) throw new Exception('Rotation out of range');
		$this->rot = $rot;
	}
	
	/**
	 * get possible rotation states (symmetries)
	 * 
	 * @return array
	 */
	
	public function getSym() {
		return $this->sym;
	}
	
	/**
	 * get name of piece
	 * 
	 * @return string
	 */
	public function getName() {
		return get_class($this);
	}
}

/**
 * factory class for now only used for getting a random piece
 */
class Factory {
	private static $pieces = array('i','j','l','o','s','t','z');
	public static function randomPiece() {
		$r = mt_rand(0, count(self::$pieces)-1);
		$pieceInfo = self::$pieces[$r];
		$piece = new $pieceInfo['name'];
		return $piece;
	}
}

/** Individual pieces */

class i extends Piece {
	protected $sym = array(0,1);
	protected $shapes = array(
		array(
			array('i','i','i','i'),
			'w'=>0,
			'h'=>3,
		),
		array(
			array('i'),
			array('i'),
			array('i'),
			array('i'),
			'w'=>3,
			'h'=>0,
		),
	);
}

class j extends Piece{
	protected $sym = array(0,1,2,3);
	protected $shapes = array(
		array(
			array('j', 0,  0),
			array('j','j','j'),
			'w'=>1,
			'h'=>2,
		),
		array(
			array('j','j'),
			array('j', 0),
			array('j', 0),
			'w'=>2,
			'h'=>1,
		),
		array(
			array('j','j','j'),
			array( 0,  0, 'j'),
			'w'=>1,
			'h'=>2,
		),
		array(
			array( 0, 'j'),
			array( 0, 'j'),
			array('j','j'),
			'w'=>2,
			'h'=>1,
		),
	);
}

class l extends Piece{
	protected $sym = array(0,1,2,3);
	protected $shapes = array(
		array(
			array('l','l','l'),
			array('l', 0,  0),
			'w'=>1,
			'h'=>2,
		),
		array(
			array('l','l'),
			array( 0, 'l'),
			array( 0, 'l'),
			'w'=>2,
			'h'=>1,
		),
		array(
			array( 0,  0, 'l'),
			array('l','l','l'),
			'w'=>1,
			'h'=>2,
		),
		array(
			array('l', 0),
			array('l', 0),
			array('l','l'),
			'w'=>2,
			'h'=>1,
		),
	);
}

class o extends Piece {
	protected $sym = array(0);
	protected $shapes = array(
		array(
			array('o','o'),
			array('o','o'),
			'w'=>1,
			'h'=>1,
		)
	);
}

class t extends Piece{
	protected $sym = array(0,1,2,3);
	protected $shapes = array(
		array(
			array(0,  't'),
			array('t','t'),
			array(0,  't'),
			'w'=>2,
			'h'=>1,
		),
		array(
			array(0,  't', 0),
			array('t','t','t'),
			'w'=>1,
			'h'=>2,
		),
		array(
			array('t', 0),
			array('t','t'),
			array('t', 0),
			'w'=>2,
			'h'=>1,
		),
		array(
			array('t','t','t'),
			array( 0, 't', 0),
			'w'=>1,
			'h'=>2,
		),
	);
}

class s extends Piece{
	protected $sym = array(0,1);
	protected $shapes = array(
		array(
			array('s', 0),
			array('s','s'),
			array( 0, 's'),
			'w'=>2,
			'h'=>1,
		),
		array(
			array( 0, 's','s'),
			array('s','s', 0),
			'w'=>1,
			'h'=>2,
		),
	);
}

class z extends Piece{
	protected $sym = array(0,1);
	protected $shapes = array(
		array(
			array( 0, 'z'),
			array('z','z'),
			array('z', 0),
			'w'=>2,
			'h'=>1,
		),
		array(
			array('z','z', 0),
			array( 0, 'z','z'),
			'w'=>1,
			'h'=>2,
		),
	);
}