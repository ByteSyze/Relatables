/*Copyright (C) Tyler Hackett 2014*/

var userElement 	= document.getElementById("user_input");
var userVerifyImg 	= document.getElementById("user_verify_img");
var passElement 	= document.getElementById("pass_input");
var passVerifyImg 	= document.getElementById("pass_verify_img");
var rePassElement 	= document.getElementById("repass_input");
var rePassVerifyImg = document.getElementById("repass_verify_img");
var emailElement 	= document.getElementById("email_input");
var emailVerifyImg 	= document.getElementById("email_verify_img");
var reEmailElement 	= document.getElementById("reemail_input");
var reEmailVerifyImg = document.getElementById("reemail_verify_img");

var rememberElement = document.getElementById("remember_input");

var loginUserElement	= document.getElementById("login_user_input");
var loginPassElement	= document.getElementById("login_pass_input");

var validColor 		= "#A4D4A4";
var invalidColor 	= "#FF5C5C";

var userRegex 	= /^[A-Za-z0-9_]+$/;

function register()
{
	var user	 = userElement.value;
	var pass	 = passElement.value;
	var email	 = emailElement.value;
	
	if(verify())
	{
		$.ajax({
		type: "POST",
		url: "register.php",
		data: { username: user, password: pass, email: email }
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
	var user = loginUserElement.value;
	var pass = loginPassElement.value;
	
	var remember = 0;
	
	if(rememberElement.checked)
		remember = 1;
	
	$.ajax({
		type: "POST",
		url: "login.php",
		data: { username: user, password: pass, rememberme: remember }
	})
		.done(function(data) {
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

// Verify everything. Returns true if everything is valid
function verify()
{
	if(verifyUser() && verifyPassword() && verifyRePassword() && verifyEmail())
	{
		return true;
	}
	return false;
}

// Verify username
function verifyUser()
{
	var user = userElement.value;
	var valid = false;
	
	var email = emailElement.value;
	
	$.ajax({
		type: "POST",
		url: "verifyUser.php",
		data: { username: user, email: email}
	})
		.done(function(data) {
			if(data === "user unavailable")
			{
				//Username is already in use.	
				userVerifyImg.src = "x_mark.png";
			}
	});
	if(user.length < 3)
	{
		//Username is too short
		userVerifyImg.style.display = "block";
		userVerifyImg.src = "x_mark.png";
	}
	else if(user.length > 16)
	{
		//Username is too long
		userVerifyImg.style.display = "block";
		userVerifyImg.src = "x_mark.png";
	}
	else if(user.length < 1)
	{	
		//Username is too short
		userVerifyImg.style.display = "block";
		userVerifyImg.src = "x_mark.png";
	}
	else if(!userRegex.test(user))
	{	
		//Username contains invalid characters
		userVerifyImg.style.display = "block";
		userVerifyImg.src = "x_mark.png";
	}
	else
	{		
		//Username is A-Okay
		userVerifyImg.style.display = "block";
		userVerifyImg.src = "check_mark.png";
		valid = true;
	}
	return valid;
}

// Verify password
function verifyPassword()
{
	var pass = passElement.value;
	
	var valid = false;
	
	if(pass.length < 6)
	{
		//Password is too short
		passVerifyImg.style.display = "block";
		passVerifyImg.src = "x_mark.png";
	}
	else
	{
		//Hey look, they can make a password!
		passVerifyImg.style.display = "block";
		passVerifyImg.src = "check_mark.png";
		valid = true;
	}
	
	verifyRePassword();
	return valid;
}

// Verify re-entered password
function verifyRePassword()
{
	var pass 		= passElement.value;
	var rePass 		= rePassElement.value;
	
	var valid = false;
	
	if(rePass.length < 6)
	{
		//Too small
		rePassVerifyImg.style.display = "block";
		rePassVerifyImg.src = "x_mark.png";
	}
	else if(pass !== rePass)
	{
		//Does not match!
		rePassVerifyImg.style.display = "block";
		rePassVerifyImg.src = "x_mark.png";
	}
	else
	{
		//Matches
		rePassVerifyImg.style.display = "block";
		rePassVerifyImg.src = "check_mark.png";
		valid = true;
	}
	return valid;
}

// Verify Email
function verifyEmail()
{
	var email = emailElement.value;
	var valid = false;
	
	$.ajax({
		type: "POST",
		url: "verifyEmail.php",
		data: { email: email }
	})
		.done(function(data) {
			if(data === "email unavailable")
			{
				//email is already in use.
				emailVerifyImg.style.display = "block";
				emailVerifyImg.src = "x_mark.png";
			}
	});
	if(email.length < 4)
	{
		//Email is too short.
		emailVerifyImg.style.display = "block";
		emailVerifyImg.src = "x_mark.png";
	}
	else if((email.indexOf("@") == -1) || (email.indexOf(".") == -1))
	{
		//Does not contain @ and/or . characters.
		emailVerifyImg.style.display = "block";
		emailVerifyImg.src = "x_mark.png";
	}
	else
	{
		//Seems valid.
		emailVerifyImg.style.display = "block";
		emailVerifyImg.src = "check_mark.png";
		valid = true;
	}
	return valid;
}
