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
				$('#comments').children().first().before(comment);
				
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
	$c = $(this).parent().parent();
	
	$(this).parent().after("<div class='reply-input'><textarea class='reply input-submit small'></textarea><button data-r='"+$c.attr("data-r")+"' data-user='"+$c.attr("data-user")+"' class='button blue-hover smaller'>Submit</button></div>");
	$(this).removeAttr('data-reply');
});
$(document).on("click", "button[data-r]", function()
{
	//Grab value from the textarea behind the reply button.
	comment = $(this).prev().val();
	button = $(this);
	
	rid = $(this).attr('data-r');
	user = $(this).attr('data-user');
	
	if(comment.length <= 140)
	{
		$.post('/comment.php', {p: pid, c: comment, r: rid, u: user}, function(result)
		{
			if(result != 0)
			{
				var commentEl = $.parseHTML(result);
				var lastReply = button.parent().parent();
				
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
$(document).on("click", "span[data-v]", function()
{
	button = $(this);
	
	if(!button.data('disabled'))
	{
		cid = $(this).parent().parent().attr('data-c');
		vote = $(this).attr('data-v');
	
		$.post('/ratecomment.php', {c: cid, v: vote}, function(result)
		{
			$p = $('#points-'+cid);
			points = parseInt($p.html());
			
			if(vote == 'up')
			{
				points += parseInt(result);
				
				if(points > 0)
				{
					$p.addClass('positive');
					$p.removeClass('negative');
				}
				else
				{
					$p.addClass('negative');
					$p.removeClass('positive');
				}
				
				$p.html(points);
			}
			else
			{
				points -= parseInt(result);
				
				if(points < 0)
				{
					$p.addClass('negative');
					$p.removeClass('positive');
				}
				else
				{
					$p.addClass('positive');
					$p.removeClass('negative');
				}
					
				$p.html(points);
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
	
	$.post('/deletecomment.php', {c: cid}, function(result)
	{
		button.nextAll().eq(3).html('<i>Comment removed.</i>');
		button.remove();
	});
});
$(document).on("click", "span[data-showmore]", function()
{
	show = $(this);

	$.post('/getcomments.php', {i: pid, x: show.attr('data-showmore'), c: 10}, function(result)
	{
		$('#comments').append(result);
		show.remove();
	});
});
$(document).on("click", "span[data-r-showmore]", function()
{
	show = $(this);

	$.post('/getreplies.php', {i: show.parent().attr('data-r'), x: show.attr('data-r-showmore')}, function(result)
	{
		show.parent().parent().append(result);
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


$('#comment-sort').change(function()
{
	$.post('/getcomments.php', {i: pid, x: 0, c: 10, s:$('#comment-sort').val()}, function(result)
	{
		$('#comments').empty();
		$('#comments').append(result);
	});
});
