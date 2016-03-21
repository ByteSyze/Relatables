<?php
	/*Copyright (C) Tyler Hacket 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/user.php';
	
	class Post
	{
		private static $connection; //Connection to MySQL database.
		
		private $id;			/**ID of this post. Only valid if it exists in database.*/
		private $uid; 			/**ID of the user that submitted this post.*/
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
		private $comment_count; /**Number of comments on this post*/
		private $user_vote;		/**The vote of the current session's user on this post.*/
		
		private $author;		/**The User that made this post.*/
		
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
					$this->id = $id;
					
					if($statement = self::$connection->prepare("SELECT uid, verification, category, DATE_FORMAT(date,'%M %d, %Y'), (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date))/60, (SELECT count(CASE WHEN alone THEN 1 END) FROM related WHERE pid=submissions.id), (SELECT count(CASE WHEN NOT alone THEN 1 END) FROM related WHERE pid=submissions.id), pending, submission, anonymous, (SELECT COUNT(cid) FROM comments WHERE pid=submissions.id AND rid=0), (SELECT alone FROM related WHERE uid=" . GlobalUtils::$user->getID() . " AND pid=submissions.id) FROM submissions WHERE id=(?)"))
					{
						$statement->bind_param('i', $id);
						$statement->execute();
						
						$statement->bind_result($this->uid,$this->verification,$this->category,$this->fdate,$this->date_diff,$this->alone,$this->notalone,$this->pending,$this->submission,$this->anonymous,$this->comment_count,$this->user_vote);
						$statement->fetch();
						
						
						$this->author = new User($this->uid);
					}
					else
						echo self::$connection->error;
				}
			}
			$this->submission = htmlspecialchars($this->submission);
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
		
		public function getAuthor()
		{
			return $this->author;
		}
		
		private function calculateDateDifference()
		{
			if($this->date_diff/60/24/365 == 1)
				$date_diff = '(' . floor($this->date_diff/60/24/365) . ' year ago)';
			else if($this->date_diff/60/24/365 > 1)
				$date_diff = '(' . floor($this->date_diff/60/24/365) . ' years ago)'; 
			else if($this->date_diff/60/24 == 1)
				$date_diff = '(' . floor($this->date_diff/60/24) . ' day ago)'; 
			else if($this->date_diff/60/24 > 1)
				$date_diff = '(' . floor($this->date_diff/60/24) . ' days ago)'; 
			else if($this->date_diff/60 == 1)
				$date_diff = '(' . floor($this->date_diff/60) . ' hour ago)'; 
			else if($this->date_diff/60 > 1)
				$date_diff = '(' . floor($this->date_diff/60) . ' hours ago)'; 
			else if($this->date_diff == 1)
				$date_diff = '(' . floor($this->date_diff) . ' minute ago)';
			else
				$date_diff = '(' . floor($this->date_diff) . ' minutes ago)';
				
			return $date_diff;
		}
		
		/**Prints a formatted AITOO post.*/
		public function format()
		{
			$author = $this->author;
			
			$format_date_diff = $this->calculateDateDifference();
			$format_user = $this->anonymous ? 'Anonymous' : $author->getUsername();
			
			include $_SERVER['DOCUMENT_ROOT']."/post.html";
		}
		
		//Convert a numerical code to MYSQL syntax for ordering a query.
		public static function order2mysql($order)
		{
			switch($order)
			{
				case '0':
					return 'ORDER BY submissions.date DESC';
				case '1':
					return 'ORDER BY submissions.date ASC';
				case '2':
					return 'ORDER BY (SELECT COUNT(*) FROM related WHERE pid=submissions.id AND alone=0) DESC';
				case '3':
					return 'ORDER BY (SELECT COUNT(*) FROM related WHERE pid=submissions.id AND alone=1) DESC';
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
		
		//Convert a numerical code to MYSQL syntax for including reported posts.
		public static function report2mysql($reported)
		{
			if($reported)
				return '';
			else
				return 'AND reported < 1';
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
		public static function getPosts($index = 0, $count = 20, $order = 1, $category = 0, $nsfw = 0, $reported = 0)
		{
			if(self::$connection == null)
				self::$connection = GlobalUtils::getConnection();
				
			$count += $index;
			
			$order 		= self::order2mysql($order);
			$category 	= self::cat2mysql($category);
			$nsfw 		= self::nsfw2mysql($nsfw);
			$reported	= self::report2mysql($reported);
			
			$posts = array();
			$p_data = array();
			
			if($statement = self::$connection->prepare("SELECT id, uid, verification, category, DATE_FORMAT(date,'%M %d, %Y'), (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date))/60,  (SELECT count(CASE WHEN alone THEN 1 END) FROM related WHERE pid=submissions.id), (SELECT count(CASE WHEN NOT alone THEN 1 END) FROM related WHERE pid=submissions.id), pending, submission, anonymous, (SELECT COUNT(cid) FROM comments WHERE pid=submissions.id), (SELECT alone FROM related WHERE uid=" . intval(GlobalUtils::$user->getID()) . " AND pid=submissions.id) FROM submissions  WHERE pending = 0 $nsfw $category $reported $order LIMIT ?, ?"))
			{
				$statement->bind_param('ii', $index, $count);
				$statement->execute();
				
				$statement->bind_result($p_data['id'], $p_data['uid'],$p_data['verification'],$p_data['category'],$p_data['fdate'],$p_data['date_diff'],$p_data['alone'],$p_data['notalone'],$p_data['pending'],$p_data['submission'],$p_data['anonymous'],$p_data['comment_count'],$p_data['user_vote']);
				
				while($statement->fetch())
				{
					$p_data['author'] = new User($p_data['uid']);
					array_push($posts, new Post($p_data));
				}
			}
			else
				echo self::$connection->error;
			
			return $posts;
		}
		
		
		
		public static function getRelated($uid, $index = 0, $count = 5)
		{
			if(self::$connection == null)
				self::$connection = GlobalUtils::getConnection();
				
			$index *= $count;
			$count += $index + 1; //Grab one extra, to determine whether more posts exist.
			
			if($statement = self::$connection->prepare("SELECT pid FROM related WHERE related.uid=(?) AND related.alone=0 LIMIT ?,?"))
			{	
				$statement->bind_param('iii', $uid, $index, $count);
				$statement->execute();
				
				$posts = array();
				
				$statement->store_result();
				$statement->bind_result($id);
				
				while($statement->fetch())
					array_push($posts, new Post($id));
					
				$statement->free_result();
				
				return $posts;
			}
		}
		
	}
?>
