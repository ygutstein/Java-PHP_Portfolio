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
				
		if($_SESSION['currentStatus']=='4'){	
			$findMsg = mysqli_query($con,"SELECT * 
				FROM MessageAdmin m, Admin a, User u
				WHERE (SELECT deptID FROM Admin a WHERE a.adminID = '$curUser' AND a.deptID = m.programID)
				AND (SELECT o.threadID FROM OpenMessages o WHERE o.threadID = m.threadID
				AND o.isClosed = 0 AND (o.answeredBy = 0 OR o.answeredBy = '$curUser')
				AND (u.userID = o.userID) AND (m.userID = o.userID)) GROUP BY m.timeSent");
			if(mysqli_num_rows($findMsg) > 0){
				echo "<table id='viewMsg'>";
				echo "<tr>";
				echo "<th>Sender Name</th>";
				echo "<th>Subject</th>";
				echo "<th>Message</th>";
				echo "</tr>";
				while($row = mysqli_fetch_array($findMsg)){
					echo "<tr>";
					echo "<td>" .$row['firstName']." ".$row['lastName']."</td>";
					echo "<td>" .$row['subject']."</td>";
					echo "<td>" .$row['message']."</td>";
					echo "<td><button class='ans_button' 
						value='".$row['threadID'].'*'.$row['subject'].'*'.$row['programID']."'>Respond</button></td>";
					echo "<td><button class='remove_button' 
						value='".$row['threadID'].'*'.$row['timeSent']."'>Delete</button></td>";
					echo "</tr>";		
				}
				echo "</table>";
			}
			else
				echo "<h3>You currently have no messages.</h3>";	
		}				
		else{	
			$findMsg = mysqli_query($con,"SELECT *
			FROM MessageStudent m, User u, OpenMessages o 
			WHERE ((o.userID = '$curUser') AND (u.userID = o.answeredBy)
			AND (o.threadID = m.threadID) AND (m.userID = o.answeredBy)) ORDER BY m.timeSent");
			if(mysqli_num_rows($findMsg) > 0){
				echo "<table id='viewMsg'>";
				echo "<tr>";
				echo "<th>Sender Name</th>";
				echo "<th>Subject</th>";
				echo "<th>Message</th>";
				echo "</tr>";		
				while($row = mysqli_fetch_array($findMsg)){										
						echo "<tr>";
						echo "<td>" .$row['firstName']." ".$row['lastName']."</td>";
						echo "<td>" .$row['subject']."</td>";
						echo "<td>" .$row['message']."</td>";
						echo "<td><button class='ans_button' 
							value='".$row['threadID'].'*'.$row['subject'].'*'.$row['programID']."'>Respond</button></td>";
						echo "<td><button class='remove_button' 
							value='".$row['threadID'].'*'.$row['timeSent']."'>Delete</button></td>";
						echo "</tr>";									
				}
				echo "</table>";
			}
			else
				echo "<h3>You currently have no messages.</h3>";	
		}	
		break;
		
	case 2:
		$thread = $_POST['thread'];
		$subj = $_POST['subj'];
		$msg = $_POST['msg'];
		$pID = $_POST['pID'];
		if($_SESSION['currentStatus']=='4'){
			mysqli_query($con,"UPDATE OpenMessages SET answeredBy ='$curUser' WHERE threadID = '$thread'");
			mysqli_query($con,"INSERT INTO MessageStudent (threadID, userID, programID, subject, message)
				VALUES ((SELECT threadID FROM OpenMessages WHERE threadID = '$thread'),
				'$curUser','$pID','$subj','$msg')");
			$test = mysqli_query($con, "SELECT * FROM MessageStudent WHERE message = '$msg'");
			if(mysqli_num_rows($test) > 0)
				echo "success";
			else
				echo "fail";
		}
		else{		
		mysqli_query($con,"INSERT INTO MessageAdmin (threadID, userID, programID, subject, message)
			VALUES ((SELECT threadID FROM OpenMessages WHERE threadID = '$thread'),
			'$curUser','$pID','$subj','$msg')");
		$test = mysqli_query($con, "SELECT * FROM MessageAdmin WHERE message = '$msg'");
			if(mysqli_num_rows($test) > 0)
				echo "success";
			else
				echo "fail";
		}
		break;
		
	case 3:
		$threadID= $_POST['thread'];
		$time = $_POST['time'];
		if($_SESSION['currentStatus']=='4'){
			mysqli_query($con,"DELETE FROM MessageAdmin WHERE threadID = '$threadID' AND timeSent = '$time'");
			$test = mysqli_query($con, "SELECT * FROM MessageAdmin WHERE timeSent = '$time'");
			if(mysqli_num_rows($test) > 0)
				echo "fail";
			else
				echo "success";
		}
		else{
			mysqli_query($con,"DELETE FROM MessageStudent WHERE threadID = '$threadID' AND timeSent = '$time'");			
			echo "success";
		}
		break;
	
}

mysqli_close($con);	
?>
