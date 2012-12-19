$(document).ready(function() {
	$.ajax({
		type: 'get',
		url: 'http://sect.io/ajax/isAuthorized.php',
		dataType: 'json',
		success: function(data) {
			if (data.uid == -1) {
				alert('show sign in buttons');
			}
		}
	});
	//buttons
	$('.suggest').click(function() {
		//TODO function to add suggestion
	});
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
	$('.submitargumentfor').click(function () {
		console.log('begin');
		$.ajax({
			type: 'post',
			url: 'http://sect.io/ajax/add_argument.php',
			dataType: 'json',
			data: {
				'proposal_id': $('.suggestionup').data('id'),
				'title': 'asdasdasdasdasdasdasd',
				'type': 1,
				'content': $('#argumentinput').val()
			},
			success: function(data) {
				console.log(data);
			}
		});
	});
});