$(document).ready(function() {

	$.getJSON('http://sect.io/danesjenovdan/ajax/list_proposals.php', function(data) {
		for (var i=0; i<data.length; i++) {
			$('.predlogi').add('
				<div class="row predlog">
					<div class="span4">
						<p class="suggestiontitle">'+ data[i].title +'</p>
					</div>
				</div>	
			');
		}
		$('#imepredloga').text(data[0].title);
		$('#timestamppredloga').text(data[0].timestamp);
		$('#voteplus').text(data[0].vote_plus);
		$('#voteminus').text(data[0].vote_minus);
	});
	
});