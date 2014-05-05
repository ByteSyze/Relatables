/*Copyright (C) Tyler Hackett 2014*/
function edit(name)
{
	var element = document.getElementById(name);
	var elementForm = document.getElementById(name+'-form');
	var elementButton = document.getElementById(name+'-button');
	
	if(elementButton.innerHTML == 'Cancel')
	{
		element.style.display = 'block';
		elementForm.style.display = 'none';
		elementButton.innerHTML = 'Edit';
	}
	else
	{
		element.style.display = 'none';
		elementForm.style.display = 'block';
		elementButton.innerHTML = 'Cancel';
	}
}

function keyPressed(element, event)
{
	var keyCode = ('which' in event) ? event.which : event.keyCode;
	
	if(keyCode == 13)
		element.form.submit();
}