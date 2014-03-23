<?php
session_start();
session_destroy();
session_start();
if(isset($_GET['logout'])){            									
	session_destroy();
	header("Location: index.php"); //Redirect the user
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
		<link rel="stylesheet" type="text/css" href="css/task1.css"/>
		<link rel="stylesheet" type="text/css" href="css/task2.css"/>
		<link rel="stylesheet" type="text/css" href="css/task3.css"/>
		<link rel="stylesheet" type="text/css" href="css/task4.css"/>
		<link rel="stylesheet" type="text/css" href="css/task5.css"/>
		<link rel="stylesheet" type="text/css" href="css/preAndPost.css"/>
		<link rel="stylesheet" href="plugins/css/validationEngine.jquery.css" type="text/css"/>
		<link rel="stylesheet" href="css/eggplant/jquery-ui-1.10.3.custom.css">
		
		<script src="js/jquery-1.9.1.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.js"></script>
		<script src="plugins/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
		<script src="plugins/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" src="js/puzzleScript1.js"></script>
		<script type="text/javascript" src="js/puzzleScript2.js"></script>	
		
		<script>
			$(function(){
				$("#introInstruct").hide();
				$("#loginDialog").show();
				$("#introDemo").hide();
				$("#introQuiz").hide();
				$("#introInstruct2").hide();
				$("#tasks").hide();
				$("#post").hide();
				$("#scores").hide();
				$("#emailDialog").hide();
/*
 ***********************************************************************
 Javascript for Intro Screens
 ***********************************************************************
 */		
				var popup;
				
				$("#loginDialog").dialog({
					modal: true,
					buttons: {
						Login: function() {
							if($("#userPassword").val().toLowerCase() == "multi" || 
								$("#userPassword").val().toLowerCase() == "testyes" ||
								$("#userPassword").val().toLowerCase() == "testno" || 
								$("#userPassword").val().toLowerCase() == "testrandom" ||
								$("#userPassword").val().toLowerCase() == "pilot" ||
								$("#userPassword").val().toLowerCase() == "bypass" ||
								$("#userPassword").val().toLowerCase() == "bypass2"){
								if($("#userPassword").val().toLowerCase() == "multi" ||
									$("#userPassword").val().toLowerCase() == "pilot" ||
									$("#userPassword").val().toLowerCase() == "testrandom"){
									popup = Math.floor((Math.random()*2)+1);
									if(popup == 2)
										popup = 0;
								}
								if($("#userPassword").val().toLowerCase() == "testno" ||
									$("#userPassword").val().toLowerCase() == "bypass" ||
									$("#userPassword").val().toLowerCase() == "bypass2")
									popup = 0;
								if($("#userPassword").val().toLowerCase() == "testyes")
									popup = 1;

								$( this ).dialog( "close" ); 
								$("#loginDialog").hide();
								$("#introInstruct").show();
								$("#introDemo").hide();
								$("#introQuiz").hide();
								$("#introInstruct2").hide();
								$("#tasks").hide();
								$("#post").hide();
								
								$.ajax({
									url: "main.php",
									async: true,
									type: "POST",
									data: {func:3}
								}).success(function(data){
								}); 
								
								if($("#userPassword").val().toLowerCase() == "bypass"){
									$("#intro").hide();
									$("#tasks").show();
									$("#post").hide();
									reset();//loads the 1st game of hangman
									newGame();//loads first number game
								}
								else if($("#userPassword").val().toLowerCase() == "bypass2"){
									$("#intro").hide();
									$("#tasks").hide();
									$("#post").show();
									$("#postQuiz").hide();
									$("#postQuest").show();
								}
								else{
									$("#intro").show();
									$("#tasks").hide();
									$("#post").hide();
								}
							}
							else
								alert("Incorrect Password. Please enter again.");
						}							
					}
				});
				
				/*
				 *******************************************************
				 Determine if user has read/checked off all instructions
				 *******************************************************
				 */									
				function readInstructions(checkNum, numInstructs){
					var totalChecks = 0;
					for(var i = 1; i <= numInstructs; i++)
						if($(".instCheck" + checkNum + i).is(":checked"))
							totalChecks++;
					if(totalChecks == numInstructs)
						return true;
					else
						return false;
				}
				
				/*
				 *******************************************************
				 Continue button on 1st instruction page
				 *******************************************************
				 */				
				$("#continue1").click(function(){
					if(readInstructions('A', 3)){					
						$("#introInstruct").hide();
						$("#introDemo").show();
						$("#introQuiz").hide();
						$("#introInstruct2").hide();
						$("#tasks").hide();
						$("#post").hide();	
					}		
					else
						alert("Please confirm that you have read all instructions");
				});
				
				/*
				 *******************************************************
				 Remove error span next to each demographic question
				 *******************************************************
				 */	
				function removeError(divId, errorId){
					$(divId).change(function(){
						$(errorId).html("");
					});
				}	
				
				$("#demoForm").submit(function(e) { 
					e.preventDefault();
				});	
				
				var q1Time = 300;
				var q1Active;
				
				/*
				 *******************************************************
				 Continue button for demographics questionnaire
				 *******************************************************
				 */			
				$("#continue2").click(function(){
					if($("#position").val() != "" && $("#ethnicity").val() != "" &&
						($("#age").val() != "" && $("#age").val() >= 18 && 
						$("#age").val() <= 95) && $("#gender").val() != "" &&
						$("#compUsePre").val() != "" && $(".juggle:checked").val() &&
						$(".multiTask:checked").val() && 
						$(".singleTask:checked").val() &&
						$(".severalTask:checked").val() &&
						$(".pshMajor:checked").val() &&
						$(".pshCourse:checked").val() &&
						$(".rateHist:checked").val()){
						$("#introInstruct").hide();
						$("#introDemo").hide();
						$("#introQuiz").show();
						$("#introInstruct2").hide();
						$("#tasks").hide();
						$("#post").hide();
						$.ajax({
							url: "main.php",
							async: true,
							type: "POST",
							data:{pass:$("#userPassword").val(), popup:popup, 
								sex:$("#gender").val() ,school:$("#position").val(), 
								ethnic:$("#ethnicity").val(), age:$("#age").val(), 
								juggle:$(".juggle:checked").val(), 
								multi:$(".multiTask:checked").val(), 
								single:$(".singleTask:checked").val(), 
								several:$(".severalTask:checked").val(),
								pshMajor:$(".pshMajor:checked").val(),
								pshCourse:$(".pshCourse:checked").val(),
								rateHist:$(".rateHist:checked").val(), func:4}
						}).success(function(data){
							q1Active = setInterval(function(){
								q1Time--;
								if(q1Time == 0){
									for(var i = 1; i <= 20; i++){
										if($(".quest"+i + ":checked").val() == $(".ans"+i).val())							
											preScore++;
									}
									$.ajax({
										url: "main.php",
										async: true,
										type: "POST",
										data: {preScore:preScore, func:5}
									}).success(function(data){
									});											
									$("#introInstruct").hide();
									$("#introDemo").hide();
									$("#introQuiz").hide();
									$("#introInstruct2").show();
									$("#tasks").hide();	
									$("#post").hide();
								}
							}, 1000);
						});
					}
					else{
						alert("Please enter a value for all questions" );
						for(var i = 1; i <= 11; i++){
							removeError(".preDemo"+i,"#preError"+i);
							if(i == 3){
								if($(".preDemo"+i).val() < 18 || $(".preDemo"+i).val() > 95 || 
									$(".preDemo"+i).val() == "")
									$("#preError"+i).html(" Please enter a valid value");
							}
							else if(i >= 5 && 5 <= 11){
								if(!$(".preDemo"+i+":checked").val())
									$("#preError"+i).html(" Please enter a value");
							}
							else{
								if($(".preDemo"+i).val() == ""){
									$("#preError"+i).html(" Please enter a value");
								}
							}
						}
					}
				});
								
				var preScore = 0;
				var questNum = 1;
				$.ajax({
					url: "main.php",
					async: true,
					type: "POST",
					data: {qNum:questNum, func:1}
				}).success(function(data){
					$(".yMiddle").html(data);				
				});
				
				/*
				 *******************************************************
				 Continue button for preTask-quix w/ check for all
				 questions answered
				 *******************************************************
				 */									
				$(".content").on('click','#continue3',function(){
					var allPreAns = 0;
					var missing = "";
					for(var i = 1; i <= 15; i++){
						removeError(".quest"+i,"#q1Error"+i);
						if($(".quest"+i + ":checked").val()){
							allPreAns++;
						}
						else{
							$("#q1Error"+i).html(" Please select an answer");
							missing += " Question " + i + ",";
						}
					}
					if(allPreAns == 15){
						clearInterval(q1Active);
						for(var i = 1; i <= 15; i++){
							if($(".quest"+i + ":checked").val() == $(".ans"+i).val())							
								preScore++;
						}
						$.ajax({
							url: "main.php",
							async: true,
							type: "POST",
							data: {preScore:preScore, func:5}
						}).success(function(data){
						});											
						$("#introInstruct").hide();
						$("#introDemo").hide();
						$("#introQuiz").hide();
						$("#introInstruct2").show();
						$("#tasks").hide();	
						$("#post").hide();
					}
					else{
						alert("Please answer all 20 questions. You forgot: " + missing);
					}		
				});
				
				var q2Time = 300;
				var q2Active;
				
				/*
				 *******************************************************
				 Continue button for 2nd instruction page
				 *******************************************************
				 */	
				$("#continue4").click(function(){
					if(readInstructions('B', 8)){					
						$("#intro").hide();
						$("#tasks").show();
						$("#post").hide();
						reset();//loads the 1st game of hangman
						newGame();//loads first number game
						getTime(overallTime);//start timer for experiment
						getTime(slideTime);//start timer for slides
						slideActive = setInterval(function(){
							slideTime++;
						}, 1000);
						getTime(currentTaskTime);
						currentActive = setInterval(function(){
							currentTaskTime++;
						}, 1000);
						overallActive = setInterval(function(){
							overallTime--;
							if(overallTime == 0){
								clearInterval(overallActive);
								clearInterval(timeAwayActive);
								switchTask();
								$.ajax({
									url: "main.php",
									async: true,
									type: "POST",
									data: {slideTime:getTime(slideTime), hmTime:getTime(hmTime),
										mathTime:getTime(mathTime),
										bjTime:getTime(bjTime), slideVisit:slideVisit,
										hmVisit:hmVisit, mathVisit:mathVisit,
										bjVisit:bjVisit, numSwitches:numSwitches, func:7}
								}).success(function(data){
								});
								$.ajax({
									url: "main.php",
									async: true,
									type: "POST",
									data: {slidesViewed:slidesViewed, hmAttempt:hmAttempt,
										hmWin:hmWin, hmWin:hmWin,
										mathAttempt:mathAttempt, mathWin:mathWin, 
										bjAttempt:bjAttempt, bjWin:bjWin, func:8}
								}).success(function(data){								
								});		
								q2Active = setInterval(function(){
									q2Time--;
									if(q2Time == 0){
										for(var i = 1; i <= 15; i++){
											if($(".questPost"+i + ":checked").val() == $(".ansPost"+i).val())
												postScore++;
										}
										$.ajax({
											url: "main.php",
											async: true,
											type: "POST",
											data: {postScore:postScore, scoreChange:postScore-preScore, func:9}
										}).success(function(data){
										});											
										$("#intro").hide();
										$("#tasks").hide();	
										$("#postQuiz").hide();
										$("#postQuest").show();
										$("#thanks").hide();
									}
								}, 1000);
					
								$("#introInstruct").hide();
								$("#introDemo").hide();
								$("#introQuiz").hide();
								$("#introInstruct2").hide();
								$("#tasks").hide();	
								$("#post").show();
								$("#postQuiz").show();
								$("#postQuest").hide();
								$("#scores").hide();
							}
						}, 1000);
					}		
					else
						alert("Please confirm that you have read all instructions");
				});
				
/*
 ***********************************************************************
 Javascript for Task Screens
 ***********************************************************************
 */				
				$("#tasks").tabs();
				$(".accordion").accordion({
					collapsible: true,
					heightStyle: "content"
				});
				var curTab = 0;
				var numPopups = 0;
				
				/*
				 *******************************************************
				 Variables to count clicks on each tab
				 *******************************************************
				 */
				var slideVisit = 0;
				var hmVisit = 0;
				var mathVisit = 0;
				var bjVisit = 0;
				var numSwitches = 0;
				 
				/*
				 *******************************************************
				 Variables to count score for each task
				 *******************************************************
				 */
				var slidesViewed = 1;
				var hmAttempt = 1;
				var hmWin = 0;
				var mathAttempt = 1;
				var mathWin = 0;
				var bjAttempt = 1;
				var bjWin = 0;
				var quiz1Correct = 0;
				var quiz2Correct = 0;
				var quizScoreChange = 0;
				 
				/*
				 *******************************************************
				 Variables to count time spent on each task
				 *******************************************************
				 */
				 
				var slideTime = 0;
				var slideActive;
				var hmTime = 0;
				var hmActive;
				var mathTime = 0;
				var mathActive;				 
				var bjTime = 0;
				var bjActive;
				var timeAwayFromSlides = 0;
				var timeAwayActive;
				var overallTime = 1200;
				var overallActive;
				var currentTaskTime = 0;
				var currentActive;
				
			/*
			 *******************************************************
			 Actions taken upon clicking a task:
			 -increment views
			 -increment/pause timers
			 -send timeSwitched and current/nextTask to DB
			 *******************************************************
			 */
				var currentTask = "Slides";
				var nextTask = "Slides";
				
				/*
				 *******************************************************
				 Send the current task, new task, and time of switch to
				 the database
				 *******************************************************
				 */	 
				function switchTask(){
					$.ajax({
						url: "main.php",
						async: true,
						type: "POST",
						data: {curTask:currentTask, nextTask:nextTask,
							taskTime:getTime(currentTaskTime), func:6}
					}).success(function(data){
						currentTaskTime = 0;
					});
				} 
				
				/*
				 *******************************************************
				 Resume timer for the 1st task, plus incrementing and
				 storing all relevant variables
				 *******************************************************
				 */	
				$("#t1").click(function(){
					if(curTab != 0){
						curTab = 0;
						nextTask = currentTask;
						currentTask = "Slides";
						switchTask();
						slideVisit++;
						numSwitches++;
						clearInterval(hmActive);
						clearInterval(bjActive);
						clearInterval(mathActive);
						clearInterval(currentActive);
						clearInterval(timeAwayActive);
						getTime(slideTime);
						slideActive = setInterval(function(){
							slideTime++;
						}, 1000);
						getTime(currentTaskTime);
						currentActive = setInterval(function(){
							currentTaskTime++;
						}, 1000);
					}
				});
				
				/*
				 *******************************************************
				 Resume timer for the 2nd task, plus incrementing and
				 storing all relevant variables
				 *******************************************************
				 */
				$("#t2").click(function(){
					if(curTab == 0){
						getTime(timeAwayFromSlides);
						timeAwayActive = setInterval(function(){
							timeAwayFromSlides++;
							if(popup == 1){
								if(timeAwayFromSlides % 60 == 0){
									alert("Have you reviewed for the quiz lately?");
									numPopups++;
								}
							}
						}, 1000);	
					}
					if(curTab != 1){
						curTab = 1;
						nextTask = currentTask;
						currentTask = "Hangman";
						switchTask();
						hmVisit++;
						numSwitches++;
						clearInterval(slideActive);
						clearInterval(bjActive);
						clearInterval(mathActive);
						clearInterval(currentActive);
						getTime(hmTime);
						hmActive = setInterval(function(){
							hmTime++;
						}, 1000);	
						getTime(currentTaskTime);
						currentActive = setInterval(function(){
							currentTaskTime++;
						}, 1000);					
					}
				});
				
				/*
				 *******************************************************
				 Resume timer for the 3rd task, plus incrementing and
				 storing all relevant variables
				 *******************************************************
				 */
				$("#t4").click(function(){
					if(curTab == 0){
						getTime(timeAwayFromSlides);
						timeAwayActive = setInterval(function(){
							timeAwayFromSlides++;
							if(popup == 1){
								if(timeAwayFromSlides % 60 == 0){
									alert("Have you reviewed for the quiz lately?");
									numPopups++;
								}
							}
						}, 1000);	
					}
					if(curTab != 3){
						curTab = 3;
						nextTask = currentTask;
						currentTask = "Math Puzzle";
						switchTask();
						mathVisit++;
						numSwitches++;
						clearInterval(slideActive);
						clearInterval(bjActive);
						clearInterval(hmActive);
						clearInterval(currentActive);
						getTime(mathTime);
						mathActive = setInterval(function(){
							mathTime++;
						}, 1000);
						getTime(currentTaskTime);
						currentActive = setInterval(function(){
							currentTaskTime++;
						}, 1000);
					}
				});
				
				/*
				 *******************************************************
				 Resume timer for the 4th task, plus incrementing and
				 storing all relevant variables
				 *******************************************************
				 */
				$("#t5").click(function(){
					if(curTab == 0){
						getTime(timeAwayFromSlides);
						timeAwayActive = setInterval(function(){
							timeAwayFromSlides++;
							if(popup == 1){
								if(timeAwayFromSlides % 60 == 0){
									alert("Have you reviewed for the quiz lately?");
									numPopups++;
								}
							}
						}, 1000);	
					}
					if(curTab != 4){
						curTab = 4;
						nextTask = currentTask;
						currentTask = "Blackjack";
						switchTask();
						bjVisit++;
						numSwitches++;
						clearInterval(slideActive);
						clearInterval(hmActive);
						clearInterval(mathActive);
						clearInterval(currentActive);
						getTime(bjTime);
						bjActive = setInterval(function(){
							bjTime++;
						}, 1000);
						getTime(currentTaskTime);
						currentActive = setInterval(function(){
							currentTaskTime++;
						}, 1000);
					}
				});
				
								
			/*
			 *******************************************************
			 Timer Function
			 *******************************************************
			 */ 
				/*
				 *******************************************************
				 Return the time in proper format to be stored in the 
				 database.
				 *******************************************************
				 */
				function getTime(totalS){
					function timeFormat(num){
						return(num < 10 ? "0" : "") + num;
					}

					var hours = Math.floor(totalS / 3600);
					totalS = totalS % 3600;

					var minutes = Math.floor(totalS / 60);
					totalS = totalS % 60;

					var seconds = Math.floor(totalS);

					//Format the time with 0's if need be
					hours = timeFormat(hours);
					minutes = timeFormat(minutes);
					seconds = timeFormat(seconds);

					//display the time
					var timeSpent = hours + ":" + minutes + ":" + seconds;

					return timeSpent;
				}
/*
 ***********************************************************************
 Javascript for Task 1
 ***********************************************************************
 */				
				/*
				 *******************************************************
				 Load the slides for the 1st task
				 *******************************************************
				 */
				$.ajax({
					url: "task1.php",
					async: true,
					type: "POST",
					data: {func: 1}
				}).success(function(data){
					$("#task1").html(data);
				});	
				
				/*
				 *******************************************************
				 Load the next slide when clicking "next"
				 *******************************************************
				 */	
				$(".content").on('click','.yNext',function(){
					slidesViewed++;
					$.ajax({
						url: "task1.php",
						async: true,
						type: "POST",
						data: {func: 3}
					}).success(function(data){
						$("#task1").html(data);
					});
					return false;
				});
				
				/*
				 *******************************************************
				 Load the previous slide when clicking "back"
				 *******************************************************
				 */	
				$(".content").on('click','.yBack',function(){
					slidesViewed++;
					$.ajax({
						url: "task1.php",
						async: true,
						type: "POST",
						data: {func: 2}
					}).success(function(data){
						$("#task1").html(data);
					});
					return false;
				});
/*
 ***********************************************************************
 Javascript for Task 2
 ***********************************************************************
 */		
				/*
				 *******************************************************
				 All the words to be used for Hangman
				 *******************************************************
				 */	
				var words = new Array('abate','aberrant','abscond','accolade','acerbic','acumen','adulation','adulterate','aesthetic',
					'aggrandize','alacrity','alchemy','amalgamate','ameliorate','amenable','anachronism','anomaly','approbation','archaic','arduous',
					'ascetic','assuage','astringent','audacious','austere','avarice','aver','axiom','bolster','bombast','bombastic','bucolic','burgeon',
					'cacophony','canon','canonical','capricious','castigation','catalyst','caustic','censure','chary','chicanery','cogent','complaisance',
					'connoisseur','contentious','contrite','convention','convoluted','credulous','culpable','cynicism','dearth','decorum','demur',
					'derision','desiccate','diatribe','didactic','dilettante','disabuse','discordant','discretion','disinterested','disparage',
					'disparate','dissemble','divulge','dogmatic','ebullience','eccentric','eclectic','effrontery','elegy','eloquent','emollient',
					'empirical','endemic','enervate','enigmatic','ennui','ephemeral','equivocate','erudite','esoteric','eulogy','evanescent',
					'exacerbate','exculpate','exigent','exonerate','facetious','fallacy','fawn','fervent','filibuster','flout',
					'fortuitous','fulminate','furtive','garrulous','germane','glib','grandiloquence','gregarious','hackneyed','harangue',
					'hedonism','hegemony','heretical','hubris','hyperbole','iconoclast','idolatrous','imminent','immutable','impassive','impecunious',
					'impetuous','implacable','impunity','inchoate','incipient','indifferent','inert','infelicitous','ingenuous','inimical',
					'innocuous','insipid','intractable','intransigent','intrepid','inured','inveigle','irascible','laconic','laud','loquacious','lucid',
					'luminous','magnanimity','malevolent','malleable','martial','maverick','mendacity','mercurial','meticulous','misanthrope','mitigate',
					'mollify','morose','mundane','nebulous','neologism','neophyte','noxious','obdurate','obfuscate','obsequious','obstinate','obtuse',
					'obviate','occlude','odious','onerous','opaque','opprobrium','oscillation','ostentatious','parody','pedagogy','pedantic',
					'penurious','penury','perennial','perfidy','perfunctory','pernicious','perspicacious','peruse','pervade','pervasive','phlegmatic',
					'pine','pious','pirate','pith','pithy','placate','platitude','plethora','plummet','polemical','pragmatic','prattle','precipitate',
					'precursor','predilection','preen','prescience','presumptuous','prevaricate','pristine','probity','proclivity','prodigal',
					'prodigious','profligate','profuse','proliferate','prolific','propensity','prosaic','pungent','putrefy','quaff','qualm',
					'querulous','query','quiescence','quixotic','quotidian','rancorous','rarefy','recalcitrant','recant','recondite',
					'redoubtable','refulgent','refute','relegate','renege','repudiate','rescind','reticent','reverent','rhetoric','salubrious',
					'sanction','satire','sedulous','shard','solicitous','solvent','soporific','sordid','sparse','specious','spendthrift','sporadic',
					'spurious','squalid','squander','static','stoic','stupefy','stymie','subpoena','subtle','succinct','superfluous','supplant',
					'surfeit','synthesis','tacit','tenacity','terse','tirade','torpid','torque','tortuous','tout','transient','trenchant','truculent',
					'ubiquitous','unfeigned','untenable','urbane','vacillate','variegated','veracity','vexation','vigilant','vilify','virulent','viscous',
					'vituperate','volatile','voracious','waver','zealous');
				var letterSelected = "";
				var wrongGuess = 0;
				var wrongLetter = "";
				var end = false;
				
				/*
				 *******************************************************
				 Reset the hangman board for a new game
				 *******************************************************
				 */	
				function reset(){
					end = false;
					var ranWord = "";
					var displayWord = "";
					letterSelected = "";
					chooseWords();
					wrongGuess = 1;
					document.hmpic.src="images/stage1.png";
					wrongLetter = "";
					$("#sWinNotice").html("");

					var links = $(".letter");
					for (i = 0; i < links.length; i++) {
						links[i].style.color = "Blue";
					}
				}

				/*
				 *******************************************************
				 Pick a word to be guessed by the user 
				 *******************************************************
				 */	
				function chooseWords(){
					ranWord = words[Math.floor(Math.random()* (words.length-1))].toUpperCase();
					var holder = placeHolders(ranWord);
					displayWord = holder;
					$("#displayguess").html(holder);
				}

				/*
				 *******************************************************
				 Set the proper number of blank-spaces for the current
				 word-to-be-guessed
				 *******************************************************
				 */	
				function placeHolders(word){
					var res = "";
					for(i=0;i<word.length;i++){
						res += "_ ";
					}
					return res;
				}

				/*
				 *******************************************************
				 Check if the letter selected by the user is in the current
				 word.
				 *******************************************************
				 */	
				function checkLetter(alpha){
					if(end){
						alert("To Start a New Game: Click on NewGame Button \n To Exit: Close the window");
					}
					else{
						pattern = "";
						letterSelected = letterSelected + alpha;
						for(i=0;i<ranWord.length;++i) {
							if(letterSelected.indexOf(ranWord.charAt(i)) != -1)
								pattern = pattern + (ranWord.charAt(i)+" ");
							else 
								pattern = pattern + "_ ";
						}
						$("#displayguess").html(pattern);

						if(pattern.indexOf("_ ") == -1){
							var str = "You Win!";
							var fstr = str.fontcolor("RED");
							$("#sWinNotice").html(fstr);
							$("#displayguess").html(ranWord);
							hmWin++;
							$("#sWin").val(hmWin);
							end = true;
						}

						if(ranWord.indexOf(alpha)== -1){
							if(wrongLetter.indexOf(alpha)!= -1){
							}
							else{
								wrongLetter = wrongLetter + alpha;
								wrongGuess++;
								eval("document.hmpic.src=\"images/stage" + wrongGuess + ".png\"");
							}
						}

						if(wrongGuess==7){
							$("#sLose").val(hmAttempt);
							var str1 = 'You Lose! The word was...';
							var fstr1 =str1.fontcolor("RED");
							$("#sWinNotice").html(fstr1);
							$("#displayguess").html(ranWord);
							end=true;
						}
					}
				}
				
				/*
				 *******************************************************
				 When user clicks a letter, load the function to check
				 if that letter is in the word.
				 *******************************************************
				 */	
				$(".letter").on('click',function(){
					checkLetter($(this).val());
				});
				
				/*
				 *******************************************************
				 Begin a new hangman game.
				 *******************************************************
				 */	
				$("#newHang").on('click',function(){
					hmAttempt++;
					$("#sLose").val(hmAttempt);
					reset();
				});

/*
 ***********************************************************************
 Javascript for Task 4
 ***********************************************************************
 */		
				/*
				 *******************************************************
				 Load a new math puzzle
				 *******************************************************
				 */	
				function newGame(){
					$("#aSubmitButton").show();
					$.ajax({
						url: "task4.php",
						async: true,
						type: "POST",
						data: {func:1}
					}).success(function(data){
						$("#aMathTable").html(data);
						$("#aWinNotice").html("");					
					});
				};
				
				/*
				 *******************************************************
				 When the user clicks "submit", display the board with
				 all correct number, where the incorrect user inputs are
				 replaced by the correct answers in red.
				 *******************************************************
				 */	
				$("#aSubmitButton").on('click',function(){
					$(this).hide();
					var solution = new Array();
					for(var i = 1; i <= 36; i++){
						if($(".aNum"+i).val() != "_")					
							solution.push($(".aNum"+i.toString()).val().toString());
					}
					$.ajax({
						url: "task4.php",
						async: true,
						type: "POST",
						data: {ans:solution, func:2}
					}).success(function(data){
						var parts = String(data).split("|");
						if(parts[1] == "WIN!"){
							$("#aWin").val(++mathWin);
							$("#aWinNotice").html("You won!");	
						}	
						else{
							$.ajax({
								url: "task4.php",
								async: true,
								type: "POST",
								data: {func:3}
							}).success(function(data){
								$("#aMathTable").html(parts[0]);
								$("#aWinNotice").html("You lost!");					
							});
						}					
					});
				});
				
				/*
				 *******************************************************
				 Load a new game when the user clicks "new game"
				 *******************************************************
				 */	
				$("#aNewPuzzleButton").on('click',function(){
					newGame();
					$("#aLose").val(++mathAttempt);
				});
/*
 ***********************************************************************
 Javascript for Task 5
 ***********************************************************************
 */		
				/*
				 *******************************************************
				 Load initial blackjack game
				 *******************************************************
				 */	
				$.ajax({
					url: "task5.php",
					async: true,
					type: "POST",
					data: {user:"Player", func:1}
				}).success(function(data){
					var parts = String(data).split("|");
					$("#yNametag").hide();
					$("#yGame").show();	
					$("#yRightBj").show();
					$("#yPlayerName").html(parts[0]);
					$("#yBottomRBj").html(parts[1]);
					$("#yDealer").html(parts[2]);
					$("#yPlayer").html(parts[3]);
				});		
				
				/*
				 *******************************************************
				 Load the next game when user clicks "new game"
				 *******************************************************
				 */	
				$("#yNewGame").click(function(){
					$("#yRightBj").show();				
					$("#yHit").show();
					$("#yStand").show();
					$("#yRaise").show();
					$("#yMiddleLBj").html("");	
					bjAttempt++;			
					$.ajax({
						url: "task5.php",
						async: true,
						type: "POST",
						data: {user:"Player", func:2}
					}).success(function(data){
						var parts = String(data).split("|");
						$("#yBottomRBj").html(parts[1]);
						$("#yDealer").html(parts[2]);
						$("#yPlayer").html(parts[3]);
						$("#yMiddleLBj").html("");
					});				
				});
				
				/*
				 *******************************************************
				 Update the player's bet and bank
				 *******************************************************
				 */	
				$("#yRaise").click(function(){
					$("#yHit").show();
					$("#yStand").show();
					$("#yRaise").show();
					$.ajax({
						url: "task5.php",
						async: true,
						type: "POST",
						data: {user:"Player", func:5}
					}).success(function(data){
						var parts = String(data).split("|");
						$("#yPlayerBank").html(parts[0]);
						$("#yPlayerBet").html(parts[1]);
						//alert(data);
					});				
				});
				
				/*
				 *******************************************************
				 Give the player an additional card and display all cards
				 in his/her hand.
				 Also check to see if the player's hand has busted.
				 *******************************************************
				 */	
				$("#yHit").click(function(){
					$("#yRaise").hide();
					$.ajax({
						url: "task5.php",
						async: true,
						type: "POST",
						data: {user:"Player", func:3}
					}).success(function(info){
						var parts = String(info).split("|");
						$("#yNametag").hide();
						$("#yGame").show();	
						$("#yPlayer").html(parts[0]);
						$.ajax({
							url: "task5.php",
							async: true,
							type: "POST",
							data: {user:"Player", hit:"yes", func:6}
						}).success(function(data){
							var parts2 = String(data).split("|");
							if(parts2[0] != "None"){
								setTimeout(function(){
									if(parts2[0] == "Computer")
										$("#yMiddleLBj").html(parts2[0] + "<br>Wins!");
									else
										$("#yMiddleLBj").html(parts2[0] + "<br>Win!");
								},500);
								$("#yBottomRBj").html(parts2[1]);
								$("#yHit").hide();
								$("#yStand").hide();
							}
						});
					});									
				});
				
				/*
				 *******************************************************
				 Deal additional cards to the computer.
				 Also check to determine if there is a winner after each
				 card dealt.
				 *******************************************************
				 */	
				$("#yStand").click(function(){
					$("#yHit").hide();
					$("#yRaise").hide();
					$("#yStand").hide();					
					$.ajax({
						url: "task5.php",
						async: true,
						type: "POST",
						data: {user:"Player", hit:"no", func:4}
					}).success(function(info){
						var parts = String(info).split("|");
						$("#yDealer").html(parts[0],500);
						$.ajax({
							url: "task5.php",
							async: true,
							type: "POST",
							data: {user:"Player", hit:"no", func:6}
						}).success(function(data){ 
							var parts2 = String(data).split("|");
							if(parts2[0] != "None"){
								$("#yBottomRBj").html(parts2[1]);
								$("#yHit").hide();
								$("#yStand").hide();
								setTimeout(function(){
									if(parts2[0] == "Computer")
										$("#yMiddleLBj").html(parts2[0] + "<br>Wins!");
									else{
										$("#yMiddleLBj").html(parts2[0] + "<br>Win!");
										bjWin++;
									}
								},750);							
							}
						});
					});	
					
				});
/*
 ***********************************************************************
 Javascript for Post Quiz and Questionnaire
 ***********************************************************************
*/	
				questNum = 1;
				var postScore = 0;
				/*
				 *******************************************************
				 Load the questions for the post-tasks quiz
				 *******************************************************
				 */	
				$.ajax({
					url: "main.php",
					async: true,
					type: "POST",
					data: {qNum:questNum, func:2}
				}).success(function(data){
					$(".yMiddlePost").html(data);				
				});
				
				/*
				 *******************************************************
				 Continue button for the post-quiz.
				 Ensure that the user has answered all questions.
				 *******************************************************
				 */					
				$(".content").on('click','#continue5',function(){
					var allPostAns = 0;
					var missing2 = "";
					for(var i = 1; i <= 15; i++){
						removeError(".questPost"+i,"#q2Error"+i);
						if($(".questPost"+i + ":checked").val()){
							allPostAns++;
						}
						else{
							$("#q2Error"+i).html(" Please select an answer");
							missing2 += " Question " + i + ",";
						}
					}
					if(allPostAns == 15){
						clearInterval(q2Active);
						for(var i = 1; i <= 15; i++){
							if($(".questPost"+i + ":checked").val() == $(".ansPost"+i).val())
								postScore++;
						}
						$.ajax({
							url: "main.php",
							async: true,
							type: "POST",
							data: {postScore:postScore, scoreChange:postScore-preScore, func:9}
						}).success(function(data){
						});		
						$("#intro").hide();
						$("#tasks").hide();	
						$("#postQuiz").hide();
						$("#postQuest").show();
						$("#scores").hide();
						$("#emailDialog").hide();
					}
					else
						alert("Please answer all 15 questions. You forgot: " + missing2);
				});
			
				$("#postQuestForm").submit(function(e) { 
					e.preventDefault();
				});	
						
				var allPostQuestAns = 0;
				/*
				 *******************************************************
				 Continue button for the post-task questionnaire.
				 Ensure that the user has selected a value for all
				 required questions.
				 *******************************************************
				 */	
				$("#continue6").click(function(){
					allPostQuestAns = 0;
					for(var i = 1; i <= 19; i++){
						if(i == 19){
							if($(".postQuest"+i+"b").val() != ""){
								allPostQuestAns++;
							}
						}
						if($(".postQuest"+i + ":checked").val())
							allPostQuestAns++;
					}
					if(allPostQuestAns == 20){
						$.ajax({
							url: "main.php",
							async: true,
							type: "POST",
							data:{remind:$(".remind:checked").val(),
								switched:$(".switched:checked").val(),
								complete:$(".complete:checked").val(),
								oneTask:$(".oneTask:checked").val(),
								severalTask2:$(".severalTask2:checked").val(),
								didMultiYn:$(".didMultiYn:checked").val(),
								didMulti:$(".didMultiWhy").val(),
								dQuiz1:$(".dQuiz1:checked").val(),
								dQuiz2:$(".dQuiz2:checked").val(),
								frustrate:$(".frustrate:checked").val(),
								efficient1:$(".efficient1:checked").val(),
								efficient2:$(".efficient2:checked").val(),
								happy1:$(".happy1:checked").val(),
								happy2:$(".happy2:checked").val(),
								performance1:$(".performance1:checked").val(),
								performance2:$(".performance2:checked").val(),
								effective1:$(".effective1:checked").val(),
								effective2:$(".effective2:checked").val(),
								mostTime:$(".mostTime:checked").val(),
								productive:$(".productive:checked").val(),
								other:$(".addCom").val(),
								numPopups:numPopups, func:10}
						}).success(function(data){
						});
							
						$("#intro").hide();;
						$("#tasks").hide();	
						$("#post").show();
						$("#postQuiz").hide();
						$("#postQuest").hide();
							
						$("#q1Score").val(preScore + "/15");
						$("#q2Score").val(postScore + "/15");
						var difference = postScore - preScore;
						$("#qScoreChange").val(difference);
						
						
						/*
						 ***********************************************
						 Ensure the user inputs a valid email address
						 ***********************************************
						 */	
						function validateEmail(email) {
							var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;
							if( !emailReg.test(email) ) {
								return false;
							} 
							else {
								return true;
							}
						}

						$("#scores").hide();
						
						/*
						 ***********************************************
						 Display a dialog box asking the user if they
						 would like to submit their email address in
						 order to have a chance at winning an additional
						 prize.
						 ***********************************************
						 */	
						$("#emailDialog").show();
						$("#emailDialog").dialog({
							modal: true,
							buttons: {
								'Submit': function(){
									if(validateEmail($("#email").val())){
										if($("#email").val() != ""){
											$.ajax({
												url: "main.php",
												async: true,
												type: "POST",
												data: {slidesViewed:slidesViewed, 
													hmAttempt:hmAttempt, hmWin:hmWin, hmWin:hmWin,
													mathAttempt:mathAttempt, mathWin:mathWin, 
													bjAttempt:bjAttempt, bjWin:bjWin,
													email:$('#email').val(), preScore:preScore,
													postScore:postScore, scoreChange:postScore-preScore, 
													pass:$("#userPassword").val(), func:11}
											}).success(function(data){
											});
											$(this).dialog("close");
											$("#emailDialog").hide();
											$("#scores").show();
											showScores();
										}
										else
											alert("Please enter a valid email address");
									}
									else
										alert("Please enter a valid email address");
								},
								'Decline': function(){
									$(this).dialog("close");
									$("#emailDialog").hide();
									$("#scores").show();									
									showScores();
								}					
							}
						});
						
						/*
						 ***********************************************
						 Display a dialog box with the user's scores from
						 the 1st and 2nd quizzes along with the change
						 in performance.
						 ***********************************************
						 */	
						function showScores(){
							$("#scores").dialog({
								modal: true,
								buttons: {
									Exit: function() {
										$(this).dialog("close");
										$("#intro").hide();
										$("#tasks").hide();
										$("#post").hide();
										window.location = 'index.php?logout=true';
									}					
								}
							});
						}
						
					}
					else{
						alert("Please enter a value for all questions" );
						for(var i = 1; i <= 19; i++){
							removeError(".postQuest"+i,"#postError"+i);
							if(i == 19)
								removeError(".postQuest19b","#postError19b");
							if(i < 19){
								if(!$(".postQuest"+i+":checked").val()){
									$("#postError"+i).html(" Please enter a value");
								}
								else
									$("#postError"+i).html("");
							}
							else{
								if(i == 19){
									if(!$(".postQuest"+i+":checked").val()){
										$("#postError"+i).html(" Please enter a value");
									}
									if($(".postQuest"+i+"b").val() == "")
										$("#postError"+i + "b").html(" Please enter a value");
								}
							}			
						}
					}
				});
			});//end ready
		</script>
	</head>
	<body>
		<div id="wrapper">
			<div class="container" id="intro">
				<div id="loginDialog">
					<h3>Please enter your login code: </h3>
					<input id="userPassword" type="text" value="multi">
				</div>
				<div class="content" id="introInstruct">
					<div class="introHead">					
						<h2>Welcome!</h2>
						<h3>Instructions:</h3>
						<h3 class="red3">Please read and check off all instructions</h3>
					</div>
					<div class="introTop">					
						<ul>						  
							<li class="instLi"><input type="checkbox" class="instCheckA1 checkG" name="checkG1">After filling out a basic demographic questionnaire, you will take 
								an online 15 question quiz on world history.</li>
							<li class="instLi"><input type="checkbox" class="instCheckA2 checkG" name="checkG1">You will have at most 5 minutes to complete the quiz.</li> 
							<li class="instLi"><input type="checkbox" class="instCheckA3 checkG" name="checkG1">Following the quiz, you will receive additional instructions.</li>
						</ul>
		
						<h2>Thank you for your participation!</h2>
					</div>
					<div class="introBottom">
						<div class = "buttonHolder">
							<button class="ySubmitButton" id="continue1">Continue</button>
						</div>	
					</div>					
				</div>
				<div class="content" id="introDemo">		
					<form id="demoForm">
						<h3>Please take a moment to answer a few quick questions. Thank you.</h3>
						
						<h4>What is your gender?
						<select id = "gender" class="preDemo1">
							<option value selected>-Select-</option>
							<option value = "M">Male</option>
							<option value = "F">Female</option>
						</select><span id="preError1"class="red"></span>
						</h4>				
						<h4>What is your ethnicity?
						<select id = "ethnicity" class="preDemo2">
							<option value selected>-Select-</option>
							<option value = "1">Caucasian</option>
							<option value = "2">Hispanic / Latino</option>
							<option value = "3">Middle Eastern</option>
							<option value = "4">Asian/Pacific Islander</option>
							<option value = "5">Black/African American</option>
							<option value = "6">Other</option>
						</select><span id="preError2"class="red"></span>
						</h4>
						<h4>Please enter your age.
							<input id = "age" class="preDemo3" type="number" min="18" max="95">
							<span id="preError3"class="red"></span>						
						</h4>
						
						<h4>What is your academic status?
						<select id = "position" class="preDemo4">
							<option value selected>-Select-</option>
							<option value = "1">Undergraduate Student</option>
							<option value = "2">Graduate Student</option>
							<option value = "3">Alumni</option>
						</select><span id="preError4"class="red"></span>
						</h4>
						<h4>Is/was your major or minor either Political Science or History?<span id="preError5"class="red"></span>	<br>
							<input type="radio" class="pshMajor  preDemo5 radios" name="pshMajor" value="1">Yes&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="pshMajor  preDemo5 radios" name="pshMajor" value="0">No					
						</h4>
						<h4>Have you ever taken a college-level course in Political Science or History?<span id="preError6"class="red"></span><br>
							<input type="radio" class="pshCourse  preDemo6 radios" name="pshCourse" value="1">Yes&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="pshCourse  preDemo6 radios" name="pshCourse" value="0">No					
						</h4>
						<h3>Please rate your knowledge of world history.<span id="preError7"class="red"></span><br>
							<span class="green">Poor = 1 and Excellent = 5</span></h3>			
						<h4>
							<input type="radio" class="rateHist  preDemo7 radios" name="rateHist" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="rateHist  preDemo7 radios" name="rateHist" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="rateHist  preDemo7 radios" name="rateHist" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="rateHist  preDemo7 radios" name="rateHist" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="rateHist  preDemo7 radios" name="rateHist" value="5">5					
						</h4>	
						<h3>Please state whether you agree or disagree with the following statements.<br>			
							<span class="green">Strongly Disagree = 1 and Strongly Agree = 5</span></h3>
						<h4>When I use my computer:</h4>
						<h4>I like to juggle several unrelated activities 
							at the same time.<span id="preError8"class="red"></span><br>
							<input type="radio" class="juggle  preDemo8 radios" name="juggle" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="juggle  preDemo8 radios" name="juggle" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="juggle  preDemo8 radios" name="juggle" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="juggle  preDemo8 radios" name="juggle" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="juggle  preDemo8 radios" name="juggle" value="5">5					
						</h4>	
						<h4>I try to do many computer-based tasks at once.<span id="preError9"class="red"></span><br>
							<input type="radio" class="multiTask  preDemo9 radios" name="multiTask" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="multiTask  preDemo9 radios" name="multiTask" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="multiTask  preDemo9 radios" name="multiTask" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="multiTask  preDemo9 radios" name="multiTask" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="multiTask  preDemo9 radios" name="multiTask" value="5">5					
						</h4>	
						<h4>I work on one computer-based task at a time.<span id="preError10"class="red"></span>	<br>
							<input type="radio" class="singleTask  preDemo10 radios" name="singleTask" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="singleTask  preDemo10 radios" name="singleTask" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="singleTask  preDemo10 radios" name="singleTask" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="singleTask  preDemo10 radios" name="singleTask" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="singleTask  preDemo10 radios" name="singleTask" value="5">5					
						</h4>
						<h4>I am comfortable carrying out several 
							computer-based tasks at the same time.<span id="preError11"class="red"></span>	<br>
							<input type="radio" class="severalTask  preDemo11 radios" name="severalTask" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="severalTask  preDemo11 radios" name="severalTask" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="severalTask  preDemo11 radios" name="severalTask" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="severalTask  preDemo11 radios" name="severalTask" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="severalTask  preDemo11 radios" name="severalTask" value="5">5					
						</h4>					
						<div class = "yButtonHolder">
							<button class="ySubmitButton" id="continue2">Continue</button>
						</div>	
					</form>	
					
				</div>	
				
				<div class="content" id="introQuiz">
					<div class="yTop">
						<h2>Quiz</h2>
						<h5>
							Please answer the following 15 questions, and click "Finish" when you
							are done.  <b>must complete all 15 questions to submit your quiz.</b> \
							You will have at most 5 minutes to complete the quiz, at which
							point your quiz will be submitted as is.
						</h5>
					</div>
					<div class="yMiddle">
					</div>
				</div>
				
				<div class="content" id="introInstruct2">
					<div class="introHead">
						<h3>Activity Instructions:</h3>
						<h3 class="red3">Please read and check off all instructions</h3>
					</div>
					<div class="introTop">
						<ul>
							<li class="instLi">
								<input type="checkbox" class="instCheckB1 checkG" name="checkG2">
									The next screen you see will contain four tabs.</li>
							<li class="instLi">
								<input type="checkbox" class="instCheckB2 checkG" name="checkG2">
									You have 20 minutes total and you can choose how to allocate your time.</li>
							<li class="instLi">
								<input type="checkbox" class="instCheckB3 checkG" name="checkG2">
									Your main task (the first tab) is to review slides on world history.</li> 
							<li class="instLi">
								<input type="checkbox" class="instCheckB4 checkG" name="checkG2">
									Note that you also have the option to play various games during the 20 minutes.</li>
							<li class="instLi">
								<input type="checkbox" class="instCheckB5 checkG" name="checkG2">
									You have the option of hangman, a mathematical 
									game and blackjack.</li>
							<li class="instLi">
								<input type="checkbox" class="instCheckB6 checkG" name="checkG2">
									You may switch between your main task and the games at any point.</li> 
							<li class="instLi">
								<input type="checkbox" class="instCheckB7 checkG" name="checkG2">
									Afterwards, you will receive a different quiz on world history 
									and a short post-activity questionnaire.</li> 
							<li class="instLi">
								<input type="checkbox" class="instCheckB8 checkG" name="checkG2">
									<b>The participants judged to have the largest improvement in their score will be 
									added to a drawing to win an additional $50 gift card.</b></li>    
						</ul>
						<h2>Thank you for your participation!</h2>
					</div>
					<div class="introBottom">
						<div class = "buttonHolder">
							<button class="ySubmitButton" id="continue4">Continue</button>
						</div>	
					</div>						
				</div>
			</div>
			<div class="container" id="tasks">	

				<ul id="taskList">
				<?php 
				$randomNum = rand(1, 4);
				if($randomNum==1){
				?>
					<li id="t1"><a href="#task1">Review for Quiz</a></li>
					<li id="t2"><a href="#task2">Hangman</a></li>
					<li id="t4"><a href="#task4">Math Squares</a></li>
					<li id="t5"><a href="#task5">Blackjack</a></li>
				<?php 
				}
				elseif($randomNum==2){
				?>
					<li id="t1"><a href='#task1'>Review for Quiz</a></li>
					<li id="t5"><a href='#task5'>Blackjack</a></li>
					<li id="t4"><a href="#task4">Math Squares</a></li>
					<li id="t2"><a href='#task2'>Hangman</a></li>
				<?php 
				}
				elseif($randomNum==3){
				?>
					<li id="t1"><a href='#task1'>Review for Quiz</a></li>
					<li id="t4"><a href="#task4">Math Squares</a></li>
					<li id="t5"><a href='#task5'>Blackjack</a></li>
					<li id="t2"><a href='#task2'>Hangman</a></li>
				<?php 
				}
				elseif($randomNum==4){
				?>
					<li id="t1"><a href='#task1'>Review for Quiz</a></li>
					<li id="t2"><a href='#task2'>Hangman</a></li>
					<li id="t5"><a href='#task5'>Blackjack</a></li>
					<li id="t4"><a href="#task4">Math Squares</a></li>
				<?php 
				}
				?>
				</ul>					
					
				<div class="content" id="task1"></div>
				<div class="content" id="task2">
					<div id="hmTime"></div>
					<div class="content" id="hmGame">
						<div id="sTop">
							<div id="hangarea">
								<img src="images/stage1.png" name="hmpic">								
							</div>
							<div id="sRight">
								<div id="sTopR">
									<span id="sWinNotice"></span>
									<div id="displayguess"></div>
									<div value="alphaarea">
										<?php
										$x = 'A';
										for($i='0'; $i < '26'; $i++){
											echo "<button class='letter' value='".$x.
												"' onclick=this.style.color='Red'>".$x."</button>";
											$x++;
										}
										?>
									</div>
									<div id="play">
										<button id="newHang" class="sButton">New Game</button>
									</div>
									<div id="sBottomR">								
										<label class="sTally">Games Won</label>
										<input id="sWin" type="text" value="0" disabled="true"><br>
										<label class="sTally">Games</label>
										<input id="sLose" type="text" value="1" disabled="true"><br>
									</div>
								</div>							
							</div>
						</div>

						<div id="sBottom">
							<div class="accordion">
								<h6>Instructions (Click to open/close)</h6>
								<div>
									<ul>
										<li class="sLi">Hangman is a word-guessing game.</li>
										<li class="sLi">Click "New Game" to start.</li>
										<li class="sLi">The row of dashes indicates the number of letters in the word.</li>
										<li class="sLi">Click on a letter of the alphabet.</li>
										<li class="sLi">If the letter is in the word,it replaces the dash(es).</li>
										<li class="sLi">Each wrong guess results in a stroke being added to the hangman.</li>
										<li class="sLi">Your role is to guess the word correctly before the victim meets his grisly fate.</li>
										<li class="sLi">GOOD LUCK!!</li>
									</ul>
								</div>
								
							</div>
							
						</div>
							
						
					</div>
				</div>

				<div class="content" id="task4">
					<div id="mathTime"></div>
					<div class="content" id="aGame">
						<div id="aTop">
							<div id="aLeft">
								<div id="aTopL">
									<img src="images/math-title.png" alt="MATH PUZZLE">
								</div>
								<div id="aBottomL">
									<table id="aMathTable"></table>
								</div>
							</div><!--end of aLeft-->
							<div id="aRight">
								<div id="aTopR">
									<button id="aSubmitButton" class="aButton">SUBMIT FOR ANSWER</button><br>
									<button id="aNewPuzzleButton" class="aButton">NEW PUZZLE</button><br><br>								
								</div>
								<div id="aBottomR">
									<label class="aTally">Games Won &nbsp;</label>
									<input id="aWin" type="text" value="0" readonly><br>
									<label class="aTally">Games</label>
									<input id="aLose" type="text" value="1" readonly><br><br>
									<span id="aWinNotice"></span>									
								</div>						 
							</div><!--end of aRight-->
						</div>
						<div id="aBottom">
							<div class="accordion">
								<h6>Instructions (Click to open/close)</h6>
								<div>
									<ul id="aUl">
										<li class="aLi">This puzzle has 6 empty spaces and each empty space 
											correspond to unique number from 1 to 9</li>
										<li class="aLi pink1">Remember: multiplication and division are performed 
											before addition and subtraction</li>
										<li class="aLi">Try to fill in the missing numbers</li>
										<li class="aLi">Use the numbers 1 through 9(exclude the already given 
											numbers) to complete the equations</li>
										<li class="aLi">Each number is only used once</li>
										<li class="aLi">Each row is a math equation. Each column is a math 
											equation</li>										
									</ul>
								</div>
							</div>
						</div>					
					</div><!--end of aGame-->
				</div><!--end of task4-->
				<div class="content" id="task5">
					<div id="bjTime"></div>
					<div class="content" id="yGame">
						<div id="yTopBj">
							<div class="yLeftBj">
								<div id="yTopLBj">
									<div id="yDealerName">
										Dealer
									</div>
									<div id=yDealer></div>
								</div>
								<div id="yMiddleLBj"></div>
								<div id="yBottomLBj">
									<div id="yPlayerName"></div>
									<div id=yPlayer></div>
								</div>
							</div>
							<div id="yRightBj">
								<div id="yTopRBj">
									<button id="yNewGame">NEW GAME</button>
									<button id="yStand">STAND</button> <br/>
									<button id="yHit">HIT</button> <br/>						
									<button id="yRaise">RAISE BET</button> <br>
								</div>
								<div id="yBottomRBj">
									<div id="yPlayerBank"></div>
								</div>
							</div>
						</div>
						<div id="yBottomBj">
							<div class="accordion">
								<h6>Instructions (Click to open/close)</h6>
								<div>
									<ul>
										<li class="yLi">
											This is  a basic version of Blackjack.  
											At the beginning of each round, an initial  
											wager of $100 will be deducted from your  
											bank and put towards your bet.
										</li>
										<li class="yLi">Prior to clicking "Hit", you may click 
											"Raise Bet" and raise your wager $100  
											at a time until your bank registers $0.
										</li> 
										<li class="yLi">
											Click "Hit" to be dealt a new card.
										</li>
										<li class="yLi">
											To stop your turn, click "Stand".
										</li>
										<li class="yLi">
											If after standing, the value of your cards 
											is higher than the dealer, you win. Otherwise, 
											including a tie, the dealer wins.
										</li>
										<li class="yLi">
											In this version, all values of 21 are treated the same.
										</li>
										<li class="yLi">
											If you lose and your bank registers $0, the 
											bank will be refreshed upon starting a new game
										</li>
									</ul>
								</div>
							</div>
						</div>
						
					</div><!--end of yGame-->
				</div><!--end of task 5-->
			</div><!--end of tasks-->
			<div class="container" id="post">
				<div class="content" id="postQuiz">
					<div class="yTopPost">
						<h2>Quiz</h2>
						<h5>
							Please answer the following 15 questions, and click "Finish" when you
							are done. You <b>must complete all 15 questions to submit your quiz.</b> You will have at most 5 minutes to complete the quiz, at which
							point your quiz will be submitted as is.
						</h5>
					</div>
					<div class="yMiddlePost">
					</div>
				</div><!--end of postQuiz-->
				<div class="content" id="postQuest">	
					<h2>Post Exercise Questionnaire</h2>	
					<form id="postQuestForm">
						<h3>Please Rank the level of difficulty for each of the following<br>	
							<span class="green">Easy=1 and Difficult=5</span></h3>		
						<h4>&nbsp;&nbsp;First Quiz<span id="postError1"class="red"></span><br>
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz1 postQuest1 radios" name="dQuiz1" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz1 postQuest1 radios" name="dQuiz1" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz1 postQuest1 radios" name="dQuiz1" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz1 postQuest1 radios" name="dQuiz1" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz1 postQuest1 radios" name="dQuiz1" value="5">5					
						</h4>
						<h4>&nbsp;&nbsp;Second Quiz<span id="postError2"class="red"></span><br>
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz2 postQuest2 radios" name="dQuiz2" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz2 postQuest2 radios" name="dQuiz2" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz2 postQuest2 radios" name="dQuiz2" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz2 postQuest2 radios" name="dQuiz2" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="dQuiz2 postQuest2 radios" name="dQuiz2" value="5">5					
						</h4>
								
						<h4>On which task did you spend most of your time?<span id="postError4"class="red"></span><br>
							&nbsp;&nbsp;<input type="radio" class="mostTime postQuest4 radios" name="mostTime" value="1">Slides&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;<input type="radio" class="mostTime postQuest4 radios" name="mostTime" value="2">Hangman&nbsp;&nbsp;&nbsp;&nbsp;						
							&nbsp;&nbsp;<input type="radio" class="mostTime postQuest4 radios" name="mostTime" value="3">Math Puzzle&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;<input type="radio" class="mostTime postQuest4 radios" name="mostTime" value="4">Blackjack					
						</h4>
						<h3>Please state whether you agree or disagree with the following statements.<br>			
							<span class="green">Strongly Disagree=1 and Strongly Agree=5</span></h3>
						
						<h3>In the first quiz:</h3>
						
						<h4>I believe my work was effective.<span id="postError5"class="red"></span><br>
							<input type="radio" class="effective1 postQuest5 radios" name="effective1" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="effective1 postQuest5 radios" name="effective1" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="effective1 postQuest5 radios" name="effective1" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="effective1 postQuest5 radios" name="effective1" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="effective1 postQuest5 radios" name="effective1" value="5">5				
						</h4>
						<h4>I would rate my performance in the top quarter.<span id="postError6"class="red"></span><br>
							<input type="radio" class="performance1 postQuest6 radios" name="performance1" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="performance1 postQuest6 radios" name="performance1" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="performance1 postQuest6 radios" name="performance1" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="performance1 postQuest6 radios" name="performance1" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="performance1 postQuest6 radios" name="performance1" value="5">5				
						</h4>
						<h4>I am happy with the quality of my work output.<span id="postError6"class="red"></span><br>
							<input type="radio" class="happy1 postQuest7 radios" name="happy1" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="happy1 postQuest7 radios" name="happy1" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="happy1 postQuest7 radios" name="happy1" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="happy1 postQuest7 radios" name="happy1" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="happy1 postQuest7 radios" name="happy1" value="5">5				
						</h4>
						
						<h3><span class="green">Strongly Disagree=1 and Strongly Agree=5</span></h3>
						<h3>In the second quiz:</h3>
						
						<h4>I believe my work was effective.<span id="postError8"class="red"></span><br>
							<input type="radio" class="effective2 postQuest8 radios" name="effective2" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="effective2 postQuest8 radios" name="effective2" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="effective2 postQuest8 radios" name="effective2" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="effective2 postQuest8 radios" name="effective2" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="effective2 postQuest8 radios" name="effective2" value="5">5				
						</h4>
						<h4>I would rate my performance in the top quarter.<span id="postError9"class="red"></span><br>
							<input type="radio" class="performance2 postQuest9 radios" name="performance2" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="performance2 postQuest9 radios" name="performance2" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="performance2 postQuest9 radios" name="performance2" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="performance2 postQuest9 radios" name="performance2" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="performance2 postQuest9 radios" name="performance2" value="5">5				
						</h4>
						<h4>I am happy with the quality of my work output.<span id="postError10"class="red"></span><br>
							<input type="radio" class="happy2 postQuest10 radios" name="happy2" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="happy2 postQuest10 radios" name="happy2" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="happy2 postQuest10 radios" name="happy2" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="happy2 postQuest10 radios" name="happy2" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="happy2 postQuest10 radios" name="happy2" value="5">5				
						</h4>
						
						<h3><span class="green">Strongly Disagree=1 and Strongly Agree=5</span></h3>
						<h3>In this exercise:</h3>
						
						<h4>I switched between the assigned computer-based tasks.<span id="postError11"class="red"></span><br>
							<input type="radio" class="switched postQuest11 radios" name="switched" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="switched postQuest11 radios" name="switched" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="switched postQuest11 radios" name="switched" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="switched postQuest11 radios" name="switched" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="switched postQuest11 radios" name="switched" value="5">5					
						</h4>	
						<h4>I was reminded to return to my primary task.<span id="postError12"class="red"></span><br>
							<input type="radio" class="remind postQuest12 radios" name="remind" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="remind postQuest12 radios" name="remind" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="remind postQuest12 radios" name="remind" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="remind postQuest12 radios" name="remind" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="remind postQuest12 radios" name="remind" value="5">5				
						</h4>
						<h4>I tried to complete the assigned computer-based tasks at the same time.<span id="postError13"class="red"></span><br>
							<input type="radio" class="complete postQuest13 radios" name="complete" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="complete postQuest13 radios" name="complete" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="complete postQuest13 radios" name="complete" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="complete postQuest13 radios" name="complete" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="complete postQuest13 radios" name="complete" value="5">5					
						</h4>	
						<h4>I was able to work efficiently.<span id="postError14"class="red"></span><br>
							<input type="radio" class="efficient1 postQuest14 radios" name="efficient1" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="efficient1 postQuest14 radios" name="efficient1" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="efficient1 postQuest14 radios" name="efficient1" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="efficient1 postQuest14 radios" name="efficient1" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="efficient1 postQuest14 radios" name="efficient1" value="5">5				
						</h4>
						<h4>I worked on one computer-based task at a time.<span id="postError15"class="red"></span><br>
							<input type="radio" class="oneTask postQuest15 radios" name="oneTask" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="oneTask postQuest15 radios" name="oneTask" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="oneTask postQuest15 radios" name="oneTask" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="oneTask postQuest15 radios" name="oneTask" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="oneTask postQuest15 radios" name="oneTask" value="5">5					
						</h4>	
						<h4>I was highly productive.<span id="postError16"class="red"></span><br>
							<input type="radio" class="productive postQuest16 radios" name="productive" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="productive postQuest16 radios" name="productive" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="productive postQuest16 radios" name="productive" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="productive postQuest16 radios" name="productive" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="productive postQuest16 radios" name="productive" value="5">5				
						</h4>
						<h4>I was carrying out several computer-based tasks at the same time.<span id="postError17"class="red"></span><br>
							<input type="radio" class="severalTask2 postQuest17 radios" name="severalTask2" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="severalTask2 postQuest17 radios" name="severalTask2" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="severalTask2 postQuest17 radios" name="severalTask2" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="severalTask2 postQuest17 radios" name="severalTask2" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="severalTask2 postQuest17 radios" name="severalTask2" value="5">5					
						</h4>	
						<h4>I believe the way I worked was efficient.<span id="postError18"class="red"></span><br>
							<input type="radio" class="efficient2 postQuest18 radios" name="efficient2" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="efficient2 postQuest18 radios" name="efficient2" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="efficient2 postQuest18 radios" name="efficient2" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="efficient2 postQuest18 radios" name="efficient2" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="efficient2 postQuest18 radios" name="efficient2" value="5">5				
						</h4>
						<h4>Did you multitask?<span id="postError19"class="red"></span><br>
							<input type="radio" class="didMultiYn postQuest19 radios" name="didMultiYn" value="1">Yes&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" class="didMultiYn postQuest19 radios" name="didMultiYn" value="0">No&nbsp;&nbsp;&nbsp;&nbsp;
							Why?/Why not?<span id="postError19b"class="red"></span><br>
							<textarea class="didMultiWhy postInputText postQuest19b" placeholder=""
								autocomplete="off"></textarea>							
						</h4>	
						
						<h4>How frustrating was this system for you?<span id="postError3"class="red"></span><br>
						<span class="green">Not At All Frustrating=1 and Very Frustrating=5</span><br>
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="frustrate postQuest3 radios" name="frustrate" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="frustrate postQuest3 radios" name="frustrate" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="frustrate postQuest3 radios" name="frustrate" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="frustrate postQuest3 radios" name="frustrate" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="frustrate postQuest3 radios" name="frustrate" value="5">5					
						</h4>
						
						<h4>Additional Comments:<br>
						<textarea class="addCom postInputText" placeholder="Comments are optional" 
							autocomplete="off"></textarea>	
						</h4>		
						<div class = "yButtonHolder">
							<h3>Thank you for your participation in our research study. Have a great day!</h3>
							<button class="ySubmitButton" id="continue6">Submit</button>
						</div>	
					</form>					
				</div><!--end of postQuest-->
				<div id="emailDialog">
					<h3>For the purposes of notifying the winner of the $50 gift card, 
						please enter your email address.<br><br>
						This will be stored separate from all other data collected in this study.
					</h3>
						<input id="email" type="email" placeholder="Optional" autocomplete="off"/>
				</div>
				<div id="scores">
					<h3 id="scoresTitle">Quiz Results:</h3>
					<h3> 
						Quiz 1: <input id="q1Score" class="finalScores" value="" readonly><br>
						Quiz 2: <input id="q2Score" class="finalScores" value="" readonly><br>
						Change in scores: <input id="qScoreChange" class="finalScores" value="" readonly>
					</h3>
				</div>
			</div><!--end of post-->		
		</div><!--end of wrapper-->
	</body>
</html>
