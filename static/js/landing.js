$(document).ready(function() {
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
	$('.navblock').hover(function() {
		$(this).children().css('color', 'white');
		console.log('in');
	}, function() {
		$(this).children().css('color', '#363636');
	});
	$('.navblock').click(function() {
		document.location = $(this).children().attr('href');
	});
});