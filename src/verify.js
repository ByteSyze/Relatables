/*Copyright (C) Tyler Hackett 2014*/

var userElement 	= document.getElementById("user_input");
var passElement 	= document.getElementById("pass_input");
var rePassElement 	= document.getElementById("repass_input");
var emailElement 	= document.getElementById("email_input");
var reEmailElement 	= document.getElementById("reemail_input");

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
				userElement.style.background=invalidColor;		
			}
	});
	if(user.length < 3)
	{
		//Username is too short
		userElement.style.background=invalidColor;
	}
	else if(user.length > 16)
	{
		//Username is too long
		userElement.style.background=invalidColor;
	}
	else if(user.length < 1)
	{	
		//Username is too short
		userElement.style.background=invalidColor;
	}
	else if(!userRegex.test(user))
	{	
		//Username contains invalid characters
		userElement.style.background=invalidColor;
	}
	else
	{		
		//Username is A-Okay
		userElement.style.background=validColor;
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
		passElement.style.background=invalidColor;
	}
	else
	{
		//Hey look, they can make a password!
		passElement.style.background=validColor;
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
		rePassElement.style.background=invalidColor;
	}
	else if(pass !== rePass)
	{
		//Does not match!
		rePassElement.style.background=invalidColor;
	}
	else
	{
		//Matches
		rePassElement.style.background=validColor;
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
				emailElement.style.background=invalidColor;	
			}
	});
	if(email.length < 4)
	{
		//Email is too short.
		emailElement.style.background=invalidColor;
	}
	else if((email.indexOf("@") == -1) || (email.indexOf(".") == -1))
	{
		//Does not contain @ and/or . characters.
		emailElement.style.background=invalidColor;
	}
	else
	{
		//Seems valid.
		emailElement.style.background=validColor;
		valid = true;
	}
	return valid;
}
