var logged = 0;
var uid;
var name;
var a = 0;

function batons(isloggedin) {
	if (isloggedin == 1) {
		console.log('User logged in, creating buttons.');
		
		// STYLES
		// show bar under text input
		$('.usersignedin').css('display', 'block');
		// display name
		$('.signedinname').text(name);
		// hide add document button
		$('.adddocument').css('display', 'none');
		// show document form
		$('form.adddocumentbox').css('display', 'block');
		
		// click on Dodaj predlog shows suggestionpopup
		$('.suggest').click(function() {
			$('.suggestionpopup').modal('show');
		});
		
		// click on ZA
		$('.votefor').click(function() {
			bla = $(this);
			$.ajax({
				context: this,
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
				data: {
					'proposal_id': bla.parent().data('id'),
					'type': 1,
					'uid': uid
				},
				success: function(data) {
					console.log(data);
					if (data == -1) {
						alert('Za predlog lahko glasujete samo enkrat');
					} else if (data == 1) {
						bla.next().children().first().text(parseInt(bla.next().children().first().text()) + 1);
						bla.toggleClass('marked');
						podpora(document.location.href.split('?')[0] + '/' + bla.parent().data('id'));
					}
					console.log(data);
				}
			});
		});
			
		// click on PROTI
		$('.voteagainst').click(function() {
			bla = $(this);
			$.ajax({
				context: this,
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
				data: {
					'proposal_id': bla.parent().data('id'),
					'type': -1,
					'uid': uid
				},
				success: function(data) {
					if (data == -1) {
						alert('Za predlog lahko glasujete samo enkrat');
					} else if (data == 1) {
						bla.next().children().first().text(parseInt(bla.next().children().first().text()) + 1);
						bla.toggleClass('marked');
					}
					console.log(data);
				}
			});
		});
		
		// suggestion page
		// click on ZA
		$('.suggestionup').click(function() {
			bla = $(this);
			$.ajax({
				context: this,
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
				data: {
					'proposal_id': bla.data('id'),
					'type': 1,
					'uid': uid
				},
				success: function(data) {
					if (data == -1) {
						alert('Za predlog lahko glasujete samo enkrat');
					} else if (data == 1) {
						bla.next().children().first().text(parseInt(bla.next().children().first().text()) + 1);
						bla.toggleClass('marked');
						podpora(document.location.href);
					}
				}
			});
		});
		
		//click on PROTI
		$('.suggestiondown').click(function() {
			bla = $(this);
			$.ajax({
				context: this,
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
				data: {
					'proposal_id': bla.data('id'),
					'type': -1,
					'uid': uid
				},
				success: function(data) {
					if (data == -1) {
						alert('Za predlog lahko glasujete samo enkrat');
					} else if (data == 1) {
						bla.next().children().first().text(parseInt(bla.next().children().first().text()) + 1);
						bla.toggleClass('marked');
					}
				}
			});
		});
		
		//click on Dodaj argument ZA
		$('.submitargumentfor').click(function() {			
			$.ajax({
				context: this,
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/add_argument.php',
				data: {
					'proposal_id': $('.suggestionup').data('id'),
					'type': 1,
					'content': $('#argumentinputfor').val(),
					'uid': uid
				},
				success: function(data) {
					if (data == 0) {
						alert('Nekaj je šlo narobe. :(');
					} else if (data == 1) {
						alert('Argument čaka na potrditev');
						$('#argumentinputfor').val('');
					}
					console.log(data);
				}
			});
		});
		
		// click on Dodaj argument PROTI
		$('.submitargumentagainst').click(function() {
			$.ajax({
				context: this,
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/add_argument.php',
				data: {
					'proposal_id': $('.suggestionup').data('id'),
					'type': -1,
					'content': $('#argumentinputagainst').val(),
					'uid': uid
				},
				success: function(data) {
					if (data == 0) {
						alert('Nekaj je šlo narobe. :(');
					} else if (data == 1) {
						alert('Argument čaka na potrditev');
						$('#argumentinputagainst').val('');
					}
					console.log(data);
				}
			});
		});
		
		// click on Argument up
		$('.argumentup').click(function() {
			console.log('begin');
			bla = $(this);
			$.ajax({
				context: this,
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/vote_argument.php',
				data: {
					'argument_id': bla.data('id'),
					'type': 1,
					'uid': uid
				},
				success: function(data) {
					console.log(data);
					if (data == -1) {
						alert('Za predlog lahko glasujete samo enkrat');
					} else if (data == 1) {
						bla.toggleClass('marked');
						bla.next().children().first().text(parseInt(bla.next().children().first().text()) + 1);
					}
					console.log(data);
				}
			});
		});
		
		// click on Argument down
		$('.argumentdown').click(function() {
			bla = $(this);
			$.ajax({
				context: this,
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/vote_argument.php',
				data: {
					'argument_id': bla.data('id'),
					'type': -1,
					'uid': uid
				},
				success: function(data) {
					console.log(data);
					if (data == -1) {
						alert('Za predlog lahko glasujete samo enkrat');
					} else if (data == 1) {
						bla.toggleClass('marked');
						bla.next().children().first().text(parseInt(bla.next().children().first().text()) + 1);
					}
					console.log(data);
				}
			});
		});
		
		// click on Pridruži se
		$('.toggleworkgroup').click(function() {
			$(".toggleworkgroupt").html("pošiljam...");
			$.ajax({
				type: 'post',
				url: 'http://danesjenovdan.si/ajax/add_user_group.php',
				data: {
					'right_id': $('#rightid').val(),
					'proposal_id': $('#proposal_id').val()
				},
				success: function(data) {
					console.log(data);
					$(".toggleworkgroupt").html(data);
				}
			});
		});
		
		
	} else {
		
		// show social connect
		$('.socialconnect').css('display', 'inline-block');
		$('.createaccount').css('display', 'inline-block');
		// show add document
		$('.adddocument').css('display', 'block');
		// hide document form
		$('form.adddocumentbox').css('display', 'none');
		// hide 'objavi kot'
		$('.usersignedinargument').text('');
	
		// submit login popup (login with email)
		$('.submitloginpopup').click(function() {
			console.log('email login');
			if ($('.accountname').val().split(' ')[1]) {
				$.ajax({
					type: 'post',
					url: 'http://danesjenovdan.si/login/email.php',
					data: {
						email: $('.accountemail').val(), 
						name: $('.accountname').val().split(' ')[0], 
						surname: $('.accountname').val().split(' ')[1]
					},
					success: function(response) {
						document.location.reload();
					}
				});
			} else {
				alert('Potrebujemo tvoj priimek in ime');
			}
		});
	
		// click on Dodaj predlog shows loginpopup
		$('.suggest').click(function() {
			$('.loginpopup').modal('show');
			a = 0; // ???
		});
		//click on ZA
		$('.votefor').click(function() {
			$('.loginpopup').modal('show');
		});
		//click on PROTI
		$('.voteagainst').click(function() {
			$('.loginpopup').modal('show');
		});
		
		// suggestion page
		// click on ZA
		$('.suggestionup').click(function() {
			$('.loginpopup').modal('show');
		});
		// click on PROTI
		$('.suggestiondown').click(function() {
			$('.loginpopup').modal('show');
		});
		// click on Dodaj argument ZA
		$('.submitargumentfor').click(function() {
			$('.loginpopup').modal('show');
			a = 1;
		});
		// click on Dodaj argument PROTI
		$('.submitargumentagainst').click(function() {
			$('.loginpopup').modal('show');
			a = 1;
		});
		// click on Argument up
		$('.argumentup').click(function() {
			$('.loginpopup').modal('show');
			a = 1;
		});
		// Argument input for
		$('#argumentinputfor').focus(function() {
			$('.loginpopup').modal('show');
			a = 1;
		});
		// Argument input against
		$('#argumentinputagainst').focus(function() {
			$('.loginpopup').modal('show');
			a = 1;
		});
		// click on Pridruži se
		$('.toggleworkgroup').click(function() {
			$('.loginpopup').modal('show');
		});
	}
}

function newLogin(callback) {
	$.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(data) {
		console.log('first auth check:');
		if (data.uid != -1) {
			// user is logged in
			logged = 1;
			console.log(data.uid);
			uid = data.uid;
			name = data.name;
			callback(1);
			return false;
			
			// TODO ALL THE BUTTONS
						
//			 show bar under text input
//			$('.usersignedin').css('display', 'block');
//			 display name
//			$('.signedinname').text(data.name);
//			 hide add document button
//			$('.adddocument').css('display', 'none');
//			 show document form
//			$('form.adddocumentbox').css('display', 'block');
		} else {
			// user not logged in
			
			// try to log user in with facebook
			console.log('about to check for facebook login status');
			
			FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
					// connected
					console.log('user is logged into facebook and authorised');
					
					// we know the user isn't logged in, so we log him/her in
					// first let's get the info from facebook and then pass it on to our internal login
					FB.api('/me', function(FBinfo) {
						console.log(FBinfo);
						$.ajax({
							type: 'post',
							url: 'http://danesjenovdan.si/login/facebook2.php', 
							data: {name: FBinfo.first_name, surname: FBinfo.last_name, email: FBinfo.email, fbid: FBinfo.id},
							success: function(response){
								// we know the user is logged in so refresh the page TODO
								console.log(response);
								document.location.reload();
								callback(1);
								return false;
							}
						});
					});
				} else {
					console.log('user not logged in');
					callback(0);
					return false;
					// user not logged in to facebook or hasn't given permission -> show them BATONS
//					
					// show social connect
					$('.socialconnect').css('display', 'inline-block');
					$('.createaccount').css('display', 'inline-block');
//					 show add document
					$('.adddocument').css('display', 'block');
//					 hide document form
					$('form.adddocumentbox').css('display', 'none');
//					 set up modal behavior for input
					$('#argumentinputagainst').focus(function() {
						$('.loginpopup').modal('show');
						a = 1;
					});
					$('.submitargumentagainst').click(function() {
						$('.loginpopup').modal('show');
						a = 1;
					});
					$('#argumentinputfor').focus(function() {
						$('.loginpopup').modal('show');
						a = 1;
					});
					$('.submitargumentfor').click(function() {
						$('.loginpopup').modal('show');
						a = 1;
					});
					$('.addsuggestioncontent').focus(function() {
						$('.suggestionpopup').modal('hide');
						$('.loginpopup').modal('show');
						a = 0;
					});
					$('.addsuggestiontitle').focus(function() {
						$('.suggestionpopup').modal('hide');
						$('.loginpopup').modal('show');
						a = 0;
					});
				}
			});
		}
	});
}

function podpora(predlog) { // accepts predlog as url string
	FB.api('/me/permissions', function(response) {
		if (response.data[0].publish_actions == 1) {
			FB.api(
			    '/me/danesjenovdan_si:podpora', //danesjenovdan_si is the app namespace, podpora is the action
			    'post',
			    {
			        proposal: predlog
			    },
			    function(response) {
			        console.log(response);
			        if (!response || response.error) {
			            console.log('Something went wrong. :(');
			        } else {
			            console.log('Success! Action ID: ' + response.id);
			    }
			});
		} else {
			login();
		}
	});
}

function getuid() {
	$.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(response) {a = response;});
}

// FB login function
function login() {
    FB.login(function(response) {
        if (response.authResponse) {
            // connected
            window.location.reload(true);
        } else {
            // cancelled
        }
    }, {
    	scope: 'email,publish_actions'}
    );
}
	
function loginUser() {
	FB.api('/me', function(response) {
		console.log('Good to see you, ' + response.name + '.');
	});
}

function checkUserAuth() {
	$.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(response) {
		if (response.uid != -1) {
			// user logged in
		} else {
			// user not logged in
		}
	});
}

// Additional JS functions here
window.fbAsyncInit = function() {
    console.log('ping');
	newLogin(function(isloggedin) {batons(isloggedin);});
    
//    $.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(data) {
//    	console.log('first auth check:');
//    	console.log(data.uid);
//		if (data.uid != -1) { // FALSE
			// user logged in
//			
			// show bar under text input
//			$('.usersignedin').css('display', 'block');
			// display name
//			$('.signedinname').text(data.name);
			// hide add document button
//			$('.adddocument').css('display', 'none');
			// show document form
//			$('form.adddocumentbox').css('display', 'block');
//		} else {
			// user not logged in
//			
			// try to log user in with facebook
//			console.log('about to check for facebook login status');
//			
//			FB.getLoginStatus(function(response) {
//				if (response.status === 'connected') {
					// connected
//					console.log('user is logged into facebook and authorised');
//					
					// we know the user isn't logged in, so we log him/her in
					// first let's get the info from facebook and then pass it on to our internal login
//					FB.api('/me', function(FBinfo) {
//						console.log(FBinfo);
//						$.ajax({
//							type: 'post',
//							url: 'http://danesjenovdan.si/login/facebook2.php', 
//							data: {name: FBinfo.first_name, surname: FBinfo.last_name, email: FBinfo.email, fbid: FBinfo.id},
//							success: function(response){
								// we know the user is logged in so refresh the page TODO
//								console.log(response);
//								document.location.reload();
//							}
//						});
//					});
//				} else {
					// user not logged in to facebook or hasn't given permission -> show them BATONS
//					console.log('user not logged in');
//					
					// show social connect
//					$('.socialconnect').css('display', 'inline-block');
//					$('.createaccount').css('display', 'inline-block');
					// show add document
//					$('.adddocument').css('display', 'block');
					// hide document form
//					$('form.adddocumentbox').css('display', 'none');
					// set up modal behavior for input
//					$('#argumentinputagainst').focus(function() {
//						$('.loginpopup').modal('show');
//						a = 1;
//					});
//					$('.submitargumentagainst').click(function() {
//						$('.loginpopup').modal('show');
//						a = 1;
//					});
//					$('#argumentinputfor').focus(function() {
//						$('.loginpopup').modal('show');
//						a = 1;
//					});
//					$('.submitargumentfor').click(function() {
//						$('.loginpopup').modal('show');
//						a = 1;
//					});
//					$('.addsuggestioncontent').focus(function() {
//						$('.suggestionpopup').modal('hide');
//						$('.loginpopup').modal('show');
//						a = 0;
//					});
//					$('.addsuggestiontitle').focus(function() {
//						$('.suggestionpopup').modal('hide');
//						$('.loginpopup').modal('show');
//						a = 0;
//					});
//					
//					
//				}
//			});
//			
//		}
//		console.log('end');
//	});

	// Additional init code here
	//FBinitmadafaka();
	// create buttons
	
	console.log('FBinit completed');
};

function FBinitmadafaka() {
    $.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(data) {
    	console.log('first auth check:');
    	console.log(data.uid);
		if (data.uid != -1) { // FALSE
			// user logged in
//			$('.suggest').click(function() {
//				$('.suggestionpopup').modal('show');
//			});
			// show bar under text input
			$('.usersignedin').css('display', 'block');
			// display name
			$('.signedinname').text(data.name);
			// hide add document button
			$('.adddocument').css('display', 'none');
			// show document form
			$('form.adddocumentbox').css('display', 'block');
		} else {
			// user not logged in
			
			// try to log user in with facebook
			console.log('about to check for facebook login status');
			
			FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
					// connected
					console.log('user is logged into facebook and authorised');
					
					// we know the user isn't logged in, so we log him/her in
					// first let's get the info from facebook and then pass it on to our internal login
					FB.api('/me', function(FBinfo) {
						$.ajax({
							type: 'post',
							url: 'http://danesjenovdan.si/login/facebook2.php', 
							data: {name: FBinfo.first_name, surname: FBinfo.last_name, email: FBinfo.email, fbid: FBinfo.id},
							success: function(response){
								// we know the user is logged in so refresh the page TODO
								console.log(response);
								window.location.reload(true);
							}
						});
					});
				} else {
					// user not logged in to facebook or hasn't given permission -> show them BATONS
					console.log('user not logged in');
					
					// show social connect
					$('.socialconnect').css('display', 'inline-block');
					$('.createaccount').css('display', 'inline-block');
					// show add document
					$('.adddocument').css('display', 'block');
					// hide document form
					$('form.adddocumentbox').css('display', 'none');
					// set up modal behavior for input
					$('#argumentinputagainst').focus(function() {
						$('.loginpopup').modal('show');
						a = 1;
					});
					$('#argumentinputfor').focus(function() {
						$('.loginpopup').modal('show');
						a = 1;
					});
					$('.addsuggestioncontent').focus(function() {
						$('.suggestionpopup').modal('hide');
						$('.loginpopup').modal('show');
						a = 0;
					});
					$('.addsuggestiontitle').focus(function() {
						$('.suggestionpopup').modal('hide');
						$('.loginpopup').modal('show');
						a = 0;
					});
//					$('.suggest').click(function() {
//						$('.loginpopup').modal('show');
//						a = 0;
//						console.log('ping');
//					});
					
				}
			});
			
		}
		console.log('end');
	});

	// Additional init code here
	
	// create buttons
	
	console.log('FBinitmadafaka completed');

}

function createbuttons() {
	$('.postsuggestion').click(function() {
		console.log('begin');
		$.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://danesjenovdan.si/ajax/add_proposal.php',
					data: {
						'right_id': $('#rightid').val(),
						'title': $('.addsuggestiontitle').val(),
						'content': $('.addsuggestioncontent').val(),
						'uid': response.uid
					},
					success: function(data) {
						if (data == 0) {
							alert('Nekaj je šlo narobe. :(');
						} else if (data == 1) {
							alert('Predlog čaka na potrditev');
							$('.suggestionpopup').modal('hide');
						}
						console.log(data);
					}
				});
			} else {
				$('.loginpopup').modal('show');
			}
		});
		console.log('end');
	});
	$('.ihaveanargument').click(function() {
		document.location = document.location.href.split('?')[0] + '/' + $(this).data('id');
	});
	$('.navblock').hover(function() {
		$(this).children().css('color', 'white');
		console.log('in');
	}, function() {
		$(this).children().css('color', '#363636');
	});
	$('.navblock').click(function() {
		document.location = $(this).children().attr('href');
	});
	$('.fbsignin').click(function() {
		login();
	});
 	$('.googlesign').click(function() {
 		url = 'http://danesjenovdan.si/login/google.php';
 		window.location.href = url;
 	});

//	$('.toggleworkgroup').click(function() {
//		$(".toggleworkgroupt").html("pošiljam...");
//		$.ajax({
//			type: 'post',
//			url: 'http://danesjenovdan.si/ajax/add_user_group.php',
//			data: {
//				'right_id': $('#rightid').val(),
//				'proposal_id': $('#proposal_id').val()
//			},
//			success: function(data) {
//				console.log(data);
//				$(".toggleworkgroupt").html(data);
//			}
//		});
//	}); 	
}
