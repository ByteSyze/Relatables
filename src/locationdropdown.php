<?php
	echo 
	'<form method=\'GET\' action=\'http://relatablez.com/updatelocation.php\' style=\'display:none;\' id=\'location-form\'>
		<select name=\'location\' onChange=\'this.form.submit()\'>';
		
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	$countries = mysqli_query($connection,'SELECT country_id, short_name FROM countries');
	
	while($row = mysqli_fetch_array($countries))
	{
		echo '<option value=\''.$row['country_id'].'\'>'.$row['short_name'].'</option>';
	}
	
	echo
	'	</select>
	</form>';