/*Copyright (C) Tyler Hackett 2014*/

var openedShareText = 'Share «';
var closedShareText = 'Share »';

var subIndex = 0;
var subCount = 20;

$("[id^='bna']").click(function(){ vote($(this).attr('data-vid'), 0, $(this).attr('data-v')); });
$("[id^='ba']").click(function(){ vote($(this).attr('data-vid'), 1, $(this).attr('data-v')); });
$(".showguides").click(function(){ showSubmissionGuidelines(); });
$("#submission").on('change keypress paste', function(event)
{
	var count = checkLimit(event, this, 300, true);
	var counter = $("#post-counter");
	
	counter.html(count);
	
	if(count == 0)
		counter.css('color', '#c82828');
	else
		counter.css('color', '#BFBFBF');
});
$('body').on('click', '#qotw-submit', function() {
	var val = $('input:radio[name=v]:checked').val();
	
	if( $('input:radio[name=v]').is(":checked") ){
		vote = { v : val };
		$.post( "/qotw.php", vote, function( res ) {
			$( "#qotw-wrapper" ).empty().append(res+'<h3 id="vote-msg">Thanks for voting!</h3>');
		});
	}
	return false;
});
$('body').on('click','[data-share-button]',function()
{
	var button = $(this);
	if(button.text() == openedShareText)
		button.next().animate({width: '0px'},500,function(){button.html(closedShareText);});
	else
		button.next().animate({width: '115px'},500,function(){button.html(openedShareText);});
});

function vote(id, vote, v)
{
	var notAloneEl  = document.getElementById('na'+id);
	var aloneEl 	= document.getElementById('a'+id);
	
	// Take out all formatting
	var notAlone = notAloneEl.innerHTML.replace("(","").replace(")","").replace(",",""); 
	var alone = aloneEl.innerHTML.replace("(","").replace(")","").replace(",","");
	
	$.ajax({
		type: "POST",
		url: "/vote.php",
		data: {q: id, vtn: vote, v : v}
	})
	.done(function(data) {
		console.log(data);
		
		if(data == '00')
		{
			alone++;
			notAlone--;
			
			document.getElementById('bna'+id).disabled = false;
			document.getElementById('ba'+id).disabled = true;
			
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '01')
		{	
			notAlone++;
			alone--;
			
			document.getElementById('bna'+id).disabled = true;
			document.getElementById('ba'+id).disabled = false;
			
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '10')
		{
			alone++;
			document.getElementById('ba'+id).disabled = true;
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '11')
		{
			notAlone++;
			document.getElementById('bna'+id).disabled = true;
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
	});
}

function showSubmissionGuidelines()
{
	$('#submission-wrapper').animate({height: "260px"}, 1000);
}

$( "body" ).on( "click", "#submit_form", function() {

	submission = $("#submission").val();
	$( "#submission-wrapper" ).append("");
	category = $("#submit_category").val();
	$a = $('#anonymous');
	chk = $a[0].checked;
	if(chk === true){
		anonymous = 1;
	}
	else{
		anonymous = 0;
	}
	objData = {s: submission, c: category, a: anonymous };
	objData = validate_data(objData);
	if(objData){
		$.post( "/submit.php",objData,function( res ) {
			if(res == '0')
		 		$( "#submission-wrapper" ).empty().animate({height: "17px"}, 400, function(){$( "#submission-wrapper" ).append("<div id='success_msg'>Your post has been submitted successfully and is now being moderated.</div>");});
			else if(res ==	'1')
				$("#submission").css("box-shadow", "0px 0px 5px #DD0000");
			else if(res == '2')
				$("submit_category").css("box-shadow", "0px 0px 5px #DD0000");
		});
	}
    return false;
});

function validate_data(objData){
	s = objData.s;
	c = objData.c;
	if(!s.trim()) {
		$("#submission").css("box-shadow", "0px 0px 5px #DD0000");
		return false;
	}
	else
		$("#submission").css("box-shadow", "");
		
	if(!c.trim()) {
		$("#submit_category").css("box-shadow", "0px 0px 5px #DD0000");
		return false;
	}
	else
		$("#submit_category").css("box-shadow", "");
		
	return objData;
}

$(document).ready(function()
{
	$.post('/getsubmissions.php', {o:1, c:0, n:0, s:subIndex, x:subCount}, function(data)
	{
		$('#submission-wrapper').append(data);
	});
});
 