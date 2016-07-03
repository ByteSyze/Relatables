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
	pageAttr = $(this).attr('data-p');
	
	if(pageAttr == 'prev')
	{
		if(page > 0)
		{
			page -= 1;
		}
	}
	else if(pageAttr == 'next')
	{
		page += 1;
	}
	else
		page = parseInt(pageAttr); 
	
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

$('#media-upload-controls input').on('change', function()
{
	var name 		= $(this).attr('name');
	$uploadControls = $('#media-upload-controls');
	$uploadPreview 	= $('#media-preview');
	
	$uploadControls.hide();
	$uploadPreview.parent().show();
	
	if(name == 'image')
	{
		$uploadPreview.html($(this).val());
	}
	else if(name == 'video')
	{
		$upladPreview.html("<iframe width='560' height='315' src='"+ $(this).val() +"' frameborder='0' allowfullscreen></iframe>");
	}
	else
	{
		//TODO
	}
});

$('body').on('click', '#media-upload-cancel', function()
{
	$('#media-upload-verification, #media-upload-controls').toggle(); 
});

$('#sort, #display, #category, #nsfw').on('change', function()
{
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
		if($(document.activeElement).parents('#submission-form').length == 0)
		{
			$('.post-guidelines').animate({'height': '0px'},200);
			guidelineHeight = 0;
		}
	}, 100);
});

$(document).ready(function()
{	
	paginate();
	
	$popoverContentTemplate = $("#media-popover-content");
	$popoverTitleTemplate = $("#media-popover-title");
	
	$('.post-guidelines').height('0px');
	$('#media-popover-btn').popover({
        html: true, 
		placement: "bottom",
		viewport: ".post-guidelines",
		content: function(){ return $popoverContentTemplate.html(); },
		title: function(){ return $popoverTitleTemplate.html(); }
	});
	
	$popoverContentTemplate.remove();
	$popoverTitleTemplate.remove();
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
	$paginationNav = $('#pagination-nav');
	
	if($('#display').val() != 'Continuous')
	{
		var page_start = 4 * Math.floor(page/4);
	
		$paginationNav.show();
		$paginationNav.children('.pagination').children().not('[data-p="next"], [data-p="prev"]').remove();
		
		for(var i = page_start-1; i < page_start+5; i++)
		{
			if(i >= 0)
			{
				if(i === page)
					$paginationNav.find('[data-p="next"]').before("<li><a href='#' role='button' data-p='" + i + "' class='blue'>" + (i+1) + "</a></li>");
				else
					$paginationNav.find('[data-p="next"]').before("<li><a href='#' role='button' data-p='" + i + "'>" + (i+1) + "</a></li>");
			}
		}
	}
	else
	{
		$paginationNav.hide();
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
 