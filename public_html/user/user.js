/*Copyright (C) Tyler Hackett 2015*/

$(document).ready(function(){ $.post('getrelated.php', {i: 0, u: u}, function(data){ $('#posts').append(data); }); });

$('body').on('click', '[data-getrel]', function()
{
	$loader = $(this);
	$.post('getrelated.php', {i: $loader.attr('data-getrel'), u: u }, function(data)
	{
		$loader.remove();
		$('#posts').append(data);
	});
});
