<?php

	function getLocationDropdown($id)
	{
		echo '<select data-type=\'location\' id=\'location-input\' name=\'location\'>';
			
		$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
		$countries = mysqli_query($connection,'SELECT country_id, short_name FROM countries');
		
		if($id === -1)
			echo '<option value=\'-1\'>Select a Country</option>';
		
		while($row = mysqli_fetch_array($countries))
		{
			if($row['country_id'] == $id)
				echo '<option value=\''.$row['country_id'].'\' selected>'.$row['short_name'].'</option>';
			else
				echo '<option value=\''.$row['country_id'].'\'>'.$row['short_name'].'</option>';			
		}
		
		echo '</select>';
	}