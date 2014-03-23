<?php
session_start();
if(!isset($_SESSION['currentID'])){
	$_SESSION['currentID'] = $_POST['uID'];
	$_SESSION['currentStatus'] = $_POST['uStatus'];
	
}
//echo $_SESSION['currentStatus'];
$_SESSION['currentSemester'] = 'Fall';
$curUser = $_SESSION['currentID'];
$curSemester = $_SESSION['currentSemester'];
$curStatus  = $_SESSION['currentStatus'];
//echo $curUser.": home";
//echo $_SESSION['currentStatus'].": home";

$con=mysqli_connect("localhost","gsef13g3","gsef13g3","gsef13g3");
if(mysqli_connect_errno($con))
	die('Could not connect: '.mysqli_error());
?>

<!DOCTYPE html>
<html>
	<head>
		<title>univerCITY Portal 1.0</title>
		<link rel="stylesheet" type="text/css" href="css/univercity.css"/>
		<link rel="stylesheet" type="text/css" href="css/home.css"/>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script type="text/javascript" src="plugins/jquery.redirect.min.js"> </script>
		<script>
			$(function(){
				var request1 = $.ajax({
					url: "homeDb.php",
					async: true,
					type: "POST",
					data: {func:3}
				}).success(function(data){
					//alert(data);
					$("#right").html(data);
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
				
				<div class="content" id="home">			
					<div id="left">
						<div id="top">
							<h2>My Information</h2>
							<?php
							$findUser = mysqli_query($con,"SELECT userID FROM User WHERE userID = '$curUser'");
							$userRow = mysqli_fetch_array($findUser);
							$user = $userRow['userID'];
							$findUserDetail = mysqli_query($con,"SELECT firstName, lastName, username, status FROM User WHERE userID = '$user'");
							while($row1 = mysqli_fetch_array($findUserDetail)){	
								echo "<p>Name: " . $row1['firstName']. " ". $row1['lastName']. "<br>";
								echo "Username: " . $row1['username']. "</p>";
								$uStatus = $row1['status'];
								if($uStatus == 1){
									$findUserDegree = mysqli_query($con, "SELECT degreeField, degreeLevel FROM
										StudentDegree WHERE studentID = '$user'");
									while($row2 = mysqli_fetch_array($findUserDegree)){																
										echo "<p>Degree Area: " . $row2['degreeField']. "<br>";
										echo "Degree Level: " . $row2['degreeLevel']. "<br>";	
									}
									$findUserGrad = mysqli_query($con, "SELECT projectedGrad FROM
										Student WHERE studentID = '$user'");
									while($row3 = mysqli_fetch_array($findUserGrad)){		
										echo "Projected Graduation: " . $row3['projectedGrad']. "</p>";			
									}
								}						
							}
							?>
						</div>
					<?php				
					if($_SESSION['currentStatus']=='1'){
					?>	
						<div id="bottom">
							<h2>Grades</h2>
							<h4>There are no grades currently available.</h4>
						</div>
					<?php
					}				
					else if($_SESSION['currentStatus']=='2' || $_SESSION['currentStatus']=='4'){
					?>	
						<div id="bottom1">
							<h2>Newsletters</h2>
							<h4>There are no newsletters currently available.</h4>
						</div>
						<div id="bottom2">
							<h2>Career Events</h2>
							<h4>There are no career events currently available.</h4>
						</div>
					<?php
					}				
					else{
					}
					?>
					</div>
					<div id="center">
						<h2>My Schedule</h2>
						<table>
							<tr>
								<th>Course ID</th>
								<th>Course Name</th>
								<th>Days</th>
								<th>Time</th>
							</tr>
							<?php		
							if($_SESSION['currentStatus']=='1'){
								$findUser = mysqli_query($con,"SELECT userID FROM User WHERE userID = '$curUser'");
								$userRow = mysqli_fetch_array($findUser);
								$user = $userRow['userID'];
								$findUserClass = mysqli_query($con,"SELECT courseID FROM PersonalSchedule 
									WHERE userID = '$user' AND registered = 1");
								
								while($row1 = mysqli_fetch_array($findUserClass)){	
									$cID = $row1['courseID'];
									$findClassDetail = mysqli_query($con,"SELECT c.courseDept, c.courseSection, c.courseNumber, c.courseName,
										 c.startTime, c.endTime, c.sunday, c.monday, c.tuesday, c.wednesday, c.thursday, c.friday, c.saturday
										 FROM Course c WHERE c.courseID = '$cID'");
									while($row = mysqli_fetch_array($findClassDetail)){		
										$days = "";
										if($row['sunday'] == 1)
											if($days != "")
												$days = $days.",Su";
											else
												$days = $days."Su";	
										if($row['monday'] == 1)
											if($days != "")
												$days = $days.",M";
											else
												$days = $days."M";
										if($row['tuesday'] == 1)
											if($days != "")
												$days = $days.",T";
											else
												$days = $days."T";
										if($row['wednesday'] == 1)
											if($days != "")
												$days = $days.",W";
											else
												$days = $days."W";
										if($row['thursday'] == 1)
											if($days != "")
												$days = $days.",Th";
											else
												$days = $days."Th";
										if($row['friday'] == 1)
											if($days != "")
												$days = $days.",F";
											else
												$days = $days."F";
										if($row['saturday'] == 1)
											if($days != "")
												$days = $days.",Sa";
											else
											$days = $days."Sa";
										echo "<tr>";
										echo "<td>" . $row['courseDept']. $row['courseNumber']. " - ". $row['courseSection']."</td>";
										echo "<td>" . $row['courseName']."</td>";
										echo "<td>" . $days."</td>";
										echo "<td>" . $row['startTime']."-".$row['endTime']."</td>";
										echo "</tr>";				
									}
								}
							}
							else{
								$findClassDetail = mysqli_query($con,"SELECT c.courseDept, c.courseSection, c.courseNumber, c.courseName,
									 c.startTime, c.endTime, c.sunday, c.monday, c.tuesday, c.wednesday, c.thursday, c.friday, c.saturday
									 FROM Course c WHERE c.instructorID = '$curUser'");
								while($row = mysqli_fetch_array($findClassDetail)){		
									$days = "";
									if($row['sunday'] == 1)
										if($days != "")
											$days = $days.",Su";
										else
											$days = $days."Su";	
									if($row['monday'] == 1)
										if($days != "")
											$days = $days.",M";
										else
											$days = $days."M";
									if($row['tuesday'] == 1)
										if($days != "")
											$days = $days.",T";
										else
											$days = $days."T";
									if($row['wednesday'] == 1)
										if($days != "")
											$days = $days.",W";
										else
											$days = $days."W";
									if($row['thursday'] == 1)
										if($days != "")
											$days = $days.",Th";
										else
											$days = $days."Th";
									if($row['friday'] == 1)
										if($days != "")
											$days = $days.",F";
										else
											$days = $days."F";
									if($row['saturday'] == 1)
										if($days != "")
											$days = $days.",Sa";
										else
										$days = $days."Sa";
									echo "<tr>";
									echo "<td>" . $row['courseDept']. $row['courseNumber']. " - ". $row['courseSection']."</td>";
									echo "<td>" . $row['courseName']."</td>";
									echo "<td>" . $days."</td>";
									echo "<td>" . $row['startTime']."-".$row['endTime']."</td>";
									echo "</tr>";				
								}
							}
							?>
						</table>
					</div>
					<div id="right">
					</div>
				</div><!--content#home-->
			</div><!--container-->
		</div><!--wrapper-->
	
	</body>
</html>
