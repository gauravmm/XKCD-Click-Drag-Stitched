<?php
set_time_limit (0);
ini_set('memory_limit', '2G');

error_reporting (E_ALL);
define("IS_MASK", false);
define("SIZE", 2048);
define("DIR", "data/".SIZE."px/");

global $im, $black, $white, $bounds;
//$bounds=array(array("n"=>14, "s"=>25), array("e"=>48, "w"=>33));
//$bounds=array(array("n"=>1, "s"=>0), array("e"=>48, "w"=>33));
$bounds=array(array("n"=>14, "s"=>25), array("e"=>48, "w"=>33));

function getTilePosition($x, $ew, $y, $ns){
	global $bounds;
	if($ew=="w"){
		$x = $bounds[1]["w"] - $x;
	} else {
		$x = $bounds[1]["w"] + $x - 1;
	}
	if($ns=="n"){
		$y = $bounds[0]["n"] - $y;
	} else {
		$y = $bounds[0]["n"] + $y - 1;
	}
	return array($x, $y);
}

$imsize = array(array_sum($bounds[1]) * SIZE, array_sum($bounds[0]) * SIZE);
$im = imagecreate($imsize[0], $imsize[1]);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$debugcolor1 = imagecolorallocate($im, 0x33, 0x99, 0xFF);
$debugcolor2 = imagecolorallocate($im, 0x00, 0x33, 0x99);

foreach($bounds[1] as $ew=>$lat_limit)
	for($x=1; $x<=$lat_limit; ++$x)
		foreach($bounds[0] as $ns=>$lon_limit)
			for($y=1; $y<=$lon_limit; ++$y) {
				$pos = getTilePosition($x, $ew, $y, $ns);
				$name = DIR.$y.$ns.$x.$ew.".png";
				echo "Adding ".$name." at (".$pos[0].",".$pos[1].")...<br />";
				$pos[0] *= SIZE;
				$pos[1] *= SIZE;
				$imload = @imagecreatefrompng($name);
				if($imload === false){
					// Draw the black/white rectangle
					imagefilledrectangle($im, $pos[0], $pos[1], $pos[0] + SIZE, $pos[1] + SIZE, ($ns=="n")?$white:$black);					
				} else {
					// Draw the image
					if(IS_MASK){
						imagefilledrectangle($im, $pos[0], $pos[1], $pos[0] + SIZE, $pos[1] + SIZE, (($x+$y)%2==0)?$debugcolor1:$debugcolor2);
						imagestring($im, 2, $pos[0]+1, $pos[1], $y.$ns, $white);
						imagestring($im, 2, $pos[0]+SIZE/2, $pos[1] + SIZE/2, $x.$ew, $white);
					} else {
						imagecopy($im, $imload, $pos[0], $pos[1], 0, 0, SIZE, SIZE); 
					}
					imagedestroy($imload);
				}
			}

imagepng($im, (IS_MASK?"mask_":"fragment_").SIZE.".png");
/*
function getfile($im, $y, $ns, $x, $ew){
	$pos = getTilePosition($x, $ew, $y, $ns);
	$name = DIR.$y.$ns.$x.$ew.".png";
	echo "Adding ".$name." at (".$pos[0].",".$pos[1].")...<br />";
	$imload = @imagecreatefrompng($name);
	if($imload === false){
		// Draw the black/white rectangle
		
	} else {
		// Draw the image
		
		imagedestroy($imload);
	}
}
*/
?>