function makeGraph(votefor, voteagainst) {
	var all = parseInt(votefor) + parseInt(voteagainst);
	$('#za').width((votefor/all * 100).toString() + '%');
	$('#proti').width((voteagainst/all * 100).toString() + '%');
	$('#za').width($('#za').width() - 97);
	$('#proti').width($('#proti').width() - 97);
}

$(document).ready(function() {

	$.getJSON('http://hekovnik.com/djnd/live.php', function(response) {
			$('#title').data('id', response.id);
			$('#title').text(response.title);
			makeGraph(response.votefor, response.voteagainst);
	});
	
	window.setInterval(function() {
		$.getJSON('http://hekovnik.com/djnd/live.php', function(response) {
			makeGraph(response.votefor, response.voteagainst);
		});
	}, 500)
	
});