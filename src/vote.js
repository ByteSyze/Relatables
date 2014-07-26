/*Copyright (C) Tyler Hackett 2014*/
function vote(vote, notaloneEl, aloneEl, v)
{
	// Take out all formatting
	var notalone = notAloneEl.innerHTML.replace("(","").replace(")","").replace(",",""); 
	var alone = aloneEl.innerHTML.replace("(","").replace(")","").replace(",","");
	
	$.ajax({
		type: "POST",
		url: "/vote.php",
		data: { q: vote, v : v}
	})
	.done(function(data) {
		console.log(data);
		if(data == '00')
		{	
			
		}
			vote.innerHTML = '(' + voteCount.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')'; // Re-format
			vote.parentNode.disabled='disabled';
	});
}