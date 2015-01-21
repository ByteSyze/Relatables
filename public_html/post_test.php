<?php
	include 'global.php';
	
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