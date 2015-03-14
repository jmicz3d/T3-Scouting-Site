//########################################################################################################################
// Initialize all event listeners and load homepage

var searches = [];
document.addEventListener("DOMContentLoaded", function() {init()}, false);

init = function() {
	var inputBox = document.getElementById("inputBox");
	inputBox.addEventListener("keyup", function(event) {processKeyUp(event)}, false);
	inputBox.addEventListener("focus", function() {pushHeader()}, false);
	inputBox.addEventListener("blur", function() {pullHeader()}, false);
	
	document.getElementById("back").addEventListener("click", function() {goBack()}, false);
	document.getElementById("home").addEventListener("click", function() {displayHomepage()}, false);
	
	document.getElementById("body").addEventListener("click", function() {pullHeader()}, false);
	displayHomepage();
}

//#########################################################################################################################
// Key event section
// processes key events

processKeyUp = function(event)  // Executes when a key is let up.
{
	event = event || window.event;
	if (event.keyCode == 10 || event.keyCode == 13) { // If CR or LF is pressed (Enter/Return)
		event.preventDefault();
		showUser(false);
	} else if (event.keyCode == 189 || event.keyCode == 8 || event.keyCode == 46 ||	// If any letter, number, delete, or backspace is pressed
				(event.keyCode >= 48 && event.keyCode <= 57) ||
				(event.keyCode >= 65 && event.keyCode <= 90) ||
				(event.keyCode >= 96 && event.keyCode <= 105)) {
		showHint(document.getElementById("inputBox").value);
		setTimeout(function() {makeClickable("c-list-table")}, 250, false); // makeClickable in interactive.js
	}
}




//End of Key event section
//############################################################################################################################
//Body content section
//Modifies the body section of the page

displayHomepage = function()
{   //Resets the body div tag to the homepage
	if (window.XMLHttpRequest)												//Setting up AJAX stuff 
	  {// code for IE7+, Firefox, Chrome, Opera, Safari						//	|  Creating objects
	  xmlhttp=new XMLHttpRequest();											//	|	|
	  }																		//	|	|
	else																	//	|	|
	  {// code for IE6, IE5													//	|	|
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");						//	|	|
	  }																		//	|  Done setting up objects
																			//	|
	xmlhttp.onreadystatechange=function()									//	|  Setting up the onreadystatechange
	  {																		//	|	|
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)						//	|	|	On readyState==4 (when a response is recieved):
		{																	//	|	|
		document.getElementById("body").innerHTML=xmlhttp.responseText;		// 	|	|	Make the inner html of "body" the response text of the ajax query
		}																	//	|	|
	  }																		//	|  Done setting up onreadystate change
																			//Done setting up
																			//
	document.getElementById("inputBox").value = "";
	xmlhttp.open("GET","./PHP/home_page.php",true);						//Open socket
	xmlhttp.send();	

	showUser(false);
	searches.pop();
	//searches.push("Home");

	//console.log(searches);	//Send query
}

showUser = function(back)
{
	var str = document.getElementById("inputBox").value;					// Set up and send AJAX stuff
	var  year = document.getElementById("year").value;
	
	if (str == "")  {
		return;
	} else if (str.toLowerCase() == "home" ){
		displayHomepage();
		return;
	}
	
	if (window.XMLHttpRequest)  {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();

	}  else  {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function()  {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)  {
			document.getElementById("body").innerHTML = xmlhttp.responseText;		// onreadystate make body response text
		}
	}
	if (str === "help") {
		xmlhttp.open("GET", "./PHP/help.php");
	} else {
		xmlhttp.open("GET", "./PHP/data.php?q=68&y=2014", true);					// send the server what is currently in the search box
	}
	xmlhttp.send();
	document.getElementById("inputBox").blur();								// Clean up and indirectly (event listener to blur) pull header
	document.getElementById("inputBox").value = "";
	setTimeout(function() {makeClickable("c-event-table", "c-event-team-table")}, 250, false);
	if (back) {
		searches.pop();
	} else {
		searches.push(str);
	}
}

showHint = function(str)		//show hints in the dropdown. Takes what is currently in the search box as input.
{		
	
	if (str.length == 0)													//if there is nothing in the search box:
	{
		document.getElementById('c-p-e-container').innerHTML = "";
		return;
	}
		
		if (window.XMLHttpRequest)												//Otherwise, Setup ajax stuff
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
			xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("c-p-e-container").innerHTML = xmlhttp.responseText;	// onreadystatechange modify the dropdown with the response
		}
		}
		
		xmlhttp.open("GET","./PHP/list.php?q="+str,true);						//pass the current value of the search box to the server
		xmlhttp.send();
}

//End of body content section
//############################################################################################################################
