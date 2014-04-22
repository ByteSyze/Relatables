<?php  
	$connection = mysqli_connect("mysql25.freehostia.com","tylhac_aitoo","10102S33K3R17","tylhac_aitoo");

	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}	
	
	$submissions = mysqli_query($connection,"SELECT * FROM submissions WHERE pending = 1 ORDER BY submissions.date DESC LIMIT 0, 20");

?>
<html>
	<head>
		<title>Am I The Only One?</title>
		<link rel="shortcut icon" href="favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="../indextheme.css">
	</head>
	<body>
		<?php require("../toolbar.php"); ?>
		<div id='main'>
			<div style='float:left;width:600px;'>
				<?php
					while($row = mysqli_fetch_array($submissions))
					{
						echo 
						"<div class='dialogue' id='" . $row["id"] . "'>
							<p class='dialogue'>" . $row["submission"] . "</p>
							<button class='dialoguebutton' onclick='vote(na" . $row["id"] . ")'><label id='na" . $row["id"] . "'>" . $row["notalone"] . "</label> - I do this, too!</button>
							<button class='dialoguebutton' onclick='vote(a" . $row["id"] . ")'><label id='a" . $row["id"] . "'>" . $row["alone"] . "</label> - You're alone.</button>
							<div style='text-align:right;'>
								<label>Submitted by: " . $row["username"] . " on " . $row["date"] . "</label>
							</div>
						</div>";
					}
				?>	
			</div>		
			
			<?php include("../adcontent.html"); ?>
			
		</div>
	</body>
</html>