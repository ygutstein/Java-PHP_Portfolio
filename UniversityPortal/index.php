<?php
session_start();
session_destroy();
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>univerCITY Portal 1.0</title>
		<link rel="stylesheet" type="text/css" href="css/univercity.css"/>
		<link rel="stylesheet" type="text/css" href="css/home.css"/>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="plugins/css/validationEngine.jquery.css" type="text/css"/>
		<script src="js/jquery-1.9.1.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.js"></script>
		<script type="text/javascript" src="plugins/jquery.redirect.min.js"> </script>	
		<script src="plugins/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
		<script src="plugins/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>			
		
		<script>
			$(function(){ 			 
				var capLetter = false;
				var lowLetter = false;
				var num = false;
				var specialChar = false;
				$("#uRegCont").hide();
				$("#regQ1").hide();
				$("#regQ2").hide();
				$("#major").hide();
				$("#minor").hide();
				$("#homeCont").hide();
				$("#login").dialog({
					modal: true,
					buttons: {
						Login: function(){
							var userName = $("#loginName").val();
							var userPass = $("#loginPass").val();
							$("input[type=hidden][name=hiddenL]").val(userName);
							if($("#loginName").val() == "" || $("#loginPass").val() == ""){
								$("#loginError").show();								
							}
							else{															
								$.ajax({ 
									url: "indexDb.php", 
									async: true, 
									type: "POST", 
									data:{uName:userName, uPass:userPass, func:1},
									dataType: "html" 
								}).success(function(data){
									if(data == "Password Not Found"){
										$("#loginError").text("The password you entered was incorrect");
										$("#loginError").show();
									}
									else if(data == "Name Not Found"){
										$("#loginError").text("The username you entered was not found");
										$("#loginError").show();
									}	
									else{ 	
										var u = String(data).split("-");									
										uID = u[0];
										uStatus = u[1];
										$.ajax({
											url: "home.php",
											type: "POST",
											data: {uID:uID, uStatus:uStatus},
											datatype: "html"
										}).success(function(data){		
											$().redirect('home.php', {'uID':uID, 'uStatus':uStatus});
										});									
									}//end else					
								})//end success								
							}//end else
						}, //end of login button
						'New User': function(){
							$("#firstCont").hide();
							$("#uRegCont").show();
							$(this).dialog("close");							
						}//end New User button
					} //end of buttons
				}); //end of dialog
				
				$("#regForm").validationEngine();
				
				$("#status").mouseup(function(){
					if($("#status").val() == "4") {
						$.ajax({
							url: "indexDb.php",
							async: true,
							type: "POST",
							data: {func:3}
						}).success(function(data){
							$("#dept").html(data);
						});
						$("#regQ1").show();
						$("#regQ2").hide();
						$("#major").hide();
						$("#minor").hide();
					}
					else if($("#status").val() == "1" ||
							$("#status").val() == "2") {
						$.ajax({
							url: "indexDb.php",
							async: true,
							type: "POST",
							data: {func:3}
						}).success(function(data){
							$("#major").html(data);
							$("#minor").html(data);
						});
						$("#regQ2").show();
						$("#major").show();
						$("#minor").show();
						$("#regQ1").hide();
					}
					else {
						$("#regQ1").hide();
						$("#regQ2").hide();
						$("#major").hide();
						$("#minor").hide();
					}					
				}); //end of #status
				
				$("#regForm").submit(function(e) { 
					e.preventDefault();
				});	
							
				$("#regButton").on('click',function(){
						var fName = $("#firstName").val();
						var lName = $("#lastName").val();
						var userName = $("#userLogin").val();
						var userPass = $("#userPass").val();
						var ssn = $("#ssn").val();
						var securityQ = $("#securityQ").val();
						var securityA = $("#securityA").val();
						var city = $("#city").val();
						var parts1 = $("#state").val().split("-");
						var state = parts1[0];
						var zip = $("#zip").val();
						var ethnic = $("#ethnicity").val();
						var status = $("#status").val();
						if($("input:radio[name=gender]:checked").size() > 0)
							var gender = $("input:radio[name=gender]:checked").val();
						else
							var gender = "*";
						var birth = $("#birth").val();
						if(birth != ""){
							var parts = birth.split('-','?');
							var bDay = parts[2];
							var bMonth = parts[1];
							var bYear = parts[0];
						}
						else{
							var bDay = "*";
							var bMonth = "*";
							var bYear = "*";
						}
						var degree = $("#degree").val();
						var major = $("#major").val();
						var minor = $("#minor").val();
						var proj = $("#projGrad").val();
						if(proj != ""){
							var parts2 = proj.split('-','/');
							var projYear = parts[0];
						}
						else
							var projYear = "*";
						var dept = $("#dept").val();
						
						$.ajax({ 
							url: "indexDb.php", 
							async: true, 
							type: "POST", 
							data:{fName:fName, lName:lName, uName:userName, 
								uPass:userPass, ssn:ssn, q:securityQ, 
								a: securityA, city:city, state:state, zip:zip,
								ethnic:ethnic, status:status, gender:gender,
								bYear:bYear, bMonth:bMonth, bDay:bDay, dept:dept,
								major:major, minor:minor, proj:projYear, degree:degree, func:2},
							dataType: "html" 
						}).success(function(data){
							if(data != "0na"){
								var u = String(data).split(" ");									
								uID = u[0];
								uStatus = u[1];
								$.ajax({
									url: "home.php",
									type: "POST",
									data: {uID:uID, uStatus:uStatus},
									datatype: "text"
								}).success(function(data){		
									$().redirect('home.php', {'uID':uID, 'uStatus':uStatus});
								});		
							}											
						})//end success	
					//}
					
				});//end function
			}); //end of document.ready
		</script>
	</head>
	<body>
		<div id="wrapper">
			<div class="container" id="firstCont">
				<header>
					<div id="logoHolder">
						<img id="logo" src="images/logo.png"> 
						<a href="docs/univerCITY_readMe.docx" target="_blank">Readme</a>
						<a href="docs/univerCITY_presentation.pptx" target="_blank">Powerpoint</a>
						<a href="docs/CS401_SRD_final.docx" target="_blank">SRD</a>
						<a href="docs/CS401_ADD_final.docx" target="_blank">ADD</a>
					</div><!--logoHolder-->						
				</header>
				<nav id="mainNav">
				</nav><!--mainNav-->
				<div class="content" id="first">
					<div id="login">
						<p>Username:
							<input type="text" id="loginName" Required>
						</p>
						<p>Password:
							<input type="password" id="loginPass" Required>
						</p>
						<span id="loginError">Please enter a username AND password</span>
						
					</div>
				</div><!--content=first-->
			</div><!--container-->
			
			<div class="container" id="uRegCont">
				<header>
					<div id="logoHolder">
						<img id="logo" src="images/logo.png"> 
						<a href="docs/univerCITY_readMe.docx" target="_blank">Readme</a>
						<a href="docs/univerCITY_presentation.pptx" target="_blank">Powerpoint</a>
						<a href="docs/CS401_SRD_final.docx" target="_blank">SRD</a>
						<a href="docs/CS401_ADD_final.docx" target="_blank">ADD</a>
					</div><!--logoHolder-->		
				<nav id="mainNav">
					
				</nav><!--mainNav-->
				<div class="content" id="newUser">
					<h2>Register as a new user</h2>
					<form id="regForm" class="regNew">
						<table id="regTable">
							<tr>
								<td class="regCol">
									<p>First Name:
										<input id="firstName" class="validate[required] text-input" value="" type="text">
									</p>
									<p>Last Name:
										<input id="lastName" class="validate[required] text-input" value="" type="text">
									</p>
									<p>Username:
										<input id="userLogin" class="validate[required] text-input" value="" type="text">
									</p>
									<p>Password:
										<input id="userPass" class="validate[required,[custom[letterNumberSpecial],minSize[6],maxSize[20]] text-input" type="password">										
									</p>
									<p>Confirm Password:
										<input id="userPassConfirm" class="validate[required,equals[userPass]] text-input" type="password">										
									</p>
									<p>SSN (numbers only):
										<input id="ssn" class="validate[required,custom[integer],minSize[9],maxSize[9]] text-input" type="text">
									</p>
									<p>Security Question:
										<select id="securityQ" class="validate[required] text-input">
											<option value selected>-Select-</option>
											<option value = "1">What was your childhood nickname?</option>
											<option value = "2">What is your mother's maiden name?</option>
											<option value = "3">What was your favorite place to visit as a child?</option>
											<option value = "v">What was the color of your first car?</option>
											<option value = "5">In what city were you born?</option>
											<option value = "6">What was your dream job as a child? </option>
											<option value = "7">What was the name of your first stuffed animal?</option>
										</select>
									</p>
									<p>Security Answer:
										<input id="securityA" class="validate[required] text-input" value="" type="text">
									</p>
								</td>
								<td class="regCol">
									<p>Birthday:
										<input id="birth" class="validate[[required],[custom[date]] text-input" type="text">
									</p>
									<p>City:
										<input id="city" class="validate[required] text-input" value="" type="text">
									</p>
									<p>State (2-letter abbreviation):
										<select id="state" class="validate[required] text-input">
											<option value selected>-Select-</option>
											<option>AL-Alabama</option>
											<option>AK-Alaska</option>
											<option>AZ-Arizona</option>
											<option>AR-Arkansas</option>
											<option>CA-California</option>
											<option>CO-Colorado</option>
											<option>CT-Connecticut</option>
											<option>DE-Delaware</option>
											<option>FL-Florida</option>
											<option>GA-Georgia</option>
											<option>HI-Hawaii</option>
											<option>ID-Idaho</option>
											<option>IL-Illinois</option>
											<option>IN-Indiana</option>
											<option>IA-Iowa</option>
											<option>KS-Kansas</option>
											<option>KY-Kentucky</option>
											<option>LA-Louisiana</option>
											<option>ME-Maine</option>
											<option>MD-Maryland</option>
											<option>MA-Massachusetts</option>
											<option>MI-Michigan</option>
											<option>MN-Minnesota</option>
											<option>MS-Mississippi</option>
											<option>MO-Missouri</option>
											<option>MT-Montana</option>
											<option>NE-Nebraska</option>
											<option>NV-Nevada</option>
											<option>NH-New Hampshire</option>
											<option>NJ-New Jersey</option>
											<option>NM-New Mexico</option>
											<option>NY-New York</option>
											<option>NC-North Carolina</option>
											<option>ND-North Dakota</option>
											<option>OH-Ohio</option>
											<option>OK-Oklahoma</option>
											<option>OR-Oregon</option>
											<option>PA-Pennsylvania</option>
											<option>RI-Rhode Island</option>
											<option>SC-South Carolina</option>
											<option>SD-South Dakota</option>
											<option>TN-Tennessee</option>
											<option>TX-Texas</option>
											<option>UT-Utah</option>
											<option>VT-Vermont</option>
											<option>VA-Virginia</option>
											<option>WA-Washington</option>
											<option>WV-West Virginia</option>
											<option>WI-Wisconsin</option>
											<option>WY-Wyoming</option>
											<option>AS-American Samoa</option>
											<option>DC-District of Columbia</option>
											<option>FM-Federated States of Micronesia</option>
											<option>GU-Guam</option>
											<option>MH-Marshall Islands</option>
											<option>MP-Northern Mariana Islands</option>
											<option>PW-Palau</option>
											<option>PR-Puerto Rico</option>
											<option>VI-Virgin Islands</option>
										</select>
									</p>
									<p>Zip Code:
										<input id="zip" class="validate[required,custom[integer]] text-input" value="" type="text">
									</p>
									<p>Ethnicity:
										<select id="ethnicity" class="validate[required] text-input">
											<option value selected>-Select-</option>
											<option value = "Caucasian">Caucasian</option>
											<option value = "Hispanic/Latino">Hispanic / Latino</option>
											<option value = "MiddleEastern">Middle Eastern</option>
											<option value = "AsianPacificIslander">Asian/Pacific Islander</option>
											<option value = "Black/AfricanAmerican">Black/African American</option>
											<option value = "Other">Other</option>
										</select>
									</p>
									<p>
										Gender: <input type="radio" id="male" name="gender" value="M">Male
										<input type="radio" id="female" name="gender" value="F">Female							
									</p>
									<p>College Status:
										<select id="status" class="validate[required] text-input">
											<option value selected>-Select-</option>
											<option value = "3">Prospective Student</option>
											<option value = "1">Current Student</option>
											<option value = "2">Alumni</option>
											<option value = "4">Administrator</option>
										</select>
									</p>
									<span id="regQ1">
										<p>Department:
											<select id="dept">
											</select>
										</p>
									</span>
									<span id="regQ2">
										<p>Degree Level:
											<select id="degree">
												<option value selected>-Select-</option>
												<option value = "bachelor">BA or BS</option>
												<option value = "master">MA or MS</option>
												<option value = "phd">PHD</option>
											</select>
										</p>
										<p>Major:
											<select id="major">
											</select>
										</p>
										<p>Minor:
											<select id="minor">
											</select>
										</p>
										<p>Projected Graduation:
											<input type="text" id="projGrad" class="validate[custom[date]] text-input">
										</p>
									</span>
								</td>
							</tr>
						</table>
						<input id="regButton" class="submitbutton" type="submit" value="Login" >	
					</form>			
				</div><!--content=newUser-->
			</div><!--container-->
		</div><!--wrapper-->
	
	</body>
	
</html>
