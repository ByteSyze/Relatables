/*Copyright (C) Tyler Hackett 2014*/

var descriptionCounter = document.getElementById('desc_char_counter');

function verifyLocation(input,marker)
{
	if((input.value >= 0) && (input.value <= 250))
	{
		marker.src = 'http://www.relatablez.com/check_mark.png';
	}
	else
	{
		marker.src = 'http://www.relatablez.com/x_mark.png';
	}
	marker.style.display = 'block';
}

function verifyDescription(input,marker)
{
	if(input.value.length <= 130)
	{
		marker.src = 'http://www.relatablez.com/check_mark.png';
	}
	else
	{
		marker.src = 'http://www.relatablez.com/x_mark.png';
	}
	marker.style.display = 'block';
}

function verifyUsername(input,marker)
{
	var user = input.value;
	
	$.ajax({
		type: "POST",
		url: "verifyUser.php",
		data: { username: user }
	})
		.done(function(data) {
			if(data === "user unavailable")
			{
				marker.src = "http://www.relatablez.com/x_mark.png";
			}
	});
	if(user.length < 3)
	{
		marker.src = "http://www.relatablez.com/x_mark.png";
	}
	else if(user.length > 16)
	{
		marker.src = "http://www.relatablez.com/x_mark.png";
	}
	else if(!userRegex.test(user))
	{	
		marker.src = "http://www.relatablez.com/x_mark.png";
	}
	else
	{		
		marker.src = "http://www.relatablez.com/check_mark.png";
	}
	marker.style.display = 'block';
}

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