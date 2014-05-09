<?php

	function getLocationDropdown($id)
	{
		echo '<select data-type=\'location\' id=\'location-input\' name=\'location\' onChange=\'document.getElementById("type").value="location";this.form.submit();\' style=\'display:none;\'>';
			
		$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
		$countries = mysqli_query($connection,'SELECT country_id, short_name FROM countries');
		
		while($row = mysqli_fetch_array($countries))
		{
			if($row['country_id'] == $id)
				$selected = 'selected';
			else
				$selected = '';
			echo '<option value=\''.$row['country_id'].'\' '.$selected.'>'.$row['short_name'].'</option>';
		}
		
		echo '</select>';
	}