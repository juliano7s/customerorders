

var currentClientIndex = -1;

function cleanClientForm()
{
	$("#client-id").val("");
	$("#client-name").val("");
	$("#client-email").val("");
	$("#client-phone").val("");
}

function cleanOrderForm()
{
	$("#order-delivery-date").val("");
	$("#order-request-date").val("");
	$("#order-value").val("");
	$("#order-cost").val("");
	$("#order-owner").val("");
	$("#order-description").val("");
}

function cleanClientList()
{
	$('#client-list').html("");
	$('#client-list').hide();
}

jQuery(function($) {
	//mask date input
	$(".date").mask("99/99/9999");
	//$(".currency").mask("");

	/* Disabling enter key - http://docs.jquery.com/UI/Autocomplete */
    $('#client-name').bind("keydown", function(e)
		{
			var code = (e.keyCode ? e.keyCode : e.which);
			if (code == 13)
				return false;
		});

	/* Keyup event for client-name input */
    $('#client-name').bind("keyup", function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		switch (code)
		{
			//http://stackoverflow.com/questions/3024391/how-do-i-iterate-through-child-elements-of-a-div-using-jquery
			case 40: // Down arrow key
				if (currentClientIndex == -1 || currentClientIndex >= $("#client-list").children().length - 1)
					currentClientIndex = 0;
				else
					currentClientIndex++;
				$("#client-list").children().css('background-color', 'white');
				$("#client-list").children().bind("mouseover", function() {
					$("#client-list").children().css('background-color', 'white');
					$(this).css('background-color', 'gray');
				});
				var idx = 0;
				$("#client-list").children().each(function()
					{
						if (currentClientIndex == idx)
						{
							$(this).css('background-color', 'gray');
						}
						idx++;
					});
			break;

			case 38: // Up arrow key
				if (currentClientIndex == -1 || currentClientIndex == 0)
				{
					currentClientIndex = $("#client-list").children().length - 1;
				} else
				{
					currentClientIndex--;
				}
				$("#client-list").children().css('background-color', 'white');
				$("#client-list").children().bind("mouseover", function() {
					$("#client-list").children().css('background-color', 'white');
					$(this).css('background-color', 'gray');
				});
				var idx = 0;
				$("#client-list").children().each(function()
					{
						if (currentClientIndex == idx)
						{
							$(this).css('background-color', 'gray');
						}
						idx++;
					});
			break;

			case 13: // Enter key
				var idx = 0;
				$("#client-list").children().each(function()
					{
						if (currentClientIndex == idx)
						{
							cleanClientList();
							getClientInfo($(this).attr("id"));
						}
						idx++;
					});
			break;

			default: //Other keys, search for client
				currentClientIndex = -1;
				if ($(this).val() != "")
				{
					getClientsByName($(this).val());
				}
				else //input empty, clean list and client form
				{
					cleanClientList();
					cleanClientForm();
				}
			break;
		}
    });
});

/* Call php with ajax for clients */
function getClientsByName(_name)
{
//	$('#client-list').html("Carregando...");
    $.ajax({
		type: "POST",
		url: "control/get_clients.php", //ajax file requested
		data: { clientname: _name},  // create an object will all values
		success: //callback on success
			function(data)
			{
				$('#client-list').html("");
				for (_id in data.clients)
				{
					$('#client-list').show();
					_client = data.clients[_id];
					/* http://stackoverflow.com/questions/268490/jquery-document-createelement-equivalent/268520#268520
					creating a div element */
					clientDiv = $("<div>");
					clientDiv.attr("id","client" + _client.id);
					clientDiv.text(_client.name);
					clientDiv.addClass("client-suggest");
					$('#client-list').append(clientDiv);
				}

				$(".client-suggest").click(function()
						{
							cleanClientList();
							getClientInfo($(this).attr("id"));
						});
				/* $(".client-suggest").click(getClientInfo); to send more parameters to a function, use bind()
					http://stackoverflow.com/questions/1541127/how-to-send-multiple-arguments-to-jquery-click-function */
			},
		dataType: "json" // or "html"
  	});
}

/* click event callback for client-suggest div class */
function getClientInfo(_clientId)
{
//	_clientId = $(this).attr("id");
    $.ajax({
		type: "POST",
		url: "control/get_client_info.php",
		data: { clientid: _clientId },
		success:
			function(data)
			{
				//set client info on inputs
				_client = data.client;
				$("#client-id").val(_client.id);
				$("#client-name").val(_client.name);
				$("#client-email").val(_client.email);
				$("#client-phone").val(_client.phone);
			},
		dataType: "json"
	});
}
