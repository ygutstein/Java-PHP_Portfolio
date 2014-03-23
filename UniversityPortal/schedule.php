<?php
session_start();
$curUser = $_SESSION['currentID'];
$con=mysqli_connect("localhost","gsef13g3","gsef13g3","gsef13g3");
if(mysqli_connect_errno($con))
	die('Could not connect: '.mysqli_error());
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
				$("#scheduleStud").tabs();
				$("#scheduleAdmin").tabs();
			<?php				
			if($_SESSION['currentStatus']=='1'){
			?>		
				$.ajax({
					url: "scheduleDb.php",
					async: true,
					type: "POST",
					data: {func:1}
				}).success(function(data){
					$("#viewRegisterStud").html(data);
				});
				
				$.ajax({
					url: "scheduleDb.php",
					async: true,
					type: "POST",
					data: {func:2}
				}).success(function(data){
					$("#viewWaitlistStud").html(data);
				});
				
				$('table').on('click', '.drop_button', function(){
					var clicked = $(this);
					$.ajax({
						url: "scheduleDb.php",
						async: true,
						type: "POST",
						data: {course:clicked.val(), func:5}
					}).success(function(data){
						alert(data);
						$("html").load("schedule.php");
					});
				});
				
				$('table').on('click', '.dropWait_button', function(){
					var clicked = $(this);
					$.ajax({
						url: "scheduleDb.php",
						async: true,
						type: "POST",
						data: {course:clicked.val(), func:6}
					}).success(function(data){
						alert(data);
						$("html").load("schedule.php");
					});
				});
			<?php	
			}			
			else if($_SESSION['currentStatus']=='4'){
			?>			
				$.ajax({
					url: "scheduleDb.php",
					async: true,
					type: "POST",
					data: {func:3}
				}).success(function(data){
					$("#viewRegisterAdmin").html(data);
				});
				
				$.ajax({
					url: "scheduleDb.php",
					async: true,
					type: "POST",
					data: {func:4}
				}).success(function(data){
					$("#viewWaitlistAdmin").html(data);
				});
				
				$('.content').on('click', '.remove_button', function(){
					var clicked = $(this);
					var parts = clicked.val().split("-");
					var student = parts[0];
					var course = parts[1];				
					$.ajax({
						url: "scheduleDb.php",
						async: true,
						type: "POST",
						data: {student:student, course:course, func:8}
					}).success(function(data){
						if(data == "success"){								
							alert("The student has been removed from the waiting list.");
							$("html").load("schedule.php");
						}
					});			
					return false;					
				});
				
				$('table').on('click', '.add_button', function(){
					var clicked = $(this);
					var parts = clicked.val().split("-");
					var student = parts[0];
					var course = parts[1];
					$.ajax({
						url: "scheduleDb.php",
						async: true,
						type: "POST",
						data: {course:course, student:student, func:7}
					}).success(function(data){
						alert(data);
						$("html").load("schedule.php");
					});
				});
			<?php	
			}			
			?>
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
			<?php	
			if($_SESSION['currentStatus']=='4'){
			?>
				<div class="content"id="scheduleAdmin">
					<ul>
						<li><a href="#registerAdmin">Registered Courses</a></li>
						<li><a href="#waitlistAdmin">Waitlisted Courses</a></li>
					</ul>
					<div class="schedule" id="registerAdmin">
						<table id="viewRegisterAdmin">
						</table>
					</div><!--classInfo-->
					<div class="schedule"  id="waitlistAdmin">
						<table id="viewWaitlistAdmin">
						</table>
					</div><!--waitlisted-->
				</div><!--content#schedule-->
			<?php	
			}				
			else if($_SESSION['currentStatus']=='1'){
			?>
				<div class="content"id="scheduleStud">
					<ul>
						<li><a href="#registerStud">Registered Courses</a></li>
						<li><a href="#waitlistStud">Waitlisted Courses</a></li>
					</ul>
					<div class="schedule" id="registerStud">
						<table id="viewRegisterStud">
						</table>
					</div><!--classInfo-->
					<div class="schedule"  id="waitlistStud">
						<table id="viewWaitlistStud">
						</table>
					</div><!--waitlisted-->
				</div><!--content#schedule-->
			<?php	
			}			
			?>
			</div><!--container-->
		</div><!--wrapper-->
	
	</body>
</html>
