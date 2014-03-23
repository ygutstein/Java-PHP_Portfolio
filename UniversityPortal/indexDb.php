<?php
$value = $_POST['func'];
$con=mysqli_connect("localhost","gsef13g3","gsef13g3","gsef13g3");
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
			if($nameFound == true && $passFound == true)
					echo $userList['userID']."-".$userList['status'];
			else if($nameFound == true && $passFound == false)
					echo "Password Not Found";
			else 
				echo "Name Not Found";
			break;
		case 2:
			$fName = $_POST['fName'];
			$lName = $_POST['lName'];
			$userName = $_POST['uName']; 
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
			$degree = $_POST['degree'];
			$major = $_POST['major'];
			$minor = $_POST['minor'];
			$proj = $_POST['proj'];
			$dept = $_POST['dept'];
			$added = false;
			
			function isComplete($status){
				$completed = false;
				global $fName , $lName , $userName ,
				$uPass , $ssn , $q , $a ,
				$city , $state , $zip , $ethnic ,
				$status , $gender , $bYear , $bMonth ,
				$bDay , $major , $minor , $proj ,
				$dept;
				if($status == 1 || $status == 2){
					if($fName == "" || $lName == "" || $userName == ""  ||
						$uPass == "" || $ssn == "" || $q == "" || $a == "" ||
						$city == "" || $state == "" || $zip == "" || $ethnic == "" ||
						$status == "" || $gender == "*" || $bYear == "*" || $bMonth == "*" ||
						$bDay == "*" || $degree == "" || $major == "" || $minor == "" || $proj == "*"){
						echo (int)$completed;
						return false;
					}
					else{
						$completed = true;
						echo (int)$completed;
						return true;
					}
				}
				else if($status == 3){
					if($fName == "" || $lName == "" || $userName == ""  ||
						$uPass == "" || $ssn == "" || $q == "" || $a == "" ||
						$city == "" || $state == "" || $zip == "" || $ethnic == "" ||
						$status == "" || $gender == "*" || $bYear == "*" || $bMonth == "*" ||
						$bDay == "*"){
						echo (int)$completed;
						return false;
				}
					else{
						$completed = true;
						echo (int)$completed;
						return true;
					}
				}
				else if($status == 4){
					if($fName == "" || $lName == "" || $userName == ""  ||
						$uPass == "" || $ssn == "" || $q == "" || $a == "" ||
						$city == "" || $state == "" || $zip == "" || $ethnic == "" ||
						$status == "" || $gender == "*" || $bYear == "*" || $bMonth == "*" ||
						$bDay == "*" || $dept == ""){
						echo (int)$completed;
						return false;
					}
					else{
						$completed = true;
						echo (int)$completed;
						return true;
					}
				}
				else{
					if($fName == "" || $lName == "" || $userName == ""  ||
						$uPass == "" || $ssn == "" || $q == "" || $a == "" ||
						$city == "" || $state == "" || $zip == "" || $ethnic == "" ||
						$status == "" || $gender == "*" || $bYear == "*" || $bMonth == "*" ||
						$bDay == "*" || $dept == ""){
						echo (int)$completed;
						return false;
					}
					else{
						$completed = true;
						echo (int)$completed;
						return true;
					}
				}					
			}
			
			if(isComplete($status) == false)
				echo "na";
			else{
				mysqli_query($con,"INSERT INTO User (firstName, lastName, username, 
						password, ssn, gender, birthDay, birthMonth, birthYear,
						homeTown, homeState, zipcode, ethnicity, status, securityQ,
						securityAns) VALUES('$fName','$lName','$userName',
						'$uPass','$ssn','$gender','$bDay','$bMonth','$bYear',
						'$city','$state','$zip','$ethnic','$status','$q','$a')");
				$getUser = mysqli_query($con,"SELECT * FROM User WHERE username = '$userName'");
				$userList = mysqli_fetch_array($getUser);
				if($userList['username'] == $userName)
					$added = true;
				$userID = $userList['userID'];
				if($status == '1'){
					$sID = $userID;
					mysqli_query($con,"INSERT INTO Student VALUES('$sID','$gradYear','$isGrad'");
					if($major != ""){
						$isMajor = '1';
						mysqli_query($con,"INSERT INTO StudentDegree VALUES('$sID','$major','$degree','$isMajor'");
					}
					else{
						$isMajor = '0';
						mysqli_query($con,"INSERT INTO StudentDegree VALUES('$sID','$minor','$degree','$isMajor'");
					}
				}
				else if($status == '4'){
					$aID = $userID;
					mysqli_query($con,"INSERT INTO Admin VALUES('$aID','$dept')");
				}
				if($added == true)
					echo $userList['userID']." ".$userList['status'];
				else
					echo "no success";
			}
			
			break;
				
		case 3:
			echo "<option value selected>-Select-</option>";
			$getDept = mysqli_query($con,"SELECT * FROM LeadsProgram");
			while($row = mysqli_fetch_array($getDept)){
				echo "<option value='".$row['programName']."'>".$row['programName']."</option>";
			}
			break;
			
	}
	
	mysqli_close($con);	
?>
