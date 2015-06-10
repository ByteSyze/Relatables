/*Copyright (C) Tyler Hackett 2014*/

var descriptionCounter = document.getElementById('desc_char_counter');

function charCount(element,counter)
{
	counter.innerHTML = '(' + (130-element.value.length) + ' characters left)';
}

function checkEntered(element, event)
{
	var keyCode = ('which' in event) ? event.which : event.keyCode;
	
	if(keyCode == 13)
		event.preventDefault();
}

$(document).ready(function(){  $('#pass_input').parent().hide(); });

$('#currentpass_input').keyup(function(){ verifyCurrentPassword(0, false); checkErrPopups($(this)); });
$('#currentpass_input').parent().click(function()
{ 
	$('#pass_input').parent().show(); 
	$(this).animate({'padding-left':'50px'});
});
