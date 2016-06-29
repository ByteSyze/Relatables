/*Copyright (C) Tyler Hackett 2014*/

var subIndex = 0;
var subCount = 20;

var guidelineHeight = 0;

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
	if($('#display').val() == 'Continuous')
	{
		if($(window).scrollTop() + $(window).height() == $(document).height())
		{
			page++;
			paginate();
		}
	}
});

$('#sort, #display, #category, #nsfw').on('change', function()
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

$( "#submission-form" ).submit(function() 
{
	submission = $("#submission").val();
	$( "#submission-wrapper" ).append("");
	category = $("#submit-category").val();
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
		 		$( "#submission-form" ).empty().animate({height: "33px"}, 400, function(){$( "#submission-form" ).append("<div id='success_msg'>Your post has been submitted successfully and is now being moderated.<br> You will receive a notification if your post gets approved.</div>");});
			else if(res ==	'1')
				$("#submission").css("box-shadow", "0px 0px 5px #DD0000");
			else if(res == '2')
				$("submit-category").css("box-shadow", "0px 0px 5px #DD0000");
		});
	}
    return false;
});

$('#submission-form').focusin(function()
{
	if(guidelineHeight == 0)
	{
		$('.post-guidelines').css('height', 'auto');
		guidelineHeight = $('.post-guidelines').height();
		$('.post-guidelines').css('height', '0px');
		
		$('.post-guidelines').animate({'height': guidelineHeight+'px'}, 200, "swing", function(){ $('.post-guidelines').css('height', 'auto'); });
	}
});

$('#submission-form').focusout(function()
{
	setTimeout(function()
	{
		$focused = $('#submission-form').find(':focus');
		
		if($focused.length == 0)
		{
			$('.post-guidelines').animate({'height': '0px'},200);
			guidelineHeight = 0;
		}
	}, 50);
});

$(document).ready(function()
{	
	paginate();
	
	$('.post-guidelines').height('0px');
	$('#media-popover-btn').popover({
        html : true, 
		content: function(){ return $("#media-popover-content").html(); },
		title: function(){ return $("#media-popover-title").html(); }
	});
});


function validate_data(objData)
{
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
	if($('#display').val() != 'Continuous')
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
	
	if($('#category').val() != 'All'){
		newurl = newurl+"category="+$('#category').val();
	}
	
	if($('#sort').val() != 'Newest'){
		newurl = newurl+"&order="+$('#sort').val();
	}
	
	if($('#display').val() != '20'){
		newurl = newurl+"&display="+$('#display').val();
	}
	
	if(page != 0){
		newurl = newurl+"&page="+page.toString();
	}
	
	var stateObj = {index: "index"};
    
	window.history.replaceState(stateObj,'',newurl);
}

function updatePosts()
{
	var x;
	
	if($('#display').val() == '50')
		x = 50;
	else
		x = 20;

	$.post('/getposts.php', {s:page, x:x,  o:$('#sort').val(),  c:$('#category').val(), n:1}, function(data)
	{
		
		if($('#display').val() != 'Continuous')
		{
			$('#posts').empty();
		}
			
		$('#posts').append(data);
	});
	
	if($('#display').val() != 'Continuous')
		window.scrollTo(0,0);
}
 