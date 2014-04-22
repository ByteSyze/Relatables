/*Copyright (C) Tyler Hackett 2014*/
function vote(element)
{
	var count = element.innerHTML.replace("(","").replace(")","").replace(",",""); // Take out all formatting
	count++;
	element.innerHTML = "(" + count.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ")"; // Re-format
	
	element.parentNode.disabled='disabled';
	
	if (window.XMLHttpRequest)
	{
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","updatevote.php?q="+element.getAttribute("id"),true);
	xmlhttp.send();	
}