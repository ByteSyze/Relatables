/*Copyright (C) Tyler Hackett 2014*/

var descriptionCounter = document.getElementById('desc_char_counter');

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
