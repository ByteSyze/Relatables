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
		 * If a post ID is passed in, the post will be populated with the data of the corresponding ID.
		 *
		 * If an array is passed in, variables will start being filled with the data from the array.
		 * The array's keys must be the exact name of Post variables, else you'd best be ready for some bugs.
		 * */
		public function __construct()
		{
			if(self::$connection == null)
				self::$connection = GlobalUtils::getConnection();
			
			if(func_num_args()>0)//If an anything is passed in, treat it as a post ID
			{
				if(is_array(func_get_arg(0)))
				{
					$post_data = func_get_arg(0);
					foreach($post_data as $variable => $data)
					{
						$var = '$' . $variable;
						$this->$var = $data; //Taking advantage of PHP's crazy ass member access capabilities. Honestly, WTF?
					}
				}
				else
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
		}
		
		public function updateDB()
		{
			//TODO update the database's information for this post.
		}
		
		public function getID()
		{
			return $this->id;
		}
		
		public function getUID()
		{
			return $this->uid;
		}
		
		public function setUID($uid)
		{
			$this->uid = $uid;
		}
		public function getUsername()
		{
			return $this->username;
		}
		
		public function setusername($username)
		{
			$this->username = $username;
		}
		
		public function getVerification()
		{
			return $this->verification;
		}
		
		public function setVerification($verifictaion)
		{
			$this->verification = $verification;
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
			
			echo "\r\n<div class='dialogue downpadding' id='{$this->id}' data-v='{$this->verification}' data-d='{$this->date_diff}'>";
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
				echo ' >' . $user . "</a> - " . $this->calculateDateDifference() . "</span></div>";
			else
			{
				if($this->admin)
					echo 'class=\'admin\'';
				echo " href='/user/$user'>$user</a> " . $this->calculateDateDifference() . "</span></div>";
			}
		}
		
		//Convert a numerical code to MYSQL syntax for ordering a query.
		public static function order2mysql($order)
		{
			switch($order)
			{
				case '0':
					return 'ORDER BY submissions.date ASC';
				case '1':
					return 'ORDER BY submissions.date DESC';
				case '2':
					return 'ORDER BY submissions.notalone DESC';
				case '3':
					return 'ORDER BY submissions.alone DESC';
				default:
					return 'ORDER BY submissions.date DESC';
			}
		}
		
		//Convert a numerical code to MYSQL syntax for selecting a submission category.
		public static function cat2mysql($cat)
		{
			if($cat >= 1 && $cat <= 100)
				return 'AND category = '.$cat;
			else
				return '';
		}
		
		//Convert a numerical code to MYSQL syntax for including NSFW posts.
		public static function nsfw2mysql($nsfw)
		{
			if($nsfw)
				return '';
			else
				return 'AND nsfw=0';
		}
		
		/**
		*	Returns an array of Posts.
		*	The default parameters will grab the latest 20 non-NSFW posts from any category.
		*
		*	@param		$index		the index of the query to grab posts from
		*	@param		$count		the number of posts to grab
		*	@param		$order		the order to query posts by. The value will be passed to Post::order2mysql()
		*	@param		$category	the specified category to filter posts by. The value will be passed to Post::cat2mysql()
		*	@param		$nsfw		whether or not to include NSFW posts. This does not exclusively grab NSFW posts.
		* */
		public static function getPosts($index = 0, $count = 20, $order = 0, $category = 0, $nsfw = 0)
		{
			$count += $index;
			
			$order 		= self::order2mysql($order);
			$category 	= self::cat2mysql($category);
			$nsfw 		= self::nsfw2mysql($nsfw);
			
			$posts = array();
			$p_data = array();
			
			if($statement = self::$connection->prepare("SELECT (SELECT username FROM accounts where id=uid), verification, category, DATE_FORMAT(date,'%M %d, %Y'), (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date))/60, alone, notalone, pending, submission, anonymous, (SELECT admin FROM accounts WHERE id=submissions.uid), (SELECT COUNT(cid) FROM comments WHERE pid=(?) AND rid=0), (SELECT alone FROM related WHERE uid={$_SESSION['id']} AND pid=(?)) FROM submissions  WHERE pending = 0 $nsfw $category $order LIMIT ?, ?"))
			{
				$statement->bind_param('ii', $start, $count);
				$statement->execute();
				
				$statement->bind_result($p_data['username'],$p_data['verification'],$p_data['category'],$p_data['fdate'],$p_data['date_diff'],$p_data['alone'],$p_data['notalone'],$p_data['pending'],$p_data['submission'],$p_data['anonymous'],$p_data['admin'],$p_data['comment_count'],$p_data['user_vote']);
				
				while($statement->fetch())
					array_push($posts, new Post($p_data));
			}
			
			return $posts;
		}
	}
	
	new Post(0); //Initialize $connection
	$posts = Post::getPosts();
	
	foreach($posts as $post)
		$post->format();
?>
