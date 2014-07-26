/*Copyright (C) Tyler Hackett 2014*/
function vote(id, vote, notAloneEl, aloneEl, v)
{
	// Take out all formatting
	var notAlone = notAloneEl.innerHTML.replace("(","").replace(")","").replace(",",""); 
	var alone = aloneEl.innerHTML.replace("(","").replace(")","").replace(",","");
	
	$.ajax({
		type: "POST",
		url: "/vote.php",
		data: { q: id, vtn: vote, v : v}
	})
	.done(function(data) {
		console.log(data);
		
		if(data == '00')
		{
			alone++;
			notAlone--;
			
			notAloneEl.disabled='false';
			aloneEl.disabled='disabled';
			
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '01')
		{	
			notAlone++;
			alone--;
			
			notAloneEl.disabled='disabled';
			aloneEl.disabled='false';
			
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '10')
		{
			alone++;
			aloneEl.disabled='disabled';
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '11')
		{
			notAlone++;
			notAloneEl.disabled='disabled';
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
	});
}