<?php
session_start();
/*
 ***********************************************************************
 * This is the file to store and retrieve data for the math puzzle
 ***********************************************************************
 */
if(!isset($_SESSION['nextLine']))
	$_SESSION['nextLine'] = '0';
if($_SESSION['nextLine'] == '49')
	$_SESSION['nextLine'] = '0';	
$nextLine=$_SESSION['nextLine'];

$file=fopen("puzzles.txt","r") or exit("Unable to open file!");

$func = $_POST['func'];

/*
 ***********************************************************************
 * Display the table at the beginning of each new math puzzle.
 * Some spaces will have values given, while 6 spaces will be left blank
 * for the player to solve.
 ***********************************************************************
 */
function printTable($a){
	$index = '1';
	$row = '1';
	$place = '0';
	for($index; $index <= '36'; $index++){
		if($row % '2' == '1'){
			if($index == '1')
				echo "<tr class='aMathRow'><td class='aMathCell'>
					<input class='aSol aNum".$index."' type='text' value='' maxlength=1/></td>";
			else{
				if($index % '6' == '0'){
					echo "<td class='aMathCell'><input class='aAns aNum".$index.
						"' type='text' value='".$a[$place]."' readonly/></td></tr>";
					$row++;
				}
				else{
					if($index % '5' == '0')
						echo "<td class='aMathCell'><input class='aSol aNum".$index.
							"' type='text' value='' maxlength=1/></td>";
					else{
						if($index == '17' || $index == '27')
							echo "<td class='aMathCell'><input class='aSol aNum".$index.
								"' type='text' value='' maxlength=1/></td>";	
						else
							echo "<td class='aMathCell'><input class='aInput aNum".$index.
								"' type='text' value='".$a[$place]."' readonly/></td>";							
						}
				}				
			}
			$place++;
		}	
		else{
			if($index == '36')
				echo "<td class='aMathCell'><input class='aBlank aNum".$index.
					"' type='text' value='_' /></td><tr class='aMathRow'>";
			else{
				if($index % '6' == '0'){
					echo "<td class='aMathCell'><input class='aBlank aNum".$index.
							"' type='text' value='_' /></td></tr>";
					$row++;
				}
				else{
					if($index % '2' == '0')
						echo "<td class='aMathCell'><input class='aBlank aNum".$index.
							"' type='text' value='_' /></td>";
					else{
						if($row =='6')
							echo "<td class='aMathCell'><input class='aAns aNum".$index.
								"' type='text' value='".$a[$place]."' readonly/></td>";
						else
							echo "<td class='aMathCell'><input class='aInput aNum".$index.
								"' type='text' value='".$a[$place]."' readonly/></td>";
						$place++;
					}
				}
			}
		}
	}
}

/*
 ***********************************************************************
 * Display the puzzle with correct answers when the user clicks submit
 ***********************************************************************
 */
function printAnswer($a, $index, $correct){
	if($index == '0')
		echo "<tr class='aMathRow'>";
	if($index == '6' || $index == '9' || $index == '15' || $index == '18' ||
		$index == '24')
		echo "</tr><tr class='aMathRow'>";
	if($index == '36')
		echo "</tr>";
	if(($index>='6' && $index<'9') || ($index>='15' && $index<'18') || 
		($index>='24' && $index<='26')){
		
		if($correct == true){
			if($index == '24' || $index == '25' || $index == '26'){
				echo "<td><input class='aAns ".$index."' type='text' value='".$a."'/></td>";
				echo "<td><input class='aBlank' type='text' value='_' /></td>";
			}
			else{
				echo "<td><input class='aInput ".$index."' type='text' value='".$a."'/></td>";
				echo "<td><input class='aBlank' type='text' value='_' /></td>";
			}
		}
		else{
			if($index == '26'){
				echo "<td><input class='aAns ".$index."' type='text' value='".$a."'/></td>";
				echo "<td><input class='aBlank' type='text' value='_' /></td>";
			}
			else{
				echo "<td><input class='aSol ".$index."' type='text' value='".$a."'/></td>";
				echo "<td><input class='aBlank ".$index."' type='text' value='_' /></td>";
			}
		}
	}
	else{
		if($correct == true){
			if($index == '5' || $index == '14' || $index == '23')
				echo "<td><input class='aAns ".$index."' type='text' value='".$a."'/></td>";
			else
				echo "<td><input class='aInput ".$index."' type='text' value='".$a."'/></td>";
		}
		else
			echo "<td><input class='aSol ".$index."' type='text' value='".$a."'/></td>";
	}
}

switch($func){
	case 1://load puzzle
		if($file){ 	
			$line=file("puzzles.txt");	
			$array =explode(" ", $line[$nextLine]); 
			$_SESSION['array'] = $array;
			printTable($array);
			$_SESSION['nextLine']++;
		}
		break;
		
	case 2://check puzzle for correct answers
		$ans = $_POST['ans'];
		$array = $_SESSION['array'];
		$numCorrect = '0';
		for($i = 0; $i < sizeof($array); $i++){
			if($ans[$i] == $array[$i]){
				$numCorrect++;
				printAnswer($array[$i], $i, true);
			}
			else
				printAnswer($array[$i], $i, false);
		}
		if($numCorrect == '26')
			echo "|WIN!|";
		else{
			echo "|LOSE!";
		}
		break;
}
?>
