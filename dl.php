<?php

define("DIR", "dl/");

$suffix=array(array("n"=>14, "s"=>25), array("e"=>48, "w"=>33));
// n, e, s, w
// 14,48,25,33

foreach($suffix[0] as $lat=>$lat_limit)
	for($i=1; $i<=$lat_limit; ++$i)
		foreach($suffix[1] as $lon=>$lon_limit)
			for($j=1; $j<=$lon_limit; ++$j)
				getfile($i.$lat.$j.$lon);

function getfile($in){
	//echo "http://imgs.xkcd.com/clickdrag/".$in.".png<br />";
	file_put_contents(DIR.$in.".png", file_get_contents("http://imgs.xkcd.com/clickdrag/".$in.".png"));
}
?>