<?php
	/*Copyright (C) Tyler Hackett 2014*/

	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';

	if(GlobalUtils::$user->getID() == 0)
	{
		if(isset($_COOKIE["rrm"]) && isset($_COOKIE["rrmi"]))
		{
			$cUser = new User($_COOKIE["rrmi"], User::TYPE_INT);

			if(password_verify($_COOKIE["rrm"], $cUser->getCookieLogin()))
			{
				$_SESSION['id']=$cUser->getID();
				GlobalUtils::$cUser = $cUser;

				GlobalUtils::log("$dbUser logged in", $_SESSION['id'], $_SERVER['REMOTE_ADDR']);

				//Update their last login date and unique cookie login ID.
				$cUser->setLastLogin();

				$cookie_login = $cUser->generateCookieLogin();
				$cUser->update();

				$expire = time()+(60*60*24*365*5);
				setcookie("rrmi", $cUser->getID(), $expire, '/');
				setcookie("rrm", $cookie_login, $expire, '/');
			}
		}
	}

	//Recheck, to give cookie login a chance.
	if(GlobalUtils::$user->getID() != 0)
	{
		$connection = GlobalUtils::getConnection();

		$notifications = mysqli_query($connection, 'SELECT *, DATE_FORMAT(created,\'%M %d, %Y\') AS fdate FROM notifications WHERE uid='.$_SESSION['id'].' AND deleted=0 AND (seen=0 OR created >= CURDATE() - INTERVAL 1 WEEK) ORDER BY created DESC');

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

if($_SESSION['id'] == null) {
GlobalUtils::createPopup('registerpopup', 'Sign Up', "
		<h6>If you already have an account, <a class='showlogin'>Log In</a></h6>
		<div style='text-align:center;margin:auto;width:100%;'>
			<div>
				<form method='post' class='vertical'>
					<div>
						<input id='user_input' class='textbox' type='text' name='username' placeholder='Username'>
						<div class='input-info-icon' data-togg='#user-guide-pop'></div>
						<div id='user-guide-pop' class='info-popup'><span>Usernames must be 3-16 characters long. They can only consist of alphanumerical characters (a-z, 0-9)</span></div>
						<div class='verify marker nomark' data-err-popup ></div><div class='popup-offset'><div class='error-popup' id='username-popup'></div></div>
					</div>
					<div>
						<input id='pass_input' class='textbox' type='password' autocomplete='off' name='password' placeholder='Password'>
						<div class='input-info-icon' data-togg='#pass-guide-pop'></div>
						<div id='pass-guide-pop' class='info-popup'><span>Password must be atleast 6 characters long. There are no limitations on which characters you can/can't use.</span></div>
						<div class='verify marker nomark' data-err-popup ></div><div class='popup-offset'><div class='error-popup' id='new-password-popup'></div></div>
					</div>
					<div>
						<input id='repass_input' class='textbox' type='password' name='repassword' placeholder='Confirm Password'>
						<div class='verify marker nomark' data-err-popup ></div><div class='popup-offset'><div class='error-popup' id='renew-password-popup'></div></div>
					</div>
					<div>
						<input id='email_input' class='textbox' type='text' name='email' placeholder='Email'>
						<div class='verify marker nomark' data-err-popup ></div><div class='popup-offset'><div class='error-popup' id='email-popup'></div></div>
					</div>
					<span style='font-size:10px'>By clicking Sign Up, you agree to our <a href='/about/terms'>Terms & Conditions</a>.</span>
					<div style='padding-top:10px' class='buttons'>
						<input id='registerbutton' class='button blue-hover block' type='submit' value='Sign Up'>
					</div>
				</form>
			</div>
		</div>");

GlobalUtils::createPopup('loginpopup', 'Log In', "
		<h6>If you don't have an account, <a data-signup-header='Sign Up'>Sign up</a></h6>
		<form class='vertical' method='post' action='javascript:login();'>
			<div><input id='login_user_input' type='text' name='u' placeholder='Username'></div>
			<div><input id='login_pass_input' type='password' name='p' placeholder='Password'></div>
			<div id='login-errors'></div>
			<div class='buttons padded align-center'>
				<div class='login-extras'>
					<div><label for='r'>Remember me</label><input id='remember_input' type='checkbox' name='r' value='1'></div>
					<a class='forgot-password' data-show='#pwrecoverypopup' data-hide='#loginpopup'>Forgot password?</a>
				</div>
				<input type='submit' value='Log In' class='button blue-hover block' type='submit' />
			</div>
		</form>");

GlobalUtils::createPopup('pwrecoverypopup', 'Recover Password', "
		<form class='vertical' method='post' action='/recover.php' id='pwrecoveryform'>
			<input id='recovery-email' type='text' name='e' placeholder='Email'>
			<div class='verify marker nomark' data-err-popup ></div><div class='popup-offset'><div class='error-popup' id='recovery-email-popup'></div></div>
			<div class='buttons padded align-center'>
				<input type='submit' value='Submit' class='button blue-hover block' type='submit' />
			</div>
		</form>");
}

//Display any error message.
if($_SESSION['error_msg'])
{
	$error = $_SESSION['error_msg'];
	unset($_SESSION['error_msg']);
	
	GlobalUtils::createPopupVisible("errorpopup", "Oops!", "<span class='popup-small'>$error</span>");
}
//Display any popup message.
if($_SESSION['popup_msg'])
{
	$popup = $_SESSION['popup_msg'];
	unset($_SESSION['popup_msg']);

	GlobalUtils::createPopupVisible("infopopup", "", "<span class='popup-small'>$popup</span>");
}
?>

<div class="navigation-bar">
	<div class="grid wrap wider no-gutters">
		<div class="pull-left">
			<ul class="navigation-items">
				<li><a href="/" class="logo"><img src='/images/icons/logotextwhite.png' alt='Relatables / Am I The Only One?' title='Relatables / Am I The Only One?'></a></li>
				<li><a href="/moderate">Moderate</a></li>
				<li>
					<a href="/about" data-togg="#about-drop">About</a>
					<ul id="about-drop" class='dropdown'>
						<li><a href="/about/faq">FAQ</a></li>
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
					echo '"></div></a><div id="notif-drop" class="dropdown notifications-dropdown">';


					while($notification = mysqli_fetch_array($notifications))
					{
						echo '<a href="/readmessage.php?id='. $notification['id'] . '&redirect=' . htmlspecialchars($notification['href']) . '">';
						if(!$notification['seen'])
							echo '<div class="indicator"></div>';
						echo $notification['message'] . '<span>' . $notification['fdate'] . '</span></a>';
					}

					echo '</div></li>';

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
