<?php
	/*Copyright (C) Tyler Hacket 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/user.php';
	
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
			
			if(func_num_args()>0)
			{
				if(is_array(func_get_arg(0)))
				{
					$post_data = func_get_arg(0);
					
					foreach($post_data as $variable => $data)
						$this->$variable = $data; //Taking advantage of PHP's crazy ass member access capabilities. Honestly, WTF?
				}
				else
				{
					$id = func_get_arg(0);
					
					if($statement = self::$connection->prepare("SELECT (SELECT username FROM accounts where id=uid), verification, category, DATE_FORMAT(date,'%M %d, %Y'), (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date))/60, alone, notalone, pending, submission, anonymous, (SELECT admin FROM accounts WHERE id=submissions.uid), (SELECT COUNT(cid) FROM comments WHERE pid=(?) AND rid=0), (SELECT alone FROM related WHERE uid=" . $_SESSION['user']->getID() . " AND pid=(?)) FROM submissions WHERE id=(?)"))
					{
						$statement->bind_param('iii', $id, $id, $id);
						$statement->execute();
						
						$statement->bind_result($this->username,$this->verification,$this->category,$this->fdate,$this->date_diff,$this->alone,$this->notalone,$this->pending,$this->submission,$this->anonymous,$this->admin,$this->comment_count,$this->user_vote);
						$statement->fetch();
					}
					else
						echo self::$connection->error;
				}
			}
		}
		
		/**
		*	Update this post's information in the database.
		* */
		public function update()
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
		
		public function getUsername()
		{
			return $this->username;
		}
		
		public function getVerification()
		{
			return $this->verification;
		}
		
		public function setVerification($verifictaion)
		{
			$this->verification = $verification;
		}
		
		public function getCategory()
		{
			return $this->category;
		}
		
		public function setCategory($category)
		{
			$this->category = $category;
		}
		
		public function getFormattedDate()
		{
			return $this->fdate;
		}
		
		public function setFormattedDate($fdate)
		{
			$this->fdate = $fdate;
		}
		
		public function getTimeDifference()
		{
			return $this->date_diff;
		}
		
		public function setTimeDifference($date_diff)
		{
			$this->date_diff = $date_diff;
		}
		
		public function getAlone()
		{
			return $this->alone;
		}
		
		public function setAlone($alone)
		{
			$this->alone = $alone;
		}
		
		public function getNotAlone()
		{
			return $this->notalone;
		}
		
		public function setNotAlone($notalone)
		{
			$this->notalone = $notalone;
		}
		
		public function getPending()
		{
			return $this->pending;
		}
		
		public function setPending($pending)
		{
			$this->pending = $pending;
		}
		
		public function getSubmission()
		{
			return $this->submission;
		}
		
		public function setSubmission($submission)
		{
			$this->submission = $submission;
		}
		
		public function getAnonymous()
		{
			return $this->anonymous;
		}
		
		public function setAnonymous($anonymous)
		{
			$this->anonymous = $anonymous;
		}
		
		public function getNSFW()
		{
			return $this->nsfw;
		}
		
		public function setNSFW($nsfw)
		{
			$this->nsfw = $nsfw;
		}
		
		public function getAuthorIsAdmin()
		{
			return $this->admin;
		}
		
		public function getCommentCount()
		{
			return $this->comment_count;
		}
		
		public function setCommentCount($comment_count)
		{
			$this->comment_count = $comment_count;
		}
		
		public function getCurrentUserVote()
		{
			return $this->user_vote;
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
			
			$format_date_diff = $this->calculateDateDifference();
			$format_user = $this->anonymous ? 'Anonymous' : $this->username;
			
			echo '<div class="box">';
				echo '<div class="box-content">';
					echo $this->submission;
					echo '<div class="post-actions">';
						echo '<div class="buttons">';

							$button_yes_classes = "green-hover";
							$button_yes_meta = "";
							$button_no_classes = "red-hover";
							$button_no_meta = "";

							if($_SESSION["username"] != null) {
									$button_yes_meta = $button_no_meta = 'id="bna' . $this->id . '" data-vid="' . $this->id . '" ';
									if($this->user_vote === '0') {
										$button_yes_classes = "green";
										$button_yes_meta .= "disabled";
									} else if($this->user_vote === '1') {
										$button_no_classes = "red";
										$button_no_meta .= "disabled";
									}
							} else {
								$button_no_meta = "data-signup-header='Please sign up to vote'";
								$button_yes_meta = "data-signup-header='Please sign up to vote'";
							}

							echo '<button class="button small ' . $button_yes_classes . '" ' . $button_yes_meta . '>No, me too!</button>';
							echo '<button class="button small ' . $button_no_classes . '" ' . $button_no_meta . '>No, me too!</button>';
							echo '<a href="/post/' . $this->id . '" class="button small">' . $this->comment_count . '</a>';

						echo '</div>';
						echo '<div class="submission-info">';
							if($this->anonymous) {
								echo '<span>' . $format_user . '</span>';
							} else {
								echo '<a class="user ';

								if($this->admin)
									echo 'admin';

								echo '" href="/user/' . $format_user . '">' . $format_user . '</a>';
							}

							echo '<span class="datediff">' . $format_date_diff . '</span>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
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
		public static function getPosts($index = 0, $count = 20, $order = 1, $category = 0, $nsfw = 0)
		{
			if(self::$connection == null)
				self::$connection = GlobalUtils::getConnection();
				
			$count += $index;
			
			$order 		= self::order2mysql($order);
			$category 	= self::cat2mysql($category);
			$nsfw 		= self::nsfw2mysql($nsfw);
			
			$posts = array();
			$p_data = array();
			
			$query = "SELECT (SELECT username FROM accounts where id=uid), verification, category, DATE_FORMAT(date,'%M %d, %Y'), (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date))/60, alone, notalone, pending, submission, anonymous, (SELECT admin FROM accounts WHERE id=submissions.uid), (SELECT COUNT(cid) FROM comments WHERE pid=submissions.id AND rid=0), (SELECT alone FROM related WHERE uid=" . $_SESSION['user']->getID() . " AND pid=submissions.id) FROM submissions  WHERE pending = 0 $nsfw $category $order LIMIT ?, ?";
			echo "Query: '$query'";
			if($statement = self::$connection->prepare($query))
			{
				$statement->bind_param('ii', $start, $count);
				$statement->execute();
				
				$statement->bind_result($p_data['username'],$p_data['verification'],$p_data['category'],$p_data['fdate'],$p_data['date_diff'],$p_data['alone'],$p_data['notalone'],$p_data['pending'],$p_data['submission'],$p_data['anonymous'],$p_data['admin'],$p_data['comment_count'],$p_data['user_vote']);
				
				while($statement->fetch())
					array_push($posts, new Post($p_data));
			}
			else
				echo self::$connection->error;
			
			return $posts;
		}
	}
?>
