/*Copyright (C) Tyler Hackett 2014*/

$("[id^='bna']").click(function(){ vote($(this).attr('data-vid'), 0, $(this).attr('data-v')); });
$("[id^='ba']").click(function(){ vote($(this).attr('data-vid'), 1, $(this).attr('data-v')); });

function vote(id, vote, v)
{
	var notAloneEl  = document.getElementById('na'+id);
	var aloneEl 	= document.getElementById('a'+id);
	
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
			
			document.getElementById('bna'+id).disabled = false;
			document.getElementById('ba'+id).disabled = true;
			
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '01')
		{	
			notAlone++;
			alone--;
			
			document.getElementById('bna'+id).disabled = true;
			document.getElementById('ba'+id).disabled = false;
			
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '10')
		{
			alone++;
			document.getElementById('ba'+id).disabled = true;
			aloneEl.innerHTML = '(' + alone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
		else if(data == '11')
		{
			notAlone++;
			document.getElementById('bna'+id).disabled = true;
			notAloneEl.innerHTML = '(' + notAlone.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,') + ')';
		}
	});
}