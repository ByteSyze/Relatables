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
				
				if($('#comments').children().length == 0)
					$('#comments').append(comment);
				else
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
	
	$(this).parent().after("<div class='reply-input'><textarea class='reply input-submit small'></textarea><span style='margin-right:10px;line-height:30px;' data-limiter='#c"+$c.attr('data-c')+" textarea' data-limit='800'>800</span><button data-r='"+$c.attr("data-r")+"' data-user='"+$c.attr("data-user")+"' class='button blue-hover smaller'>Submit</button></div>");
	$(this).removeAttr('data-reply');
	
	var $limiter = $("span[data-limiter='#c"+$c.attr('data-c')+" textarea']");
	var limit = 800;
	
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

	cid 	= button.parent().parent().attr('data-c');
	vote 	= button.attr('data-v');
	rescind = (button.hasClass('positive') || button.hasClass('negative'));

	$.post('/ratecomment.php', {c: cid, v: vote, r: rescind}, function(result)
	{
		$p = $('#points-'+cid);
		points = parseInt($p.html());
		
		if(rescind)
		{
			if(result == '1')
			{
				points -= 1;
				
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
				
				console.log(points);
				$p.html(points);
			}
		}
		else
		{
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
		}	
	});
});
$(document).on("click", "span[data-delete]", function()
{
	button = $(this);
	cid = $(this).parent().parent().attr('data-c');
	
	$.post('/deletecomment.php', {c: cid}, function(result)
	{
		button.parent().prev().first().html('<i>Comment removed.</i>');
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
	cid = $(this).parent().parent().attr('data-c');
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
