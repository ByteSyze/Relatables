function editLocation()
{
	var location = document.getElementById('location');
	var locationForm = document.getElementById('location-form');
	var locationButton = document.getElementById('location-button');
	
	if(locationButton.innerHTML == 'Cancel')
	{
		location.style.display = 'block';
		locationForm.style.display = 'none';
		locationButton.innerHTML = 'Edit';
	}
	else
	{
		location.style.display = 'none';
		locationForm.style.display = 'block';
		locationButton.innerHTML = 'Cancel';
	}
}
function editDescription()
{
	var description = document.getElementById('description');
	var descriptionForm = document.getElementById('description-form');
	var descriptionButton = document.getElementById('description-button');
	
	if(descriptionButton.innerHTML == 'Cancel')
	{
		description.style.display = 'block';
		descriptionForm.style.display = 'none';
		descriptionButton.innerHTML = 'Edit';
	}
	else
	{
		description.style.display = 'none';
		descriptionForm.style.display = 'block';
		descriptionButton.innerHTML = 'Cancel';
	}
}
function keyPressed(element, event)
{
	var keyCode = ('which' in event) ? event.which : event.keyCode;
	
	if(keyCode == 13)
		element.form.submit();
}