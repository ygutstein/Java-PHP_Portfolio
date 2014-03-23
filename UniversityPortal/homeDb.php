<?php
session_start();
$curUser = $_SESSION['currentID'];
$value = $_POST['func'];

$con=mysqli_connect("localhost","gsef13g3","gsef13g3","gsef13g3");
if($con)
	//echo ("connected");
if(mysqli_connect_errno($con))
	die('Could not connect: '.mysqli_error());

switch($value){	
	case 0:
		echo "made it";
		break;
	case 1:
		$username = $_POST['uName'];
		$userPass = $_POST['uPass'];
		$nameFound = false;
		$passFound = false;

		$testUser = mysqli_query($con,"SELECT * FROM User WHERE username = '$username'");
		$userList = mysqli_fetch_array($testUser);
			if($userList['username'] == $username)
				$nameFound = true;
				if($userList['password'] == $userPass)
					$passFound = true;
		//echo "pass:".(int)$passFound."user:".(int)$nameFound;
		if($nameFound == true && $passFound == true)
				echo $userList['userID'];
		else if($nameFound == true && $passFound == false)
				echo "Password Not Found";
		else
			echo "Name Not Found";
		break;
	case 2:
		$fName = $_POST['fName'];
		$lName = $_POST['lName'];
		$uName = $_POST['uName']; 
		$uPass = $_POST['uPass'];
		$ssn = $_POST['ssn'];
		$q  = $_POST['q'];
		$a = $_POST['a'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];
		$ethnic = $_POST['ethnic'];
		$status = $_POST['status'];
		$gender = $_POST['gender'];
		$bYear = $_POST['bYear'];
		$bMonth = $_POST['bMonth'];
		$bDay = $_POST['bDay'];
		$major = $_POST['major'];
		$minor = $_POST['minor'];
		$proj = $_POST['proj'];
		$dept = $_POST['dept'];
		$added = false;
		//mysql_query("INSERT INTO User (firstName) VALUES('$fName')");
		
		mysqli_query($con,"INSERT INTO User (firstName, lastName, username, 
					password, ssn, gender, birthDay, birthMonth, birthYear,
					homeTown, homeState, zipcode, ethnicity, status, securityQ,
					securityAns) VALUES('$fName','$lName','$uName',
					'$uPass','$ssn','$gender','$bDay','$bMonth','$bYear',
					'$city','$state','$zip','$ethnic','$status','$q','$a')");
		$getUser = mysql_query("SELECT * FROM User WHERE username = '$uName'");
		$userList = mysql_fetch_array($getUser);
		if($userList['username'] == $uName)
			$added = true;
		$userID = $userList['userID'];
		if($status == '1'){
			$sID = $userID;
			mysqli_query($con,"INSERT INTO Student VALUES('$sID','$gradYear','$isGrad'");
			if($major != ""){
				$isMajor = '1';
				mysqli_query($con,"INSERT INTO StudentDegree VALUES('$sID','$major','$isMajor'");
			}
			else{
				$isMajor = '0';
				mysqli_query($con,"INSERT INTO StudentDegree VALUES('$sID','$minor','$isMajor'");
			}
		}
		else if($status == '4'){
			$aID = $userID;
			mysqli_query($con,"INSERT INTO Admin VALUES('$aID','$dept')");
		}
		echo (int)$added." ".$userID;
		break;
	
	case 3:
		echo "<h2>Message Board</h2>";		
		if($_SESSION['currentStatus']=='4'){	
			$findMsg = mysqli_query($con,"SELECT * 
				FROM MessageAdmin m, Admin a, User u
				WHERE (SELECT deptID FROM Admin a WHERE a.adminID = '$curUser' AND a.deptID = m.programID)
				AND (SELECT o.threadID FROM OpenMessages o WHERE o.threadID = m.threadID
				AND o.isClosed = 0 AND (o.answeredBy = 0 OR o.answeredBy = '$curUser')
				AND (u.userID = o.userID) AND (m.userID = o.userID))
				GROUP BY m.timeSent");
			$result = mysqli_num_rows($findMsg);
			if($result == "0"){
				echo "<h4>You currently have no messages.</h4>";
			}
			else{
				echo "<table id='viewMsg'>";
				echo "<tr>";
				echo "<th>Sender Name</th>";
				echo "<th>Subject</th>";
				echo "</tr>";
				while($row = mysqli_fetch_array($findMsg)){
					echo "<tr>";
					echo "<td class='msgCol'>" .$row['firstName']." ".$row['lastName']."</td>";
					echo "<td class='msgCol'>" .$row['subject']."</td>";
					echo "</tr>";		
				}
				echo "</table>";
			}	
		}				
		else{	
			$findMsg = mysqli_query($con,"SELECT *
			FROM MessageStudent m, User u, OpenMessages o 
			WHERE ((o.userID = '$curUser') AND (u.userID = o.answeredBy)
			AND (o.threadID = m.threadID) AND (m.userID = o.answeredBy))");
			$result = mysqli_num_rows($findMsg);
			if($result == "0"){
				echo "<h4>You currently have no messages.</h4>";
			}
			else{
				echo "<table id='viewMsg'>";
				echo "<tr>";
				echo "<th>Sender Name</th>";
				echo "<th>Subject</th>";
				echo "</tr>";		
				while($row = mysqli_fetch_array($findMsg)){										
						echo "<tr>";
						echo "<td class='msgCol'>" .$row['firstName']." ".$row['lastName']."</td>";
						echo "<td class='msgCol'>" .$row['subject']."</td>";
						echo "</tr>";									
				}
				echo "</table>";
			}
		}	
		break;
		
}

mysqli_close($con);	
?>
