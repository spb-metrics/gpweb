<?php
class Vector2{
  public $x;
  public $y;
  
  public function __construct($x = 0.0, $y = 0.0){
	  $this->x = $x;
	  $this->y = $y;
  	  }
  	  
  public function zero(){
	  $this->x = 0.0;
	  $this->y = 0.0;
  	  }
  	  
  public function add($v){
	  $this->x += $v->x;
	  $this->y += $v->y;
  	  }
  	  
  public function sub($v){
	  $this->x -= $v->x;
	  $this->y -= $v->y;
  	  }
  
  public static function addPoint($v1, $v2){
	  return new Vector2( $v1->x + $v2->x, $v1->y + $v2->y );
  	  }
  	  
  public static function subPoint($v1, $v2){
	  return new Vector2( $v1->x - $v2->x, $v1->y - $v2->y );
  	  }
  	  
  public function length(){
	  return sqrt(($this->x * $this->x) + ($this->y * $this->y));
  	  }
  
  public function length2(){
	  return abs(($this->x * $this->x) + ($this->y * $this->y));
  	  }
  	  
  public function mulScalar($value){
	  $this->x *= $value;
	  $this->y *= $value;
  	  }
  	  
  public function divScalar($value){
	  $this->x /= $value;
	  $this->y /= $value;
  	  }
  
  public function normalize(){
	  $len = $this->length();
	  if(!$len) $this->zero();
	  else{
	  	  	$len = 1.0/$len;
	  		$this->x *= $len;
	  		$this->y *= $len;		  
	  	  }
  	  }
  	  	  
  public function normal(){
	  $len = $this->length();
	  if(!$len) return new Vector2();
	  $len = 1.0/$len;
	  return new Vector2($this->x*$len, $this->y*$len);
  	  }
  	  
  public function dotProduct($v){
  	  return ($this->x * $v->x) + ($this->y * $v->y);
	  }
	  
  public static function dotProduct2($v1, $v2){
  	  return ($v1->x * $v2->x) + ($v1->y * $v2->y);
	  }
  	  
  public function switchDirection(){
	  $this->x = -($this->x);
	  $this->y = -($this->y); 
  	  }
  	  
  public function distance($v){
	  $x = $this->x - $v->x;
	  $y = $this->y - $v->y;
	  return sqrt( ($x*$x) + ($y*$y) );
  	  }
  	  
  public function distance2($v){
	  $x = $this->x - $v->x;
	  $y = $this->y - $v->y;
	  return ($x*$x) + ($y*$y);
  	  }
  	  
  public static function pointDistance($v1, $v2){
	  $x = $v1->x - $v2->x;
	  $y = $v1->y - $v2->y;
	  return sqrt( ($x*$x) + ($y*$y) );
  	  }
  	  
  public static function pointDistance2($v1, $v2){
	  $x = $v1->x - $v2->x;
	  $y = $v1->y - $v2->y;
	  return ($x*$x) + ($y*$y);
  	  }	
};

class Polygon2{
  public $vertex;
  
  public function __construct($vertex = null){
	  if(!$vertex) $this->vertex = array();
	  else $this->vertex = $vertex;
  	  }
  	  
  public function addPoint($x, $y){
	  	$this->vertex[] = new Vector2($x,$y);
  	  }	
};

class Circle2{
	public $radius;
	public $center;
	
	public function __construct($center = null, $radius = 0){
		$this->center = $center ? $center : new Vector2();
		$this->radius = $radius; 
		}
		
	public function fromPolygon($poly){
		if($poly->vertex){
			$max_x = -(INF);
			$max_y = -(INF);
			$min_x = INF;
			$min_y = INF;
			
			foreach($poly->vertex as $v){
				if($v->x > $max_x) $max_x = $v->x;
				if($v->x < $min_x) $min_x = $v->x;
				
				if($v->y > $max_y) $max_y = $v->y;
				if($v->y < $min_y) $min_y = $v->y;
				}
			
			$max_x = abs(($max_x - $min_x)/2.0);
			$max_y = abs(($max_y - $min_y)/2.0); 
			$this->center->x = $min_x + $max_x;
			$this->center->y = $min_y + $max_y;
			$this->radius = $max_x > $max_y ? $max_x : $max_y;
			}
		else{
			$this->center->zero();
			$this->radius = 0.0;
			} 
		}
		
	public function testCircle($circ){
		$v = Vector2::subPoint($this->center, $circ->center);
		$dist = $v->length2();
		$r1 = abs($this->radius);
		$r1 *= $r1;
		$r2 = abs($circ->radius);
		$r2 *= $r2;
		return ($dist <= ($r2 + $r1)) ? true : false;
		}
		
	public function testPoint($point){
		$dist = Vector2::subPoint($this->center, $point)->length2();
		$r2 = $this->radius;
		$r2 *= $r2;
		return ($dist <= abs($r2)) ? true : false;
		}	
};

?>
