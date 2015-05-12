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

function register()
{
	
	verifyUser(function()
	{
		console.log('Verified');
		$.post("/register.php", { username: $('#user_input').val(), password: $('#pass_input').val(), email: $('#email_input').val() }, function(data)
		{
			if(data.indexOf("success") != -1)
			{
				document.getElementById("registerbutton").setAttribute("value","Thank you");
			}
		});
	});
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

function verifyUser(successCallback)
{
	var userVal = user.value;
	
	usernamePopup.innerHTML = '';
	
	if(userVal == '')
		verifyPassword(successCallback);
	
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
	$.post("/verifyUser.php", {username: userVal}, function(data)
	{
			if(data === "user unavailable")
			{	
				userVerifyImg.src = "/x_mark.png";
				usernamePopup.innerHTML = usernamePopup.innerHTML.concat(' Username is already in use.');
				
				userVerifyImg.style.display = 'block';
				return valid;
			}
			else
			{
				userVerifyImg.src = "/check_mark.png";
				verifyPassword(successCallback);
			}
	});
}

function verifyPassword(successCallback)
{
	var passVal = pass.value;	
	
	newPasswordPopup.innerHTML = '';
	
	if(passVal.length < 6)
	{
		passVerifyImg.src = "/x_mark.png";
		newPasswordPopup.innerHTML = newPasswordPopup.innerHTML.concat(' Password must be atleast 6 characters long. ');
	}
	else
	{
		passVerifyImg.src = "/check_mark.png";
		verifyRePassword(successCallback);
	}
	
	passVerifyImg.style.display = "block";
	verifyRePassword(successCallback);
}

function verifyRePassword(successCallback)
{
	var passVal 		= pass.value;
	var rePassVal 		= rePass.value;
	
	renewPasswordPopup.innerHTML = '';
	
	if(passVal !== rePassVal)
	{
		rePassVerifyImg.src = "/x_mark.png";
		renewPasswordPopup.innerHTML = renewPasswordPopup.innerHTML.concat(' Password verification doesn\'t match original password. ');
	}
	else
	{
		rePassVerifyImg.src = "/check_mark.png";
		verifyEmail(successCallback);
	}
	rePassVerifyImg.style.display = "block";
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

function verifyEmail(successCallback)
{
	var emailVal = $('#email_input').val();
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
			if(data !== '0')
			{
				emailVerifyImg.src = "/x_mark.png";
				emailPopup.innerHTML = emailPopup.innerHTML.concat(' Email is already in use. ');
			}
			else
			{
				emailVerifyImg.src = "/check_mark.png";
				
				if(successCallback)
					successCallback();
			}
			
			emailVerifyImg.style.display = "block";
		});	
	}
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
	$('.popup').hide();
	resetRegister();
}

function showLogin()
{
	hideAll();
	$('#loginpopup').show();
}

function showRegister(element)
{
	hideAll();
	$('#registerheader').html(element.getAttribute("data-header"));
	$('#registerpopup').show();
}

function resetRegister()
{
	$('#registerpopup :input').val('');
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

function createPopup(message)
{
	$('body').append("<div class='popup' style='display:block;'><div class='buttons'><button class='button blue-hover smaller'>Close</button></div><span class='popup-small'>" + message + "</span></div>");
}

function vote(id, vote, v)
{
	//var notAloneEl  = document.getElementById('na'+id);
	//var aloneEl 	= document.getElementById('a'+id);
	
	// Take out all formatting
	//var notAlone = notAloneEl.innerHTML.replace("(","").replace(")","").replace(",",""); 
	//var alone = aloneEl.innerHTML.replace("(","").replace(")","").replace(",","");
	
	$.ajax({
		type: "POST",
		url: "/vote.php",
		data: {q: id, vtn: vote, v : v}
	})
	.done(function(data) {
		console.log(data);
		
		if(data == '2' && vote == 1)
		{
			//alone++;
			//notAlone--;
			
			$('button.green-hover[data-vid="'+id+'"]').prop('disabled', false);
			$('button.red-hover[data-vid="'+id+'"]').prop('disabled', true);
			
			//notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
			//aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '2' && vote == 0)
		{	
			//notAlone++;
			//alone--;
			
			$('button.green-hover[data-vid="'+id+'"]').prop('disabled', true);
			$('button.red-hover[data-vid="'+id+'"]').prop('disabled', false);
			
			//notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
			//aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '1' && vote == 1)
		{
			//alone++;
			$('button.red-hover[data-vid="'+id+'"]').prop('disabled', true);
			//aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '1' && vote == 0)
		{
			//notAlone++;
			$('button.green-hover[data-vid="'+id+'"]').prop('disabled', true);;
			//notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
	});
}

$('#pwrecoveryform').submit(function()
{
	$.post('/recover.php', {e: $(this).children().first().val(), s: 1}, function(data){ createPopup(data); });
	$(this).parent().hide();
	return false;
});

$(document).ready(function()
{
	$('.dropdown').hide();
});

$(document).on('click', function(event)
{
	$('[data-togg]').each(function()
	{ 
		if(event.target != $(this)[0])
			$($(this).attr('data-togg')).hide();
	});
});

$('body').on('click','[data-share-button]',function()
{
	var button = $(this);
	if(button.text() == openedShareText)
		button.next().animate({width: '0px'},500,function(){button.html(closedShareText);});
	else
		button.next().animate({width: '115px'},500,function(){button.html(openedShareText);});
});

$('body').on('click', '.popup .buttons > button', function(){$(this).parent().parent().hide();});
$('body').on('click', '[data-vid]', function(){ vote($(this).attr('data-vid'), $(this).html() == 'No, me too!' ? 0 : 1, $(this).parent().parent().attr('data-v')); });

$('.showlogin').click(function(){showLogin();});

$('#registerpopup form').submit(function(){ register(); return false;  });

$('[data-signup-header]').click(function(){showRegister($(this)[0]);});
$('[data-signup-hide]').click(function(){ $('registerpopup').hide(); resetRegister();});

$('body').on('click', '[data-show]',function(){ $($(this).attr('data-show')).show(); return false; });
$('body').on('click', '[data-hide]',function(){ $($(this).attr('data-hide')).hide(); return false; });
$('body').on('click', '[data-togg]',function(){ $($(this).attr('data-togg')).toggle(); return false; });
