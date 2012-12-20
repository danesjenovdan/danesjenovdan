function getmore() {
	var url = 'http://sect.io/danesjenovdan/ajax/list_proposals.php?id=' + $('#rightid').val();
	$.getJSON(url, function(data) {
		for (var i=0; i<data.length; i++) {
			$('#predlogi').prepend('<div class="row predlog"><div class="span4"><p class="suggestiontitle">'+ data[i].title +'</p></div><div class="span1"><p class="timestamp">'+data[i].timestamp+'</p></div><div class="span4"><div class="votebox" data-id="'+data[i].id+'"><div class="votefor">glasujza '+data[i].vote_plus+'</div><div class="voteagainst">glasujproti '+data[i].vote_minus+'</div></div></div><div class="span3"><div class="ihaveanargument">imam argument</div></div></div>');
		}
	});
}

function createbuttons() {
	$('.votefor').click(function() {
		$.ajax({
			type: 'post',
			url: 'http://sect.io/ajax/vote_proposal.php',
			dataType: 'json',
			data: {
				'proposal_id': $(this).parent().data('id'),
				'type': 1
			},
			success: function(data) {
				console.log(data);
			}});
	});
	$('.voteagainst').click(function() {
		$.ajax({
			type: 'post',
			url: 'http://sect.io/ajax/vote_proposal.php',
			dataType: 'json',
			data: {
				'proposal_id': $(this).parent().data('id'),
				'type': -1
			},
			success: function(data) {
				console.log(data);
			}});
	});
	$('.suggest').click(function() {
		$('.modal').modal('show');
	});
	$('.postsuggestion').click(function() {
		console.log('begin');
		$.ajax({
			type: 'post',
			url: 'http://sect.io/ajax/add_proposal.php',
			dataType: 'json',
			data: {
				'right_id': $('#rightid').val(),
				'title': $('.addsuggestiontitle').val(),
				'content': $('.addsuggestioncontent').val()
			},
			success: function(data) {
				console.log(data);
			}
		});
		console.log('end');
	});
//	$('.postsuggestionfromsuggestion').click(function() {
//		console.log('begin');
//		$.ajax({
//			type: 'post',
//			url: 'http://sect.io/ajax/add_proposal.php',
//			dataType: 'json',
//			data: {
//				'right_id': $('#rightid').val(),
//				'title': $('.addsuggestiontitle').val(),
//				'content': $('.addsuggestioncontent').val()
//			},
//			success: function(data) {
//				console.log(data);
//			}
//		});
//		console.log('end');
//	});
	$('.ihaveanargument').click(function() {
		document.location = document.location.href + '/' + $(this).data('id');
	});
}

$(document).ready(function() {
	createbuttons();
	$('.timestamp').text($('.timestamp').text().split(' ')[0])
	$('.'+$('#rightid').val()+' a').css('color', 'white');
});