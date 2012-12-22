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
	$.ajax({
		type: 'get',
		url: 'http://danesjenovdan.si/ajax/isAuthorized.php',
		dataType: 'json',
		success: function(data) {
			if (data.uid == -1) {
				$('.socialconnect').css('display', 'inline-block');
				$('.createaccount').css('display', 'inline-block');
				$('.adddocument').css('display', 'block');
				$('form.adddocumentbox').css('display', 'none');
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
			} else {
				$('.usersignedin').css('display', 'block');
				$('.signedinname').text(data.name);
				$('.adddocument').css('display', 'none');
				$('form.adddocumentbox').css('display', 'block');
			}
		}
	});
	//buttons
	$('.submitloginpopup').click(function() {
		console.log('email login');
		$.ajax({
			type: 'post',
			url: 'http://danesjenovdan.si/login/email.php',
			dataType: 'json',
			data: {
				'email': $('.accountemail').val(), 
				'name': $('.accountname').val().split(' ')[0], 
				'surname': $('.accountname').val().split(' ')[1]
			},
			success: function(data) {
				if (a == 0) {
					$('.loginpopup').modal('hide');
					$('.suggestionpopup').modal('show');
					$('.addsuggestiontitle').unbind('focus');
					$('.addsuggestioncontent').unbind('focus');
				} else {
					$('.loginpopup').modal('hide');
				}
				console.log(data);
			}
		});
	});
	$('.suggestionup').click(function() {
		console.log('begin');
		$.ajax({
			context: this,
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
			dataType: 'json',
			data: {
				'proposal_id': parseInt($('.suggestionup').data('id')),
				'type': 1
			},
			success: function(data) {
				if (data.success == -1) {
					alert('Za predlog lahko glasuješ samo enkrat.');
				} else if (data.success == 1) {
					$(this).next().children().first().text(parseInt($(this).next().children().first().text()) + 1);
					$(this).toggleClass('marked');
				} else if (data.success == 0) {
					$('.loginpopup').modal('show');
				}
				console.log(data);
			}
		});
		//TODO function to vote for
	});
	$('.suggestiondown').click(function() {
		$.ajax({
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
			dataType: 'json',
			data: {
				'proposal_id': parseInt($(this).data('id')),
				'type': -1
			},
			success: function(data) {
				if (data.success == -1) {
					alert('Za predlog lahko glasuješ samo enkrat.');
				} else if (data.success == 1) {
					$(this).next().children().first().text(parseInt($(this).next().children().first().text()) + 1);
					$(this).toggleClass('marked');
				} else if (data.success == 0) {
					$('.loginpopup').modal('show');
				}
				console.log(data);
			}
		});
		//TODO function to vote against
	});
	$('.adddocument').click(function() {
		//TODO function to add document
	});
	$('.argumentup').click(function() {
		console.log('begin');
		$.ajax({
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/vote_argument.php',
			dataType: 'json',
			data: {
				'argument_id': parseInt($(this).data('id')),
				'type': 1
			},
			success: function(data) {
				if (data.success == -1) {
					alert('Za argument lahko glasuješ samo enkrat.');
				} else if (data.success == 1) {
//					alert('Hvala za glas!');
				} else if (data.success == 0) {
					$('.loginpopup').modal('show');
				}
				console.log(data);
			}
		});
		$(this).toggleClass('marked');
		//TODO function to vote for
	});
	$('.argumentdown').click(function() {
		$.ajax({
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/vote_argument.php',
			dataType: 'json',
			data: {
				'argument_id': parseInt($(this).data('id')),
				'type': -1
			},
			success: function(data) {
				if (data.success == -1) {
					alert('Za argument lahko glasuješ samo enkrat.');
				} else if (data.success == 1) {
					alert('Hvala za glas!');
				} else if (data.success == 0) {
					$('.loginpopup').modal('show');
				}
				console.log(data);
			}
		});
		$(this).toggleClass('marked');
		//TODO function to vote against
	});
	$('.fbsignin').click(function() {
		//TODO function for facebook login
	});
	$('.googlesign').click(function() {
		window.location.href = "http://danesjenovdan.si/login/google.php"
	});
	$('.submitargumentfor').click(function() {
		console.log('begin');
		$.ajax({
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/add_argument.php',
			dataType: 'json',
			data: {
				'proposal_id': $('.suggestionup').data('id'),
				'type': 1,
				'content': $('#argumentinputfor').val(),
			},
			success: function(data) {
				if (data.success == 1) {
					alert(data.description);
					$('#argumentinputfor').val('');
				}
				console.log(data);
			}
		});
	});
	$('.submitargumentagainst').click(function() {
		console.log('begin');
		$.ajax({
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/add_argument.php',
			dataType: 'json',
			data: {
				'proposal_id': $('.suggestionup').data('id'),
				'type': -1,
				'content': $('#argumentinputagainst').val(),
			},
			success: function(data) {
				if (data.success == 1) {
					alert(data.description);
					$('#argumentinputagainst').val('');
				}
				console.log(data);
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
});