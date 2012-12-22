function getmore() {
	var url = 'http://danesjenovdan.si/ajax/list_proposals.php?id=' + $('#rightid').val();
	$.getJSON(url, function(data) {
		for (var i=0; i<data.length; i++) {
			$('#predlogi').prepend('<div class="row predlog"><div class="span4"><p class="suggestiontitle">'+ data[i].title +'</p></div><div class="span1"><p class="timestamp">'+data[i].timestamp+'</p></div><div class="span4"><div class="votebox" data-id="'+data[i].id+'"><div class="votefor">glasujza '+data[i].vote_plus+'</div><div class="voteagainst">glasujproti '+data[i].vote_minus+'</div></div></div><div class="span3"><div class="ihaveanargument">imam argument</div></div></div>');
		}
	});
}

function createbuttons() {
	$('.votefor').click(function() {
		$.ajax({
			context: this,
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
			dataType: 'json',
			data: {
				'proposal_id': $(this).parent().data('id'),
				'type': 1
			},
			success: function(data) {
				if (data.success == -1) {
					alert('Za predlog lahko glasujete samo enkrat');
				} else if (data.success == 1) {
					$(this).next().children().first().text(parseInt($(this).next().children().first().text()) + 1);
					$(this).toggleClass('marked');
				} else if (data.success == 0) {
					$('.loginpopup').modal('show');
				}
				console.log(data);
			}
		});
	});
	$('.voteagainst').click(function() {
		$.ajax({
			context: this,
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/vote_proposal.php',
			dataType: 'json',
			data: {
				'proposal_id': $(this).parent().data('id'),
				'type': -1
			},
			success: function(data) {
				if (data.success == -1) {
					alert('Za predlog lahko glasujete samo enkrat.');
				} else if (data.success == 1) {
					$(this).next().children().first().text(parseInt($(this).next().children().first().text()) + 1);
					$(this).toggleClass('marked');
				} else if (data.success == 0) {
					$('.loginpopup').modal('show');
				}
				console.log(data.success);
			}
		});
	});
	$('.suggest').click(function() {
		$('.suggestionpopup').modal('show');
	});
	$('.postsuggestion').click(function() {
		console.log('begin');
		$.ajax({
			type: 'post',
			url: 'http://danesjenovdan.si/ajax/add_proposal.php',
//			url: 'danejenovdan:8888/ajax/add_proposal.php',
			dataType: 'json',
			data: {
				'right_id': $('#rightid').val(),
				'title': $('.addsuggestiontitle').val(),
				'content': $('.addsuggestioncontent').val()
			},
			success: function(data) {
				if (data.success == 1) {
					alert(data.description);
					$('.addsuggestiontitle').val('');
					$('.addsuggestioncontent').val('');
					$('.suggestionpopup').modal('hide');
				} else if (data.success == 0) {
					alert(data.description);
				}
				console.log(data);
			}
		});
		console.log('end');
	});
	$('.ihaveanargument').click(function() {
		document.location = document.location.href + '/' + $(this).data('id');
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
		document.location = 'http://danesjenovdan.si/login/facebook.php?ref=' + encodeURIComponent(document.location.href);
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

$(document).ready(function() {
	createbuttons();
	$('.timestamp').text($('.timestamp').text().split(' ')[0])
	$('.'+$('#rightid').val()+' a').toggleClass('iamthestar');


            $('#adddocumentbox').ajaxForm({
            	target:        '#docresp',   // target element(s) to be updated with server response 
 		       	beforeSubmit:  function(){
 		       		$("#docresp").html("Nalagam...");
 		       	},  // pre-submit callback 
        		success:       function(){
        		}  // post-submit callback 
 
            });	
});






