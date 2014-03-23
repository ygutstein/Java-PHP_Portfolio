<?php
session_start();
//echo $_SESSION['currentID'].": events";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>univerCITY Portal 1.0</title>
		<link rel="stylesheet" type="text/css" href="css/univercity.css"/>
		<link rel="stylesheet" type="text/css" href="css/schedule.css"/>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.3.custom.css"/>
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
			
		<script>
			$(function(){
			}); //end of document.ready
		</script>
	</head>
	<body>
		<div id="wrapper">	
			<div class="container">
				<header>
					<div id="logoHolder">
						<img id="logo" src="images/logo.png"> 
						<a href="docs/univerCITY_readMe.docx" target="_blank">Readme</a>
						<a href="docs/univerCITY_presentation.pptx" target="_blank">Powerpoint</a>
						<a href="docs/CS401_SRD_final.docx" target="_blank">SRD</a>
						<a href="docs/CS401_ADD_final.docx" target="_blank">ADD</a>
					</div><!--logoHolder-->		
				</header>
				<nav id="mainNav">
				<?php				
				if($_SESSION['currentStatus']=='1'){
				?>	
					<ul class='nav1' id='navStudent'>
						<li id='active1'><a href='home.php'>HOME</a></li>
						<li><a href='programs.php'>PROGRAMS</a></li>
						<li><a href='courses.php'>COURSE SEARCH</a></li>
						<li><a href='schedule.php'>MY SCHEDULE</a></li>
						<li><a href='messages.php'>MESSAGES</a></li>
					</ul>
				<?php
				}
				else if($_SESSION['currentStatus']=='2'){
				?>
					 <ul class='nav1' id='navAlum'>
						<li id='active1'><a href='home.php'>HOME</a></li>
						<li><a href='programs.php'>PROGRAMS</a></li>
						<li><a href='messages.php'>MESSAGES</a></li>
						<li><a href='newsletters.php'>NEWS LETTERS</a></li>
						<li><a href='projects.php'>PROJECTS</a></li>
						<li><a href='events.php'>ALUMNI EVENTS</a></li>
					</ul>
				<?php
				}				
				else if($_SESSION['currentStatus']==3){
				?>
					<ul class='nav1' id='navProspi'>
						<li id='active1'><a href='home.php'>HOME</a></li>
						<li><a href='programs.php'>PROGRAMS</a></li>
						<li><a href='messages.php'>MESSAGES</a></li>
					</ul>
			<?php
			}
			else{
			?>
				 <ul class='nav1' id='navAdmin'>
					<li id='active1'><a href='home.php'>HOME</a></li>
					<li><a href='programs.php'>PROGRAMS</a></li>
					<li><a href='courses.php'>COURSE SEARCH</a></li>
					<li><a href='schedule.php'>MY SCHEDULE</a></li>
					<li><a href='messages.php'>MESSAGES</a></li>
					<li><a href='newsletters.php'>NEWS LETTERS</a></li>
					<li><a href='projects.php'>PROJECTS</a></li>
					<li><a href='events.php'>ALUMNI EVENTS</a></li>
				</ul>
			<?php
			}
			?>
				</nav><!--mainNav-->

				<div class="content">
					<h1>Sorry! This page is under construction. Please try again later.</h1>
				</div><!--content-->

			</div><!--container-->
		</div><!--wrapper-->
	
	</body>
</html>
