function kontakt() {

	// die datei "kontakt.htm" in das html-div mit der id "content" laden
	$("#content").load("kontakt.htm");

}

function woeffshop() {
	if (mainMode != 'woeffshop') {

		$('#loading').show();

		$("#content").html('').load("woeff/woeffshopTest.php", function() {
			$(':button').button();
		});

		// hier wird dem html-tag "body" eine css eigenschaft zugewiesen
		//$("body").css("margin-top", "20px");

		//alert(loginIsValid);

		//$("#configPanel").html("");
		mainMode = 'woeffshop';
	}

}

function login() {
	if (mainMode != 'login') {
		$("#content").html('').load("login.php", function() {
			$(':button').button();
		});
		$("body").css("margin-top", "20px");

		$("#configPanel").html("");
		mainMode = 'login';
	}

}

function home() {
	if (mainMode != 'home') {
		$("#content").html('').load("titel.htm", function() {
			$(':button').button();
		});

		$("#configPanel").html("");
		mainMode = 'home';
	}

}

function anmelden() {

	if (mainMode != 'enroll') {
		document.getElementById('pacman').src = 'img/loading.gif';
		$('#loading').show();

		$("#content").html('').load("anmeldenTest.php", function() {
			$(':button').button();
		});
		$("body").css("margin-top", "20px");

		$("#configPanel").html("");
		mainMode = 'enroll';

	}
}

function register() {

	if (mainMode != 'register') {
		$("#content").html('').load("registerTest.php", function() {
			$(':button').button();
		});
		$("body").css("margin-top", "20px");

		$("#configPanel").html("");
		mainMode = 'register';
	}
}

function logMeOut() {

	// synchronous jquery ajax post
	var res = $.ajax({

		type : 'POST',
		async : false,
		url : 'logout.php',
		data : ({})

	}).responseText;

	// logoutnachricht anzeigen
	$('#content').html(res);
	$('#registerBtn').show();
	$('#configPanel').html('');

	mainMode = '';

}
function logMeIn(callback) {
	/*
	 $("#content").html('').load("anmeldenTest.php", function() {$( ':button' ).button();});
	 $("body").css("margin-top", "20px");
	
	 $("#configPanel").html("");
	 mainMode = 'enroll';
	 */
	document.getElementById("pacman").src = 'img/loading.gif';
	$('#loading').show();

	// synchronous jquery ajax post
	var res = $.ajax({

		type : 'POST',
		async : false,
		url : 'checkLogin.php',
		data : ({
			name : $('#name').val(),
			pass : MD5($('#pass').val())
		})

	}).responseText;

	if (res == 'false') {

		$('#content').html('<b style="color: red;">login fehlgeschlagen!</b>');
		$('#loading').show();
		document.getElementById("pacman").src = 'img/loading2.gif';

	} else {

		$('#content').html(res);
		$('#registerBtn').hide();
		$(':button').button();
		$('#loading').hide();
	}

}

/**
 * This function creates todays date and builds a german styled date string.
 * Can be put into a datepicker as a startup default value.
 * The equivalent datepicker format is "dd.mm.yy".
 * @return the [de-de] datestring of today
 */
function getDateString() {

	// get todays date
	var mDate = new Date();
	var mDay = mDate.getDate(); // day of month

	var rDay = "";

	// add a 0 if the day is < 10
	if (mDay < 10)
		rDay = "0" + mDay.toString();
	else
		rDay = mDay.toString();

	var mMonth = mDate.getMonth() + 1; // months go from 0 - 11!

	var rMonth = "";

	// add a 0 if month is < 10
	if (mMonth < 10)
		rMonth = "0" + mMonth.toString();
	else
		rMonth = mMonth.toString();

	var mYear = mDate.getFullYear(); // get 4 digit year
	var sYear = mYear.toString(); // convert year to string

	// build german date, e.g. "31.12.2011"
	var ret = sYear + "-" + rMonth + "-" + rDay;

	// what?! return it ;)
	return ret;

}

function shopIfShop() {

	$("#ifNoShopMessage").slideUp('slow', function() {

		$("#ifShop").slideDown();

	});
}

function hideIfShop() {

	$("#ifShop").slideUp('slow', function() {

		$("#ifNoShopMessage").slideDown(500, function() {

			$("#noShopMsg").focus();

		});

	});
}

function loadLastMessage() {

	$.ajax({

		type : 'POST',
		async : true,
		url : 'loadFile.php',
		data : ({

			filename : "noShopMessage.txt"

		}),
		success : function(data) {

			//alert(data);

			var content = data.split("<br />").join("");

			$("#noShopMsg").text(content);

			$("#noShopMsg").focus();

		} // success ende

	});

}

function saveShop() {

	//alert("nix funkzionne noch nich...!");

	shopType = "nothing";

	// wenn kein shop sein soll, die nachricht speichern
	if ($("#gospel:checked").val() == "gospel") {

		shopType = "gospel";

	}

	if ($("#woeff:checked").val() == "woeff") {

		shopType = "woeff";

	}

	if ($("#noShop:checked").val() == "noshop") {

		shopType = "noshop";

	}

	switch (shopType) {

	case "gospel":

		saveGospel();

		break;

	case "woeff":

		saveWoeff();

		break;

	case "noshop":

		saveNoShop();

		break;

	default:

		alert(unescape("du musst angeben was f%FCr ein shop es sein wird!"));

		break;

	}

}

function saveGospel() {

	// its the same as this one ;)
	saveWoeff();

}

function saveWoeff() {

	var fromDate = $("#datepickerFrom").val();
	var toDate = $("#datepickerTo").val();

	var fromTime = $("#slider1").slider("option", "value");
	var toTime = $("#slider2").slider("option", "value");

	var shopName = $('#shopName').val();

	//alert(fromDate + "\n" + fromTime);

	var config = new Object();

	config["config_id"] = 'neu';
	config["shopName"] = shopName;
	config["noShop"] = false;

	config["fromDate"] = fromDate;
	config["toDate"] = toDate;
	config["fromTime"] = fromTime;
	config["toTime"] = toTime;
	config["shopType"] = shopType;

	$
			.ajax({

				type : 'POST',
				async : true,
				url : 'save_shop.php',
				data : ({

					config : config

				}),
				success : function(data) {

					//alert(data);

					if (data.indexOf("error") < 0) {

						$("#popup").html("die shop config wurde gespeichert!");
						$("#popup").dialog({

							buttons : {
								"ok" : function() {

									$(this).dialog("close");

								}

							}

						});
						var html = $.trim($('#shopHead').html());
						if (html.length <= 0) {
							$('#shopHead')
									.html(
											'<th style="border-bottom:3px solid;">config_id</th><th style="border-bottom:3px solid;">shopName</th><th style="border-bottom:3px solid;">noShop</th><th style="border-bottom:3px solid;">fromDate</th><th style="border-bottom:3px solid;">toDate</th><th style="border-bottom:3px solid;">fromTime</th><th style="border-bottom:3px solid;">toTime</th><th style="border-bottom:3px solid;">shopType</th><th style="border-bottom:3px solid;">is_active</th><th style="border-bottom:3px solid;">edit</th><th style="border-bottom:3px solid;">delete</th>');
						}

						$('#shopBody').append('<tr>');

						for (key in config) {

							$('#shopBody').append(
									'<td style="border-bottom:1px solid;">'
											+ config[key] + '</td>');

						}

						$('#shopBody').append('<td></td><td></td></tr>');

						$('#notFound').remove();

					} else {

						$("#popup")
								.html(
										"die shop config konnte nicht gespeichert werden :(");
						$("#popup").dialog({

							buttons : {
								"grrrr" : function() {

									$(this).dialog("close");

								}

							}

						});

					}

				} // success ende

			});

}

function saveNoShop() {

	var message = $("#noShopMsg").val();

	message = message.split("ö").join("&ouml;");
	message = message.split("ü").join("&uuml;");
	message = message.split("ä").join("&auml;");
	message = message.split("Ö").join("&Ouml;");
	message = message.split("Ü").join("&Uuml;");
	message = message.split("Ä").join("&Auml;");
	message = message.split("ß").join("&szlig;");

	saveEmptyMessage = false;

	if (message.length == 0 || message == "") {

		$("#popup")
				.html(
						"du hast angegeben dass z.zt. kein shop sein wird. willst du eine nachricht an die shopper hinterlassen?");
		$("#popup").dialog({

			buttons : {
				"ja" : function() {

					$(this).dialog("close");

					$("#noShopMsg").focus();

				},
				"nein" : function() {

					saveEmptyMessage = true;

					$("#noShopMsg").html(" ");

					saveNoShopMessage();

				}
			}

		});

	} else {

		saveNoShopMessage();

	}

}

function saveNoShopMessage() {

	$("#popup").dialog("close");

	var content = $("#noShopMsg").val();

	$.ajax({

		type : 'POST',
		async : true,
		url : 'saveFile.php',
		data : ({

			filename : "noShopMessage.txt",
			content : content

		}),
		success : function(data) {

			//alert(data);

			if (data == "true") {

				if (saveEmptyMessage == false) {

					$("#popup").html("deine nachricht wurde gespeichert!");
					$("#popup").dialog({

						buttons : {
							"ok" : function() {

								saveNoMsgConfig();

								$(this).dialog("close");

							}

						}

					});

				} else {

					$("#popup").html("die leere nachricht wurde gespeichert!");
					$("#popup").dialog({

						buttons : {
							"ok" : function() {

								saveNoMsgConfig();

								$(this).dialog("close");

							}

						}

					});

				}

			} else {

				$("#popup").html(
						"die nachricht konnte nicht gespeichert werden :(");
				$("#popup").dialog({

					buttons : {
						"grrrr" : function() {

							$(this).dialog("close");

						}

					}

				});

			}

		} // success ende

	});

}

function saveNoMsgConfig() {

	var config = "noShop = true";

	$.ajax({

		type : 'POST',
		async : true,
		url : 'saveFile.php',
		data : ({

			filename : "shopConfig.txt",
			content : config

		}),
		success : function(data) {

			//alert(data);

			if (data == "true") {

				$("#popup").html("die shop config wurde gespeichert!");
				$("#popup").dialog({

					buttons : {
						"ok" : function() {

							$(this).dialog("close");

						}

					}

				});

			} else {

				$("#popup").html(
						"die shop config konnte nicht gespeichert werden :(");
				$("#popup").dialog({

					buttons : {
						"grrrr" : function() {

							$(this).dialog("close");

						}

					}

				});

			}

		} // success ende

	});

}

function loadConfig() {
	if (panel != 'config') {
		$('#configPanelContent').load('shop_config.php');
		$('#configHeader').html('CONFIG PANEL');
		panel = 'config';
	}
}

function loadProfile() {
	if (panel != 'profile') {
		$('#configPanelContent').load('profile.php');
		$('#configHeader').html('USER PROFILE');
		panel = 'profile';
	}
}

function loadUserValues() {

	$.ajax({

		type : 'POST',
		async : true,
		url : 'get_user_data.php',
		data : ({

		}),
		success : function(data) {

			var userData = $.parseJSON(data)

			$.each(userData, function(index, value) {
				if (index != 'login_name' && index != 'login_pass'
						&& index != 'id') {
					if ($('#' + index).attr('type') == 'checkbox') {
						if (value == '1') {
							$('#' + index).attr('checked', true);
						} else {
							$('#' + index).attr('checked', false);
						}
					} else {
						if ($('#' + index).is('select')) {
							var isMultiple = false;
							isMultiple = $('#' + index + '[multiple]').length;
							if (isMultiple) {
								if (value != null) {
									if (value.indexOf(',') >= 0) {
										var items = value.split(', ');
										$('#' + index).val(items);
									} else {
										var items = new Array();
										items[0] = value;
										$('#' + index).val(items);
									}
								}
							} else {
								$('#' + index).val(value);
							}
						} else {
							$('#' + index).val(value);
						}
					}
				}
			});

			displayConfig();

		} // success ende

	});

}

function displayConfig() {

	$('#fromTime').html(shopConfig.fromTime + ' Uhr');
	$('#toTime').html(shopConfig.toTime + ' Uhr');
	if (shopConfig.noShop == true) {
		$('#noShopMessage').html(shopConfig.message).show();
		$("#send").hide();
		$('#loading').hide();
		return;
	}
	switch (shopConfig.shopType) {
	case "woeff":

		$("#shopTitle").html("Melde dich hier zum W&ouml;ffshop an:");

		break;

	case "gospel":

		$("#shopTitle").html("Melde dich hier zum Gospelshop an:");

		break;

	}

	$("#datepickerFrom").attr("value", shopConfig.fromDate);
	$("#datepickerTo").attr("value", shopConfig.toDate);

	//alert("\""+shopConfig[ "fromDate" ].split(".")[0]+"\"");
	//alert("\""+shopConfig[ "toDate" ].split(".")[2].substr( 0, 4 )+"\"");

	var year = shopConfig.fromDate.split("-")[2].substr(0, 4);

	var fromDateDay = parseInt(shopConfig.fromDate.split("-")[0]);
	var fromDateMonth = parseInt(shopConfig.fromDate.split("-")[1]) - 1;
	var fromDateYear = parseInt(year);

	year = shopConfig["toDate"].split("-")[2].substr(0, 4);

	var toDateDay = parseInt(shopConfig.toDate.split("-")[0]);
	var toDateMonth = parseInt(shopConfig.toDate.split("-")[1]) - 1;
	var toDateYear = parseInt(year);

	//alert(fromDateYear);

	var dates = $('.datepicker').datepicker({

		// set today as a valid start day
		defaultDate : "+0d",
		//defaultDate: new Date( fromDateYear, fromDateMonth, fromDateYear ),

		// days before start are not clickable
		//minDate: new Date( fromDateYear, fromDateMonth, fromDateDay ),

		//maxDate: new Date( toDateYear, toDateMonth, toDateDay ),

		// set german date format
		dateFormat : 'yy-mm-dd',

		// the date-select function
		onSelect : function(selectedDate) {

			var option = $(this).hasClass('from') ? 'minDate' : 'maxDate';

			//alert("select");
			// the user cannot change the date!
			$("#datepickerFrom").attr("value", shopConfig.fromDate);
			$("#datepickerTo").attr("value", shopConfig.toDate);

		},

	});

	$("#fromTime").html(shopConfig.fromTime + ":00 Uhr");
	$("#toTime").html(shopConfig.toTime + ":00 Uhr");

	$("#shopForm").slideDown(500);

	/*		
	 var noShop = "false";

	 if( noShop == "true" ) {
	
	 $("#send").hide();
	
	 $("#noShopMessage").html( noShopMsg ).slideDown(500);
	
	 }
	
	 //alert(config["shopType"] + "\n" + noShop);
	
	 if( noShop == "false" ) {
	
	 //alert(config["shopType"]);
	
	 //var type = config[ "shopType" ].substring(0, 5);
	
	 //TODO: remove this!!!
	 var type = "woeff";
	
	 switch( type ) {
	
	 case "woeff":
	
	 $("#shopTitle").html("Melde dich hier zum W&ouml;ffshop an:");
	
	 break;
	
	 case "gospel":
	
	 $("#shopTitle").html("Melde dich hier zum Gospelshop an:");
	
	 break;
	
	 }
	
	
	
	 }
	 */
	$('#loading').hide();
}

function send() {

	if (checkFields() == true) {

		$('#send').hide();
		$('#quit').hide();
		$('#sendProgress').html('<img src="img/loading.gif" />');

		var userProfile = new Object();

		$('#mUserValues').find('input').each(function(index) {
			var id = $(this).attr('id');
			var value = $(this).attr('value');

			var type = $(this).attr('type');

			switch (type) {

			case 'checkbox':

				var checked = $(this).is(':checked');

				if (checked == true) {
					userProfile[id] = 1;
				} else {
					userProfile[id] = 0;
				}

				break;

			default:
				userProfile[id] = value;
				break;
			}

		});

		$('#mUserValues').find('select').each(
				function(index) {

					var id = $(this).attr('id');
					var value = $(this).attr('value');

					// distinguish between single and multi selects
					var isMultiple = false;
					isMultiple = $('#' + id + '[multiple]').length;

					if (isMultiple) {

						var selected = $.map($('#' + id + ' option:selected'),
								function(e) {
									return $(e).val();
								});

						value = selected;

					}

					userProfile[id] = value;

				});

		$('#mUserValues').find('textarea').each(function(index) {

			var id = $(this).attr('id');
			var value = $(this).attr('value');
			userProfile[id] = value;

		});

		userProfile.has_confirmed = 1;

		$
				.ajax({

					type : 'POST',
					async : true,
					url : 'save_userdata.php',
					data : ({
						userProfile : userProfile
					}),
					success : function(data) {
						$('#sendProgress').html(
								'<img src="img/green_hook.png" />');
						$('#sendProgress')
								.append(
										'<b style="color: red;"> &nbsp; Deine Anmeldung / Daten wurden gespeichert.</b>');
						$('#send').show();
						$('#quit').show();
					}
				});

	} else {

	}

	/*
	 if( checkFields() == true ) {
	
	 // send the mail
	 $.ajax({
	
	 type : 'POST',
	 async: true,
	 url  : 'save_user_data.php',
	 data : ({
	
	 userName:    $("#username").val(),
	 email:       $("#useremail").val(),
	 street:      $("#userstreet").val(),
	 city:        $("#usercity").val(),
	 geb:         $("#datepickerGeb").val(),
	 tel:         $("#usertel").val(),
	 shoe:        $("#usershoe").val(),
	 meal:        $("#usermeal").val(),
	 sleep:       $("#usersleep").val(),
	 instruments: $("#userinstruments").val(),
	 equipment:   $("#userequipment").val(),
	 details:     $("#userdelay").val(),
	 nick:        $("#usernick").val()

	
	 })
	
	
	
	 });
	
	 $("#send").attr("onclick", "");
	
	 /*
	 * load teilnehmer file to append this teilnehmer.
	 *//*
					 $.ajax({
					
					type : 'POST',
					async: true,
					url  : 'loadFile.php',
					data : ({
						
						filename: "teilnehmer.txt"
						
					}),
					success: function( data ) {
						
		//alert(data);
						
						//data = data.split("<br />").join("");
						
						//var configLines = new Array();
						
						//configLines = data.split("\n");
						
						if( data.length < 3 ) {
							
							data = data + $("#usernick").val() + ";";
							
						} else {
							
							data = data + "\n" + $("#usernick").val() + ";";
							
						}
						
						content = data;
						
						// save the new teilnehmer
						$.ajax({
				
							type : 'POST',
							async: true,
							url  : 'saveFile.php',
							data : ({
								
								filename: "teilnehmer.txt",
								content:  content
								
							})
							
						});
						
					} // success ende
					
				});
				
				$("#msg").html("Danke f&uuml;r deine Anmeldung!<br />Du hast eine Empfangsbest&auml;tigungs Email erhalten.");
				
				$("#msg").slideDown(500);
				
			} else {
				
				
				
			}
	 */
}

function checkFields() {

	var allesKlar = true;

	// check nickname
	if ($("#name_nick").val() == '' || $("#name_nick").val().length < 3) {

		$("#nickError").html("Gib Deinen Rufnamen ein!").slideDown(500);

		return false;

	} else {

		$("#nickError").slideUp(500, function() {

			$("#nickError").html("");

		});

		allesKlar = true;

	}

	// check name
	if ($("#name_first").val() == '' || $("#name_first").val().length < 3) {

		$("#firstnameError").html("Gib Deinen Namen ein!").slideDown(500);

		return false;

	} else {

		$("#firstnameError").slideUp(500, function() {

			$("#firstnameError").html("");

		});

		allesKlar = true;

	}

	if ($("#name_last").val() == '' || $("#name_last").val().length < 3) {

		$("#lastnameError").html("Gib Deinen Namen ein!").slideDown(500);

		return false;

	} else {

		$("#lastnameError").slideUp(500, function() {

			$("#lastnameError").html("");

		});

		allesKlar = true;

	}

	// check tel
	if ($("#phone").val() == '' || $("#phone").val().length < 3) {

		$("#telError").html("Gib Deine Telefonnummer ein!").slideDown(500);

		return false;

	} else {

		$("#telError").slideUp(500, function() {

			$("#telError").html("");

		});

		allesKlar = true;

	}

	// check email
	if ($("#email").val() == '' || $("#email").val().length < 3
			|| $("#email").val().indexOf('@') < 0
			|| $("#email").val().indexOf('.') < 0) {

		$("#emailError").html("Gib Deine Email-Adresse ein!").slideDown(500);

		return false;

	} else {

		$("#emailError").slideUp(500, function() {

			$("#emailError").html("");

		});

		allesKlar = true;

	}

	/*
	// check email
	var mailCheck = $.ajax({
		
		type : 'POST',
		async: false,
		url  : 'validateEmail.php',
		data : ({
			
			email: $("#useremail").val()
			
		})
		
	}).responseText;
	
	if( mailCheck == "true" ) {
		
		$("#emailError").slideUp( 500, function(){
		
			$("#emailError").html( "" );
			
		});
		
		allesKlar = true;
		
	} else {
		
		$("#emailError").html( "Gib Deine Email-Adresse ein!" ).slideDown( 500 );
		
		allesKlar = false;
		
	}
	 */

	return allesKlar;

}

function saveData() {

	$('#progressProfile').html('<img src="img/loading.gif" />');

	var userProfile = new Object();

	$('#configPanelContent').find('input').each(function(index) {

		var key = $(this).attr('id');
		var value = $(this).val();

		if (key != 'login_pass') {
			userProfile[key] = value;
		}

	});

	$.ajax({

		type : 'POST',
		async : true,
		url : 'save_userprofile.php',
		data : ({

			userProfile : userProfile

		}),
		success : function(data) {
			if (data.indexOf('error') < 0) {
				$('#progressProfile').html('<img src="img/green_hook.png" />');
			} else {
				$('#progressProfile').html('<img src="img/red_cross.png" />');
			}
		}

	});

}

function quit() {

}