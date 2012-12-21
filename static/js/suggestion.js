$(document).ready(function() {
	$('.naprej').click(function() {
		$('.razvijaj').toggle('slow');
		return false;
	});
	$('.medijiklik').click(function() {
		$('.mediji').toggle('slow');
		return false;
	});
	$.ajax({
		type: 'get',
		url: 'http://sect.io/ajax/isAuthorized.php',
		dataType: 'json',
		success: function(data) {
			if (data.uid == -1) {
				$('.socialconnect').css('display', 'inline-block');
				$('.createaccount').css('display', 'inline-block');
				$('.adddocument').css('display', 'block');
				$('form.adddocumentbox').css('display', 'none');
				$('#argumentinputagainst').focus(function() {
					$('.loginpopup').modal('show');
				});
				$('#argumentinputfor').focus(function() {
					$('.loginpopup').modal('show');
				});
				$('.addsuggestioncontent').focus(function() {
					$('.suggestionpopup').modal('hide');
					$('.loginpopup').modal('show');
				});
				$('.addsuggestiontitle').focus(function() {
					$('.suggestionpopup').modal('hide');
					$('.loginpopup').modal('show');
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
//		$.ajax({
//			context: this,
//			type: 'post',
//			url: 'http://sect.io/login/email.php',
//			dataType: 'json',
//			data: {
//				'email': $('.accountemail').val(), 
//				'name': $('.accountname').val().split(' ')[0], 
//				'surname': $('.accountname').val().split(' ')[1]
//			},
//			success: function(data) {
//				console.log(data);
//			}
//		});
	});
	$('.suggestionup').click(function() {
		console.log('begin');
		$.ajax({
			context: this,
			type: 'post',
			url: 'http://sect.io/ajax/vote_proposal.php',
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
				}
				console.log(data);
			}
		});
		//TODO function to vote for
	});
	$('.suggestiondown').click(function() {
		$.ajax({
			type: 'post',
			url: 'http://sect.io/ajax/vote_proposal.php',
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
			url: 'http://sect.io/ajax/vote_argument.php',
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
			url: 'http://sect.io/ajax/vote_argument.php',
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
		window.location.href = "http://sect.io/login/google.php"
	});
	$('.submitargumentfor').click(function() {
		console.log('begin');
		$.ajax({
			type: 'post',
			url: 'http://sect.io/ajax/add_argument.php',
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
			url: 'http://sect.io/ajax/add_argument.php',
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
			url: 'http://sect.io/ajax/isAuthorized.php',
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