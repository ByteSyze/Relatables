<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	if(GlobalUtils::$user->getID() == 0)
	{
		if(isset($_COOKIE["rrm"]))
		{
			include $_SERVER['DOCUMENT_ROOT'] . '/cookielogin.php';
			login($_COOKIE['rrm']);
		}
	}
	
	//Recheck, to give cookielogin.php a chance.
	if(GlobalUtils::$user->getID() != 0)
	{
		$connection = GlobalUtils::getConnection();
		
		$notifications = mysqli_query($connection, 'SELECT *, DATE_FORMAT(created,\'%M %d, %Y\') AS fdate FROM notifications WHERE uid='.$_SESSION['id'].' AND deleted=0 ORDER BY notifications.created DESC');
		
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

<?php

if($_SESSION['id'] == null)
echo
"
<div class='popup-shade'></div>
<div class='popup' id='registerpopup'>
	<div class='buttons'>
		<button class='button blue-hover smaller' >Close</button>
	</div>
	<h1 id='registerheader' class='popup-header'>Sign Up</h1>			
	<h6 style='text-align:center;margin-top:5px;'>If you already have an account, <a href='javascript:hideRegister();showLogin();'>Log In</a></h6>
	<div style='text-align:center;margin:auto;width:100%;'>
		<div>
			<form method='post' action='javascript:register();' class='vertical'>
				<table style='width:100%;'>
					<tr>
						<td><img src='/images/icons/question.png' id='username-guidelines-button' onmouseover='showGuidelines(this)' onmouseout='hideGuidelines(this)'><div id='username-guidelines-popup' class='questionpopup'><span>Usernames must be 3-16 characters long. They can only consist of alphanumerical characters (a-z, 0-9)</span></div></td>
						<td><input id='user_input' class='textbox' type='text' name='username'  onkeydown='checkLimit(event,this,32,false);' onkeyup='verifyUser();checkHideErrors(this, user_verify_img);' placeholder='Username'><label id='user_log'></label></td>
						<td style='width:15px;'><img onmouseover='showErrors(this)' onmouseout='hideErrors(this)' class='verify' src='/images/icons/check_mark.png' id='user_verify_img' /><div class='popup-offset'><div class='error-popup' id='username-popup'></div></div></td>
					</tr>
					<tr class='spacer'><td colspan='3'></td></tr>
					<tr>
						<td><img src='/images/icons/question.png' id='password-guidelines-button' onmouseover='showGuidelines(this)' onmouseout='hideGuidelines(this)'><div id='password-guidelines-popup' class='questionpopup'><span>Password must be atleast 6 characters long. There are no limitations on which characters you can/can't use.</span></div></td>
						<td><input id='pass_input' class='textbox' type='password' onkeyup='verifyPassword();checkHideErrors(this, pass_verify_img);' autocomplete='off' name='password' placeholder='Password'><label id='pass_log'></label></td>
						<td><img onmouseover='showErrors(this)' onmouseout='hideErrors(this)' class='verify' src='/images/icons/check_mark.png' id='pass_verify_img' /><div class='popup-offset'><div class='error-popup' id='new-password-popup'></div></div></td>
					</tr>
					<tr class='spacer'><td colspan='3'></td></tr>
					<tr>
						<td></td>
						<td><input id='repass_input' class='textbox' type='password' name='repassword' onkeyup='verifyRePassword()' placeholder='Confirm Password'><label id='repass_log'></label></td>
						<td><img onmouseover='showErrors(this)' onmouseout='hideErrors(this)' class='verify' src='/images/icons/check_mark.png' id='repass_verify_img' /><div class='popup-offset'><div class='error-popup' id='renew-password-popup'></div></div></td>
					</tr>
					<tr class='spacer'><td colspan='3'></td></tr>
					<tr>
						<td></td>
						<td><input id='email_input' class='textbox' type='text' name='email' onkeydown='checkLimit(event,this,32,false);' onkeyup='verifyEmail();checkHideErrors(this, email_verify_img);' placeholder='Email'><label id='email_log'></label></td>
						<td><img onmouseover='showErrors(this)' onmouseout='hideErrors(this)' class='verify' src='/images/icons/check_mark.png' id='email_verify_img' /><div class='popup-offset'><div class='error-popup' id='email-popup'></div></div></td>
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
	<div class='buttons'>
		<button class='button blue-hover smaller'>Close</button>
	</div>
	<h1 class='popup-title blue'>Log in</h1>
	<span class='popup-small'>If you don't have an account, <a>Sign up</a></span>
	<form class='vertical' method='post' action='javascript:login();'>
		<input id='login_user_input' type='text' name='u' placeholder='Username'>
		<input id='login_pass_input' type='text' name='p' placeholder='Password'>
		<label for='r'>Remember me</label><input id='remember_input' type='checkbox' name='r' value='1'>
		<div class='buttons padded align-center'>
			<button class='button blue-hover block' type='submit'>Log in</button>
		</div>
	</form>
	<a class='forgot-password' data-show='#pwrecoverypopup' data-hide='#loginpopup'>Forgot password?</a>
</div>

<div class='popup' id='pwrecoverypopup'>
	<div class='buttons'>
		<button class='button blue-hover smaller'>Close</button>
	</div>
	<h1 class='popup-title blue'>Recover Password</h1>
	<form class='vertical' method='post' action='/recover.php' id='pwrecoveryform'>
		<input type='text' name='e' placeholder='Email'>
		<div class='buttons padded align-center'>
			<button class='button blue-hover block' type='submit'>Submit</button>
		</div>
	</form>
</div>
";

//Display any error message.
if($_SESSION['error_msg'])
{
	$error = $_SESSION['error_msg'];
	unset($_SESSION['error_msg']);
	
	echo "
<div class='popup' style='display:block;' id='errorpopup'>
	<div class='buttons'>
		<button class='button blue-hover smaller'>Close</button>
	</div>
	<h1 class='popup-title blue'>Oops!</h1>
	<span class='popup-small'>$error</span>
</div>";
}
//Display any popup message.
if($_SESSION['popup_msg'])
{
	$popup = $_SESSION['popup_msg'];
	unset($_SESSION['popup_msg']);
	
	echo "
<div class='popup' style='display:block;'>
	<div class='buttons'>
		<button class='button blue-hover smaller'>Close</button>
	</div>
	<span class='popup-small'>$popup</span>
</div>";
}
?>

<div class="navigation-bar">
	<div class="grid wrap wider no-gutters">
		<div class="pull-left">
			<ul class="navigation-items">
				<li><a href="/" class="logo"><img src='/images/icons/logotextwhite.png' alt='Relatablez / Am I The Only One?' title='Relatablez / Am I The Only One?'></a></li>
				<li><a href="/moderate">Moderate</a></li>
				<li>
					<a href="/about" data-togg="#about-drop">About</a>
					<ul id="about-drop" class='dropdown'>
						<li><a href="/about/faq">FAQ</a></li>
						<li><a href="/about/privacy">Privacy</a></li>
						<li><a href="/about/blog">Blog</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="pull-right">
		<?php
			echo '<ul class="navigation-items">';
				if($_SESSION['id'] != null) {
					echo '<li><a href="#" data-togg="#notif-drop"><div class="icon notifications-icon ';
					if($unreadNotifications) echo 'unread-notifications';
					echo '"></div><ul id="notif-drop" class="dropdown">'; 
					
					
					while($notification = mysqli_fetch_array($notifications))
					{
						echo '<li><a><div class="indicator"></div>' . $notification['message'] . '<span>' . $notification['fdate'] . '</span></a></li>';
					}
					
					echo '</ul></a>';
					echo '<li><a href="#" data-togg="#prof-drop"><div class="icon profile-icon"></div></a><ul id="prof-drop" class="dropdown"><li><a href="/user/' . GlobalUtils::$user->getUsername() . '">Profile</a></li><li><a href="/settings/account">Settings</a></li><li><a href="/signout.php">Signout</a></li></ul></li>';
				} else {
					echo '<li><a class="showlogin">Log in</a></li>';
					echo '<li><a data-signup-header="Sign Up">Sign up</a></li>';
				}
			echo '</ul>';
		?>
		</div>
	</div>
</div>
<div class="navigation-spacer"></div>
