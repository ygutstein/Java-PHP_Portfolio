<?php
session_start();
/*
 ***********************************************************************
 * This is the file to store and retrieve data for the study slides
 ***********************************************************************
 */
if(!isset($_SESSION['slide']))
	$_SESSION['slide'] = '1';
$slide = $_SESSION['slide'];
$func = $_POST['func'];

/*
 *********************************************************************** 
 * Display the slides
 *********************************************************************** 
 */
function display(){
	global $slide;
	echo "<div class='yTopSs'></div>";
	echo "<div id='slideTime'></div>";
	echo "<div class='yLeftSs'>";
	echo "<input class='yBack' type='image' src='images/BackArrow.png' alt='submit'/>";
	echo "</div>";
	echo "<div class='yCenterSs'>";
	echo "<img class='ySlides' src='images/Task1_slides/Slide".$slide.".PNG'/>";
	echo "</div>";
	echo "<div class='yRightSs'>";
	echo "<input class='yNext' type='image' src='images/NextArrow.png' alt='submit'/>";
	echo "</div>";
}
		
switch($func){
	case 1://load the 1st slide
		display();
		$_SESSION['slide'] = $slide;
		break;
		
	case 2://user clicks the back button		
		if($slide == '1')
			$slide = '55';
		else
			$slide--;
		display();
		$_SESSION['slide'] = $slide;
		break;
		
	case 3://user clicks the next button
		if($slide == '55')
			$slide = '1';
		else
			$slide++;
		display();
		$_SESSION['slide'] = $slide;	
		break;
}
?>
