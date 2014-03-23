<?php
session_start();
$curUser = $_SESSION['currentID'];
$value = $_POST['func'];

$con=mysqli_connect("localhost","gsef13g3","gsef13g3","gsef13g3");
if(mysqli_connect_errno($con))
	die('Could not connect: '.mysqli_error());

switch($value){
	case 0:
		echo "made it";
		break;
	case 1:
		$dept = $_POST['dept'];
		$sem = $_POST['sem'];
		$yr = $_POST['yr'];
		//echo $dept.$sem.$yr;

		$findCourses = mysqli_query($con,"SELECT * FROM Course 
						WHERE deptName='$dept' AND semester='$sem' AND year='$yr'");

		echo "<h2>".$dept."</h2>";
		while($row = mysqli_fetch_array($findCourses)){
			if($_SESSION['currentStatus']==4){
				echo "<tr>".
				 "<td><h3>" . $row['courseDept']. $row['courseNumber']. "-".$row['courseSection']."</h3>".
				 "<p>" . $row['courseName']."</p></td>".
				 "<td><p>Course Capacity: " . $row['courseCurrent']."/". $row['courseSize']."</p>".
				 "<p>Waitlist Capacity: " . $row['waitCurrent']."/". $row['waitSize']."</p></td>".
				 "<td><button class='remove_button'  value='".$row['courseID']."'>Remove Course</button></td>".
				 "<td><button class='detail_button'  value='".$row['courseID']."'>View detail</button></td>".
				 "</tr>";		
			 }
			 else{
				 echo "<tr>".
				  "<td><h3>" . $row['courseDept']. $row['courseNumber']. "-".$row['courseSection']."</h3>".
				 "<p>" . $row['courseName']."</p>".
				 "<td><p>Course Capacity: " . $row['courseCurrent']."/". $row['courseSize']."</p>".
				 "<p>Waitlist Capacity: " . $row['waitCurrent']."/". $row['waitSize']."</p></td>".
				 "<td><button class='add_button' value='".$row['courseID']."'>Add to Schedule</button></td>".
				 "<td><button class='wait_button' value='".$row['courseID']."'>Add to Waitlist</button></td>".
				 "<td><button class='detail_button'  value=".$row['courseID'].">View detail</button></td>".
				 "</tr>";		
			 }		
		}		
		break;
		
	case 2:
		$course = $_POST['course'];
		
		function checkUser($con, $curUser, $course){
			$checkRegister = mysqli_query($con,"SELECT * FROM PersonalSchedule WHERE courseID = '$course'");
			while($row = mysqli_fetch_array($checkRegister)){			
				//echo $row['registered'].$row['userID'].$row['courseID'];
				if($row['courseID'] == $course && $row['userID'] == $curUser 
					&&($row['waitlist'] == '1' || $row['registered'] == '1'))
					return true;
			}
			return false;
		}		
		//echo (int)checkUser($con, $curUser, $course);
		if(checkUser($con, $curUser, $course))
			echo "You have already registered for this course.";
		else{
			function checkRoom($con, $curUser, $course){
				$checkRoom = mysqli_query($con,"SELECT * FROM Course WHERE courseID = '$course'");
				while($row = mysqli_fetch_array($checkRoom)){
					if($row['courseCurrent'] < $row['courseSize']){
						return true;
					}
				}
				return false;
			}
			if(checkRoom($con, $curUser, $course)){
				mysqli_query($con,"INSERT INTO PersonalSchedule VALUES('$curUser','$course',1,0,0)");
				echo "You have successfully registered for this course.";				
			}
			else
				echo "You were not registered. The course is full.";
		}
		break;
		
	case 3:
		$course = $_POST['course'];
		function checkUser($con, $curUser, $course){
			$checkRegister = mysqli_query($con,"SELECT * FROM PersonalSchedule WHERE courseID = '$course'");
			while($row = mysqli_fetch_array($checkRegister)){
				if($row['courseID'] == $course && ($row['waitlist'] == '1' || $row['registered'] == '1')){
					return true;
				}
			}
			return false;
		}
		
		if(checkUser($con, $curUser, $course))
			echo "You are either registered or already waitlisted for this course.";
		else{
			function checkRoom($con, $curUser, $course){
				$checkRoom = mysqli_query($con,"SELECT * FROM Course WHERE courseID = '$course'");
				while($row = mysqli_fetch_array($checkRoom)){
					if($row['waitCurrent'] < $row['waitSize']){
						return true;
					}
				}
				return false;
			}
			if(checkRoom($con, $curUser, $course)){
				mysqli_query($con,"INSERT INTO PersonalSchedule VALUES('$curUser','$course',0,1,0)");
				echo "You have successfully waitlisted for this course.";				
			}
			else
				echo "You were not waitlisted. The waiting list is full.";
		}
		break;
		
	case 4:
		$course = $_POST['course'];
		$getCourse = mysqli_query($con,"SELECT * FROM Course WHERE courseID = '$course'");
		$row = mysqli_fetch_array($getCourse);
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
		if($_SESSION['currentStatus']==4){
			echo "<h2>Detailed Course Information</h2>";
			echo "<h3>Course Name: ".$row['courseName']."</h3>";
			echo "<h3>Course Number: ".$row['courseDept'].$row['courseNumber']."-".$row['courseSection']."</h3>";
			echo "<h3>Semester: ".$row['semester']." ".$row['year']."</h3>";
			echo "<h3>Professor: ".$row['instructorName']."</h3>";
			echo "<h3>Location: ".$row['location']."</h3>";
			echo "<h3>Days: ".$days."</h3>";
			echo "<h3>Meeting Time: ".$row['startTime']." - ".$row['endTime']."</h3>";
			echo "<h3>Spaces Available: ".$row['courseSize']."</h3>";
			echo "<h3>Spaces Filled: ".$row['courseCurrent']."</h3>";
			echo "<h3>Waiting List Spaces Available: ".$row['waitSize']."</h3>";
			echo "<h3>Waiting List Spaces Filled: ".$row['waitCurrent']."</h3>";
			echo "<button class='remove_button'  value='".$row['courseID']."'>Remove Course</button>";
			echo "<button class='back_button'>Go Back</button>";
		}
		else{
			echo "<h2>Detailed Course Information</h2>";
			echo "<h3>Course Name: ".$row['courseName']."</h3>";
			echo "<h3>Course Number: ".$row['courseDept'].$row['courseNumber']."-".$row['courseSection']."</h3>";
			echo "<h3>Semester: ".$row['semester']." ".$row['year']."</h3>";
			echo "<h3>Professor: ".$row['instructorName']."</h3>";
			echo "<h3>Location: ".$row['location']."</h3>";
			echo "<h3>Days: ".$days."</h3>";
			echo "<h3>Meeting Time: ".$row['startTime']." - ".$row['endTime']."</h3>";
			echo "<h3>Spaces Available: ".$row['courseSize']."</h3>";
			echo "<h3>Spaces Filled: ".$row['courseCurrent']."</h3>";
			echo "<h3>Waiting List Spaces Available: ".$row['waitSize']."</h3>";
			echo "<h3>Waiting List Spaces Filled: ".$row['waitCurrent']."</h3>";
			echo "<button class='add_button' value='".$row['courseID']."'>Add to Schedule</button>";
			echo "<button class='wait_button' value='".$row['courseID']."'>Add to Waitlist</button>";
			echo "<button class='back_button'>Go Back</button>";
		}
		break;
	
	case 5:
		echo "<option value selected>-Select-</option>";
		$getProfs = mysqli_query($con,"SELECT * FROM User WHERE status = 4 ORDER BY lastName");
		while($row = mysqli_fetch_array($getProfs)){
			echo "<option value='".$row['userID']."'>".$row['lastName'].", ".$row['firstName']."</option>";
		}
		break;
	
	case 6:
		$cDept = $_POST['cDept'];
		$cName = $_POST['cName'];
		$cHead = $_POST['cHead'];
		$cSem = $_POST['cSem'];
		$cYr = $_POST['cYr'];
		$cNum = $_POST['cNum'];
		$cSec = $_POST['cSec'];
		$cSize = $_POST['cSize'];
		$wSize = $_POST['wSize'];
		$cLoc = $_POST['cLoc'];
		$cProf = $_POST['cProf'];
		$cStart = $_POST['cStart'];
		$cEnd = $_POST['cEnd'];
		$sun = $_POST['sun'];
		$mon = $_POST['mon'];
		$tue = $_POST['tue'];
		$wed = $_POST['wed'];
		$thu = $_POST['thu'];
		$fri = $_POST['fri'];
		$sat = $_POST['sat'];
		$getProf = mysqli_query($con,"SELECT * FROM User WHERE userID = '$cProf'");
		$row = mysqli_fetch_array($getProf);
		$profName = $row['firstName']." ".$row['lastName'];

		mysqli_query($con,"INSERT INTO COURSE (deptName, courseDept, courseNumber, 
					courseSection, courseName, semester, year, startTime, endTime, 
					location, instructorID, instructorName, courseSize, courseCurrent, waitSize, 
					waitCurrent, sunday, monday, tuesday, wednesday, thursday, friday, saturday)
					VALUES('$cDept','$cHead','$cNum','$cSec','$cName',
					'$cSem','$cYr','$cStart','$cEnd','$cLoc','$cProf','$profName','cSize',0,
					'wSize',0,'$sun','$mon','$tue','$wed','$thu','$fri','$sat')");
		echo "success";
		break;
		
	case 7:
		$courseID= $_POST['course'];
		mysqli_query($con,"DELETE FROM Course WHERE courseID = '$courseID'");
		mysqli_query($con,"DELETE FROM PersonalSchedule WHERE courseID = '$courseID'");
		$test = mysqli_query($con, "SELECT * FROM Course WHERE courseID = '$courseID'");
		if(mysqli_num_rows($test) > 0)
			echo "fail";
		else
			echo "success";
		break;
}
mysqli_close($con);	
?>
