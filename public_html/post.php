<?php
	class Post
	{
		public static function getPost($id)
		{
			if($statement = $connection->prepare("SELECT (SELECT uid, username FROM accounts where id=uid), verification, category, DATE_FORMAT(date,'%M %d, %Y') AS date, alone, notalone, pending, submission, anonymous, (SELECT COUNT(cid) FROM comments WHERE pid=(?) AND rid=0) FROM submissions WHERE id=(?)"))
			{
				$statement->bind_param('ii', $id, $id);
				$statement->execute();
				
				$statement->bind_result($post['uid'], $post['user'], $post['verification'], $post['category'], $post['fdate'], $post['alone'], $post['notalone'], $post['pending'], $post['submission'], $post['anonymous'], $post['comment_count']);
				$statement->fetch();
			}
		}
		
		/**Prints a formatted AITOO post.*/
		public static function formatPost($post)
		{
			echo "\r\n<div class='dialogue uppadding' id='{$post['id']}' data-category='{$post['category']}' data-nsfw='{$post['nsfw']}' data-date='{$post['fdate']}'>";
			echo "\r\n<p class='dialogue'>{$post['submission']}</p>";
			echo "\r\n<table class='vote-table'>";
			echo "\r\n<tr>";
			if($_SESSION["username"] != null)
			{
				if($post['user_vote'] === '0')
					echo "\r\n<td><button class='dialoguebutton' id='bna{$post['id']}' data-vid='{$post['id']}' data-v='{$post['verification']}' disabled>No, me too!</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton' id='bna{$post['id']}' data-vid='{$post['id']}' data-v='{$post['verification']}'>No, me too!</button></td>";
					
				if($post['user_vote'] === '1')
					echo "\r\n<td><button class='dialoguebutton' id='ba{$post['id']}'  data-vid='{$post['id']}' data-v='{$post['verification']}' disabled>You're alone.</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton' id='ba{$post['id']}'  data-vid='{$post['id']}' data-v='{$post['verification']}'>You're alone.</button></td>";
			}
			else
			{
				echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>No, me too!</button></td>";
				echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>You're alone.</button></td>";				
			}
			echo "\r\n<td><a href='http://www.relatablez.com/post/{$post['id']}'  target='_blank' class='comment-button'></a></td>";
			echo "\r\n<td><div class='share-button' data-share-button=''>Share &raquo;</div></td>";
			echo "\r\n<tr>";
			echo "\r\n<td><span class='vote-counter' id='na{$post['id']}'>(" . number_format($post["notalone"]) . ")</span></td>";
			echo "\r\n<td><span class='vote-counter' id='a{$post['id']}'>(" . number_format($post["alone"]) . ")</span></td>";
			echo "\r\n</table>";
			echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
			
			if($post['anonymous'])
				echo " >Anonymous</a> - $post[fdate]</span></div>";
			else
			{
				if(isAdmin($connection, $post['uid']))
					echo 'class=\'admin\'';
				echo " href='http://www.relatablez.com/user/$user'>$user</a> - $post[fdate]</span></div>";
			}
			echo "\r\n</div>";
		}
	}
?>
