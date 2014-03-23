<?php
session_start();
/*
 ***********************************************************************
 * This is the file to store and retrieve data for blackjack.
 ***********************************************************************
 */
if(!isset($_SESSION['bank']))
	$_SESSION['bank'] = '5000';
if(!isset($_SESSION['deck']))
	$_SESSION['deck'] = array();
if(!isset($_SESSION['pHand'])){
	$_SESSION['pHand'] = "";
	$_SESSION['pVal'] = "";
}
if(!isset($_SESSION['dHand'])){
	$_SESSION['dHand'] = "";	
	$_SESSION['dVal'] = "";	
}
if(!isset($_SESSION['bet']))
	$_SESSION['bet'] = '0';
$comp = 'Computer';
$con = mysqli_connect('localhost','ygutstein','ygutstein','db_multitasking');
if(mysqli_connect_errno($con))
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	
$func = $_POST['func'];
$deck = $_SESSION['deck'];
$dHand = $_SESSION['dHand'];
$dVal = $_SESSION['dVal'];
$pHand = $_SESSION['pHand'];
$pVal = $_SESSION['pVal'];
$bet = $_SESSION['bet']; 

/*
 ***********************************************************************
 * Clear all arrays, fetch a new deck and shuffle the new deck
 ***********************************************************************
 */
function begin(){
	global $deck, $dHand, $pHand, $dVal, $pVal, $con;	
	$func = "";
	$deck = array();
	$dHand = "";
	$dVal = "";
	$pHand = "";
	$pVal = "";
	if($_SESSION['bank'] == '0'){
		$_SESSION['bank'] = '5000';
		$_SESSION['bet'] += '100';
	}
	else{
		$_SESSION['bank'] = $_SESSION['bank'] - '100';
		$_SESSION['bet'] += '100';
	}
		
	echo "<div id='yPlayerName'>You</div>|";
	echo "<h2>Bank</h2>";
	echo "<div id='yPlayerBank'>$".$_SESSION['bank']."</div><br>";
	echo "<h2>Bet</h2>";
	echo "<div id='yPlayerBet'>$100</div>";
	echo "|";
	$cards = mysqli_query($con,"SELECT* FROM Cards");
	while($row2 = mysqli_fetch_array($cards)){
		array_push($deck,array($row2['suit'].$row2['number'],$row2['value']));
	}
	shuffle($deck);
}

/*
 *********************************************************************** 
 * Deal a card to the player whose turn it is and display that player's
 * hand.
 ***********************************************************************
 */
function deal($dealTo){
	global $deck, $dHand, $pHand, $dVal, $pVal, $con;
	if($dealTo == 'Computer'){
		$curCard = array_pop($deck);
		$dHand = $dHand."|".$curCard[0];
		$dVal = $dVal."|".$curCard[1];
	}
	else{
		$curCard = array_pop($deck);
		$pHand = $pHand."|".$curCard[0];
		$pVal = $pVal."|".$curCard[1];
	}
	$_SESSION['deck'] = $deck;
	$_SESSION['dHand'] = $dHand;
	$_SESSION['pHand'] = $pHand;
	$_SESSION['dVal'] = $dVal;
	$_SESSION['pVal'] = $pVal;
}

/*
 *********************************************************************** 
 * Print hand
 ***********************************************************************
 */
function printHand($hand, $user, $back){
	$card = strtok($hand,"|");
	while($card != false){
		if($back == "yes" && $user == "Computer")
			echo "<img src='images/backVertRed.png' width='108' height='144'/>";
		else
			echo "<img src='images/".$card.".png' width='108' height='144'/>";
		$card = strtok("|");
		$back = "no";
	}
	echo "|";
}

/*
 *********************************************************************** 
 * Checks for a winner
 ***********************************************************************
 */
function isWinner($hit){
	global $deck, $dHand, $pHand, $dVal, $pVal, $con;
	if($hit == "yes"){
		if(handValue($pHand, $pVal) > 21)
			return "Computer";
		else
			return "None";
	}
	else{
		if(handValue($pHand, $pVal) > 21)
			return "Computer";
		else{
			if(handValue($dHand, $dVal) >= 17){
				if(handValue($dHand, $dVal) > 21)
					return "You";
				if(handValue($pHand, $pVal) == handValue($dHand, $dVal))
					return "Computer";
				else if(handValue($pHand, $pVal) < handValue($dHand, $dVal))
					return "Computer";
				else
					return "You";
			}
			else
				return "Computer";
		}
	}
}

/*
 ***********************************************************************
 * Checks the value of a hand
 ***********************************************************************
 */
function handValue($hand, $val){
	global $deck, $dHand, $pHand, $dVal, $pVal, $con;
	$total = 0;
	$aces = 0;
	$card = strtok($val,"|");
	while($card != false){
		$total += $card;
		if($card == "11")
			$aces++;
		$card = strtok("|");
	}
	if($aces > 0){
		if($total > 21){
			while($aces > 0){
				$total -= 10;
				$aces--;
			}			
		}
	}
	return $total;
}

switch($func){
	case 1://User enters name before start of game
		begin();
		for($i=0; $i<4; $i++){
			if($i%2 == 0)
				deal($comp);
			else
				deal("Player");
		}
		printHand($dHand, $comp, "yes");
		printHand($pHand, "Player", "no");
		break;
		
	case 2://User clicks 'New Game'
		$_SESSION['bet'] = '0';
		begin();
		for($i=0; $i < 4; $i++){
			if($i%2 == 0)
				deal($comp);
			else
				deal("Player");
		}
		printHand($dHand, $comp, "yes");
		printHand($pHand, "Player", "no");
		break;
		
	case 3://User clicks 'Hit'
		deal("Player");
		printHand($pHand, "Player", "no");
		break;
		
	case 4://User clicks 'Stand'
		$hit = $_POST['hit'];	
		$end = false;	
		if(handValue($dHand, $dVal) >= 17)
			$end = true;
		while($end == false){
			deal($comp);
			if(handValue($dHand, $dVal) >= 17)
				$end =true;
		}
		printHand($dHand, $comp, "no");
		break;
				
	case 5://User clicks 'Raise'
		if($_SESSION['bank'] > '0'){
			$_SESSION['bet'] += 100;
			$_SESSION['bank'] = $_SESSION['bank'] - '100';
			echo "$".($_SESSION['bank'])."|$".$_SESSION['bet'];
		}
		else
			echo "$".$_SESSION['bank']."|$".$_SESSION['bet'];
		break;
		
	case 6://Check winner
		$hit = $_POST['hit'];
		$win = isWinner($hit);
		$bet = $_SESSION['bet'];
		if($win == "You")
			$_SESSION['bank'] = $_SESSION['bank'] + $_SESSION['bet']*2;
		echo $win;
		echo "|";
		echo "<h2>Bank</h2>";
		echo "<div id='yPlayerBank'>$".$_SESSION['bank']."</div><br>";
		echo "<h2>Bet</h2>";
		echo "<div id='yPlayerBet'>$0</div>";
		break;
}
?>
