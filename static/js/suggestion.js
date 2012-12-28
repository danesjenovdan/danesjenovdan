var a;

$(document).ready(function() {
	$('.naprej').click(function() {
		$('.razvijaj').toggle('slow');
		$('html, body').animate({ scrollTop: $(document).height() }, 'slow');
		return false;
	});
	$('.medijiklik').click(function() {
		$('.mediji').toggle('slow');
		$('html, body').animate({ scrollTop: $(document).height() }, 'slow');
		return false;
	});
	
	//buttons
	$('.submitloginpopup').click(function() {
		console.log('email login');
		if ($('.accountname').val().split(' ')[1]) {
			$.ajax({
				type: 'post',
				url: 'http://www.danesjenovdan.si/login/email.php',
				data: {
					email: $('.accountemail').val(), 
					name: $('.accountname').val().split(' ')[0], 
					surname: $('.accountname').val().split(' ')[1]
				},
				success: function(response) {
					if (a == 0) {
						$('.loginpopup').modal('hide');
						$('.suggestionpopup').modal('show');
						$('.addsuggestiontitle').unbind('focus');
						$('.addsuggestioncontent').unbind('focus');
						// show bar under text input
						$('.usersignedin').css('display', 'block');
						// display name
						$('.signedinname').text(response.name);
						// hide add document button
						$('.adddocument').css('display', 'none');
						// show document form
						$('form.adddocumentbox').css('display', 'block');
					} else {
						$('.loginpopup').modal('hide');
					}
					console.log(response);
				}
			});
		} else {
			alert('Potrebujemo tvoj priimek in ime');
		}
	});
	$('.suggestionup').click(function() {
		console.log('begin');
		bla = $(this);
		$.getJSON('http://www.danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://www.danesjenovdan.si/ajax/vote_proposal.php',
					data: {
						'proposal_id': bla.data('id'),
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
		//TODO function to vote for
	});
	$('.suggestiondown').click(function() {
		bla = $(this);
		$.getJSON('http://www.danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://www.danesjenovdan.si/ajax/vote_proposal.php',
					data: {
						'proposal_id': bla.data('id'),
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
		//TODO function to vote against
	});
	$('.adddocument').click(function() {
		//TODO function to add document
	});
	$('.argumentup').click(function() {
		console.log('begin');
		bla = $(this);
		$.getJSON('http://www.danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://www.danesjenovdan.si/ajax/vote_argument.php',
					data: {
						'argument_id': bla.data('id'),
						'type': 1,
						'uid': response.uid
					},
					success: function(data) {
						console.log(data);
						if (data == -1) {
							alert('Za predlog lahko glasujete samo enkrat');
						} else if (data == 1) {
							bla.toggleClass('marked');
						}
						console.log(data);
					}
				});
			} else {
				$('.loginpopup').modal('show');
			}
		});
		//TODO function to vote for
	});
	$('.argumentdown').click(function() {
		bla = $(this);
		$.getJSON('http://www.danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://www.danesjenovdan.si/ajax/vote_argument.php',
					data: {
						'argument_id': bla.data('id'),
						'type': -1,
						'uid': response.uid
					},
					success: function(data) {
						console.log(data);
						if (data == -1) {
							alert('Za predlog lahko glasujete samo enkrat');
						} else if (data == 1) {
							bla.toggleClass('marked');
						}
						console.log(data);
					}
				});
			} else {
				$('.loginpopup').modal('show');
			}
		});
		//TODO function to vote against
	});
	$('.submitargumentfor').click(function() {
		console.log('begin');
		$.getJSON('http://www.danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://www.danesjenovdan.si/ajax/add_argument.php',
					data: {
						'proposal_id': $('.suggestionup').data('id'),
						'type': 1,
						'content': $('#argumentinputfor').val(),
						'uid': response.uid
					},
					success: function(data) {
						if (data == 0) {
							alert('Nekaj je šlo narobe. :(');
						} else if (data == 1) {
							alert('Argument čaka na potrditev');
						}
						console.log(data);
					}
				});
			} else {
				$('.loginpopup').modal('show');
			}
		});
	});
	$('.submitargumentagainst').click(function() {
		console.log('begin');
		$.getJSON('http://www.danesjenovdan.si/ajax/isAuthorized.php', function(response) {
			console.log(response);
			if (response.uid != -1) {
				console.log(response.uid);
				$.ajax({
					context: this,
					type: 'post',
					url: 'http://www.danesjenovdan.si/ajax/add_argument.php',
					data: {
						'proposal_id': $('.suggestionup').data('id'),
						'type': -1,
						'content': $('#argumentinputfor').val(),
						'uid': response.uid
					},
					success: function(data) {
						if (data == 0) {
							alert('Nekaj je šlo narobe. :(');
						} else if (data == 1) {
							alert('Argument čaka na potrditev');
						}
						console.log(data);
					}
				});
			} else {
				$('.loginpopup').modal('show');
			}
		});
	});
	$('.adddocument').click(function() {
		$.ajax({
			type: 'get',
			url: 'http://danesjenovdan.si/ajax/isAuthorized.php',
			dataType: 'json',
			success: function(data) {
				if (data.uid == -1) {
					$('.loginpopup').modal('show');
				} else {
					alert('ups, napaka!');
				}
			}
		});
		return false;
	});
	$('.toggleworkgroup').click(function() {
		
		return false;
	});
	createbuttons();
});