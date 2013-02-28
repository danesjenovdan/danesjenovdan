$(document).ready(function() {

	$.getJSON('http://hekovnik.com/djnd/info.php', function(response) {
			$('#title').data('id', response.id);
			$('#title').text(response.title);
	});

	$('#za').click(function() {
		$.ajax({
			type: 'get',
			data: {
				'suggestion' : $('#title').data('id'),
				'vote' : 1
			},
			url: 'http://hekovnik.com/djnd/djnd.php'
		});
		$(this).toggleClass('marked');
		$('#za').unbind('click');
		$('#proti').unbind('click');
		setTimeout(function() {document.location = 'http://danesjenovdan.si/mobile/setcookie.php'}, 1000)
		return false;
	});
	$('#proti').click(function() {
		$.ajax({
			type: 'get',
			data: {
				'suggestion' : $('#title').data('id'),
				'vote' : -1
			},
			url: 'http://hekovnik.com/djnd/djnd.php'
		});
		$(this).toggleClass('marked');
		$('#za').unbind('click');
		$('#proti').unbind('click');
		setTimeout(function() {document.location = 'http://danesjenovdan.si/mobile/setcookie.php'}, 1000)
		return false;
	});
});