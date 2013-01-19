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
    	scope: 'email'}
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
	FB.init({
		appId      : '301375193309601', // App ID
		channelUrl : 'http://danesjenovdan.si/channel.html', // Channel File
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		xfbml      : true  // parse XFBML
    });
    
    $.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(data) {
    	console.log('first auth check:');
    	console.log(data.uid);
		if (data.uid != -1) { // FALSE
			// user logged in
			
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
						console.log(FBinfo);
						$.ajax({
							type: 'post',
							url: 'http://danesjenovdan.si/login/facebook2.php', 
							data: {name: FBinfo.first_name, surname: FBinfo.last_name, email: FBinfo.email, fbid: FBinfo.id},
							success: function(response){
								// we know the user is logged in so refresh the page TODO
								console.log(response);
								document.location.reload();
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
		console.log('end');
	});

	// Additional init code here
	
	// create buttons
	
	console.log('FBinit completed');
};

function FBinitmadafaka() {
    $.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(data) {
    	console.log('first auth check:');
    	console.log(data.uid);
		if (data.uid != -1) { // FALSE
			// user logged in
			$('.suggest').click(function() {
				$('.suggestionpopup').modal('show');
			});
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
					$('.suggest').click(function() {
						$('.loginpopup').modal('show');
						a = 0;
						console.log('ping');
					});
					
				}
			});
			
		}
		console.log('end');
	});

	// Additional init code here
	
	// create buttons
	
	console.log('FBinit completed');

}

function createbuttons() {
	$('.votefor').click(function() {
		bla = $(this);
		$.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
					data: {
						'proposal_id': bla.parent().data('id'),
						'type': 1,
						'uid': response.uid
					},
					success: function(data) {
						console.log(data);
						if (data == -1) {
							alert('Za predlog lahko glasujete samo enkrat');
						} else if (data == 1) {
							bla.next().children().first().text(parseInt(bla.next().children().first().text()) + 1);
							bla.toggleClass('marked');
						}
						console.log(data);
					}
				});
			} else {
				$('.loginpopup').modal('show');
			}
		});
	});
	$('.voteagainst').click(function() {
		bla = $(this);
		$.getJSON('http://danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
					data: {
						'proposal_id': bla.parent().data('id'),
						'type': -1,
						'uid': response.uid
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
			} else {
				$('.loginpopup').modal('show');
			}
		});
	});
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
				$(".toggleworkgroupt").html(data);
			}
		});
	}); 	
}
