<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	if($_SESSION["username"] == null)
	{
		if(isset($_COOKIE["rrmi"]) && isset($_COOKIE["rrmp"]))
		{
			include($_SERVER['DOCUMENT_ROOT']."/cookielogin.php");
			
			login($_COOKIE["rrmi"],$_COOKIE["rrmp"]);
		}
	}
?>

<div id='toolbar'>
	<div id='toolbaralignment'>
		<div style='float:left;padding:0px;height:100%'>
				<a href='http://www.relatablez.com/'><img style='vertical-align:middle;' height="30" src='http://www.relatablez.com/logotextwhite.png' alt='Relatablez / Am I The Only One?' title='Relatablez / Am I The Only One?'></a>								
		</div>		
		<div  class='account-buttons'>
			<?php
				if($_SESSION["username"] != null)
				{
					echo "<button class='toolbar'><img src='http://www.relatablez.com/notification_icon.png' width='25'></button>";
					echo "<button class='toolbar' onclick='toggleProfileDropdown()'><img src='http://www.relatablez.com/profile_icon.png' width='25'></button>\r\n";	
					echo 
					"
			<table class='profile' id='profile-dropdown'>
				<tr><td><a class='profile' href='http://www.relatablez.com/user/" . $_SESSION["username"] ."'>Profile</a></td></tr>
				<tr><td><a class='profile' href='http://www.relatablez.com/settings/profile'>Settings</a></td></tr>
				<tr><td><a class='profile' href='http://www.relatablez.com/signout.php'>Sign Out</a></td></tr>
			</table>";	
				}
				else
				{
					echo "<button class='toolbar margleft margright' onclick='showLogin()'>Log In</button>\r\n";
					echo "<button class='toolbar margleft margright' data-header='Sign Up' onclick='showRegister(this)'>Sign Up</button>\r\n";	
				}
			?>			
		</div>
	</div>
</div>

<?php

if($_SESSION['username'] == null)
echo
"
<div class='popup' id='registerpopup'>
	<button class='closebutton' onclick='hideRegister()'></button><br>
	<h1 id='registerheader' class='popup-header'>Sign Up</h1>			
	<h6 style='text-align:center;margin-top:5px;'>If you already have an account, <a href='javascript:hideRegister();showLogin();'>Log In</a></h6>
	<div style='text-align:center;margin:auto;width:100%;'>
		<div>
			<div style='height:100%;width:50px;float:left;display:none;'>
			
					<button class='question' id='username-guidelines-button' onmouseover='showGuidelines(this)' onmouseout='hideGuidelines(this)'><div id='username-guidelines-popup' class='questionpopup'><span>Usernames must be 3-16 characters long. They can only consist of alphanumerical characters (a-z, 0-9)</span></div></button>
				<br><button class='question' id='password-guidelines-button' onmouseover='showGuidelines(this)' onmouseout='hideGuidelines(this)'><div id='password-guidelines-popup' class='questionpopup'><span>Password must be atleast 6 characters long. There are no limitations on which characters you can/can't use.</span></div></button>
			</div>
			<form method='post' action='javascript:register();'>
				<table style='width:100%;'>
					<tr>
						<td><button class='question' id='username-guidelines-button' onmouseover='showGuidelines(this)' onmouseout='hideGuidelines(this)'><div id='username-guidelines-popup' class='questionpopup'><span>Usernames must be 3-16 characters long. They can only consist of alphanumerical characters (a-z, 0-9)</span></div></button></td>
						<td><input id='user_input' class='textbox' type='text' name='username' onkeyup='verifyUser()' placeholder='Username'><label id='user_log'></label></td>
						<td style='width:29px;'></td>
					</tr>
					<tr class='spacer'></tr>
					<tr>
						<td><button class='question' id='password-guidelines-button' onmouseover='showGuidelines(this)' onmouseout='hideGuidelines(this)'><div id='password-guidelines-popup' class='questionpopup'><span>Password must be atleast 6 characters long. There are no limitations on which characters you can/can't use.</span></div></button></td>
						<td><input id='pass_input' class='textbox' type='password' onkeyup='verifyPassword()' name='password' placeholder='Password'><label id='pass_log'></label></td>
						<td></td>
					</tr>
					<tr class='spacer'></tr>
					<tr>
						<td></td>
						<td><input id='repass_input' class='textbox' type='password' name='repassword' onkeyup='verifyRePassword()' placeholder='Confirm Password'><label id='repass_log'></label></td>
						<td></td>
					</tr>
					<tr class='spacer'></tr>
					<tr>
						<td></td>
						<td><input id='email_input' class='textbox' type='text' name='email' onkeyup='verifyEmail()' placeholder='Email'><label id='email_log'></label></td>
						<td></td>
					</tr>
					<tr class='spacer'></tr>
				</table><br>
				<input id='registerbutton' class='bigbluebutton' type='submit' value='Sign Up'></td>
			</form>
		</div>
		<label style='font-size:10px'>By clicking Sign Up, you agree to our <a href='javascript:hideRegister();showLogin();'>Terms & Conditions</a>.</label>
	</div>
</div>

<div class='popup' id='loginpopup'>
	<button class='closebutton' onclick='hideLogin()'></button><br>
	<h1 id='loginheader' class='popup-header'>Log In</h1>
	<div style='text-align:center'>
		<form method='post' action='javascript:login();'>
			<input id='login_user_input' class='textbox' type='text' name='username' placeholder='Username'><br><label id='login_user_log'></label>
			<input id='login_pass_input' class='textbox' type='password' name='password' placeholder='Password'><br><label id='login_pass_log'></label>
			<div><label style='bottom:0px;'>Remember me</label><input style='width:auto;height:auto;bottom:0px;' id='remember_input' type='checkbox' name='rememberme' value='1'></div>
			<input id='loginbutton' class='bigbluebutton' type='submit' value='Login'>
		</form>
	<label style='font-size:10px'>To recover password, click <a href='#passwordrecovery'>here</a></label>
	</div>
</div>
";
?>

<div class='infobarwrapper'>
	<div class='infobar'>
		<div id='infobar'>
			<a href='http://www.relatablez.com/FAQ'>FAQ</a> <a href='http://www.relatablez.com/privacy'>Privacy</a> <a href='mailto:contact@relatablez.com' title='Contact@Relatablez.com'>Contact</a> Relatablez &copy; 2014
		</div>
	</div>
</div>