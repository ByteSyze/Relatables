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
				GlobalUtils::$user = $cUser;

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
//Display any error message.
if(!empty($_SESSION['error_msg']))
{
	$error = $_SESSION['error_msg'];
	unset($_SESSION['error_msg']);
	
	GlobalUtils::createPopupVisible("errorpopup", "Oops!", "<span class='popup-small'>$error</span>");
}
//Display any popup message.
if(!empty($_SESSION['popup_msg']))
{
	$popup = $_SESSION['popup_msg'];
	unset($_SESSION['popup_msg']);

	GlobalUtils::createPopupVisible("infopopup", "", "<span class='popup-small'>$popup</span>");
}
?>

<?php if($_SESSION['id'] == null) : ?>

<div class='modal fade' role='dialog' id='registerpopup' >
	<div class="modal-dialog">
		<div class='modal-content'>
			<div class='modal-body'>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div id="register-popup-content">
					<h1 class='popup-title'>Sign Up</h1>
					<h6>If you already have an account, <a href='#' role='button' data-toggle='modal' data-target='#registerpopup, #loginpopup'>Log In</a></h6>
					<div style='text-align:center;margin:auto;width:100%;'>
						<div>
							<form method='post'>
								<div class='form-group has-feedback'>
									<input id='user_input' class='form-control' type='text' name='username' placeholder='Username' data-toggle="popover" data-placement="bottom" data-content='Usernames must be 3-16 characters long. They can only consist of alphanumerical characters (a-z, 0-9)'>
									<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
									<div class='popup-offset'><div class='error-popup' id='username-popup'></div></div>
								</div>
								<div class='form-group has-feedback'>
									<input id='pass_input' class='form-control' type='password' autocomplete='off' name='password' placeholder='Password' data-toggle="popover" data-placement="bottom" data-content='Password must be atleast 6 characters long. There are no limitations on which characters you can use.'>
									<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
									<div class='popup-offset'><div class='error-popup' id='new-password-popup'></div></div>
								</div>
								<div class='form-group has-feedback'>
									<input id='repass_input' class='form-control' type='password' name='repassword' placeholder='Confirm Password'>
									<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
									<div class='popup-offset'><div class='error-popup' id='renew-password-popup'></div></div>
								</div>
								<div class='form-group has-feedback'>
									<input id='email_input' class='form-control' type='email' name='email' placeholder='Email'>
									<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
									<div class='popup-offset'><div class='error-popup' id='email-popup'></div></div>
								</div>
								<span style='font-size:10px'>By clicking Sign Up, you agree to our <a href='/about/terms'>Terms & Conditions</a>.</span>
								<div style='padding-top:10px' class='buttons'>
									<input id='registerbutton' class='button blue-hover block' type='submit' value='Sign Up'>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='modal fade' role='dialog' id='loginpopup' >
	<div class="modal-dialog">
		<div class='modal-content'>
			<div class='modal-body'>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h1 class='popup-title'>Log In</h1>
				<h6>If you don't have an account, <a  href='#' role='button' data-toggle='modal' data-target='#loginpopup, #registerpopup'>Sign up</a></h6>
				<form method='post' action='javascript:login();'>
					<div class='form-group'>
						<input id='login_user_input' type='text' class='form-control' name='u' placeholder='Username'>
					</div>
					<div class='form-group'>
						<input id='login_pass_input' type='password' class='form-control' name='p' placeholder='Password'>
					</div>
					<div id='login-errors'></div>
					<div class='login-extras'>
						<div><label for='r'>Remember me</label><input id='remember_input' type='checkbox' name='r' value='1'></div>
						<a class='forgot-password' role='button' data-toggle='modal' data-target='#pwrecoverypopup, #loginpopup'>Forgot password?</a>
					</div>
					<input type='submit' value='Log In' class='button blue-hover block' type='submit' />
				</form>
			</div>
		</div>
	</div>
</div>

<div class='modal fade' role='dialog' id='pwrecoverypopup' >
	<div class="modal-dialog">
		<div class='modal-content'>
			<div class='modal-body'>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h1 class='popup-title'>Recover Password</h1>
				<form class='vertical' method='post' action='/recover.php' id='pwrecoveryform'>
					<div class='form-group has-feedback'>
						<input id='recovery-email' class='form-control' type='text' name='e' placeholder='Email'>
						<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
					</div>
					<div class='popup-offset'><div class='error-popup' id='recovery-email-popup'></div></div>
					<div class='buttons padded align-center'>
						<input type='submit' value='Submit' class='button blue-hover block' type='submit' />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php endif; ?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class='navbar-brand' href='/'>
		<img src='/images/icons/navbar-logo.svg' alt='Relatables Beta' />
	  </a>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="navbar-collapse">
	  <ul class="nav navbar-nav">
		<li><a href="/moderate">Moderate</a></li>
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About <span class="caret"></span></a>
		  <ul class="dropdown-menu">
			<li><a href="/about/faq">FAQ</a></li>
			<li><a href="/about/blog">Blog</a></li>
			<li><a href="/about/privacy">Privacy</a></li>
			<li><a href="/about/terms">Terms</a></li>
			<li><a href="/contact">Contact</a></li>
		  </ul>
		</li>
	  </ul>
	  <ul class="nav navbar-nav navbar-right">
		<?php if($_SESSION['id'] == null) : ?>
		<li><a href="#" data-toggle='modal' data-target='#loginpopup'>Log In</a></li>
		<li><a href="#" data-toggle='modal' data-target='#registerpopup'>Sign Up</a></li>
		<?php else : ?>
		<li class='dropdown'>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="icon glyphicon glyphicon-bell <?php if($unreadNotifications) echo 'highlight'; ?> "></div></a>
			<ul id="notif-drop"  class="dropdown-menu notifications-dropdown">
				<?php
					while($notification = mysqli_fetch_array($notifications)) :
				?>
				
				<li>
					<a href="/readmessage.php?id=<?php echo $notification['id'] ?>&redirect=<?php echo htmlspecialchars($notification['href']) ?>">
						<?php if(!$notification['seen']) : ?>
						
						<div class="indicator"></div>
						<?php 
							endif;
							echo $notification['message'];
						?>
						
						<span><?php echo $notification['fdate'] ?></span>
					</a>
				</li>
				<?php
					endwhile;
					
					if($notifications->num_rows == 0) :
				?>
				<li><h5>No Messages :(</h5></li>
				
				<?php endif; ?>
				
			</ul>
		</li>
		<li class='dropdown'>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="icon glyphicon glyphicon-user"></div></a>
			<ul id="prof-drop"  class="dropdown-menu">
				<li><a href="/user/<?php echo GlobalUtils::$user->getUsername(); ?>">Profile</a></li><li><a href="/settings/account">Settings</a></li>
				<li><a href="/signout.php">Signout</a></li>
			</ul>
		</li>
		<?php endif; ?>
	  </ul>
	</div>
  </div>
</nav>
