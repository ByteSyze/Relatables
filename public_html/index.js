/*Copyright (C) Tyler Hackett 2014*/

var openedShareText = 'Share «';
var closedShareText = 'Share »';

var subIndex = 0;
var subCount = 20;

$("[data-vid]").click(function(){ vote($(this).attr('data-vid'), $(this).html() == 'No, me too!' ? 0 : 1, $(this).attr('data-v')); });
$(".showguides").click(function(){ $('#submission-wrapper').animate({height: "260px"}, 1000); });
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

$('#sort, #display, #category, #nsfw').change(function()
{
	paginate();
});

$('body').on('click', '[data-p]', function()
{
	page = parseInt($(this).attr('data-p')); 
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

$(document).ready(function()
{
	if(nsfw)
		$('#nsfw').prop('checked', true);
	
	$('#category').val(category);
	$('#display').val(display);
	$('#sort').val(order);
	
	paginate();
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
	var page_start = 6 * Math.floor(page/6);
	
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
	
	updatePosts();
}

function updatePosts()
{
	var nsfw;

	if($('#nsfw').prop('checked'))
		nsfw = 1;
	else
		nsfw = 0;
		
	var x;
	
	if($('#display').val() < 2)
		x = 20;
	else
		x = 50;

	$.post('/getposts.php', {s:page, x:x,  o:$('#sort').val(),  c:$('#category').val(),  n:nsfw}, function(data){
		$('#posts').empty();
		$('#posts').append(data);
	});
}
 