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
		$clg = $_POST['clg'];

		$findProg = mysqli_query($con,"SELECT * FROM LeadsProgram 
						WHERE college='$clg'");
		echo "<h2>".$clg."</h2>";
			while($row = mysqli_fetch_array($findProg)){
				if($_SESSION['currentStatus']==4){
					echo "<tr>".
					 "<td><h3>" . $row['programName']."</h3></td>".
					 "<td><button class='remove_button' value=".$row['programID'].">Remove Program</button></td>".
					 "<td><button class='detail_button' value=".$row['programID'].">View detail</button></td>".
					 "</tr>";		
				 }
				 else{
					 echo "<tr>".
					 "<td><h3>" . $row['programName']."</h3></td>".
					 "<td><button class='detail_button' value=".$row['programID'].">View detail</button></td>".
					 "</tr>";		
				 }		
			}		
		break;
		
	case 2:
		$program = $_POST['program'];
		$getProgram = mysqli_query($con,"SELECT * FROM LeadsProgram WHERE programID = '$program'");
		$row = mysqli_fetch_array($getProgram);
		$degrees = "";
		if($row['bachelor'] == 1)
			if($degrees != "")
				$degrees = $degrees.", Bachelor";
			else
				$degrees = "Bachelor";
		if($row['master'] == 1)
			if($degrees != "")
				$degrees = $degrees.", Master";
			else
				$degrees = "Master";
		if($row['phd'] == 1)
			if($degrees != "")
				$degrees = $degrees.", PHD";
			else
				$degrees = "PHD";
		if($_SESSION['currentStatus']==4){
			echo "<h2>Detailed Degree Program Information</h2>";
			echo "<h3>College of ".$row['college']."</h3>";
			echo "<h3>Program Name: ".$row['programName']."</h3>";
			echo "<h3>Degrees Offered: ".$degrees."</h3>";
			echo "<h3>Program Description:</h3>";
			echo "<p>".$row['programDesc']."</p>";
			echo "<button class='remove_button' value=".$row['programID'].">Remove Program</button>";
			echo "<button class='back_button'>Go Back</button>";
		}
		else{
			echo "<h2>Detailed Degree Program Information</h2>";
			echo "<h3>College of ".$row['college']."</h3>";
			echo "<h3>Program Name: ".$row['programName']."</h3>";
			echo "<h3>Degrees Offered: ".$degrees."</h3>";
			echo "<h3>Program Description:</h3>";
			echo "<p>".$row['programDesc']."</p>";
			echo "<button class='ask_button' value='".$row['programID']."'>Ask a Question</button>";
			echo "<button class='back_button'>Go Back</button>";
		}
		
		break;
	case 3:
		$pClg = $_POST['pClg'];
		$pName = $_POST['pName'];
		$bs = $_POST['bs'];
		$ms = $_POST['ms'];
		$phd = $_POST['phd'];
		$pDesc = $_POST['pDesc'];

		mysqli_query($con,"INSERT INTO LeadsProgram (college,programName,programDesc,
					bachelor,master,phd) VALUES('$pClg','$pName','$pDesc','$bs','$ms',
					'$phd')");
		echo "success";
		break;
	
	case 4:
		$pID = $_POST['program'];
		$subj = $_POST['subj'];
		$msg = $_POST['msg'];
		$thread = $curUser.rand(10,1000);
		if($_SESSION['currentStatus']=='1'){
			mysqli_query($con,"INSERT INTO OpenMessages (threadID, userID, isClosed) VALUES ($thread,'$curUser',0)");
			mysqli_query($con,"INSERT INTO MessageAdmin (threadID, userID, programID, subject, message)
				VALUES ((SELECT threadID FROM OpenMessages WHERE threadID = '$thread'),
				'$curUser','$pID','$subj','$msg')");
		}
		else{
			mysqli_query($con,"INSERT INTO MessageStudent (threadID, userID, programID, subject, message)
				VALUES ((SELECT threadID FROM OpenMessages WHERE threadID = '$thread'),
				'$curUser','$pID','$subj','$msg')");
		}		
		echo "success";
		break;
		
	case 5:
		$programID= $_POST['program'];
		mysqli_query($con,"DELETE FROM LeadsProgram WHERE programID = '$programID'");
		$test = mysqli_query($con, "SELECT * FROM LeadsProgram WHERE programID = '$programID'");
		if(mysqli_num_rows($test) > 0)
			echo "fail";
		else
			echo "success";
		break;
}

mysqli_close($con);	
?>
