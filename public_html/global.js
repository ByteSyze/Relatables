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

var openedShareText = 'Share «';
var closedShareText = 'Share »';

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
	}, true);
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

function verifyUser(successCallback, verifyAll)
{
	var userVal = user.value;
	
	usernamePopup.innerHTML = '';
	
	if(userVal == '')
	{
		if(verifyAll)
		{
			if(currentPass)
				verifyCurrentPassword(successCallback, verifyAll);
			else
				verifyPassword(successCallback, verifyAll);
			return true;
		}
		else
			return true;
	}
	
	$img = $('#user_input').next();
	$pop = $('username-popup');
	
	if(userVal.length < 3)
		setMarker($img, $pop, 'Username must be atleast 3 characters long.', false);
	else if(userVal.length > 16)
		setMarker($img, $pop, ' Username must be under 16 characters long.', false);
	else if(!userRegex.test(userVal))
		setMarker($img, $pop, ' Username can only contain characters a-z and 0-9.', false);
	else
	{
		$.post("/verifyUser.php", {username: userVal}, function(data)
		{
				if(data === "user unavailable")
				{	
					setMarker($img, $pop, ' Username is already in use.', false);
					
					return valid;
				}
				else
				{
					setMarker($img, 0, 0, true);
					
					if(verifyAll)
						verifyPassword(successCallback, verifyAll);
					else
						return true;
				}
		});
	}
}

function verifyPassword(successCallback, verifyAll)
{
	var passVal = pass.value;	
	
	newPasswordPopup.innerHTML = '';
	
	$img = $('#pass_input').next();
	$pop = $('#new-password-popup');
	
	if(passVal.length < 6)
		setMarker($img, $pop, 'Password must be atleast 6 characters long.', false);
	else
	{
		setMarker($img, 0, 0, true);
		verifyRePassword(successCallback);
	}
	
	if(verifyAll)
	{
		verifyRePassword(successCallback, verifyAll);
		return true;
	}
	else 
		return true;
}

function verifyRePassword(successCallback, verifyAll)
{
	var passVal 		= pass.value;
	var rePassVal 		= rePass.value;
	
	renewPasswordPopup.innerHTML = '';
	
	$img = $('#repass_input').next();
	$pop = $('#renew-password-popup');
	
	if(passVal !== rePassVal)
		setMarker($img, $pop, 'Password verification doesn\'t match original password.', false);
	else
	{
		setMarker($img, 0, 0, true);
		
		if(verifyAll)
			verifyEmail(successCallback, verifyAll);
		else 
			return true;
	}
}

function verifyCurrentPassword(successCallback, verifyAll)
{
	var passVal = currentPass.value;
	var valid = false;
	
	currentPasswordPopup.innerHTML = '';
	
	$img = $('#currentpass_input').next();
	$pop = $('#currentpass-popup');
	
	if((passVal == '') && (pass.value == '') && (rePass.value == ''))
	{
		$img.hide();
		
		if(verifyAll)
			verifyPassword(successCallback, verifyAll);
		else 
			return true;
	}
	
	if(passVal.length < 6)
		setMarker($img, $pop, 'Password must be atleast 6 characters long.', false);
	else
	{		
		$.post("/verifyPassword.php", {p: passVal}, function(data)
		{
				if(data == "0")
				{
					setMarker($img, 0, 0, true);
					valid = true;
					
					if(verifyAll)
						verifyPassword(successCallback, verifyAll);
				}
				else
					setMarker($img, $pop, ' Password is incorrect.', false);
		});
	}
	
	return valid;
}

function verifyEmail(successCallback, verifyAll)
{
	var emailVal = $('#email_input').val();
	
	emailPopup.innerHTML = '';
	
	$img = $('#email_input').next();
	$pop = $('#email-popup');
	
	if(emailVal == '')
	{
		if(verifyAll)
		{
			successCallback();
			return;
		}
		else
			return true;
	}
	
	if(emailVal.length < 4)
		setMarker($img, $pop, 'Email must be atleast 4 characters long.', false);
	else if((emailVal.indexOf("@") == -1) || (emailVal.indexOf(".") == -1))
		setMarker($img, $pop, ' Email is invalid.', false);
	else
	{
		$.post("/verifyEmail.php", {e: emailVal}, function(data)
		{
			if(data !== '0')
			{
				setMarker($img, $pop, ' Email is already in use.', false);
			}
			else
			{
				setMarker($img, 0, 0, true);
				
				if(successCallback)
					successCallback();
					
				if(!verifyAll)
					return true;
			}
		});	
	}
}

function verifyRecoveryEmail()
{
	$img = $('#recovery-email').next();
	$pop = $('#recovery-email-popup');
	
	$pop.html('');
	
	if($('#recovery-email').val())
	{
		$.post("/verifyEmail.php", {e: $('#recovery-email').val()}, function(data)
		{
			if(data !== '0')
				setMarker($img, 0, 0, true);
			else
				setMarker($img, $pop, 'There is no account linked to that email.', false);
		});	
	}
}

function setMarker($img, $msg, msg, checkmark)
{
	if(checkmark)
		$img.addClass('checkmark');
	else
		$img.removeClass('checkmark');
		
	if(msg)
		$msg.html(msg);
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
	$('#registerheader').html(element.getAttribute("data-signup-header"));
	$('#registerpopup').show();
}

function resetRegister()
{
	$('#registerpopup :input').val('');
}

function checkErrPopups($el)
{
	if($el.val() == '')
		$el.next().hide();
	else
		$el.next().show();
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

function vote($button)
{
	var id = $button.attr('data-vid');
	var v = $button.parent().parent().attr('data-v');
	var unvote = ($button.hasClass('red') || $button.hasClass('green'));
	
	var notAloneEl = $('[data-v="'+id+'"] .buttons > button').first();
	var aloneEl = notAloneEl.next();
	
	var vote = $button.is(notAloneEl) ? 0 : 1;
	
	console.log(vote);
	console.log(notAloneEl);
	console.log($button);
	
	notAlone = notAloneEl.attr('data-vc');
	alone = aloneEl.attr('data-vc');
	
	if(!notAlone) //If null, ensure they are 0
		notAlone = 0;
	if(!alone)
		alone = 0;
		
	$.ajax({
		type: "POST",
		url: "/vote.php",
		data: {q: id, vtn: vote, v: v, u: unvote}
	})
	.done(function(data) {
		console.log(data);
		if(unvote)
		{
			if(data == '1')
			{
				if(vote == 0)
					$('button.green[data-vid="'+id+'"]').removeClass('green').addClass('green-hover');
				else
					$('button.red[data-vid="'+id+'"]').removeClass('red').addClass('red-hover');
			}
		}
		else
		{
			if(data == '2' && vote == 1)
			{
				alone++;
				notAlone--;
				
				$nae = $('button.green[data-vid="'+id+'"]');
				$nae.removeClass('green').addClass('green-hover');
				
				$ae = $('button.red-hover[data-vid="'+id+'"]');
				$ae.addClass('red');
			}
			else if(data == '2' && vote == 0)
			{	
				notAlone++;
				alone--;
				
				$nae = $('button.green-hover[data-vid="'+id+'"]');
				$nae.addClass('green');
				
				$ae = $('button.red[data-vid="'+id+'"]');
				$ae.removeClass('red').addClass('red-hover');
			}
			else if(data == '1' && vote == 1)
			{
				alone++;
				
				$ae = $('button.red-hover[data-vid="'+id+'"]');
				$ae.addClass('red');
			}
			else if(data == '1' && vote == 0)
			{
				notAlone++;
				
				$nae = $('button.green-hover[data-vid="'+id+'"]');
				$nae.addClass('green');
			}
		}
		
		notAloneEl.attr('data-vc',notAlone);
		notAloneEl.html("No, me too! ("+notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,')+")");
		
		aloneEl.attr('data-vc',alone);
		aloneEl.html("You're alone ("+alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,')+")");
	});
}

//Analytics
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-63194305-1', 'auto');
ga('send', 'pageview');

$('#pwrecoveryform').submit(function()
{
	$.post('/recover.php', {e: $(this).children().first().val(), s: 1}, function(data){ createPopup(data); });
	$(this).parent().hide();
	return false;
});

$(document).ready(function()
{
	$('.dropdown').hide();
	
	$('[data-limiter]').each(function()
	{
		var $limiter = $(this);
		var limit = parseInt($limiter.attr('data-limit'));
		
		$($limiter.attr('data-limiter')).on('change keypress paste',function(event)
		{
			var charCount = $(this).val().length;
			$limiter.html(limit-charCount);
			
			console.log(charCount);
			console.log(charCount > (limit-1));
			if(charCount > (limit-1))
			{
				if(event.key != "Backspace")
				{
					console.log("stap");
					event.stopPropagation();
					event.stopImmediatePropagation();
					event.preventDefault();
					
					$limiter.html('0');
					return false;
				}
				else
				{
					$limiter.html('1');
				}
			}
		});
	});
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
		button.next().animate({width: '0px'},100,function(){button.html(closedShareText);});
	else
		button.next().animate({width: '90px'},100,function(){button.html(openedShareText);});
});

$('#save-button').click(function(){ verifyUser(function(){ $('#settings-form').submit(); } ,true); return false; });

$('#user_input').keydown(function(event){ checkLimit(event, $(this)[0], 32, false); });

$('#user_input').keyup(function(){ verifyUser(0, false); checkErrPopups($(this)); });
$('#pass_input').keyup(function(){ verifyPassword(0, false); checkErrPopups($(this)); });
$('#repass_input').keyup(function(){ verifyRePassword(0, false); checkErrPopups($(this)); });
$('#email_input').keyup(function(){ verifyEmail(0, false); checkErrPopups($(this)); });
$('#recovery-email').keyup(function(){ verifyRecoveryEmail(); checkErrPopups($(this)); });

$('[data-err-popup]').mouseover(function(){ if($(this).next().first().html()) $(this).next().first().show(); });
$('[data-err-popup]').mouseout(function(){ $(this).next().first().hide(); });

$('body').on('click', '.popup > .buttons > button', function(){ $(this).parent().parent().hide(); });
$('body').on('click', '[data-vid]', function(){ vote($(this)); });

$('.showlogin').click(function(){showLogin();});

$('#registerpopup form').submit(function(){ register(); return false;  });

$('[data-signup-header]').click(function(){showRegister($(this)[0]);});
$('[data-signup-hide]').click(function(){ $('registerpopup').hide(); resetRegister();});

$('body').on('click', '[data-show]',function(){ $($(this).attr('data-show')).show(); return false; });
$('body').on('click', '[data-hide]',function(){ $($(this).attr('data-hide')).hide(); return false; });
$('body').on('click', '[data-togg]',function(){ $($(this).attr('data-togg')).toggle(); return false; });
