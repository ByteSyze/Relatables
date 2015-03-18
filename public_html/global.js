/*Copyright (C) Tyler Hackett 2014*/

var user 				 = document.getElementById('user_input');
var userVerifyImg 		 = document.getElementById('user_verify_img');
var pass 				 = document.getElementById('pass_input');
var passVerifyImg 		 = document.getElementById('pass_verify_img');
var rePass 				 = document.getElementById('repass_input');
var rePassVerifyImg 	 = document.getElementById('repass_verify_img');
var currentPass 		 = document.getElementById('currentpass_input');
var currentPassVerifyImg = document.getElementById('currentpass_verify_img');
var email 				 = document.getElementById('email_input');
var emailVerifyImg 		 = document.getElementById('email_verify_img');

var rememberEl 			 = document.getElementById('remember_input');

var loginUser			 = document.getElementById('login_user_input');
var userLog				 = document.getElementById('login_user_log');

var loginPass			 = document.getElementById('login_pass_input');
var passLog				 = document.getElementById('login_pass_log');

var usernamePopup 		 = document.getElementById('username-popup');
var currentPasswordPopup = document.getElementById('current-password-popup');
var newPasswordPopup 	 = document.getElementById('new-password-popup');
var renewPasswordPopup 	 = document.getElementById('renew-password-popup');
var emailPopup 			 = document.getElementById('email-popup');

var userRegex 			 = /^[A-Za-z0-9_]+$/;

var loginPopup 			 = document.getElementById("loginpopup");
var loginOpen 			 = false;

var registerPopup 		 = document.getElementById("registerpopup");
var registerHeader 		 = document.getElementById("registerheader");
var registerOpen 		 = false;

function register()
{
	var userVal	 	= user.value;
	var passVal		= pass.value;
	var emailVal	= email.value;
	
	if(verifyRegister())
	{
		console.log('Verified');
		$.post("/register.php", { username: userVal, password: passVal, email: emailVal }, function(data)
		{
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
	
	if(rememberEl.checked)
		remember = 1;
	
	$.post("/login.php", { u: userVal, p: passVal, r: remember }, function(data) {
		console.log(data);
		
		var hideUserLog = true;
		var hidePassLog = true;
		
		if(data == '0')
		{
			location.reload();
		}
		else if(data == '1')
		{
			userLog.innerHTML = 'Account does not exist.';
			hideUserLog = false;
		}
		else if(data == '2')
		{
			userLog.innerHTML = 'Account not verified.';
			hideUserLog = false;
		}
		else if(data == '3')
		{
			passLog.innerHTML = 'Password is incorrect.';
			hidePassLog = false;
		}
		
		if(hideUserLog)
			userLog.style.display = 'none';
		else
			userLog.style.display = 'block';
			
		if(hidePassLog)
			passLog.style.display = 'none';
		else
			passLog.style.display = 'block';
		
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
		form.submit();
	}
}

function verifyUser()
{
	var userVal = user.value;
	var valid = false;
	
	usernamePopup.innerHTML = '';
	
	if(userVal == '')
		return true;
	
	$.post("/verifyUser.php", {username: userVal}, function(data)
	{
			if(data === "user unavailable")
			{	
				userVerifyImg.src = "/x_mark.png";
				usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username is already in use.');
			}
	});
	if(userVal.length < 3)
	{
		userVerifyImg.src = "/x_mark.png";
		usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username must be atleast 3 characters long.');
	}
	else if(userVal.length > 16)
	{
		userVerifyImg.src = "/x_mark.png";
		usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username must be under 16 characters long.');
	}
	else if(!userRegex.test(userVal))
	{	
		userVerifyImg.src = "/x_mark.png";
		usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username can only contain characters a-z and 0-9.');
	}
	else
	{		
		userVerifyImg.src = "/check_mark.png";
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
		passVerifyImg.src = "/x_mark.png";
		newPasswordPopup.innerHTML = newPasswordPopup.innerHTML.concat(' Password must be atleast 6 characters long. ');
	}
	else
	{
		passVerifyImg.src = "/check_mark.png";
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
		rePassVerifyImg.src = "/x_mark.png";
		renewPasswordPopup.innerHTML = renewPasswordPopup.innerHTML.concat(' Password must be atleast 6 characters long. ');
	}
	else if(passVal !== rePassVal)
	{
		rePassVerifyImg.src = "/x_mark.png";
		renewPasswordPopup.innerHTML = renewPasswordPopup.innerHTML.concat(' Password verification doesn\'t match original password. ');
	}
	else
	{
		rePassVerifyImg.src = "/check_mark.png";
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
		currentPassVerifyImg.src = "/x_mark.png";
		currentPasswordPopup.innerHTML = currentPasswordPopup.innerHTML.concat(' Password must be atleast 6 characters long. ');
	}
	else
	{		
		$.post("/verifyPassword.php", {p: passVal}, function(data)
		{
				if(data == "0")
				{
					currentPassVerifyImg.src = "/check_mark.png";	
					currentPasswordPopup.innerHTML = '';
					valid = true;
				}
				else
				{
					currentPassVerifyImg.src = "/x_mark.png";
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
		emailVerifyImg.src = "/x_mark.png";
		emailPopup.innerHTML = emailPopup.innerHTML.concat(' Email must be atleast 4 characters long. ');
	}
	else if((emailVal.indexOf("@") == -1) || (emailVal.indexOf(".") == -1))
	{
		emailVerifyImg.src = "/x_mark.png";
		emailPopup.innerHTML = emailPopup.innerHTML.concat(' Email is invalid. ');
	}
	else
	{
		$.post("/verifyEmail.php", {e: emailVal}, function(data)
		{
			if(data === "1")
			{
				emailVerifyImg.src = "/x_mark.png";
				emailPopup.innerHTML = emailPopup.innerHTML.concat(' Email is already in use. ');
			}
			else
			{
				emailVerifyImg.src = "/check_mark.png";
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
	
	if(event.which < 0x20)
		return remaining;
	
	if(remaining < 1)
	{
		if(substr) //Use substring method for refusing further input
			element.value = element.value.substring(0,limit);
		else
			event.preventDefault();
		
		return 0;
	}
	
	return remaining;
}

function hideAll()
{
	if(loginOpen)
		hideLogin();
	if(registerOpen)
		hideRegister();
}

function toggleProfileDropdown()
{
	$('#profile-dropdown').toggleClass('hidden');
}

function toggleNotificationDropdown()
{
	
	$('#notification-dropdown').toggleClass('hidden');
}

function toggleAboutDropdown()
{
	$('#about-dropdown').toggleClass('hidden');
}

function showLogin()
{
	hideAll();
	loginOpen = true;
	loginPopup.style.display = "block";
}
function hideLogin()
{
	loginOpen = false;
	loginPopup.style.display = "none";
}

function showRegister(element)
{
	hideAll();
	registerOpen = true;
	registerHeader.innerHTML = element.getAttribute("data-header");
	registerPopup.style.display = "block";
}

function hideRegister()
{
	registerOpen = false;
	registerPopup.style.display = "none";
	
	for(input in registerPopup.getElementsByTagName('input'))
		input.value = '';
}

function showGuidelines(element)
{
	element.firstChild.style.display="block";
}

function hideGuidelines(element)
{
	element.firstChild.style.display="none";
}

function showErrors(element)
{
	var popup = element.nextSibling.children[0];
	
	if(popup.innerHTML)
		popup.style.display = 'block';
}

function hideErrors(element)
{
	element.nextSibling.children[0].style.display = 'none';
}

function checkHideErrors(element, errorPopup)
{
	if(element.value == '')
		errorPopup.style.display = 'none';
}

function showSendMessagePopup()
{
	document.getElementById('send-message-popup').style.display='block';
}

function hideSendMessagePopup()
{
	document.getElementById('send-message-popup').style.display='none';	
}

function updateMessageStatus(id)
{
	$.post("/readmessage.php", {id: id}, function(data)
	{
			if(data == '1')
			{
				$('#ntf'+id).addClass('read');
				setTimeout(function(){$('#ntf'+id).removeClass('unread');}, 3000);
			}
	});
	
	document.getElementById('ntfmsg'+id).removeAttribute('onmouseover');
}

function deleteMessage(id)
{
	$.post("/deletemessage.php", {id: id}, function(data)
	{
			if(data == '1')
			{
				$('[data-nid="'+id+'"]').remove();
				
				var notificationsTable = document.getElementById('notification-tbody');
				
				if(notificationsTable.children.length == 0)
				{
					var noMessages = document.createElement('tr');
					noMessages.innerHTML = '<td class=\'no-messages\'><b>You have no messages</b></td>';
					notificationsTable.appendChild(noMessages);
				}
			}
	});
}

function hideAllDropdowns()
{
	$('#notification-dropdown').hide();
	$('#profile-dropdown').hide();
	$('#about-dropdown').hide();
}

function createPopup(message)
{
	$('body').append("<div class='popup' style='display:block;'><div class='buttons'><button class='button blue-hover smaller'>Close</button></div><h1 class='popup-title blue'>" + message + "</h1></div>");
}

$('#pwrecoveryform').submit(function()
{
	$.post('/recover.php', {e: $(this).children().first().val()}, function(){ createPopup('An email has been sent to your email address.'); });
	return false;
});

$(document).ready(function(){hideAllDropdowns();});

$('body').on('click', function(event)
{
	if(event.target === $('#notification-button')[0] || event.target.parentNode === $('#notification-button')[0])
	{
		$('#notification-dropdown').toggle();
		$('#profile-dropdown, #about-dropdown').hide();
	}
	else if(event.target === $('#profile-button')[0] || event.target.parentNode === $('#profile-button')[0])
	{
		$('#profile-dropdown').toggle();
		$('#notification-dropdown, #about-dropdown').hide();
	}
	else if(event.target === $('#about-button')[0] || event.target.parentNode === $('#about-button')[0])
	{
		$('#about-dropdown').toggle();
		$('#profile-dropdown, #notification-dropdown').hide();
	}
	else
	{
		$('#notification-dropdown, #profile-dropdown,#about-dropdown').hide();
	}
});

$('body').on('click', '.popup .buttons > button', function(){$(this).parent().parent().hide();});

$('.showlogin').click(function(){showLogin();});

$('[data-signup-header]').click(function(){showRegister($(this)[0]);});
$('[data-signup-hide]').click(function(){hideRegister();});

$('[data-show]').click(function(){ $($(this).attr('data-show')).show(); });
$('[data-hide]').click(function(){ $($(this).attr('data-hide')).hide(); });
