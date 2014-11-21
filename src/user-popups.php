<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();

	function getPopup($notifications, $connection)
	{
		$notification_count = mysqli_num_rows($notifications);
		$i = 0;
		
		echo
"<table class='profile' id='profile-dropdown'>
<tr><td><a class='profile' href='http://www.relatablez.com/user/{$_SESSION['username']}'>Profile</a></td></tr>
<tr><td><a class='profile' href='http://www.relatablez.com/settings/profile'>Settings</a></td></tr>
<tr><td><a class='profile' href='http://www.relatablez.com/signout.php'>Sign Out</a></td></tr>
</table>
<div id='notification-dropdown'>
<table class='notifications' >
<tbody id='notification-tbody'>";
		if($notification_count < 1)
		{
			echo '<tr><td class=\'no-messages\'><b>You have no messages</b></td></tr>';
		}
		else
		{
			while($notification = mysqli_fetch_array($notifications))
			{
				$i++;
				$sender = getUsername($connection, $notification['sid']);
				echo '<tr data-nid=\''.$notification['id'].'\' class=\'notification-header\'>';
				if(!$notification['seen'])
					echo '<td><div id=\'ntf'.$notification['id'].'\' class=\'unread\'></div></td>';
				else
					echo '<td><div class=\'read\'></div></td>';
				echo '<td class=\'notification-subject\'>'.$notification['subject'].'<div class=\'notification-delete\' onclick=\'deleteMessage('.$notification['id'].')\'></div></td>';
				echo '</tr><tr data-nid=\''.$notification['id'].'\'>';
				echo '<td colspan=\'2\' id=\'ntfmsg'.$notification['id'].'\' onmouseover=\'updateMessageStatus('.$notification['id'].')\'>'.$notification['message'].'</td>';
				echo '</tr>';
				echo '<tr data-nid=\''.$notification['id'].'\'>';
				echo '<td class=\'notification-date\'>'.$notification['fdate'].'</td>';
				echo '<td class=\'notification-sender\'><a href=\'http://www.relatablez.com/user/'.$sender.'\'>'.$sender.'</a></td>';
				if($i < $notification_count)
					echo '</tr><tr data-nid=\''.$notification['id'].'\'><td colspan=\'2\'><hr style=\'margin:0px\'></td></tr>';
			}
		}
		
		echo "
</tbody>
</table>
</div>";
	}
?>