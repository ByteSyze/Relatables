/*Copyright (C) Tyler Hackett 2014*/

var subIndex = 0;
var subCount = 20;

var guidelineHeight;

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

$('body').on('click', '[data-p]', function()
{
	page = parseInt($(this).attr('data-p')); 
	paginate();
});

$(window).scroll(function()
{
	if($('#display').val() == 2)
	{
		if($(window).scrollTop() + $(window).height() == $(document).height())
		{
			page++;
			paginate();
		}
	}
});

$('#sort, #display, #category, #nsfw').change(function()
{
	paginate();
});

$('#prev').click(function()
{
	if(page > 0)
	{
		page -= 1;
		paginate();
	}
});

$('#next').click(function()
{
	page += 1;
	paginate();
});

$( "#submission-form" ).submit(function() {

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

$('#submission').focus(function()
{
	$('.post-guidelines').animate({'height': guidelineHeight+'px'},200);
});

$('#submission').blur(function()
{
	$('.post-guidelines').animate({'height': '0px'},200);
});

$(document).ready(function()
{
	$('#category').val(category);
	$('#display').val(display);
	$('#sort').val(order);
	
	paginate();
	
	guidelineHeight = $('.post-guidelines').height() + 10;
	$('.post-guidelines').height('0px');
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

function paginate()
{
	
	if($('#display').val() < 2)
	{
		var page_start = 6 * Math.floor(page/6);
	
		$('.bottom-navigation').show();
		$('.page-buttons').empty();
		
		for(var i = page_start-1; i < page_start+7; i++)
		{
			if(i >= 0)
			{
				if(i === page)
					$('.page-buttons').append("<span data-p='" + i + "' class='button blue'>" + (i+1) + "</span>");
				else
					$('.page-buttons').append("<span data-p='" + i + "' class='button blue-hover'>" + (i+1) + "</span>");
			}
		}
	}
	else
	{
		$('.bottom-navigation').hide();
	}
	
	updatePosts();
	updateUrl();

}

function updateUrl()
{
	var newurl = "?";
	
	if($('#category').val() != 0){
		newurl = newurl+"&c="+($('#category').val()).toString();
	}
	
	if($('#sort').val() != 0){
		newurl = newurl+"&o="+($('#sort').val()).toString();
	}
	
	if($('#display').val() != 0){
		newurl = newurl+"&d="+($('#display').val()).toString();
	}
	
	if(page != 0){
		newurl = newurl+"&p="+page.toString();
	}
	
	var stateObj = {index: "index"};
    
	window.history.replaceState(stateObj,'',newurl);	

}

function updatePosts()
{
	var x;
	
	if($('#display').val() != 1)
		x = 20;
	else
		x = 50;

	$.post('/getposts.php', {s:page, x:x,  o:$('#sort').val(),  c:$('#category').val(), n:1}, function(data)
	{
		
		if($('#display').val() < 2)
		{
			$('#posts').empty();
		}
			
		$('#posts').append(data);
	});
}
 