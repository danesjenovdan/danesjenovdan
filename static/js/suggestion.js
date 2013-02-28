var a;

$(document).ready(function() {
	$('.naprej').click(function() {
		$('.razvijaj').toggle('slow');
		$('html, body').animate({ scrollTop: $(document).height() }, 'slow');
		return false;
	});
	$('.medijiklik').click(function() {
		$('.mediji').toggle('slow');
		$('html, body').animate({ scrollTop: $(document).height() }, 'slow');
		return false;
	});
	
	//buttons
	
	$('.adddocument').click(function() {
		//TODO function to add document
	});
	$('.adddocument').click(function() {
		$.ajax({
			type: 'get',
			url: 'http://danesjenovdan.si/ajax/isAuthorized.php',
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
	createbuttons();
//	FBinitmadafaka();
});