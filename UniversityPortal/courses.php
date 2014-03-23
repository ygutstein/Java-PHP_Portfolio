<?php
session_start();
//echo $_SESSION['currentID'].": courses";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>univerCITY Portal 1.0</title>
		<link rel="stylesheet" type="text/css" href="css/univercity.css"/>
		<link rel="stylesheet" type="text/css" href="css/courses.css"/>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.3.custom.css"/>
		<link rel="stylesheet" href="plugins/css/validationEngine.jquery.css" type="text/css"/>
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src="plugins/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
		<script src="plugins/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>	
		
		<script>
			$(function(){
				$("#courseDetailStud").hide()
				$("#courseDetailAdmin").hide()
				$("#coursetab").tabs();
				$("#tab1").on('click',function(){
					//$("#programListAdmin").show();
					$("#courseDetailAdmin").hide();
				});	
				$("#tab2").on('click',function(){
					//$("#programListAdmin").show();
					$("#courseDetailAdmin").hide();
				});	
				$("#courseButtonAdmin").on('click',function(){
					var parts = $("#lookupSemAdmin").val().split("_");
					var semester = parts[0];
					var year = parts[1];
					//alert(semester);
					//alert(year);
					$.ajax({
						url: "courseDb.php",
						async: true,
						type: "POST",
						data: {dept:$("#lookupDeptAdmin").val(), sem:parts[0], yr:parts[1], func:1}
					}).success(function(data){
						//alert(data);
						$("#viewCoursesAdmin").html(data);
					});
				});
				
				$("#courseButtonStud").on('click',function(){
					var parts = $("#lookupSemStud").val().split("_");
					var semester = parts[0];
					var year = parts[1];
					//alert("student");
					//alert(year);
					$.ajax({
						url: "courseDb.php",
						async: true,
						type: "POST",
						data: {dept:$("#lookupDeptStud").val(), sem:parts[0], yr:parts[1], func:1}
					}).success(function(data){
						//alert(data);
						$("#viewCoursesStud").html(data);
					});
				});
				
				$('table').on('click', '.add_button', function(){
					var clicked = $(this);
					//alert(clicked.val());
					$.ajax({
						url: "courseDb.php",
						async: true,
						type: "POST",
						data: {course:clicked.val(), func:2}
					}).success(function(data){
						alert(data);
						$("html").load("courses.php");
					});
				});
				
				$('table').on('click', '.wait_button', function(){
					var clicked = $(this);
					//alert(clicked.val());
					$.ajax({
						url: "courseDb.php",
						async: true,
						type: "POST",
						data: {course:clicked.val(), func:3}
					}).success(function(data){
						alert(data);
						$("html").load("courses.php");
					});
				});
				
				$('table').on('click', '.detail_button', function(){
					var clicked = $(this);
					//alert(clicked.val());
					$.ajax({
						url: "courseDb.php",
						async: true,
						type: "POST",
						data: {course:clicked.val(), func:4}
					}).success(function(data){
						//alert(data);
					<?php				
					if($_SESSION['currentStatus']=='4'){
					?>
						$(".content").hide();
						$("#courseDetailAdmin").show();
						$("#courseDetailAdmin").html(data);
					<?php		
					}		
					else{
					?>
						$(".content").hide();
						$("#courseDetailStud").show();
						$("#courseDetailStud").html(data);
					<?php		
					}		
					?>
					});									
				});
				
				$('.courseDetail').on('click', '.add_button', function(){
					var clicked = $(this);
					//alert(clicked.val());
					$.ajax({
						url: "courseDb.php",
						async: true,
						type: "POST",
						data: {course:clicked.val(), func:2}
					}).success(function(data){
						alert(data);
					});
				});
				
				$('.courseDetail').on('click', '.wait_button', function(){
					var clicked = $(this);
					//alert(clicked.val());
					$.ajax({
						url: "courseDb.php",
						async: true,
						type: "POST",
						data: {course:clicked.val(), func:3}
					}).success(function(data){
						alert(data);
					});
				});
				
				$('.content').on('click', '.back_button', function(){
					var clicked = $(this);
					//alert(clicked.val());
				<?php				
				if($_SESSION['currentStatus']=='4'){
				?>
					$(".content").hide();
					$("#courseListAdmin").show();
				<?php		
				}		
				else{
				?>
					$(".content").hide();
					$("#courseListStud").show();
				<?php		
				}		
				?>
				});
								
			<?php				
			if($_SESSION['currentStatus']=='4'){
			?>
				$.ajax({
					url: "courseDb.php",
					async: true,
					type: "POST",
					data: {func:5}
				}).success(function(data){
					//alert(data);
					$("#cProf").html(data);
				});
			<?php				
			}
			?>
			
				$("#courseForm").validationEngine();
				
				$("#courseForm").submit(function(e) { 
					e.preventDefault();
				});
				
				$('.content').on('click', '.remove_button', function(){
					var clicked = $(this);
					var course = clicked.val();					
					$.ajax({
						url: "courseDb.php",
						async: true,
						type: "POST",
						data: {course:course, func:7}
					}).success(function(data){
						if(data == "success"){								
							alert("The course has successfully been removed.");
							$(".content").hide();
							$("html").load("courses.php");
							$("#courseListAdmin").show();
						}
					});			
					return false;					
				});	
				
				$("#cAddButton").on('click',function(){
					var cDept = $("#cDept").val();
					var cName = $("#cName").val();
					var cHead = $("#cHead").val();
					var cSemYr = $("#cSem").val();
					var parts = $("#cSem").val().split("_");
					var cSem = parts[0];
					var cYr = parts[1];
					var cNum = $("#cNum").val();
					var cSec = $("#cSec").val();
					var cSize = $("#cSize").val();
					var wSize = $("#wSize").val();
					var cLoc = $("#cLoc").val();
					var cProf = $("#cProf").val();
					var cStart = $("#stTime").val();
					var cEnd = $("#eTime").val();
					var sun = 0;
					var mon = 0;
					var tue = 0;
					var wed = 0;
					var thu = 0;
					var fri = 0;
					var sat = 0;
					if($("#sun").is(':checked'))
						sun = 1;
					if($("#mon").is(':checked'))
						mon = 1;
					if($("#tue").is(':checked'))
						tue = 1;
					if($("#wed").is(':checked'))
						wed = 1;
					if($("#thu").is(':checked'))
						thu = 1;
					if($("#fri").is(':checked'))
						fri = 1;
					if($("#sat").is(':checked'))
						sat = 1;
					var cDesc = $("#cDesc").val(); 
					if(cDept != "" && cName != "" && cName != "" && cSemYr != "" && 
						cNum != "" && cSec != "" && cSize != "" && wSize != "" && 
						cLoc != "" && cProf != "" && cStart != "" && cEnd != "" && 
						cDesc != ""){
						$.ajax({
							url: "courseDb.php",
							async: true,
							type: "POST",
							data: {cDept:cDept, cName:cName, cHead:cHead, cSem:cSem,
									cYr:cYr, cNum:cNum, cSec:cSec, cSize:cSize, wSize:wSize,
									cLoc:cLoc, cProf:cProf, cStart:cStart, cEnd:cEnd,
									sun:sun, mon:mon, tue:tue, wed:wed, thu:thu,
									fri:fri, sat:sat, func:6}
						}).success(function(data){
							alert(data);
							if(data == "success"){
								alert("The course has been successfully added to the university schedule.");
								//window.location.replace("courses.php");
								$("html").load("courses.php");
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
                <div id="coursetab" class="clearfix">
                    <ul>
                        <li id="tab1"><a href="#courseListAdmin">View Course List</a></li>
                        <li id="tab2"><a href="#courseAdd">Create Courses</a></li>
                    </ul>
                
                    <div class="content clearfix courseList" id="courseListAdmin">
                        <!--content for list of courses should go here-->
                        <select id='lookupDeptAdmin'>
							<option value selected>-Department-</option>						
							<option value = 'Biology'>Biology</option>
							<option value = 'Chemistry'>Chemistry</option>
							<option value = 'Computer Science'>Computer Science</option>
							<option value = 'History'>History</option>
							<option value = 'Math'>Math</option>
							<option value = 'Marketing'>Marketing</option>
							<option value = 'Physics'>Physics</option>						
						</select>
						
						<select id='lookupSemAdmin'>
							<option value selected>-Semester-</option>						
							<option value = 'Fall_2013'>Fall 2013</option>
							<option value = 'Spring_2014'>Spring 2014</option>	
							<option value = 'Summer_2014'>Summer 2014</option>			
						</select>
						<input class="submitbutton" id="courseButtonAdmin" type="button" value="Search" >
                        <table id="viewCoursesAdmin">
                        </table>
                    </div><!--content#courseList-->
                    
                    <div class="content clearfix courseDetail" id="courseDetailAdmin">
                        <!--content for detailed view of a course should go here-->
                    </div><!--content#courseDetail-->
                    
                    <div class="content clearfix" id="courseAdd">
                        <!--content for an admin adding a course should go here-->	
						<form id="courseForm">										
							<table>
								<tr>
									<td>
										Select Department: 
										<select id='cDept' class="validate[required] text-input">
											<option value selected>-Department-</option>						
											<option value = 'Biology'>Biology</option>
											<option value = 'Chemistry'>Chemistry</option>
											<option value = 'Computer Science'>Computer Science</option>
											<option value = 'History'>History</option>
											<option value = 'Math'>Math</option>
											<option value = 'Marketing'>Marketing</option>
											<option value = 'Physics'>Physics</option>						
										</select><br>
										
										Select Semester: 
										<select id='cSem' class="validate[required] text-input">
											<option value selected>-Semester-</option>						
											<option value = 'Fall_2013'>Fall 2013</option>
											<option value = 'Spring_2014'>Spring 2014</option>	
											<option value = 'Summer_2014'>Summer 2014</option>			
										</select><br>
										Course Name: <input type="text" id="cName" class="validate[required] text-input"><br>
										Course Heading: <select id='cHead' class="validate[required] text-input">
											<option value selected>-Select-</option>						
											<option value = 'BIO'>BIO</option>
											<option value = 'CHEM'>CHEM</option>
											<option value = 'CS'>CS</option>
											<option value = 'HIST'>HIST</option>
											<option value = 'MATH'>MATH</option>
											<option value = 'MKTG'>MKTG</option>
											<option value = 'PHYS'>PHYS</option>						
										</select><br>
										Course Number: <input type="text" id="cNum" class="validate[required,custom[integer]] text-input"><br>									
										Course Section:					
										<select id='cSec' class="validate[required] text-input">
											<option value selected>-Select-</option>						
											<option value = '1'>001</option>
											<option value = '2'>002</option>	
											<option value = '3'>003</option>	
											<option value = '4'>004</option>			
										</select><br>
										Course Size: <input type="number" id="cSize" class="validate[required,custom[integer]] text-input" min="1" max="50"><br>
										Waiting List Size: <input type="number" id="wSize" class="validate[required,custom[integer]] text-input" min="0" max="5"><br>
										
									</td>
									<td>
										Professor Name: <select id='cProf' class="validate[required] text-input">
										</select>
										Location: <input type="text" id="cLoc" class="validate[required] text-input"><br>
										Starting  Time: <input type="time" id="stTime" class="validate[required] text-input"><br>								
										End Time: <input type="time" id="eTime" class="validate[required] text-input"><br>									
										Days: <input type="checkbox" id="sun" name="days" value="sunday">SUN 
										<input type="checkbox" id="mon" name="days" value="monday">MON 
										<input type="checkbox" id="tue" name="days" value="tuesday">TUE
										<input type="checkbox" id="wed" name="days" value="wednesday">WED 
										<input type="checkbox" id="thu" name="days" value="thursday">THU 
										<input type="checkbox" id="fri" name="days" value="friday">FRI 
										<input type="checkbox" id="sat" name="days" value="saturday">SAT							
								
										Course Description: <textarea id="cDesc" class="validate[required] text-input"></textarea><br><br>								
										<input class="submitbutton" id="cAddButton" type="submit" value="Add Course" >
									</td>
								</tr>
								<tr>
									
								</tr>
							</table>
                        </form>
						
                        <table id="viewCoursesAdmin">
                        </table>

                    </div><!--content#courseAdd-->
				</div><!-- End of Tab -->
			<?php
			}
			else{
			?>
				<div class="content clearfix courseList" id="courseListStud">
					<!--content for list of courses should go here-->
					<h2>Course Search</h2>
					<select id='lookupDeptStud'>
							<option value selected>-Department-</option>						
							<option value = 'Biology'>Biology</option>
							<option value = 'Chemistry'>Chemistry</option>
							<option value = 'Computer Science'>Computer Science</option>
							<option value = 'History'>History</option>
							<option value = 'Math'>Math</option>
							<option value = 'Marketing'>Marketing</option>
							<option value = 'Physics'>Physics</option>						
						</select>
					
					<select id='lookupSemStud'>
						<option value selected>-Semester-</option>						
						<option value = 'Fall_2013'>Fall 2013</option>
						<option value = 'Spring_2014'>Spring 2014</option>	
						<option value = 'Summer_2014'>Summer 2014</option>			
					</select>
					<input class="submitbutton" id="courseButtonStud" type="button" value="Search" >
					<table id="viewCoursesStud">
					</table>
				</div><!--content#courseList-->
				
				<div class="content clearfix courseDetail" id="courseDetailStud">
					<!--content for detailed view of a course should go here-->
				</div><!--content#courseDetail-->
			<?php
			}
			?>
			</div><!--container-->
		</div><!--wrapper-->
	
	</body>
</html>
