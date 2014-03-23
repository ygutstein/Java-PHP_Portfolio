<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>univerCITY Portal 1.0</title>
		<link rel="stylesheet" type="text/css" href="css/univercity.css"/>
		<link rel="stylesheet" type="text/css" href="css/programs.css"/>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.3.custom.css"/>
		<link rel="stylesheet" href="plugins/css/validationEngine.jquery.css" type="text/css"/>
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src="plugins/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
		<script src="plugins/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>	
				
		<script>
			$(function(){
				$("#message").hide();
				$("#programtab").tabs();
				$("#tab1").on('click',function(){
					$("#programDetailAdmin").hide();
				});	
				$("#tab2").on('click',function(){
					$("#programDetailAdmin").hide();
				});	
				var clg;
				$("#clgButtonAdmin").on('click',function(){				
					if($("#lookupClgAdmin").val() == "cas")
						clg = "Arts and Science";
					else if($("#lookupClgAdmin").val() == "cbm")
						clg = "Business and Management";
					else
						clg = "Education";
					$.ajax({
						url: "programDb.php",
						async: true,
						type: "POST",
						data: {clg:clg, func:1}
					}).success(function(data){
						$("#viewClgAdmin").html(data);
					});
				});
				
				$("#clgButtonStud").on('click',function(){
					$("#message").hide();
					var clg;
					if($("#lookupClgStud").val() == "cas")
						clg = "Arts and Science";
					else if($("#lookupClgAdmin").val() == "cbm")
						clg = "Business and Management";
					else
						clg = "Education";
					$.ajax({
						url: "programDb.php",
						async: true,
						type: "POST",
						data: {clg:clg, func:1}
					}).success(function(data){
						$("#viewClgStud").html(data);
					});
				});
				
				$('.programList').on('click', '.detail_button', function(){
					var clicked = $(this);
					$.ajax({
						url: "programDb.php",
						async: true,
						type: "POST",
						data: {program:clicked.val(), func:2}
					}).success(function(data){
					<?php				
					if($_SESSION['currentStatus']=='4'){
					?>
						$(".content").hide();
						$("#programDetailAdmin").show();
						$("#programDetailAdmin").html(data);
					<?php		
					}		
					else{
					?>
						$(".content").hide();
						$("#programDetailStud").show();
						$("#programDetailStud").html(data);
					<?php		
					}		
					?>
					});									
				});
				
				$('.content').on('click', '.back_button', function(){
					var clicked = $(this);
				<?php				
				if($_SESSION['currentStatus']=='4'){
				?>
					$(".content").hide();
					$("#programListAdmin").show();
				<?php		
				}		
				else{
				?>
					$(".content").hide();
					$("#programListStud").show();
				<?php		
				}		
				?>
				});
							
				$('.content').on('click', '.remove_button', function(){
					var clicked = $(this);
					var program = clicked.val();					
					$.ajax({
						url: "programDb.php",
						async: true,
						type: "POST",
						data: {program:program, func:5}
					}).success(function(data){
						if(data == "success"){								
							alert("The program has successfully been removed.");
							$(".content").hide();
							$("html").load("programs.php");
							$("#programListAdmin").show();	
						}
					});		
					return false;					
				});
				
				var qProgram = "";
				
				$('.content').on('click', '.ask_button', function(){
					var clicked = $(this);
					qProgram = clicked.val();
					$("#message").show();	
					
					$("#askQ").on('click', function(){
						var subject = $("#subject").val();
						var question = $("#question").val();
						$.ajax({
							url: "programDb.php",
							async: true,
							type: "POST",
							data: {program:qProgram, subj:subject, msg:question, func:4}
						}).success(function(data){
							if(data == "success"){								
								$("#subject").val("");
								$("#question").val("");
								$("body").load("programs.php");
								$("#message").hide();
							}
						});
						$("#message").hide();
						return false;
					});					
					return false;					
				});
				
				$("#cancelQ").on('click', function(){
					$("#message").hide();
					$("#subject").val("");
					$("#question").val("");
					return false;
				});
				
				$("#progForm").validationEngine();
				
				$("#progForm").submit(function(e) { 
					e.preventDefault();
				});	
				
				$("#pAddButton").on('click',function(){
					var pClg = ""; 
					if($("#pClg").val() == "cas")
						pClg = "Arts and Science";
					else if($("#pClg").val() == "cbm")
						pClg = "Business and Management";
					else
						pClg = "Education";
					var pName = $("#pName").val();
					var bs = 0;
					var ms = 0;
					var phd = 0;
					if($("#bs").is(':checked'))
						bs = 1;
					if($("#ms").is(':checked'))
						ms = 1;
					if($("#phd").is(':checked'))
						phd = 1;
					var pDesc = $("#pDesc").val(); 
					if(pClg != "" && pName != "" && pDesc != ""){
						$.ajax({
							url: "programDb.php",
							async: true,
							type: "POST",
							data: {pClg:pClg, pName:pName, bs:bs, ms:ms, phd:phd, 
									pDesc:pDesc, func:3}
						}).success(function(data){
							//alert(data);
							if(data == "success"){
								alert("The course has been successfully added to the university schedule.");
								window.location.replace("programs.php");
							}
						});
					}
					
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
			<?php				
			if($_SESSION['currentStatus']=='4'){
			?>	
				<div id="programtab" class="clearfix">
                    <ul>
                        <li id="tab1"><a href="#programListAdmin">View program List</a></li>
                        <li id="tab2"><a href="#programAdd">Create programs</a></li>
                    </ul>
                
                    <div class="content clearfix programList" id="programListAdmin">
                        <!--content for list of programs should go here-->
						 <select id='lookupClgAdmin'>
							<option value selected>-College of-</option>						
							<option value = 'cas'>Arts and Science</option>
							<option value = 'cbm'>Business and Mgmt</option>
							<option value = 'ce'>Education</option>					
						</select>
						<input class="submitbutton" id="clgButtonAdmin" type="button" value="Search" >
                        <table id="viewClgAdmin">
                        </table>
                    </div><!--content#programListAdmin-->
                    
                    <div class="content clearfix programDetail" id="programDetailAdmin">
                        <!--content for detailed view of a program should go here-->
                    </div><!--content#programDetail-->
                    
                    <div class="content clearfix" id="programAdd">
                        <!--content for an admin adding a program should go here-->	
                        <form id="progForm">
							 College Name: <select id='pClg' class="validate[required] text-input">
								<option value selected>-College of-</option>						
								<option value = 'cas'>Arts and Science</option>
								<option value = 'cbm'>Business and Mgmt</option>
								<option value = 'ce'>Education</option>					
							</select><br>
							Program Name: <input type="text" id="pName" class="validate[required] text-input"><br>
							Degrees Offered: <input type="checkbox" id="bs" name="degree" value="bs">Bachelor
								<input type="checkbox" id="ms" name="degree" value="ms">Master
								<input type="checkbox" id="phd" name="degree" value="phd">PHD<br>
							Program Description:  <textarea id="pDesc" class="validate[required] text-input"></textarea><br><br>					
							<input class="submitbutton" id="pAddButton" type="submit" value="Add Program" >
                        </form>
                       
                    </div><!--content#programAdd-->
                    
				</div><!-- End of Tab -->
			<?php
			}
			else{
			?>
				<div class="content clearfix programList" id="programListStud">
					<!--content for list of programs should go here-->
					<h2>Degree Program Search</h2>
					 <select id='lookupClgStud'>
						<option value selected>-College of-</option>						
						<option value = 'cas'>Arts and Science</option>
						<option value = 'cbm'>Business and Mgmt</option>
						<option value = 'ce'>Education</option>					
					</select>
					<input class="submitbutton" id="clgButtonStud" type="button" value="Search" >
					<table id="viewClgStud">
					</table>
				</div><!--content#programListStud-->
				
				<div class="content clearfix programDetail" id="programDetailStud">
					<!--content for detailed view of a program should go here-->
				</div><!--content#programDetail-->
				
				<div class="content" id="message">
					Subject:<input id="subject" type="text"><br><br>
					Question:<textarea id="question"></textarea><br><br>
					<input class="submitbutton" id="cancelQ" type="button" value="Cancel" >
					<input class="submitbutton" id="askQ" type="button" value="Ask Question" >
					
				</div>
			<?php
			}
			?>
			</div><!--container-->
		</div><!--wrapper-->
	
	</body>
</html>
