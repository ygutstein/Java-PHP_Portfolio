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
		echo "<tr>";
		echo "<th>Course ID</th>";
		echo "<th>Course Name</th>";
		echo "<th>Professor</th>";
		echo "<th>Days</th>";
		echo "<th>Time</th>";
		echo "<th>Location</th>";
		echo "</tr>";
		$findUserClass = mysqli_query($con,"SELECT courseID FROM PersonalSchedule 
									WHERE userID = '$curUser' AND registered = 1");
		while($row1 = mysqli_fetch_array($findUserClass)){	
			$cID = $row1['courseID'];
			$findClassDetail = mysqli_query($con,"SELECT c.location, c.instructorName, c.courseID, c.courseDept, c.courseSection, c.courseNumber, c.courseName,
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
				echo "<td>" . $row['instructorName']."</td>";
				echo "<td>" . $days."</td>";
				echo "<td>" . $row['startTime']."-".$row['endTime']."</td>";
				echo "<td>" . $row['location']."</td>";
				echo "<td><button class='drop_button'  value='".$row['courseID']."'>Drop Course</button></td>";
				echo "</tr>";		
			}
		}	
		break;
		
	case 2:
		echo "<tr>";
		echo "<th>Course ID</th>";
		echo "<th>Course Name</th>";
		echo "<th>Professor</th>";
		echo "<th>Days</th>";
		echo "<th>Time</th>";
		echo "<th>Location</th>";
		echo "</tr>";
		$findUserClass = mysqli_query($con,"SELECT courseID FROM PersonalSchedule 
			WHERE userID = '$curUser' AND waitlist = 1");
		while($row1 = mysqli_fetch_array($findUserClass)){	
			$cID = $row1['courseID'];
			$findClassDetail = mysqli_query($con,"SELECT c.location, c.instructorName, c.courseID, c.courseDept, c.courseSection, c.courseNumber, c.courseName,
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
				echo "<td>" . $row['instructorName']."</td>";
				echo "<td>" . $days."</td>";
				echo "<td>" . $row['startTime']."-".$row['endTime']."</td>";
				echo "<td>" . $row['location']."</td>";
				echo "<td><button class='dropWait_button' value='".$row['courseID']."'>Drop Waitlist</button></td>";
				echo "</tr>";		
			}
		}	
		break;
		
	case 3:
		echo "<tr>";
		echo "<th>Course ID</th>";
		echo "<th>Course Name</th>";
		echo "<th>Days</th>";
		echo "<th>Time</th>";
		echo "<th>Location</th>";
		echo "</tr>";
		
		$findClass = mysqli_query($con,"SELECT c.location, c.instructorName, c.courseID, c.courseDept, 
			c.courseSection, c.courseNumber, c.courseName,c.startTime, c.endTime, c.sunday, 
			c.monday, c.tuesday, c.wednesday, c.thursday, c.friday, c.saturday,p.courseID,p.userID, 
			u.userID,u.firstName,u.lastName
			FROM Course c, PersonalSchedule p, User u 
			WHERE c.instructorID = '$curUser' AND c.courseID = p.courseID AND p.userID = u.userID 
			AND p.registered = 1 GROUP BY p.courseID");
		$c = array();
		while($row = mysqli_fetch_array($findClass)){
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
			array_push($c,array($row['courseID'],$row['courseDept'],$row['courseNumber'],
				$row['courseSection'],$row['courseName'],$days,$row['startTime'],$row['endTime'],
				$row['location']));	
		}
		for($i=0;$i<sizeof($c);$i++){
			for($j=0;$j<1;$j++){
				$cID = $c[$i][0];
				echo "<tr>";
				echo "<td>" . $c[$i][1]. $c[$i][2]. " - ". $c[$i][3]."</td>";
				echo "<td>" . $c[$i][4]."</td>";
				echo "<td>" . $c[$i][5]."</td>";
				echo "<td>" . $c[$i][6]."-".$c[$i][7]."</td>";
				echo "<td>" . $c[$i][8]."</td>";
				echo "</tr>";
				echo "<tr>";
				$findStudents = mysqli_query($con,"SELECT userID FROM PersonalSchedule 
					WHERE courseID = '$cID' AND registered = 1");
				while($row1 = mysqli_fetch_array($findStudents)){
					$sID = $row1['userID'];
					$getStName = mysqli_query($con,"SELECT firstName,lastName 
						FROM User WHERE userID = '$sID'");
					$row2 = mysqli_fetch_array($getStName);
					echo "<td class='rosterSpot'>" .$row2['lastName'].", ".$row2['firstName']."</td>";
				}
				echo "</tr>";
			}		
		}
		
		break;
		
	case 4:
		echo "<tr>";
		echo "<th>Course ID</th>";
		echo "<th>Course Name</th>";
		echo "<th>Days</th>";
		echo "<th>Time</th>";
		echo "<th>Location</th>";
		echo "</tr>";
		
		$findClass = mysqli_query($con,"SELECT c.location, c.instructorName, c.courseID, c.courseDept, 
			c.courseSection, c.courseNumber, c.courseName,c.startTime, c.endTime, c.sunday, 
			c.monday, c.tuesday, c.wednesday, c.thursday, c.friday, c.saturday,p.courseID,p.userID, 
			u.userID,u.firstName,u.lastName
			FROM Course c, PersonalSchedule p, User u 
			WHERE c.instructorID = '$curUser' AND c.courseID = p.courseID AND p.userID = u.userID 
			AND registered = 0 AND waitlist = 1 GROUP BY p.courseID");
		$c = array();
		while($row = mysqli_fetch_array($findClass)){
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
			array_push($c,array($row['courseID'],$row['courseDept'],$row['courseNumber'],
				$row['courseSection'],$row['courseName'],$days,$row['startTime'],$row['endTime'],
				$row['location']));	
		}
		for($i=0;$i<sizeof($c);$i++){
			for($j=0;$j<1;$j++){
				$cID = $c[$i][0];
				echo "<tr>";
				echo "<td>" . $c[$i][1]. $c[$i][2]. " - ". $c[$i][3]."</td>";
				echo "<td>" . $c[$i][4]."</td>";
				echo "<td>" . $c[$i][5]."</td>";
				echo "<td>" . $c[$i][6]."-".$c[$i][7]."</td>";
				echo "<td>" . $c[$i][8]."</td>";
				echo "</tr>";
				echo "<tr>";
				$findStudents = mysqli_query($con,"SELECT userID FROM PersonalSchedule 
					WHERE courseID = '$cID' AND registered = 0 AND waitlist = 1");
				while($row1 = mysqli_fetch_array($findStudents)){
					$sID = $row1['userID'];
					$getStName = mysqli_query($con,"SELECT * 
						FROM User WHERE userID = '$sID'");
					$row2 = mysqli_fetch_array($getStName);
					echo "<td class='rosterSpot'>" .$row2['lastName'].", ".$row2['firstName']."</td>";
					echo "<td><button class='add_button' value='".$row2['userID']."-".$cID."'>Approve Waitlist</button></td>";
					echo "<td><button class='remove_button' value='".$row2['userID']."-".$cID."'>Disallow Waitlist</button></td>";
					echo "<td></td>";
				}
				echo "</tr>";
			}		
		}
		
		break;
	
	case 5:
		$course = $_POST['course'];
		$drop = true;
		mysqli_query($con,"DELETE FROM PersonalSchedule 
			WHERE courseID = '$course' AND userID ='$curUser' AND registered = 1");
		$check = mysqli_query($con,"SELECT * FROM PersonalSchedule 
			WHERE courseID = '$course'");
		while($row = mysqli_fetch_array($check)){
			if($row['userID'] == $curUser)
				$drop = false;
		}
		if(!$drop)
			echo "An error has occured.";
		else
			echo "You have successfully dropped this course";
		break;
		
	case 6:
		$course = $_POST['course'];
		$drop = true;
		mysqli_query($con,"DELETE FROM PersonalSchedule 
			WHERE courseID = '$course' AND userID ='$curUser' AND registered = 0 AND waitlist = 1");
		$check = mysqli_query($con,"SELECT * FROM PersonalSchedule 
			WHERE courseID = '$course'");
		while($row = mysqli_fetch_array($check)){
			if($row['userID'] == $curUser)
				$drop = false;
		}
		if(!$drop)
			echo "An error has occured.";
		else
			echo "You have successfully dropped this course";
		break;
	
	case 7:
		$student = $_POST['student'];
		$course = $_POST['course'];
		$approve = true;
		mysqli_query($con,"UPDATE PersonalSchedule SET registered = 1 
			WHERE courseID = '$course' AND userID ='$student'");
		$check = mysqli_query($con,"SELECT * FROM PersonalSchedule 
			WHERE courseID = '$course' AND userID ='$student'");
		while($row = mysqli_fetch_array($check)){
			if($row['registered'] == '0')
				$approve = false;
		}
		if(!$approve)
			echo "An error has occured.";
		else
			echo "This student has been approved for this course";
		break;
		
	case 8:
		$student = $_POST['student'];
		$course = $_POST['course'];
		mysqli_query($con,"DELETE FROM PersonalSchedule WHERE courseID = '$course' AND userID = '$student'");
		$test = mysqli_query($con, "SELECT * FROM PersonalSchedule WHERE courseID = '$course' AND userID = '$student'");
		if(mysqli_num_rows($test) > 0)
			echo "fail";
		else
			echo "success";
		break;
}
mysqli_close($con);	
?>
