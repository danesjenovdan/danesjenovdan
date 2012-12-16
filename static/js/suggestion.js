$(document).ready(function() {
	//buttons
	$('.suggest').click(function() {
		//TODO function to add suggestion
	});
	$('.suggestionup').click(function() {
		$.ajax({
			type: 'post',
			url: 'http://sect.io/danesjenovdan/ajax/vote_suggestion.php',
			dataType: 'json',
			data: {
				'proposal_id': $(this).parent().data('id'),
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
			url: 'http://sect.io/danesjenovdan/ajax/vote_proposal.php',
			dataType: 'json',
			data: {
				'proposal_id': $(this).parent().data('id'),
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
		//TODO function to vote for
	});
	$('.argumentdown').click(function() {
		//TODO function to vote against
	});
});