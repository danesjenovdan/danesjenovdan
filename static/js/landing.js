$('.tableft').click(function() {
	$('.leftbox').css('display', 'block');
	$('.rightbox').css('display', 'none');
	return false;	
});
$('.tabright').click(function() {
	$('.rightbox').css('display', 'block');
	$('.leftbox').css('display', 'none');
	return false;
});