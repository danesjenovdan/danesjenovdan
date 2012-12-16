$(document).ready(function() {
	var url = 'http://sect.io/danesjenovdan/ajax/list_proposals.php?id=' + $('#rightid').val();
	$.getJSON(url, function(data) {
		for (var i=0; i<data.length; i++) {
			$('#predlogi').prepend('<div class="row predlog"><div class="span4"><p class="suggestiontitle">'+ data[i].title +'</p></div><div class="span1"><p class="timestamp">'+data[i].timestamp+'</p></div><div class="span4"><div class="votebox"><div class="votefor">glasujza '+data[i].vote_plus+'</div><div class="voteagainst">glasujproti '+data[i].vote_minus+'</div></div></div><div class="span3"><div class="ihaveanargument">imam argument</div></div></div>');
		}
	});	
});