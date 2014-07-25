/*Copyright (C) Tyler Hackett 2014*/
function showSubmissionGuidelines()
{
	var submission = document.getElementById('submission-wrapper');
	
	var a = 0.5;
	
	var interval = setInterval(function(){expand(submission,interval)},5);
}
function expand(element,interval)
{
	var height = parseInt(element.style.height);
	if(height < 280)
		element.style.height = height+1+'px';
	else
		clearInterval(interval);
}
