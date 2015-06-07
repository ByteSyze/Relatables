/*Copyright (C) Tyler Hackett 2015*/

function verifyRecoveryPassword(successCallback, verifyAll)
{
	var passVal = $('#rec_pass_input').val();	
	
	$img = $('#rec_pass_input').next();
	$pop = $('#rec-new-password-popup');
	
	$pop.html('');
	
	if(passVal.length < 6)
		setMarker($img, $pop, 'Password must be atleast 6 characters long.', false);
	else
	{
		setMarker($img, 0, 0, true);
		verifyRePassword(successCallback);
	}
	
	if(verifyAll)
	{
		verifyRePassword(successCallback, verifyAll);
		return true;
	}
	else 
		return true;
}

function verifyRecoveryRePassword(successCallback, verifyAll)
{
	var passVal 		= $('#rec_pass_input').val();
	var rePassVal 		= $('#rec_repass_input').val();
	
	$img = $('#rec_repass_input').next();
	$pop = $('#rec-renew-password-popup');
	
	$pop.html('');
	
	if(passVal !== rePassVal)
		setMarker($img, $pop, 'Password verification doesn\'t match original password.', false);
	else
	{
		setMarker($img, 0, 0, true);
		
		if(verifyAll)
			successCallback();
		else 
			return true;
	}
}

$("#rec_submit").click(function()
{
	verifyRecoveryPassword(function()
	{
		$("form[action='/resetpassword.php']").submit();
	},true);
	
	return false;
});

$('#rec_pass_input').keyup(function(){ verifyRecoveryPassword(0, false); checkErrPopups($(this)); });
$('#rec_repass_input').keyup(function(){ verifyRecoveryRePassword(0, false); checkErrPopups($(this)); });