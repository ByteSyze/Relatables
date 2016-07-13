/*Copyright (C) Tyler Hackett 2014*/

var subIndex = 0;
var subCount = 20;

var guidelineHeight = 0;

var mediaType = "none";

var $imgPreview;
var $vidPreview;

var invalidImage = false;

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

$('body').on('change', '#media-upload-controls input', function()
{
	mediaType 		= $(this).attr('name');
	$uploadControls = $('#media-upload-controls');
	$uploadPreview 	= $('#media-preview');
	
	if(mediaType == 'image')
	{
		$imgPreview.attr('src', URL.createObjectURL($(this)[0].files[0]));
	}
	else if(mediaType == 'video')
	{
		$vidPreview.attr('src', $(this).val());
		$vidPreview.parent().show();
		
		$uploadControls.hide();
		$uploadPreview.parent().show();
	}
	else
	{
		//TODO
	}
});

$('#media-upload-cancel').click(function()
{
	$('#media-upload-verification, #media-upload-controls').toggle(); 
	
	$imgPreview.attr('src', '');
	$vidPreview.attr('src', '');
	
	$vidPreview.parent().hide();
	
	mediaType = "none";
});

$('#sort, #display, #category, #nsfw').on('change', function()
{
	paginate();
});

$("#submission-form").submit(function() 
{
	submission = $("#submission-input").val();
	//$( "#submission-wrapper" ).append("");
	category = $("#submit-category").val();
	$a = $('#anonymous');
	chk = $a[0].checked;
	if(chk === true){
		anonymous = 1;
	}
	else{
		anonymous = 0;
	}
	//objData = {s: submission, c: category, a: anonymous };
	//objData = validate_data(objData);
	var fData = new FormData();
	fData.append('s', submission);
	fData.append('c', category);
	fData.append('a', anonymous);
	fData.append('m', mediaType);
	
	if(mediaType == 'image')
		fData.append('i', $('#smu-image-tab input')[0].files[0]);
	else if(mediaType == 'video')
		fData.append('v', $('#smu-video-tab input').val());
	
	if(fData){
		$.ajax({ type: "POST", url: "/submit.php", data: fData, processData: false, contentType: false, success: function( res )
		{
			if(res == '0')
		 		$("#submission-form").empty().animate({height: "33px"}, 400, function(){$("#submission-form").append("<div id='success_msg'>Your post has been submitted successfully and is now being moderated.<br> You will receive a notification if your post gets approved.</div>");});
			else if(res ==	'1')
				$("#submission-input").css("box-shadow", "0px 0px 5px #DD0000");
			else if(res == '2')
				$("submit-category").css("box-shadow", "0px 0px 5px #DD0000");
		}});
	}
    return false;
});

$("#submission-form input[type='submit']").click(function()
{
	$("#submission-form").submit();
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
			if(!$('#media-upload-modal').is(':visible'))
			{
				$('.post-guidelines').animate({'height': '0px'},200);
				guidelineHeight = 0;
			}
		}
	}, 100);
});

$(document).ready(function()
{	
	paginate();
	
	$('.post-guidelines').height('0px');
	
	$imgPreview = $('#media-preview img');
	$vidPreview = $('#media-preview iframe');
	
	$vidPreview.parent().hide();
	
	$imgPreview.on('error', function()
	{
		$('#media-upload-errors').html('<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span class="glyphicon glyphicon-warning-sign"></span> Oops! The file you tried to use couldn\'t be loaded.</div>');
	});
	$imgPreview.on('load', function()
	{
		if($('#media-upload-controls input[type="file"]')[0].files[0].size > 2000000)
		{
			$('#media-upload-errors').html('<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span class="glyphicon glyphicon-warning-sign"></span> Oops! The maximum image size is 2MB. Please shrink your image.</div>');
		}
		$uploadControls.hide();
		$uploadPreview.parent().show();
	});
});


function validate_data(objData)
{
	s = objData.s;
	c = objData.c;
	if(!s.trim()) {
		$("#submission-input").css("box-shadow", "0px 0px 5px #DD0000");
		return false;
	}
	else
		$("#submission-input").css("box-shadow", "");
		
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
 