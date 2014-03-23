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
		<link rel="stylesheet" type="text/css" href="css/messages.css"/>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.3.custom.css"/>
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
			
		<script>
			$(function(){	
				$("#message").hide();
				$.ajax({
					url: "messageDb.php",
					async: true,
					type: "POST",
					data: {func:1}
				}).success(function(data){					
					$("#messageView").html(data);
				});
					 
				$('.content').on('click', '.ans_button', function(){ 					 
					var parts = $(this).val().split("*");
					var thread = parts[0];
					var subject = parts[1];
					var program = parts[2]; 
					$("#subject").val(subject);
					$("#message").show();
					
					$("#askQ").on('click', function(){		
						$(this).attr("disabled", "disabled");	                      		
						var question = $("#question").val();
						//alert("s:" + subject + ",q:" + question + ",p:" + program);
						$.ajax({
							url: "messageDb.php",
							async: true,
							type: "POST",
							data: {thread:thread, subj:subject, msg:question, pID:program, func:2}
						}).success(function(data){
							//alert(data);
							if(data == "success") {
								$("#message").hide();
								$("#question").reset();
								$(this).removeAttr("disabled", "disabled");
							}
						});				
						return false;
					});
					return false;
				});
					
				$("#cancelQ").on('click', function(){
					$("#message").hide();
					$("#question").reset();
					return false;
				});
				
				$('.content').on('click', '.remove_button', function(){
					$(this).attr("disabled", "disabled");				
					var parts = $(this).val().split("*");
					var thread = parts[0];
					var time = parts[1];
					$.ajax({
						url: "messageDb.php",
						async: true,
						type: "POST",
						data: {thread:thread, time:time, func:3}
					}).success(function(data){
						if(data == "success"){
							//alert(data);	
							$("#message").hide();							
							$("#message").load("messages.php");
							$("#messageView").show();
							 $(this).removeAttr("disabled", "disabled"); 	
						}
						
					});
					return false;
				});
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
				<div class="content" id="messageView">

				</div><!--content#messageView-->
				<div class="content" id="message">
					Subject:<input id="subject" type="text"><br><br>
					Question:<textarea id="question"></textarea><br><br>
					<button id="cancelQ" type="button" value="Cancel" >Cancel</button>
					<button id="askQ" type="button" value="Respond" >Respond</button>					
				</div><!--content#message-->
			</div><!--container-->
		</div><!--wrapper-->
	
	</body>
</html>
