<?php
session_start();
/*
 *********************************************************************** 
 * This is the file to store for the pre and post quizzes, pre and post
 * questionnaires, and store all scores and general data obtained from
 * the expirement.
 *********************************************************************** 
 */
if(!isset($_SESSION['qNums'])){
	$_SESSION['qNums'] = array();
	for($i=0; $i<15; $i++){
		$index = mt_rand(1,43);
		while(in_array($index,$_SESSION['qNums'])){
			$index = mt_rand(1,43);
		}
		array_push($_SESSION['qNums'],$index);	
		
	}	
}
$qNums = $_SESSION['qNums'];

if(!isset($_SESSION['qNumsPost'])){
	$_SESSION['qNumsPost'] = array();
	for($i=0; $i<15; $i++){
		$index = mt_rand(1,43);
		while(in_array($index,$_SESSION['qNumsPost'])){
			$index = mt_rand(1,43);
		}
		array_push($_SESSION['qNumsPost'],$index);	
	}	
}
$qNumsPost = $_SESSION['qNumsPost'];

if(!isset($_SESSION['displayCount']))
	$_SESSION['displayCount'] = '1';
$displayCount = $_SESSION['displayCount'];

if(!isset($_SESSION['displayCountPost']))
	$_SESSION['displayCountPost'] = '1';
$displayCountPost = $_SESSION['displayCountPost'];

$con = mysqli_connect("localhost","ygutstein","ygutstein","db_multitasking");

if(mysqli_connect_errno($con))
	echo "Failed to connect to MySQL: " . mysqli_connect_error();

$func = $_POST['func'];

switch($func){
	case 1://Display preQuiz Questions
		$max = '16';
		for($displayCount; $displayCount < $max; $displayCount++){
			$currentQ = array_pop($qNums);
			$select = mysqli_query($con, "SELECT* FROM Question WHERE qID = '$currentQ'");
			$row = mysqli_fetch_array($select);
			echo "<div class='yQuest'>".$displayCount. ") ".$row['question']."?".
				"<span id='q1Error".$displayCount."' class='red'></span></div>";
			echo "<div class='yAns'>";
			echo "<input type='radio' class='yQuizButton quest".$displayCount."' name='quest".$displayCount."' value='".$row['c1']."'>".$row['c1']."<br>";
			echo "<input type='radio' class='yQuizButton quest".$displayCount."' name='quest".$displayCount."' value='".$row['c2']."'>".$row['c2']."<br>";
			echo "<input type='radio' class='yQuizButton quest".$displayCount."' name='quest".$displayCount."' value='".$row['c3']."'>".$row['c3']."<br>";
			echo "<input type='radio' class='yQuizButton quest".$displayCount."' name='quest".$displayCount."' value='".$row['c4']."'>".$row['c4']."<br>";
			echo "<input class='answer ans".$displayCount."' type='hidden' value='".$row['answer']."'>";
			echo "</div>";
		}	
		$_SESSION['qNums'] = $qNums;
		$_SESSION['displayCount'] = $displayCount;
		echo "<div class='yBottom'>";
		echo "<button class='ySubmitButton' id='continue3'>Finish</button>";
		echo "</div>";
		break;
		
	case 2://Display postQuest questions
		$max = '16';
		for($displayCountPost; $displayCountPost < $max; $displayCountPost++){
			$currentQ = array_pop($qNumsPost);
			$select = mysqli_query($con, "SELECT* FROM Question WHERE qID = '$currentQ'");
			$row = mysqli_fetch_array($select);
			echo "<div class='yQuestPost'>".$displayCountPost. ") ".$row['question']."?".
				"<span id='q2Error".$displayCountPost."' class='red'></span></div>";
			echo "<div class='yAnsPost'>";
			echo "<input type='radio' class='yQuizButtonPost questPost".$displayCountPost."' name='questPost".$displayCountPost."' value='".$row['c1']."'>".$row['c1']."<br>";
			echo "<input type='radio' class='yQuizButtonPost questPost".$displayCountPost."' name='questPost".$displayCountPost."' value='".$row['c2']."'>".$row['c2']."<br>";
			echo "<input type='radio' class='yQuizButtonPost questPost".$displayCountPost."' name='questPost".$displayCountPost."' value='".$row['c3']."'>".$row['c3']."<br>";
			echo "<input type='radio' class='yQuizButtonPost questPost".$displayCountPost."' name='questPost".$displayCountPost."' value='".$row['c4']."'>".$row['c4']."<br>";
			echo "<input class='answerPost ansPost".$displayCountPost."' type='hidden' value='".$row['answer']."'>";
			echo "</div>";
		}	
		$_SESSION['qNumsPost'] = $qNumsPost;
		$_SESSION['displayCountPost'] = $displayCountPost;
		echo "<div class='yBottom'>";
		echo "<button class='ySubmitButton' id='continue5'>Finish</button>";
		echo "</div>";
		break;
		
	case 3://create userID
		$id = rand(1,1000);
			
		function findExists($id) {
			global $con;
			$userExist = false;
			$uID = mysqli_query($con,"SELECT userID FROM Demographics");
			if(mysqli_num_rows($uID) == '0'){
				$_SESSION['id'] = $id;
				return $id;
			}
			else{
				while($row = mysqli_fetch_array($uID)){
					if($row['userID'] == $id){
						$id = rand(1,1000);
						findExists($id);
					}
					else{
						$_SESSION['id'] = $id;
						return $id;
					}
				}
			}
		}		
		findExists($id);
		echo $_SESSION['id'];	
		break;
		
	case 4://enter UserID and Demographics info into Demographics Table
		$id = $_SESSION['id'];
		$pass = $_POST['pass'];
		$popup = $_POST['popup'];
		$sex = $_POST['sex'];
		$school = $_POST['school'];
		$ethnic = $_POST['ethnic'];
		$age = $_POST['age'];
		$juggle = $_POST['juggle'];
		$multi = $_POST['multi'];
		$single = $_POST['single'];
		$several = $_POST['several'];
		$pshMajor = $_POST['pshMajor'];
		$pshCourse = $_POST['pshCourse'];
		$rateHist = $_POST['rateHist'];
		mysqli_query($con,"INSERT INTO Demographics (userID, password, popup,
			gender, schoolStatus, majorPsH, coursesPsH, rateHist, ethnicity, age, jugglePre, multiTaskPre,
			singleTaskPre, severalTaskPre) VALUES ('$id', '$pass', '$popup', '$sex', '$school',
			'$pshMajor', '$pshCourse', '$rateHist', '$ethnic', '$age', '$juggle', '$multi', '$single', '$several')"); 
		$uID = mysqli_query($con,"SELECT userID FROM Demographics WHERE userID = '$id'");	
		if(mysqli_num_rows($uID) > '0') 
			echo "success";
		else
			echo "fail";
		break;
	
	case 5://enter preQuiz score
		$id = $_SESSION['id'];
		$preScore = $_POST['preScore'];
		echo $preScore;
		mysqli_query($con, "INSERT INTO Scores (userID, quiz1Correct) VALUES ('$id', '$preScore')");  
		break;
		
	case 6://enter when user switches tasks
		$id = $_SESSION['id'];
		$curTask = $_POST['curTask'];
		$nextTask = $_POST['nextTask'];
		$taskTime = $_POST['taskTime'];
		mysqli_query($con, "INSERT INTO Switch (userID, switchTo, switchFrom, timeSpent) 
			VALUES ('$id', '$curTask', '$nextTask', '$taskTime')"); 
		echo $curTask.$taskTime; 
		break;
		
	case 7://enter task times
		$id = $_SESSION['id'];
		$slideTime = $_POST['slideTime']; 
		$hmTime = $_POST['hmTime']; 
		$mathTime = $_POST['mathTime']; 
		$bjTime = $_POST['bjTime']; 
		$numSwitches = $_POST['numSwitches']; 
		$slideVisit = $_POST['slideVisit']; 
		$hmVisit = $_POST['hmVisit']; 
		$mathVisit = $_POST['mathVisit'];
		$bjVisit = $_POST['bjVisit'];
		mysqli_query($con, "INSERT INTO TaskTime (userID, slideTime, hmTime,
			mathTime, bjTime, slideTimesClick, hmTimesClick, mathTimesClick,
			bjTimesClick, numSwitches) VALUES ('$id', '$slideTime', '$hmTime',
			'$mathTime', '$bjTime', '$slideVisit', '$hmVisit', '$mathVisit',
			'$bjVisit', '$numSwitches')");   
		break;

	case 8://enter the scores from each task
		$id = $_SESSION['id'];
		$slidesViewed = $_POST['slidesViewed']; 
		$hmWin = $_POST['hmWin']; 
		$hmAttempt = $_POST['hmAttempt']; 
		$mathWin = $_POST['mathWin'];
		$mathAttempt = $_POST['mathAttempt']; 
		$bjWin = $_POST['bjWin'];
		$bjAttempt = $_POST['bjAttempt'];
		mysqli_query($con, "UPDATE Scores SET slidesNumView = '$slidesViewed', 
			hmNumWin = '$hmWin', hmNumAttempt = '$hmAttempt', mathNumWin = '$mathWin',
			mathNumAttempt = '$mathAttempt', bjNumWin = '$bjWin',
			bjNumAttempt = '$bjAttempt' WHERE userID = '$id'");
		break;
		
	case 9://enter postQuiz score
		$id = $_SESSION['id'];
		$postScore = $_POST['postScore'];
		$scoreChange = $_POST['scoreChange'];
		mysqli_query($con, "UPDATE Scores SET quiz2Correct = '$postScore', 
			quizChangeCorrect = '$scoreChange' WHERE userID = '$id'");  
		break;
		
	case 10://enter the post questionnaire data
		$id = $_SESSION['id'];
		$remind = $_POST['remind'];
		$switched = $_POST['switched'];
		$complete = $_POST['complete'];
		$oneTask = $_POST['oneTask'];
		$severalTask2 = $_POST['severalTask2'];
		$didMultiYn = $_POST['didMultiYn'];
		$didMulti = $_POST['didMulti'];
		$dQuiz1 = $_POST['dQuiz1'];
		$dQuiz2 = $_POST['dQuiz2'];
		$effective1 = $_POST['effective1'];
		$effective2 = $_POST['effective2'];
		$performance1 = $_POST['performance1'];
		$performance2 = $_POST['performance2'];
		$happy1 = $_POST['happy1'];
		$happy2 = $_POST['happy2'];
		$efficient1 = $_POST['efficient1'];
		$efficient2 = $_POST['efficient2'];
		$frustrate = $_POST['frustrate'];
		$productive = $_POST['productive'];
		$mostTime = $_POST['mostTime'];
		$other = $_POST['other'];
		if($other == "")
			$other = "n/a";
		$numPopups = $_POST['numPopups'];
		mysqli_query($con, "UPDATE Demographics SET remindedPost = '$remind',
			switchedPost = '$switched', completeSameTimePost = '$complete',
			singleTaskPost = '$oneTask', severalTaskPost = '$severalTask2',
			multiTaskPost = '$didMultiYn', whyMultiTaskPost = '$didMulti',
			dLevelQuiz1 = '$dQuiz1', dLevelQuiz2 = '$dQuiz2',
			effectiveQ1 = '$effective1', effectiveQ2 = '$effective2',
			performanceQ1 = '$performance1', performanceQ2 = '$performance2',
			happyQ1 = '$happy1', happyQ2 = '$happy2', mostTime = '$mostTime',
			efficient1 = '$efficient1', efficient2 = '$efficient2',
			productive = '$productive', frustrate = '$frustrate',
			comments = '$other', numPopups = '$numPopups'
			WHERE userID = '$id'");
		echo $id.":".$remind.":".$switched.":".$complete.":".$oneTask.":".
		$severalTask2.":".$didMultiYn.":".$didMulti.":".$dQuiz1.":".
		$dQuiz2.":".$effective1.":".$effective2.":".$performance1.":".
		$performance2.":".$happy1.":".$happy2.":".$efficient1.":".
		$efficient2.":".$frustrate.":".$productive.":".$mostTime.":".
		$other.":".$numPopups;
		break;
		
	case 11://enter scores and email into NotifyWinner table
		$email = $_POST['email'];
		$pass = $_POST['pass'];
		$hmWin = $_POST['hmWin']; 
		$hmAttempt = $_POST['hmAttempt']; 
		$mathWin = $_POST['mathWin'];
		$mathAttempt = $_POST['mathAttempt']; 
		$bjWin = $_POST['bjWin'];
		$bjAttempt = $_POST['bjAttempt'];
		$preScore = $_POST['preScore'];
		$postScore = $_POST['postScore'];
		$scoreChange = $_POST['scoreChange'];

		mysqli_query($con, "INSERT INTO NotifyWinner
			(email, password, hmNumWin, hmNumAttempt, mathNumWin, mathNumAttempt,
			bjNumWin, bjNumAttempt, quiz1Correct, quiz2Correct,
			quizChangeCorrect) values ('$email', '$pass', '$hmWin', '$hmAttempt',
			'$mathWin', '$mathAttempt', '$bjWin', '$bjAttempt', '$preScore',
			'$postScore', '$scoreChange')");  
		if(mysqli_num_rows(mysqli_query($con,"SELECT* FROM NotifyWinner
			WHERE email = '$email'")))
			echo "success";
		else
			echo $email." failure";
		break;
}	
?>
