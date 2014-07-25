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

function checkEntered(element, event)
{
	var keyCode = ('which' in event) ? event.which : event.keyCode;
	
	if(keyCode == 13)
		event.preventDefault();
}