/*Copyright (C) Tyler Hackett 2014*/
var reader = new FileReader();
var preview = document.getElementById("preview-img");
var cheatsheetVisible = false;

var oldContents;
var newContents;
var selPoint;

$('#submit').click(function(event)
{
	if(!$('#article-title').val())
	{
		$('#article-title').css('box-shadow', '0px 0px 10px red');
		return false;
	}
	if(!$('#article-contents').val())
	{
		$('#article-contents').css('box-shadow', '0px 0px 10px red');
		return false;
	}
});
$('#article-img').change(function(event)
{
	var file = document.getElementById("article-img").files[0];

	if (!file.type.match(/image.*/))
		$('#article-img').css('box-shadow', '0px 0px 10px red');

	preview.file = file;	
	reader.onload = function (e) {
		$('#preview-img').attr('src', e.target.result);
	}

	reader.readAsDataURL(file);
		
});

$('#cheatsheet-view-button').click(function(event)
{
	if(cheatsheetVisible)
	{
		$('#cheatsheet').animate({left:"-310px"}, 1000);
		cheatsheetVisible = !cheatsheetVisible;
	}
	else
	{
		$('#cheatsheet').animate({left:"0px"}, 1000);
		cheatsheetVisible = !cheatsheetVisible;
	}
});
$("#article-contents").keypress(function(event)
{
	if(event.keyCode == 13)
	{
		oldContents = $(this).val();
		selPoint = $(this)[0].selectionStart;
		
		newContents = oldContents.slice(0, selPoint) + "<br>\r\n" + oldContents.slice(selPoint, oldContents.length);
		$(this).val(newContents);
		
		$(this)[0].setSelectionRange(selPoint+=5, selPoint);
		
		return false;
	}
});

$("#article-title").on('keyup change paste', function(event){ $('#preview-title').html($(this).val()); });
$("#article-contents").on('keyup change paste', function(event){ $('#preview-contents').html($(this).val()); });
$('#cheatsheet-view-button').mouseout(function(event){if(!cheatsheetVisible) $('#cheatsheet').animate({left:"-310px"}, 500); });
$('#cheatsheet-view-button').mouseover(function(event){if(!cheatsheetVisible) $('#cheatsheet').animate({left:"-290px"}, 500); });
