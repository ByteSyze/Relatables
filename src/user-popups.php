<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	if($_SESSION['username'] == null)
		return;
?>
<table class='profile' id='profile-dropdown'>
	<tr><td><a class='profile' href='http://www.relatablez.com/user/<?php echo $_SESSION['username']; ?>'>Profile</a></td></tr>
	<tr><td><a class='profile' href='http://www.relatablez.com/settings/profile'>Settings</a></td></tr>
	<tr><td><a class='profile' href='http://www.relatablez.com/signout.php'>Sign Out</a></td></tr>
</table>
<div id='notification-dropdown'>
	<table class='notifications' >
		<?php
			while($notification = mysqli_fetch_array($notifications))
			{
				$sender = getUsername($connection, $notification['sid']);
				echo '<tr class=\'notification-header\'>';
				if(!$notification['seen'])
					echo '<td><div class=\'unread\'></div></td>';
				else
					echo '<td><div class=\'read\'></div></td>';
				echo '<td class=\'notification-subject\'>'.$notification['subject'].'</td>';
				echo '</tr><tr>';
				echo '<td colspan=\'2\'>'.$notification['message'].'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class=\'notification-date\'>'.$notification['fdate'].'</td>';
				echo '<td class=\'notification-sender\'>'.$sender.'</td>';
				echo '</tr><tr><td colspan=\'2\'><hr style=\'margin:0px\'></td></tr>';
			}
		?>
	</table>
</div>