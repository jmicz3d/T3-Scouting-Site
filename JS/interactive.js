pullHeader = function()
{
	var header = document.getElementById("header");
	var cp = document.getElementById("c-p-e-container");
	header.style.height = "40px";
	header.style.overflowY = "hidden";
	cp.style.display = "none";
}

pushHeader = function()
{
	var header = document.getElementById("header");
	var cp = document.getElementById("c-p-e-container");
	header.style.height = "250px";
	setTimeout(function() {cp.removeAttribute("style");}, 500);
	setTimeout(function() {header.style.overflowY = "auto";}, 500);
}

clicked = function(event)
{
	var input = document.getElementById('inputBox');
	var card = event.currentTarget;
	var title = document.getElementById("title");
	if (card.className == "c-list-table")
	{
		var temp = card.children[0].children[0].children[0].innerHTML.split(" - ");
		input.value = temp[0];
	}
	else if (card.className == "c-event-team-table")
	{
		var eventID = card.children[0].children[0].children[0].innerHTML.split(" - ");
		input.value = eventID[1];
	}
	else if (card.className == "c-event-table")
	{
		input.value = card.children[0].children[0].children[0].innerHTML;
	}
	showUser(false);
}

makeClickable = function(name, name2)
{
	var results = document.getElementsByClassName(name);
	var results2 = document.getElementsByClassName(name2);

	if (results.length || results2.length)
	{
		for (var i = 0; i < results.length; i++)
		{
			results[i].addEventListener('mousedown', function(event) {clicked(event);}, false);
		}
		for (var i = 0; i < results2.length; i++)
		{
			results2[i].addEventListener('mousedown', function(event) {clicked(event);}, false);
		}
		return;
	}
}

goBack = function()
{
	var input = document.getElementById("inputBox");
	var value = searches[searches.length - 2];
	if (!value || value == "Home")
	{
		searches.pop();
		searches.pop();
		value = "home";
	}
	input.value = value;
	showUser(true);
}
