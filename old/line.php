<?php
$i_colorsRequired = 0;

function createRandomColor($o_object) 
{
	global $i_colorsRequired;
	$i_minColor = 20;
	$i_maxColor = 255;
	
	$i_ceilTo = round((($i_maxColor - $i_minColor) ^3) / $i_colorsRequired);

	$i_red = ceiling(mt_rand($i_minColor,$i_maxColor),$i_ceilTo); 		//r(ed)
	$i_green = ceiling(mt_rand($i_minColor,$i_maxColor), $i_ceilTo); 	//g(reen)
	$i_blue = ceiling(mt_rand($i_minColor,$i_maxColor), $i_ceilTo);	 	//b(lue)

	// Check if we haven't already used all colors
	if(imagecolorstotal($o_object) >= 255) 
	{
		$o_color = imagecolorclosest($o_object, $i_red, $i_green, $i_blue);
	}
	else
	{	
		while(randomColorCheck($o_object, $i_red, $i_green, $i_blue)) 
		{
			$i_red = ceiling(mt_rand($i_minColor,$i_maxColor),$i_ceilTo); 		//r(ed)
			$i_green = ceiling(mt_rand($i_minColor,$i_maxColor), $i_ceilTo); 	//g(reen)
			$i_blue = ceiling(mt_rand($i_minColor,$i_maxColor), $i_ceilTo);	 	//b(lue)	
		}	
		
        $o_color = imagecolorallocate($o_object, $i_red, $i_green, $i_blue);		
	}
	
	return $o_color;
}

function ceiling($number, $significance = 1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
}

function randomColorCheck($o_object, $i_red, $i_green, $i_blue)
{
	if(imagecolorexact($o_object, $i_red, $i_green, $i_blue) != -1)
	{
		return true;
	}	
	
	if($i_red == 255 && $i_green == 255 && $i_blue == 255)
	{
		return true;
	}		
		
	return false;
}

@session_start();
$s_uniqId = $_GET['uniqId'];

if(isset($_SESSION['graphs'][$s_uniqId]))
{
	$a_dataArray = $_SESSION['graphs'][$s_uniqId];
	//Unset($_SESSION['graphs'][$s_uniqId]);
}
else
{
	exit();
}

$i_max = 0;
$i_cells = 0;

$i_gridSize = 50;
$i_x = 1;
$i_y = 10;

$i_minY = 0;
$i_maxY = 0;

$i_minX = 0;
$i_maxX = 0;

$i_colorsRequired = count($a_dataArray);

foreach($a_dataArray as $a_data)
{
	foreach($a_data as $k => $v)
	{
		if(is_numeric($k) && is_numeric($v))
		{
			if($k > $i_maxX)
			{
				$i_maxX = $k;
			}
			if($k < $i_minX)
			{
				$i_minX = $k;
			}
			
			if($v > $i_maxY)
			{
				$i_maxY = $v;
			}
			if($v < $i_minY)
			{
				$i_minY = $v;
			}
		}
	}
}

$i_cells = ceil(($i_maxX - $i_minX) / $i_x);
$i_rows = ceil(($i_maxY - $i_minY) / $i_y);

$i_imgWidth = $i_cells * $i_gridSize;
$i_imgHeight = $i_rows * $i_gridSize;

$i_pxPerPoint = $i_imgHeight / ($i_maxY - $i_minY);

$a_drawData = array();
foreach($a_dataArray as $k => $a_data)
{
	$a_lineData = array();
	for($i = $i_minX; $i <= $i_maxX; $i++)
	{
		$i_value = isset($a_data[$i]) ? $a_data[$i] : false;
				
		if(!$i_value)
		{
			if($i == $i_minX)
			{
				$i_next = $a_data[($i+1)];
				$i_nextnext = $a_data[($i+2)];
				$i_value = $i_next - ($i_nextnext - $i_next);
				$a_data[$i] = $i_value;
			}
			else
			{
				$i_prev = $a_data[($i-1)];
				$i_next = $a_data[($i+1)];
				$i_value = ($i_prev + $i_next) / 2;
				$a_data[$i] = $i_value;
			}
		}
		
		$a_lineData[$i] = ($i_value * $i_pxPerPoint);
	}
	
	$a_drawData[$k] = $a_lineData;
}

// Create the graph image
$o_graph = imagecreate(($i_imgWidth + 200), ($i_imgHeight + 20)); // Extra 200 width for legenda, Extra 20 height for Labels

// Create colors we need for drawing the graph
$c_white = imagecolorallocate($o_graph, 255, 255, 255);		// FIRST MUST BE WHITE! BACKGROUND COLOR!!
$c_grey = imagecolorallocate($o_graph, 192, 192, 192);
$c_blue = imagecolorallocate($o_graph, 0, 0, 255);
$c_black = imagecolorallocate($o_graph, 0, 0, 0);

// Draw grid
for ($i=0; $i < $i_cells; $i++)
{
	// Vertical Line
	imageline($o_graph, $i*$i_gridSize, 0, $i*$i_gridSize, $i_imgHeight, $c_grey);
}
for ($i=0; $i < $i_rows; $i++)
{
	// Horizontal Line
	imageline($o_graph, 0, $i*$i_gridSize, $i_imgWidth, $i*$i_gridSize, $c_grey);
}

$a_colorArray = array();

// Draw lines
foreach($a_drawData as $k => $a_lineData)
{
	$a_colorArray[$k] = createRandomColor($o_graph);

	// Loop every cell
	$c = 0;
	for ($i=$i_minX; $i < $i_maxX; $i++)
	{
		if($a_lineData[$i] !== false)
		{
			// Draw the line
			$i_startingX = $c*$i_gridSize;
			$i_startingY = ($i_imgHeight - $a_lineData[$i]);
			
			$i_finalX = ($c+1)*$i_gridSize;
			$i_finalY = ($i_imgHeight - $a_lineData[$i+1]);
			
			$i_yPerX = ($i_finalY - $i_startingY) / ($i_finalX - $i_startingX);
			while($i_startingY > $i_imgHeight)
			{	
				$i_startingY += $i_yPerX;
				$i_startingX ++;
			}			
			
			imageline($o_graph, $i_startingX, $i_startingY, $i_finalX, $i_finalY, $a_colorArray[$k]);
			// Draw the text
			if (isset($a_dataArray[$k][$i]))
				imagestring($o_graph, 4, $i_startingX - 20,($i_startingY - 25), $a_dataArray[$k][$i], $c_black);
		}		
		$c++;
	}
}

// Outer rectangle of the graph
imagerectangle($o_graph,0,0,$i_imgWidth-1,$i_imgHeight-1,$c_blue);  

// Legenda time
$i_lastLine = 0;
foreach($a_drawData as $k => $a_lineData)
{
	// Draw the line	(20 px from end of graph)
	imageline($o_graph, $i_imgWidth + 20, 40 + ($k * 20), $i_imgWidth + 60, 40 + ($k * 20), $a_colorArray[$k]);
	// Draw the text
	imagestring($o_graph, 4, $i_imgWidth + 65, 30 + ($k * 20), $a_dataArray[$k]['title'], $a_colorArray[$k]);
	$i_lastLine ++;
}
// Labels

$c = 0;
for ($i=$i_minX; $i <= $i_maxX; $i++)
{
	$s_label = $i; 
	$i_strOffset = strlen($s_label) * 5;
	imagestring($o_graph, 4, $c*$i_gridSize-$i_strOffset, $i_imgHeight, $s_label, $c_black);
	$c++;
}
// Outer rectangle of the Legenda
imagerectangle($o_graph,$i_imgWidth + 15, 40 -15 ,$i_imgWidth + 199,30 + ($i_lastLine * 20) + 5,$c_blue);


// Output image
header("Content-type: image/png");
imagepng($o_graph);

?> 