/*Copyright (C) Tyler Hackett 2014*/

var user 	= document.getElementById("user_input");
var userVerifyImg 	= document.getElementById("user_verify_img");
var pass 	= document.getElementById("pass_input");
var passVerifyImg 	= document.getElementById("pass_verify_img");
var rePass 	= document.getElementById("repass_input");
var rePassVerifyImg = document.getElementById("repass_verify_img");
var currentPass = document.getElementById("currentpass_input");
var currentPassVerifyImg = document.getElementById("currentpass_verify_img");
var email 	= document.getElementById("email_input");
var emailVerifyImg 	= document.getElementById("email_verify_img");
var reEmail 	= document.getElementById("reemail_input");
var reEmailVerifyImg = document.getElementById("reemail_verify_img");

var remember = document.getElementById("remember_input");

var loginUser	= document.getElementById("login_user_input");
var loginPass	= document.getElementById("login_pass_input");

var validColor 		= "#A4D4A4";
var invalidColor 	= "#FF5C5C";

var userRegex 	= /^[A-Za-z0-9_]+$/;

function register()
{
	var userVal	 	= user.value;
	var passVal		= pass.value;
	var emailVal	= email.value;
	
	if(verify())
	{
		$.ajax({
		type: "POST",
		url: "register.php",
		data: { username: userVal, password: passVal, email: emailVal }
	})
		.done(function(data) {
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

function verify()
{
	if(verifyUser() && verifyPassword() && verifyRePassword() && verifyEmail())
	{
		return true;
	}
	return false;
}

function verifyUser()
{
	var userVal = user.value;
	var valid = false;
	
	$.ajax({
		type: "POST",
		url: "/verifyUser.php",
		data: { username: userVal }
	})
		.done(function(data) {
			if(data === "userVal unavailable")
			{	
				userVerifyImg.src = "http://www.relatablez.com/x_mark.png";
			}
	});
	if(userVal.length < 3)
	{
		userVerifyImg.src = "http://www.relatablez.com/x_mark.png";
	}
	else if(userVal.length > 16)
	{
		userVerifyImg.src = "http://www.relatablez.com/x_mark.png";
	}
	else if(!userRegex.test(userVal))
	{	
		userVerifyImg.src = "http://www.relatablez.com/x_mark.png";
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
	
	if(passVal.length < 6)
	{
		passVerifyImg.src = "http://www.relatablez.com/x_mark.png";
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
	
	if(rePassVal.length < 6)
	{
		//Too small
		rePassVerifyImg.src = "http://www.relatablez.com/x_mark.png";
	}
	else if(passVal !== rePassVal)
	{
		//Does not match!
		rePassVerifyImg.src = "http://www.relatablez.com/x_mark.png";
	}
	else
	{
		//Matches
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
	
	if(passVal.length < 6)
	{
		currentPassVerifyImg.src = "http://www.relatablez.com/x_mark.png";
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
				}
				else
				{
					currentPassVerifyImg.src = "http://www.relatablez.com/x_mark.png";
				}
		});
	}
	
	currentPassVerifyImg.style.display = 'block';
}

function verifyEmail()
{
	var emailVal = email.value;
	var valid = false;
	
	$.ajax({
		type: "POST",
		url: "/verifyEmail.php",
		data: { emailVal: emailVal }
	})
		.done(function(data) {
			if(data === "emailVal unavailable")
			{
				emailVerifyImg.src = "http://www.relatablez.com/x_mark.png";
			}
	});
	if(emailVal.length < 4)
	{
		emailVerifyImg.src = "http://www.relatablez.com/x_mark.png";
	}
	else if((emailVal.indexOf("@") == -1) || (emailVal.indexOf(".") == -1))
	{
		emailVerifyImg.src = "http://www.relatablez.com/x_mark.png";
	}
	else
	{
		emailVerifyImg.src = "http://www.relatablez.com/check_mark.png";
		valid = true;
	}
	emailVerifyImg.style.display = "block";
	return valid;
}
