/*Copyright (C) Tyler Hackett 2014*/

var loginPopup = document.getElementById("loginpopup");
var loginOpen = false;

var registerPopup = document.getElementById("registerpopup");
var registerHeader = document.getElementById("registerheader");
var registerOpen = false;

function hideAll()
{
	if(loginOpen)
		hideLogin();
	if(registerOpen)
		hideRegister();
}

function toggleProfileDropdown()
{
	var dropdown = document.getElementById("profile-dropdown");
	
	if(dropdown.style.display == "block")
		dropdown.style.display = "none";
	else
		dropdown.style.display = "block";
}

function showLogin()
{
	hideAll();
	loginOpen = true;
	loginPopup.style.display = "block";
}
function hideLogin()
{
	loginOpen = false;
	loginPopup.style.display = "none";
}

function showRegister(element)
{
	hideAll();
	registerOpen = true;
	registerHeader.innerHTML = element.getAttribute("data-header");
	registerPopup.style.display = "block";
}

function hideRegister()
{
	registerOpen = false;
	registerPopup.style.display = "none";
	
	for(input in registerPopup.getElementsByTagName('input'))
		input.value = '';
}

function showGuidelines(element)
{
	element.firstChild.style.display="block";
}

function hideGuidelines(element)
{
	element.firstChild.style.display="none";
}

function showErrors(element)
{
	var popup = element.nextSibling.children[0];
	
	if(popup.innerHTML)
		popup.style.display = 'block';
}

function hideErrors(element)
{
	element.nextSibling.children[0].style.display = 'none';
}

