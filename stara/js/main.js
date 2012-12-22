/* Signatures */

/* Start Up */
function StartUp(runnable)
{
	$(document).ready(runnable.run);
}

var Main =
{
	run: function ()
	{
		$('a.botext_trigger').bind ('click', function () {
			$('.botext').toggle ('slow');
			return false;
		});
		$('a.disctext_trigger').bind('click', function() {
			$('.disctext').toggle('slow');
			return false;
		});
		$('a.sign_trigger').bind('click', function() {
			$('.signatures').text($('.signatures').text().split(' ...')[0] + signatures);
			$('a.sign_trigger').hide();
			return false;
		});
		$('.tooltiptitle').hover(function() {
			$('.tooltip').css('display', 'inline-block');
		}, function() {
			$('.tooltip').css('display', 'none');
		});
	}
}
StartUp (Main);
