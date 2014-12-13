<?php
	class Post
	{
		private $connection; //Connection to MySQL database.
		
		private $id;			/**ID of this post. Only valid if it exists in database.*/
		private $uid; 			/**ID of the user that submitted this post.*/
		private $username; 		/**Username of the user that submitted this post.*/
		private $verification;	/**4-byte verification code associated with this post.*/
		private $category; 		/**numerical representation of this post's category.*/
		private $fdate; 		/**Formatted date this post was submitted on.*/
		private $alone; 		/**Number of "alone" votes.*/
		private $notalone; 		/**Number of "not alone" votes.*/
		private $pending;		/**Whether or not this post is pending approval.*/
		private $submission;	/**Question submitted by user.*/
		private $anonymous;		/**Whether or not to display the user as anonymous.*/
		private $nsfw;			/**Whether or not this post is not safe for work.*/
		
		/**
		 * Creates a new Post.
		 * 
		 * if a post ID is passed in, the post will be populated with the data of the corresponding ID.
		 * */
		public function __construct()
		{
			$connection = GlobalUtils::getConnection;
			
			if(func_num_args()>0)//If an anything is passed in, treat it as a post ID
			{
				$id = func_get_arg(0);
				if($statement = $connection->prepare("SELECT (SELECT uid, username FROM accounts where id=uid), verification, category, DATE_FORMAT(date,'%M %d, %Y') AS date, alone, notalone, pending, submission, anonymous, (SELECT COUNT(cid) FROM comments WHERE pid=(?) AND rid=0) FROM submissions WHERE id=(?)"))
				{
					$statement->bind_param('ii', $id, $id);
					$statement->execute();
					
					$statement->bind_result($uid,$username,verification,$category,$fdate,$alone,$notalone,$pending,$submission,$anonymous,$comment_count);
					$statement->fetch();
				}
			}
		}
		
		/**Prints a formatted AITOO post.*/
		public function formatPost()
		{
			echo "\r\n<div class='dialogue uppadding' id='{$id}' data-category='{$category}' data-nsfw='{$nsfw}' data-date='{$fdate}'>";
			echo "\r\n<p class='dialogue'>{$submission}</p>";
			echo "\r\n<table class='vote-table'>";
			echo "\r\n<tr>";
			if($_SESSION["username"] != null)
			{
				if($post['user_vote'] === '0')
					echo "\r\n<td><button class='dialoguebutton' id='bna{$id}' data-vid='{$id}' data-v='{$verification}' disabled>No, me too!</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton' id='bna{$id}' data-vid='{$id}' data-v='{$verification}'>No, me too!</button></td>";
					
				if($post['user_vote'] === '1')
					echo "\r\n<td><button class='dialoguebutton' id='ba{$id}'  data-vid='{$id}' data-v='{$verification}' disabled>You're alone.</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton' id='ba{$id}'  data-vid='{$id}' data-v='{$verification}'>You're alone.</button></td>";
			}
			else
			{
				echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>No, me too!</button></td>";
				echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>You're alone.</button></td>";				
			}
			echo "\r\n<td><a href='http://www.relatablez.com/post/{$id}'  target='_blank' class='comment-button'></a></td>";
			echo "\r\n<td><div class='share-button' data-share-button=''>Share &raquo;</div></td>";
			echo "\r\n<tr>";
			echo "\r\n<td><span class='vote-counter' id='na{$id}'>(" . number_format($notalone) . ")</span></td>";
			echo "\r\n<td><span class='vote-counter' id='a{$id}'>(" . number_format($alone) . ")</span></td>";
			echo "\r\n</table>";
			echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
			
			if($anonymous)
				echo " >Anonymous</a> - $fdate</span></div>";
			else
			{
				if(isAdmin($connection, $post['uid']))
					echo 'class=\'admin\'';
				echo " href='http://www.relatablez.com/user/$user'>$user</a> - $fdate</span></div>";
			}
			echo "\r\n</div>";
		}
	}
?>
