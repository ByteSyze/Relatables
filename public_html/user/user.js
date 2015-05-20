/*Copyright (C) Tyler Hackett 2015*/

$(body).ready(function(){ $.post('getrelated.php', {i: 0}, function(data){ $('#posts').append(data); }); });

$(body).on('click', '[data-getrel]', function()
{
	$loader = $(this);
	$.post('getrelated.php', {i: $loader.attr('getrel')}, function(data)
	{
		$loader.remove();
		$('#posts').append(data);
	});
});
