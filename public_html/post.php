<?php
	class Post
	{
		private static $connection; //Connection to MySQL database.
		
		private $id;			/**ID of this post. Only valid if it exists in database.*/
		private $uid; 			/**ID of the user that submitted this post.*/
		private $username; 		/**Username of the user that submitted this post.*/
		private $verification;	/**4-byte verification code associated with this post.*/
		private $category; 		/**numerical representation of this post's category.*/
		private $fdate; 		/**Formatted date this post was submitted on.*/
		private $date_diff;		/**Time (in minutes) since this post was created.*/
		private $alone; 		/**Number of "alone" votes.*/
		private $notalone; 		/**Number of "not alone" votes.*/
		private $pending;		/**Whether or not this post is pending approval.*/
		private $submission;	/**Question submitted by user.*/
		private $anonymous;		/**Whether or not to display the user as anonymous.*/
		private $nsfw;			/**Whether or not this post is not safe for work.*/
		private $admin;			/**Whether or not the author is an admin.*/
		private $comment_count; /**Number of comments on this post*/
		private $user_vote;		/**The vote of the current session's user on this post.*/
		
		/**
		 * Creates a new Post.
		 * 
		 * if a post ID is passed in, the post will be populated with the data of the corresponding ID.
		 * */
		public function __construct()
		{
			if(self::$connection == null)
				self::$connection = GlobalUtils::getConnection();
			
			if(func_num_args()>0)//If an anything is passed in, treat it as a post ID
			{
				$id = func_get_arg(0);
				if($statement = self::$connection->prepare("SELECT (SELECT username FROM accounts where id=uid), verification, category, DATE_FORMAT(date,'%M %d, %Y'), (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date))/60, alone, notalone, pending, submission, anonymous, (SELECT admin FROM accounts WHERE id=submissions.uid), (SELECT COUNT(cid) FROM comments WHERE pid=(?) AND rid=0), (SELECT alone FROM related WHERE uid={$_SESSION['id']} AND pid=(?)) FROM submissions WHERE id=(?)"))
				{
					$statement->bind_param('iii', $id, $id, $id);
					$statement->execute();
					
					$statement->bind_result($this->username,$this->verification,$this->category,$this->fdate,$this->date_diff,$this->alone,$this->notalone,$this->pending,$this->submission,$this->anonymous,$this->admin,$this->comment_count,$this->user_vote);
					$statement->fetch();
				}
			}
		}
		
		private function calculateDateDifference()
		{
			if($this->date_diff/60/24/365 >= 1)
				$date_diff = '(' . floor($this->date_diff/60/24/365) . ' years ago)'; 
			else if($this->date_diff/60/24 >= 1)
				$date_diff = '(' . floor($this->date_diff/60/24) . ' days ago)'; 
			else if($this->date_diff/60 >= 1)
				$date_diff = '(' . floor($this->date_diff/60) . ' hours ago)'; 
			else
				$date_diff = '(' . floor($this->date_diff) . ' minutes ago)';
				
			return $date_diff;
		}
		
		/**Prints a formatted AITOO post.*/
		public function format()
		{	
			if($this->anonymous)
				$user='Anonymous';
			else
				$user = $this->username;
			
			echo "\r\n<div class='dialogue downpadding' id='{$this->id}' data-v='{$this->verification}'>";
			echo "\r\n<p class='dialogue'>{$this->submission}</p>";
			echo "\r\n<table class='vote-table'>";
			echo "\r\n<tr>";
			if($_SESSION["username"] != null)
			{
				if($this->user_vote === '0')
					echo "\r\n<td><button class='dialoguebutton' disabled>No, me too!</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton'>No, me too!</button></td>";
					
				if($this->user_vote === '1')
					echo "\r\n<td><button class='dialoguebutton' disabled>You're alone.</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton'>You're alone.</button></td>";
			}
			else
			{
				echo "\r\n<td><button class='dialoguebutton showreg' data-signup-header='Please sign up to vote'>No, me too!</button></td>";
				echo "\r\n<td><button class='dialoguebutton showreg' data-signup-header='Please sign up to vote'>You're alone</button></td>";				
			}
			echo "\r\n<td><a href='/post/{$this->id}'  target='_blank' class='comment-button hover-icon'></a></td>";
			//echo "\r\n<td><div class='share-button' data-share-button=''>Share »</div></td>";
			echo "\r\n<td>"; GlobalUtils::getShareButton("http://www.relatablez.com/post/{$this->id}", $this->submission); echo "</td>";
			echo "\r\n<tr>";
			echo "\r\n<td><span class='vote-counter' data-c='na'>(" . number_format($this->notalone) . ")</span></td>";
			echo "\r\n<td><span class='vote-counter' data-c='a'>(" . number_format($this->alone) . ")</span></td>";
			echo "\r\n</table>";
			echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
			
			if($this->anonymous)
				echo ' >' . $user . "</a> - $date_diff</span></div>";
			else
			{
				if($this->admin)
					echo 'class=\'admin\'';
				echo " href='/user/$user'>$user</a> $date_diff</span></div>";
			}
		}
	}
?>
