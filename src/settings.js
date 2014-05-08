/*Copyright (C) Tyler Hackett 2014*/

var type = document.getElementById('type');

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

function keyPressed(element, event)
{
	type.value = element.getAttribute('data-type');
	
	var keyCode = ('which' in event) ? event.which : event.keyCode;
	
	if(keyCode == 13)
		element.form.submit();
}