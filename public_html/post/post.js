/*Copyright (C) Tyler Hackett 2014*/
$('#comment-submit-button').click(function()
{
	comment = $('#comment-submit-text').val();
	button = $(this);
	
	if((comment.length <= 140) && (comment.length > 0))
	{
		$.post('/comment.php', {p: pid, c: comment, r: 0}, function(result)
		{
			if(result != 0)
			{
				var comment = $.parseHTML(result);
				$('#comment-submit-wrapper').after(comment);
				
				button.prev().css('border', '');
			}
		});
	}
	else
	{
		button.prev().css('border', '1px solid red');
	}
	
	return false;
});
$(document).ready(function()
{
	//Load up first comments.
	$.post('/getcomments.php', {i: pid, x: 0, c: 10}, function(result)
	{
		$('#comments').append(result);
	});
});
$(document).on("click", "span[data-reply]", function()
{
	$c = $(this).parent().parent().parent();
	
	$(this).parent().after("<div class='reply-input'><textarea class='reply input-submit small'></textarea><button data-reply='"+$c.attr("data-reply")+"' data-user='"+$c.attr("data-user")+"' class='button blue-hover smaller'>Submit</button></div>");
	$(this).removeAttr('data-reply');
});
$(document).on("click", "button[data-reply]", function()
{
	//Grab value from the textarea behind the reply button.
	comment = $(this).prev().val();
	button = $(this);
	
	rid = $(this).parent().prev().attr('data-r');
	user = $(this).parent().prev().attr('data-user');
	
	if(comment.length <= 140)
	{
		$.post('http://www.relatablez.com/comment.php', {p: pid, c: comment, r: rid, u: user}, function(result)
		{
			if(result != 0)
			{
				var commentEl = $.parseHTML(result);
				var lastReply = button.parent();
				
				while(lastReply.next().hasClass('reply')) lastReply = lastReply.next();
				
				lastReply.after(commentEl);
				button.parent().remove();
			}
		});
	}
	else
	{
		//TODO red border around comment box.
	}
	
	return false;
});
$(document).on("click", "button[data-v]", function()
{
	button = $(this);
	
	if(!button.data('disabled'))
	{
		cid = $(this).parent().parent().parent().attr('data-c');
		vote = $(this).attr('data-v');
	
		$.post('http://www.relatablez.com/ratecomment.php', {c: cid, v: vote}, function(result)
		{
		
			points = parseInt($('#points-'+cid).html());
			
			if(vote == 'up')
			{
				points += parseInt(result);
				
				if(points > 0)
				{
					$('#points-'+cid).addClass('positive');
					$('#points-'+cid).removeClass('negative');
				}
				else
				{
					$('#points-'+cid).addClass('negative');
					$('#points-'+cid).removeClass('positive');
				}
				
				$('#points-'+cid).html(points);
			}
			else
			{
				points -= parseInt(result);
				
				if(points < 0)
				{
					$('#points-'+cid).addClass('negative');
					$('#points-'+cid).removeClass('positive');
				}
				else
				{
					$('#points-'+cid).addClass('positive');
					$('#points-'+cid).removeClass('negative');
				}
					
				$('#points-'+cid).html(points);
			}
			
			button.data('disabled', true);
			
			if(vote == 'up')
				button.next().data('disabled', false);
			else
				button.prev().data('disabled', false);
				
		});
	}
});
$(document).on("click", "button[data-delete]", function()
{
	button = $(this);
	cid = $(this).parent().attr('data-c');
	
	$.post('http://www.relatablez.com/deletecomment.php', {c: cid}, function(result)
	{
		button.nextAll().eq(3).html('<i>Comment removed.</i>');
		button.remove();
	});
});
$(document).on("click", "span[data-show]", function()
{
	show = $(this);

	$.post('/getcomments.php', {i: pid, x: show.attr('data-show'), c: 10}, function(result)
	{
		$('#comments').append(result);
		show.remove();
	});
});
$(document).on("click", "span[data-report]", function()
{
	cid = $(this).parent().attr('data-c');
	button = $(this);

	$.post('/report.php', {c: cid}, function(result)
	{
		console.log(result);
		button.html('<i>Reported</i>');
	});
});
