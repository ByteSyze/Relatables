/*Copyright (C) Tyler Hackett 2014*/

var type = document.getElementById('type');

var descriptionCounter = document.getElementById('desc_char_counter');

function edit(name)
{
	if(name == 'password')
	{
		editPassword();
		return;
	}
		
	var element = document.getElementById(name);
	var elementInput = document.getElementById(name+'-input');
	var elementButton = document.getElementById(name+'-button');
	
	if(elementButton.innerHTML == 'Cancel')
	{
		element.style.display = 'block';
		elementInput.style.display = 'none';
		elementButton.innerHTML = 'Edit';
	}
	else
	{
		element.style.display = 'none';
		elementInput.style.display = 'block';
		elementButton.innerHTML = 'Cancel';
	}
}

function editPassword()
{	
	var password = document.getElementById('password');
	var oldPass = document.getElementById('oldpassword');
	var newPass = document.getElementById('newpassword');
	var reNewPass = document.getElementById('renewpassword');
	var passwordButton = document.getElementById('password-button');
	
	if(passwordButton.innerHTML == 'Cancel')
	{
		password.style.display = 'table-row';
		oldPass.style.display = 'none';
		newPass.style.display = 'none';
		reNewPass.style.display = 'none';
		passwordButton.innerHTML = 'Edit';
	}
	else
	{
		password.style.display = 'none';
		oldPass.style.display = 'table-row';
		newPass.style.display = 'table-row';
		reNewPass.style.display = 'table-row';
		passwordButton.innerHTML = 'Cancel';
	}
}

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

function charCount(element,counter)
{
	counter.innerHTML = '(' + (130-element.value.length) + ' characters left)';
}

function keyPressed(element, event)
{
	type.value = element.getAttribute('data-type');
	
	var keyCode = ('which' in event) ? event.which : event.keyCode;
	
	if(keyCode == 13)
		element.form.submit();
}