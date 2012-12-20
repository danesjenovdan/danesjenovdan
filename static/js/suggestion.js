$(document).ready(function() {
	$.ajax({
		type: 'get',
		url: 'http://sect.io/ajax/isAuthorized.php',
		dataType: 'json',
		success: function(data) {
			if (data.uid == -1) {
				$('.socialconnect').css('display', 'inline-block');
				$('.createaccount').css('display', 'inline-block');
			} else {
				$('.usersignedin').css('display', 'block');
				$('.signedinname').text(data.name);
			}
		}
	});
	//buttons
	$('.suggestionup').click(function() {
		console.log('begin');
		$.ajax({
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
				}
				console.log(data);
			}});
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
				}
				console.log(data);
			}});
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
					alert('Hvala za glas!');
				}
				console.log(data);
			}
		});
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
				}
				} else if (data.success == 1) {
					alert('Hvala za glas!');
				}
				console.log(data);
			}
		});
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
});