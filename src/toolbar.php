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
	
	//Recheck, to give cookielogin.php a chance.
	if($_SESSION['username'] != null)
	{
		$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
		
		$notifications = mysqli_query($connection, 'SELECT *, DATE_FORMAT(date,\'%M %d, %Y\') AS fdate FROM notifications WHERE uid='.$_SESSION['id'].' AND deleted=0 ORDER BY notifications.date DESC');
		
		while($notification = mysqli_fetch_array($notifications))
		{
			//Check for unread messages.
			if(!$notification['seen'])
			{
				$unreadNotifications = true;
				break;
			}
		}
		$notifications->data_seek(0);
	}
?>

<div id='toolbar'>
	<div id='toolbaralignment'>
		<div style='float:left;padding:0px;height:100%'>
				<a href='http://www.relatablez.com/'><img height='35' src='http://www.relatablez.com/logotextwhite.png' alt='Relatablez / Am I The Only One?' title='Relatablez / Am I The Only One?'></a>
				<span class='toolbar-item' id='aitoo-dropdown'>Am I The Only One?</span>
				<a class='toolbar-item' href='http://www.relatablez.com/blog/'>Blog</a>
		</div>		
		<div  class='account-buttons'>
			<?php
				if($_SESSION["username"] != null)
				{
					if($unreadNotifications)
						echo "<button class='toolbar notifs'><img src='http://www.relatablez.com/notification_icon2.png'></button>";
					else
						echo "<button class='toolbar notifs'><img src='http://www.relatablez.com/notification_icon.png'></button>";
						
					echo "<button class='toolbar profile'><img src='http://www.relatablez.com/profile_icon.png'></button>\r\n";	
					include($_SERVER['DOCUMENT_ROOT'].'/user-popups.php');
					getPopup($notifications, $connection);
				}
				else
				{
					echo "<button class='toolbar margleft margright showlogin'>Log In</button>\r\n";
					echo "<button class='toolbar margleft margright showreg' data-header='Sign Up'>Sign Up</button>\r\n";	
				}
			?>
		</div>
	</div>
</div>
<div id='toolbar-spacer'></div>

<?php

if($_SESSION['username'] == null)
echo
"
<div class='popup' id='registerpopup'>
	<button class='closebutton hidereg'></button><br>
	<h1 id='registerheader' class='popup-header'>Sign Up</h1>			
	<h6 style='text-align:center;margin-top:5px;'>If you already have an account, <a href='javascript:hideRegister();showLogin();'>Log In</a></h6>
	<div style='text-align:center;margin:auto;width:100%;'>
		<div>
			<form method='post' action='javascript:register();'>
				<table style='width:100%;'>
					<tr>
						<td><img src='http://www.relatablez.com/question.png' id='username-guidelines-button' onmouseover='showGuidelines(this)' onmouseout='hideGuidelines(this)'><div id='username-guidelines-popup' class='questionpopup'><span>Usernames must be 3-16 characters long. They can only consist of alphanumerical characters (a-z, 0-9)</span></div></td>
						<td><input id='user_input' class='textbox' type='text' name='username'  onkeydown='checkLimit(event,this,32,false);' onkeyup='verifyUser();checkHideErrors(this, user_verify_img);' placeholder='Username'><label id='user_log'></label></td>
						<td style='width:15px;'><img onmouseover='showErrors(this)' onmouseout='hideErrors(this)' class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /><div class='popup-offset'><div class='error-popup' id='username-popup'></div></div></td>
					</tr>
					<tr class='spacer'><td colspan='3'></td></tr>
					<tr>
						<td><img src='http://www.relatablez.com/question.png' id='password-guidelines-button' onmouseover='showGuidelines(this)' onmouseout='hideGuidelines(this)'><div id='password-guidelines-popup' class='questionpopup'><span>Password must be atleast 6 characters long. There are no limitations on which characters you can/can't use.</span></div></td>
						<td><input id='pass_input' class='textbox' type='password' onkeyup='verifyPassword();checkHideErrors(this, pass_verify_img);' autocomplete='off' name='password' placeholder='Password'><label id='pass_log'></label></td>
						<td><img onmouseover='showErrors(this)' onmouseout='hideErrors(this)' class='verify' src='http://www.relatablez.com/check_mark.png' id='pass_verify_img' /><div class='popup-offset'><div class='error-popup' id='new-password-popup'></div></div></td>
					</tr>
					<tr class='spacer'><td colspan='3'></td></tr>
					<tr>
						<td></td>
						<td><input id='repass_input' class='textbox' type='password' name='repassword' onkeyup='verifyRePassword()' placeholder='Confirm Password'><label id='repass_log'></label></td>
						<td><img onmouseover='showErrors(this)' onmouseout='hideErrors(this)' class='verify' src='http://www.relatablez.com/check_mark.png' id='repass_verify_img' /><div class='popup-offset'><div class='error-popup' id='renew-password-popup'></div></div></td>
					</tr>
					<tr class='spacer'><td colspan='3'></td></tr>
					<tr>
						<td></td>
						<td><input id='email_input' class='textbox' type='text' name='email' onkeydown='checkLimit(event,this,32,false);' onkeyup='verifyEmail();checkHideErrors(this, email_verify_img);' placeholder='Email'><label id='email_log'></label></td>
						<td><img onmouseover='showErrors(this)' onmouseout='hideErrors(this)' class='verify' src='http://www.relatablez.com/check_mark.png' id='email_verify_img' /><div class='popup-offset'><div class='error-popup' id='email-popup'></div></div></td>
					</tr>
					<tr class='spacer'><td colspan='3'></td></tr>
					<tr>
						<td></td>
						<td><input id='registerbutton' class='bigbluebutton' type='submit' value='Sign Up'></td>
						<td></td>
					</tr>
				</table><br>
				
			</form>
		</div>
		<label style='font-size:10px'>By clicking Sign Up, you agree to our <a href='javascript:hideRegister();showLogin();'>Terms & Conditions</a>.</label>
	</div>
</div>

<div class='popup' id='loginpopup'>
	<button class='closebutton hidelogin'></button><br>
	<h1 id='loginheader' class='popup-header'>Log In</h1>
	<div style='text-align:center'>
		<form method='POST' action='javascript:login();'>	
			<table style='width:100%;'>
				<tr>
					<td><input id='login_user_input' class='textbox' type='text' name='u' placeholder='Username'><br><span id='login_user_log'></span></td>
				</tr>
				
				<tr class='spacer'></tr>
				
				<tr>
					<td><input id='login_pass_input' class='textbox' type='password' name='p' placeholder='Password'><br><span id='login_pass_log'></span></td>
				</tr>
				
				<tr class='spacer'></tr>
				
				<tr>
					<td><div><label style='bottom:0px;'>Remember me</label><input style='width:auto;height:auto;bottom:0px;' id='remember_input' type='checkbox' name='r' value='1' /></div></td>
				</tr>
			</table>
			<input id='loginbutton' class='bigbluebutton' type='submit' value='Login'>
		</form>
	<label style='font-size:10px'>To recover password, click <a href='#passwordrecovery'>here</a></label>
	</div>
</div>
";
?>

<!-- <div class='infobarwrapper'>
	<div class='infobar'>
		<div id='infobar'>
			<a href='http://www.relatablez.com/FAQ'>FAQ</a> <a href='http://www.relatablez.com/privacy'>Privacy</a> <a href='mailto:contact@relatablez.com' title='Contact@Relatablez.com'>Contact</a> Relatablez &copy; 2014
		</div>
	</div>
</div>-->
