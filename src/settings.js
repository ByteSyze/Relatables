/*Copyright (C) Tyler Hackett 2014*/

var descriptionCounter = document.getElementById('desc_char_counter');

function hideDeletePopup()
{
	document.getElementById('delete-account-popup').style.display = 'none';
}

function showDeletePopup()
{
	document.getElementById('delete-account-popup').style.display = 'block';
}

function charCount(element,counter)
{
	counter.innerHTML = '(' + (130-element.value.length) + ' characters left)';
}

function checkLimit(event, element, limit, substr)
{
	var remaining = limit - element.value.length;
	
	if(remaining < 0)
	{
		if(substr) //Use substring method for refusing further input
			element.value = element.value.substring(0,limit);
		else
			event.preventDefault();
	}
}

function checkEntered(element, event)
{
	var keyCode = ('which' in event) ? event.which : event.keyCode;
	
	if(keyCode == 13)
		event.preventDefault();
}