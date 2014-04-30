function editLocation()
{
	var location = document.getElementById('location');
	var locationForm = document.getElementById('location-form');
	
	if(location.style.display == 'none')
	{
		location.style.display = 'block';
		locationForm.style.display = 'none';
	}
	else
	{
		location.style.display = 'none';
		locationForm.style.display = 'block';
	}
}