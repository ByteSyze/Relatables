/*Copyright (C) Tyler Hackett 2014*/

var user 				 = document.getElementById('user_input');
var userGlyph 		 = document.getElementById('user-glyph');
var pass 				 = document.getElementById('pass_input');
var passGlyph 		 = document.getElementById('pass-glyph');
var rePass 				 = document.getElementById('repass_input');
var rePassGlyph 	 = document.getElementById('repass-glyph');
var currentPass 		 = document.getElementById('currentpass_input');
var currentPassGlyph = document.getElementById('currentpass-glyph');
var email 				 = document.getElementById('email_input');
var emailGlyph 		 = document.getElementById('email-glyph');

var rememberEl 			 = document.getElementById('remember_input');

var loginUser			 = document.getElementById('login_user_input');

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

var MARKER_NOMARK 		= 0;
var MARKER_XMARK 		= 1;
var MARKER_CHECKMARK	= 2;

function register()
{
	verifyUser(function()
	{
		console.log('Verified');
		$.post("/register.php", { username: $('#user_input').val(), password: $('#pass_input').val(), email: $('#email_input').val() }, function(data)
		{
			if(data.indexOf("success") != -1)
			{
				$('#registerbutton').val("Thank you");
				$('#registerpopup').hide();
				
				createPopup("<h3>A verification email has been sent to " + $('#email_input').val() + ".</h3>");
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
		
		if(data == '0')
			location.reload();
		else if(data == '1')
			$('#login-errors').html('Account does not exist.');
		else if(data == '2')
			$('#login-errors').html('Please verify your account.');
		else if(data == '3')
			$('#login-errors').html('Password is incorrect.');
	});
}

function verifyUser(successCallback, verifyAll)
{
	var userVal = user.value;
	
	usernamePopup.innerHTML = '';
	
	if(userVal == '')
	{
		setGlyph($glyph, 0, 0, MARKER_NOMARK);
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
	
	$glyph = $('#user_input').next().next().next();
	$pop = $('#username-popup');
	
	if(userVal.length < 3)
		setGlyph($glyph, $pop, 'Username must be atleast 3 characters long.', MARKER_XMARK);
	else if(userVal.length > 16)
		setGlyph($glyph, $pop, ' Username must be under 16 characters long.', MARKER_XMARK);
	else if(!userRegex.test(userVal))
		setGlyph($glyph, $pop, ' Username can only contain characters a-z and 0-9.', MARKER_XMARK);
	else
	{
		$.post("/verifyUser.php", {username: userVal}, function(data)
		{
				if(data === "user unavailable")
				{	
					setGlyph($glyph, $pop, ' Username is already in use.', MARKER_XMARK);
					
					return valid;
				}
				else
				{
					setGlyph($glyph, 0, 0, MARKER_CHECKMARK);
					
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
	
	$glyph = $('#pass_input').next().next().next();
	$pop = $('#new-password-popup');
	
	if(passVal.length == 0)
		setGlyph($glyph, 0, 0, MARKER_NOMARK);
	else if(passVal.length < 6)
		setGlyph($glyph, $pop, 'Password must be atleast 6 characters long.', MARKER_XMARK);
	else
	{
		setGlyph($glyph, 0, 0, MARKER_CHECKMARK);
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
	
	$glyph = $('#repass_input').next();
	$pop = $('#renew-password-popup');
	
	if(passVal.length == 0 && rePassVal == 0)
		setGlyph($glyph, 0, 0, MARKER_NOMARK);
	else if(passVal !== rePassVal)
		setGlyph($glyph, $pop, 'Password verification doesn\'t match original password.', MARKER_XMARK);
	else
	{
		setGlyph($glyph, 0, 0, MARKER_CHECKMARK);
		
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
	
	$glyph = $('#currentpass_input').next();
	$pop = $('#currentpass-popup');
	
	if((passVal == '') && (pass.value == '') && (rePass.value == ''))
	{
		setGlyph($glyph, 0, 0, MARKER_NOMARK);
		
		if(verifyAll)
			verifyPassword(successCallback, verifyAll);
		else 
			return true;
	}
	else if(passVal.length < 6)
		setGlyph($glyph, $pop, 'Password must be atleast 6 characters long.', MARKER_XMARK);
	else
	{		
		$.post("/verifyPassword.php", {p: passVal}, function(data)
		{
				if(data == "0")
				{
					setGlyph($glyph, 0, 0, MARKER_CHECKMARK);
					valid = true;
					
					if(verifyAll)
						verifyPassword(successCallback, verifyAll);
				}
				else
					setGlyph($glyph, $pop, ' Password is incorrect.', MARKER_XMARK);
		});
	}
	
	return valid;
}

function verifyEmail(successCallback, verifyAll)
{
	var emailVal = $('#email_input').val();
	
	emailPopup.innerHTML = '';
	
	$glyph = $('#email_input').next();
	$pop = $('#email-popup');
	
	if(emailVal == '')
	{
		setGlyph($glyph, 0, 0, MARKER_NOMARK);
		if(verifyAll)
		{
			successCallback();
			return;
		}
		else
			return true;
	}
	
	if(emailVal.length < 4)
		setGlyph($glyph, $pop, 'Email must be atleast 4 characters long.', MARKER_XMARK);
	else if((emailVal.indexOf("@") == -1) || (emailVal.indexOf(".") == -1))
		setGlyph($glyph, $pop, ' Email is invalid.', MARKER_XMARK);
	else
	{
		$.post("/verifyEmail.php", {e: emailVal}, function(data)
		{
			if(data !== '0')
			{
				setGlyph($glyph, $pop, ' Email is already in use.', MARKER_XMARK);
			}
			else
			{
				setGlyph($glyph, 0, 0, MARKER_CHECKMARK);
				
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
	$glyph = $('#recovery-email').next();
	$pop = $('#recovery-email-popup');
	
	$pop.html('');
	
	if($('#recovery-email').val())
	{
		$.post("/verifyEmail.php", {e: $('#recovery-email').val()}, function(data)
		{
			if(data !== '0')
				setGlyph($glyph, 0, 0, MARKER_CHECKMARK);
			else
				setGlyph($glyph, $pop, 'There is no account linked to that email.', MARKER_XMARK);
		});	
	}
	else
		setGlyph($glyph, 0, 0, MARKER_NOMARK);
}

function setGlyph($glyph, $msg, msg, marker)
{
	if(marker == MARKER_NOMARK)
		$glyph.removeClass('success remove');
	if(marker == MARKER_CHECKMARK)
		$glyph.addClass('success').removeClass('remove');
	if(marker == MARKER_XMARK)
		$glyph.addClass('remove').removeClass('success');
		
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

function showRegister(message)
{
	hideAll();
	$('#registerheader').html(message);
	$('#registerpopup').show();
}

function resetRegister()
{
	$('#registerpopup :input').not('[type="submit"]').val('');
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
	
	var notAloneEl = $('[data-vid="'+id+'"]').first();
	var aloneEl = notAloneEl.next();
	
	var vote = $button.is(notAloneEl) ? 0 : 1;
	
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
	.done(function(data)
	{
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
		notAloneEl.html("You're not alone ("+notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,')+")");
		
		aloneEl.attr('data-vc',alone);
		aloneEl.html("You're alone ("+alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,')+")");
	});
}

//Analytics
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-67136428-1', 'auto');
ga('send', 'pageview');

$('#pwrecoveryform').submit(function()
{
	$.post('/recover.php', {e: $(this).children().first().val(), s: 1}, function(data){ createPopup(data); });
	$(this).parent().hide();
	return false;
});

$(document).ready(function()
{
	$('[data-limiter]').each(function()
	{
		var $limiter = $(this);
		var limit = parseInt($limiter.attr('data-limit'));
		
		$($limiter.attr('data-limiter')).on('change keypress paste',function(event)
		{
			var charCount = $(this).val().length;
			
			if(charCount > (limit-1))
			{
				if(event.key != "Backspace")
				{
					event.stopPropagation();
					event.stopImmediatePropagation();
					event.preventDefault();
					
					return false;
				}
			}
		});
		$($limiter.attr('data-limiter')).on('change keyup paste',function(event)
		{
			var charCount = $(this).val().length;
			$limiter.html(limit-charCount);
		});
	});
	
	$('[data-toggle="tooltip"]').tooltip();
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

$('.dropdown-menu a').click(function(){ $('#' + $(this).parent().parent().attr('data-dropdown')).html($(this).html()); $(this).parent().parent().trigger("change"); });

$('#save-button').click(function(){ verifyUser(function(){ $('#settings-form').submit(); } ,true); return false; });

$('#user_input').keydown(function(event){ checkLimit(event, $(this)[0], 32, false); });

$('#user_input').keyup(function(){ verifyUser(0, false); });
$('#pass_input').keyup(function(){ verifyPassword(0, false); });
$('#repass_input').keyup(function(){ verifyRePassword(0, false); });
$('#email_input').keyup(function(){ verifyEmail(0, false); });
$('#recovery-email').keyup(function(){ verifyRecoveryEmail(); });

//$('[data-err-popup]').mouseover(function(){ if($(this).next().first().html()) $(this).next().first().show(); });
//$('[data-err-popup]').mouseout(function(){ $(this).next().first().hide(); });

$('body').on('click', '.popup > .buttons > button', function(){ $(this).parent().parent().hide(); });
$('body').on('click', '[data-vid]', function(){ vote($(this)); });

$('.showlogin').click(function(){showLogin();});

$('#registerpopup form').submit(function(){ register(); return false;  });

$('[data-signup-header]').click(function(){showRegister($(this).attr('data-signup-header'));});
$('[data-signup-hide]').click(function(){ $('registerpopup').hide(); resetRegister();});

$('body').on('click', '[data-show]',function(){ $($(this).attr('data-show')).show(); return false; });
$('body').on('click', '[data-hide]',function(){ $($(this).attr('data-hide')).hide(); return false; });
$('body').on('click', '[data-togg]',function(){ $($(this).attr('data-togg')).toggle(); return false; });
