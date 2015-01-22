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
		private $date_diff;		/**Time (in minutes) since this post was created.*/
		private $alone; 		/**Number of "alone" votes.*/
		private $notalone; 		/**Number of "not alone" votes.*/
		private $pending;		/**Whether or not this post is pending approval.*/
		private $submission;	/**Question submitted by user.*/
		private $anonymous;		/**Whether or not to display the user as anonymous.*/
		private $nsfw;			/**Whether or not this post is not safe for work.*/
		private $admin;			/**Whether or not the author is an admin.*/
		private $comment_count; /**Number of comments on this post*/
		
		/**
		 * Creates a new Post.
		 * 
		 * if a post ID is passed in, the post will be populated with the data of the corresponding ID.
		 * */
		public function __construct()
		{
			$this->connection = GlobalUtils::getConnection();
			
			if(func_num_args()>0)//If an anything is passed in, treat it as a post ID
			{
				$id = func_get_arg(0);
				if($statement = $this->connection->prepare("SELECT (SELECT username FROM accounts where id=uid), verification, category, DATE_FORMAT(date,'%M %d, %Y'), (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date))/60, alone, notalone, pending, submission, anonymous, (SELECT admin FROM accounts WHERE id=submissions.uid), (SELECT COUNT(cid) FROM comments WHERE pid=(?) AND rid=0) FROM submissions WHERE id=(?)"))
				{
					$statement->bind_param('ii', $id, $id);
					$statement->execute();
					
					$statement->bind_result($this->username,$this->verification,$this->category,$this->fdate,$this->date_diff,$this->alone,$this->notalone,$this->pending,$this->submission,$this->anonymous,$this->admin,$this->comment_count);
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
			echo "\r\n<div class='dialogue uppadding' id='{$this->id}' data-category='{$this->category}' data-nsfw='{$this->nsfw}' data-date='{$this->date_diff}'>";
			echo "\r\n<p class='dialogue'>{$this->submission}</p>";
			echo "\r\n<table class='vote-table'>";
			echo "\r\n<tr>";
			if($_SESSION["username"] != null)
			{
				if($post['user_vote'] === '0')
					echo "\r\n<td><button class='dialoguebutton' id='bna{$this->id}' data-vid='{$this->id}' data-v='{$this->verification}' disabled>No, me too!</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton' id='bna{$this->id}' data-vid='{$this->id}' data-v='{$this->verification}'>No, me too!</button></td>";
					
				if($post['user_vote'] === '1')
					echo "\r\n<td><button class='dialoguebutton' id='ba{$this->id}'  data-vid='{$this->id}' data-v='{$this->verification}' disabled>You're alone.</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton' id='ba{$this->id}'  data-vid='{$this->id}' data-v='{$this->verification}'>You're alone.</button></td>";
			}
			else
			{
				echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>No, me too!</button></td>";
				echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>You're alone.</button></td>";				
			}
			echo "\r\n<td><a href='http://www.relatablez.com/post/{$this->id}'  target='_blank' class='comment-button'></a></td>";
			echo "\r\n<td><div class='share-button' data-share-button=''>Share &raquo;</div></td>";
			echo "\r\n<tr>";
			echo "\r\n<td><span class='vote-counter' id='na{$this->id}'>(" . number_format($this->notalone) . ")</span></td>";
			echo "\r\n<td><span class='vote-counter' id='a{$this->id}'>(" . number_format($this->alone) . ")</span></td>";
			echo "\r\n</table>";
			echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
			
			if($this->anonymous)
				echo " >Anonymous</a> - " . $this->calculateDateDifference() . "</span></div>";
			else
			{
				if($this->admin)
					echo 'class=\'admin\'';
				echo " href='http://www.relatablez.com/user/" . $this->user . "'>" . $this->user</a> . " - " . $this->calculateDateDifference() . "</span></div>";
			}
			echo "\r\n</div>";
		}
	}
?>
