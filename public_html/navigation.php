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
		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="136px" height="30px" viewBox="0 0 136 30" enable-background="new 0 0 136 30" xml:space="preserve">
			<g>
				<path fill="#FFFFFF" d="M0.079,6.671C1.622,6.419,3.89,6.229,6.472,6.229c3.149,0,5.354,0.473,6.866,1.669
					c1.291,1.008,1.984,2.52,1.984,4.472c0,2.677-1.921,4.535-3.748,5.196v0.095c1.48,0.599,2.299,1.984,2.834,3.937
					c0.662,2.426,1.292,5.197,1.701,6.016h-4.913c-0.315-0.63-0.851-2.33-1.449-4.944c-0.598-2.677-1.512-3.37-3.496-3.401H4.834v8.346
					H0.079V6.671z M4.834,15.804h1.89c2.394,0,3.811-1.196,3.811-3.055c0-1.921-1.323-2.929-3.527-2.929
					c-1.166,0-1.827,0.063-2.173,0.157V15.804z"/>
				<path fill="#FFFFFF" d="M21.334,21.441c0.126,1.984,2.11,2.929,4.346,2.929c1.638,0,2.96-0.221,4.251-0.661l0.63,3.244
					c-1.574,0.661-3.496,0.976-5.574,0.976c-5.228,0-8.22-3.023-8.22-7.842c0-3.905,2.425-8.22,7.779-8.22
					c4.976,0,6.866,3.874,6.866,7.685c0,0.818-0.095,1.543-0.157,1.89H21.334z M26.972,18.166c0-1.165-0.504-3.118-2.709-3.118
					c-2.016,0-2.834,1.858-2.96,3.118H26.972z"/>
				<path fill="#FFFFFF" d="M33.569,5.253h4.787v22.361h-4.787V5.253z"/>
				<path fill="#FFFFFF" d="M45.314,27.961c-3.118,0-4.977-2.268-4.977-4.725c0-3.999,3.591-5.89,9.039-5.89v-0.188
					c0-0.851-0.441-2.016-2.803-2.016c-1.575,0-3.244,0.535-4.252,1.165l-0.882-3.086c1.071-0.599,3.181-1.386,5.984-1.386
					c5.133,0,6.74,3.023,6.74,6.677v5.385c0,1.449,0.063,2.866,0.252,3.717 M49.534,20.339c-2.52,0-4.472,0.599-4.472,2.426
					c0,1.228,0.818,1.826,1.89,1.826c1.165,0,2.173-0.787,2.488-1.764c0.063-0.252,0.094-0.535,0.094-0.818V20.339z"/>
				<path fill="#FFFFFF" d="M62.252,8.057v4.157h3.433v3.527h-3.433v5.574c0,1.858,0.472,2.709,1.89,2.709
					c0.661,0,0.977-0.032,1.417-0.127l0.031,3.622c-0.599,0.221-1.764,0.409-3.086,0.409c-1.543,0-2.835-0.535-3.622-1.322
					c-0.882-0.913-1.323-2.394-1.323-4.566v-6.299h-2.047v-3.527h2.047V9.348L62.252,8.057z"/>
				<path fill="#FFFFFF" d="M71.705,27.961c-3.118,0-4.977-2.268-4.977-4.725c0-3.999,3.591-5.89,9.039-5.89v-0.188
					c0-0.851-0.44-2.016-2.803-2.016c-1.575,0-3.244,0.535-4.252,1.165l-0.882-3.086c1.07-0.599,3.181-1.386,5.984-1.386
					c5.133,0,6.739,3.023,6.739,6.677v5.385c0,1.449,0.063,2.866,0.252,3.717 M75.925,20.339c-2.52,0-4.472,0.599-4.472,2.426
					c0,1.228,0.818,1.826,1.89,1.826c1.165,0,2.173-0.787,2.488-1.764c0.063-0.252,0.094-0.535,0.094-0.818V20.339z"/>
				<path fill="#FFFFFF" d="M83.297,27.614c0.063-1.008,0.126-2.866,0.126-4.598V5.253h4.787v8.787h0.063
					c0.913-1.322,2.52-2.173,4.661-2.173c3.685,0,6.361,3.055,6.33,7.779c0,5.543-3.496,8.314-7.023,8.314 M88.21,21.063
					c0,0.314,0.031,0.599,0.095,0.851c0.315,1.26,1.417,2.268,2.803,2.268c2.048,0,3.308-1.575,3.308-4.347
					c0-2.394-1.071-4.283-3.308-4.283c-1.291,0-2.487,0.977-2.803,2.362c-0.063,0.283-0.095,0.567-0.095,0.882V21.063z"/>
				<path fill="#FFFFFF" d="M101.425,5.253h4.787v22.361h-4.787V5.253z"/>
				<path fill="#FFFFFF" d="M112.872,21.441c0.125,1.984,2.109,2.929,4.346,2.929c1.638,0,2.961-0.221,4.252-0.661l0.63,3.244
					c-1.575,0.661-3.496,0.976-5.575,0.976c-5.228,0-8.22-3.023-8.22-7.842c0-3.905,2.425-8.22,7.779-8.22
					c4.976,0,6.866,3.874,6.866,7.685c0,0.818-0.095,1.543-0.158,1.89H112.872z M118.509,18.166c0-1.165-0.504-3.118-2.708-3.118
					c-2.016,0-2.835,1.858-2.961,3.118H118.509z"/>
				<path fill="#FFFFFF" d="M124.959,23.457c0.882,0.535,2.708,1.134,4.126,1.134c1.448,0,2.047-0.473,2.047-1.26
					c0-0.818-0.473-1.197-2.236-1.795c-3.212-1.071-4.44-2.803-4.409-4.63c0-2.897,2.457-5.07,6.268-5.07
					c1.795,0,3.37,0.44,4.314,0.913l-0.818,3.307c-0.693-0.378-2.048-0.882-3.339-0.882c-1.165,0-1.826,0.473-1.826,1.229
					s0.598,1.134,2.488,1.795c2.929,1.008,4.125,2.52,4.156,4.756c0,2.897-2.235,5.008-6.645,5.008c-2.016,0-3.811-0.473-4.977-1.103
					L124.959,23.457z"/>
			</g>
			<text transform="matrix(1 0 0 1 110.041 8.7295)" fill="#FFFFFF" font-family="'Arial'" font-size="9">BETA</text>
		</svg>
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
		  </ul>
		</li>
	  </ul>
	  <ul class="nav navbar-nav navbar-right">
		<?php if($_SESSION['id'] == null) : ?>
		<li><a href="#" data-toggle='modal' data-target='#loginpopup'>Log In</a></li>
		<li><a href="#" data-toggle='modal' data-target='#registerpopup'>Sign Up</a></li>
		<?php else : ?>
		<li class='dropdown'>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="icon notifications-icon"></div></a>
			<ul id="notif-drop"  class="dropdown-menu notifications-dropdown">
				<!-- /////////////////// TODO -->
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
				?>
				
			</ul>
		</li>
		<li class='dropdown'>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="icon profile-icon"></div></a>
			<ul id="prof-drop"  class="dropdown-menu">
				<li><a href="/user/<?php echo GlobalUtils::$user->getUsername(); ?>">Profile</a></li><li><a href="/settings/account">Settings</a></li>
				<li><a href="/signout.php">Signout</a></li>
			</ul>
		</li>
		<?php endif; ?>
	  </ul>
	</div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
