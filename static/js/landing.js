$('.tableft').click(function() {
	$('.leftbox').css('display', 'block');
	$('.rightbox').css('display', 'none');
	$('.tableft>').toggleClass('infocus');
	$('.tabright>').toggleClass('infocus');
	return false;	
});
$('.tabright').click(function() {
	$('.rightbox').css('display', 'block');
	$('.leftbox').css('display', 'none');
	$('.tableft>').toggleClass('infocus');
	$('.tabright>').toggleClass('infocus');
	return false;
});