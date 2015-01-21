<?php
	include 'post.php';
	
	$post = new Post(1);
?>
<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<?php $post->format(); ?>
	</body>
</html>