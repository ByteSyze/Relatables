/*Copyright (C) Tyler Hackett 2014*/

var user 	= document.getElementById('user_input');
var userVerifyImg 	= document.getElementById('user_verify_img');
var pass 	= document.getElementById('pass_input');
var passVerifyImg 	= document.getElementById('pass_verify_img');
var rePass 	= document.getElementById('repass_input');
var rePassVerifyImg = document.getElementById('repass_verify_img');
var currentPass = document.getElementById('currentpass_input');
var currentPassVerifyImg = document.getElementById('currentpass_verify_img');
var email 	= document.getElementById('email_input');
var emailVerifyImg 	= document.getElementById('email_verify_img');
var reEmail 	= document.getElementById('reemail_input');
var reEmailVerifyImg = document.getElementById('reemail_verify_img');

var remember = document.getElementById('remember_input');

var loginUser	= document.getElementById('login_user_input');
var loginPass	= document.getElementById('login_pass_input');

var usernamePopup = document.getElementById('username-popup');
var currentPasswordPopup = document.getElementById('current-password-popup');
var newPasswordPopup = document.getElementById('new-password-popup');
var renewPasswordPopup = document.getElementById('renew-password-popup');
var emailPopup = document.getElementById('email-popup');

var userRegex 	= /^[A-Za-z0-9_]+$/;

function register()
{
	console.log('Registering');
	var userVal	 	= user.value;
	var passVal		= pass.value;
	var emailVal	= email.value;
	
	if(verifyRegister())
	{
		console.log('Verified');
		$.ajax({
		type: "POST",
		url: "register.php",
		data: { username: userVal, password: passVal, email: emailVal }
		})
			.done(function(data) {
				console.log(data);
				if(data.indexOf("success") != -1)
				{
					document.getElementById("registerbutton").setAttribute("value","Thank you");
				}
		});
	}
}

function login()
{
	var userVal = loginUser.value;
	var passVal = loginPass.value;
	
	var remember = 0;
	
	if(remember.checked)
		remember = 1;
	
	$.ajax({
		type: "POST",
		url: "login.php",
		data: { username: userVal, password: passVal, rememberme: remember }
	})
		.done(function(data) {
			console.log(data);
			if(data.indexOf("logged in") != -1)
			{
				location.reload();
			}
			if(data.indexOf("invalid username") != -1)
			{
			}
			if(data.indexOf("invalid password") != -1)
			{
			}
	});
}

function verifyRegister()
{
	if(verifyUser() && verifyPassword() && verifyRePassword() && verifyEmail())
		return true;
		
	return false;
}

function saveSettings(form)
{
	//Store all results so that all are checked, regardless of whether any are wrong.
	var validUser = verifyUser();
	var validPass = verifyCurrentPassword();
	var validEmail = verifyEmail();
	
	if(validUser && validPass && validEmail)
	{
		console.log('form is valid');
		//form.submit();
	}
}

function verifyUser()
{
	var userVal = user.value;
	var valid = false;
	
	usernamePopup.innerHTML = '';
	
	if(userVal == '')
		return true;
	
	$.ajax({
		type: "POST",
		url: "/verifyUser.php",
		data: { username: userVal }
	})
		.done(function(data) {
			if(data === "user unavailable")
			{	
				userVerifyImg.src = "http://www.relatablez.com/x_mark.png";
				usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username is already in use.');
			}
	});
	if(userVal.length < 3)
	{
		userVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username must be atleast 3 characters long.');
	}
	else if(userVal.length > 16)
	{
		userVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username must be under 16 characters long.');
	}
	else if(!userRegex.test(userVal))
	{	
		userVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username can only contain characters a-z and 0-9.');
	}
	else
	{		
		userVerifyImg.src = "http://www.relatablez.com/check_mark.png";
		valid = true;
	}
	
	userVerifyImg.style.display = 'block';
	return valid;
}

function verifyPassword()
{
	var passVal = pass.value;	
	var valid = false;
	
	newPasswordPopup.innerHTML = '';
	
	if(passVal.length < 6)
	{
		passVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		newPasswordPopup.innerHTML = newPasswordPopup.innerHTML.concat(' Password must be atleast 6 characters long. ');
	}
	else
	{
		passVerifyImg.src = "http://www.relatablez.com/check_mark.png";
		valid = true;
	}
	
	passVerifyImg.style.display = "block";
	verifyRePassword();
	return valid;
}

function verifyRePassword()
{
	var passVal 		= pass.value;
	var rePassVal 		= rePass.value;
	
	var valid = false;
	
	renewPasswordPopup.innerHTML = '';
	
	if(rePassVal.length < 6)
	{
		rePassVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		renewPasswordPopup.innerHTML = renewPasswordPopup.innerHTML.concat(' Password must be atleast 6 characters long. ');
	}
	else if(passVal !== rePassVal)
	{
		rePassVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		renewPasswordPopup.innerHTML = renewPasswordPopup.innerHTML.concat(' Password verification doesn\'t match original password. ');
	}
	else
	{
		rePassVerifyImg.src = "http://www.relatablez.com/check_mark.png";
		valid = true;
	}
	rePassVerifyImg.style.display = "block";
	return valid;
}

function verifyCurrentPassword()
{
	var passVal = currentPass.value;
	var valid = false;
	
	currentPasswordPopup.innerHTML = '';
	
	if((passVal == '') && (pass.value == '') && (rePass.value == ''))
	{
		currentPassVerifyImg.style.display = 'none';
		return true;
	}
	
	if(passVal.length < 6)
	{
		currentPassVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		currentPasswordPopup.innerHTML = currentPasswordPopup.innerHTML.concat(' Password must be atleast 6 characters long. ');
	}
	else
	{		
		$.ajax({
			type: "POST",
			url: "/verifyPassword.php",
			data: { p: passVal }
		})
			.done(function(data) {
				if(data == "0")
				{
					currentPassVerifyImg.src = "http://www.relatablez.com/check_mark.png";	
					currentPasswordPopup.innerHTML = '';
					valid = true;
				}
				else
				{
					currentPassVerifyImg.src = "http://www.relatablez.com/x_mark.png";
					currentPasswordPopup.innerHTML = ' Password is incorrect. ';
				}
		});
	}
	
	currentPassVerifyImg.style.display = 'block';
	return valid;
}

function verifyEmail()
{
	var emailVal = email.value;
	var valid = false;
	
	emailPopup.innerHTML = '';
	
	if(emailVal == '')
		return true;
	
	if(emailVal.length < 4)
	{
		emailVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		emailPopup.innerHTML = emailPopup.innerHTML.concat(' Email must be atleast 4 characters long. ');
	}
	else if((emailVal.indexOf("@") == -1) || (emailVal.indexOf(".") == -1))
	{
		emailVerifyImg.src = "http://www.relatablez.com/x_mark.png";
		emailPopup.innerHTML = emailPopup.innerHTML.concat(' Email is invalid. ');
	}
	else
	{
		$.ajax({
			type: "POST",
			url: "/verifyEmail.php",
			data: { e: emailVal }
		})
		.done(function(data){
			if(data === "1")
			{
				emailVerifyImg.src = "http://www.relatablez.com/x_mark.png";
				emailPopup.innerHTML = emailPopup.innerHTML.concat(' Email is already in use. ');
			}
			else
			{
				emailVerifyImg.src = "http://www.relatablez.com/check_mark.png";
				valid = true;
			}
		});	
	}
	emailVerifyImg.style.display = "block";
	return valid;
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
