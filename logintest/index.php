<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
	
		<!-- Facebook crap -->
		<div id="fb-root"></div>
		<script>
			// FB login function
			function login() {
			    FB.login(function(response) {
			        if (response.authResponse) {
			            // connected
			            testAPI();
			        } else {
			            // cancelled
			        }
			    });
			}
			
			function testAPI() {
			    console.log('Welcome!  Fetching your information.... ');
			    FB.api('/me', function(response) {
			        console.log('Good to see you, ' + response.name + '.');
			    });
			}
			console.log('hi');
		  // Additional JS functions here
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '301375193309601', // App ID
		      channelUrl : 'http://www.danesjenovdan.si/channel.html', // Channel File
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      xfbml      : true  // parse XFBML
		    });
		    FB.getLoginStatus(function(response) {
		      if (response.status === 'connected') {
		        // connected
		        testAPI();
		      } else if (response.status === 'not_authorized') {
		        // not_authorized
		        login();
		      } else {
		        // not_logged_in
		        login();
		      }
		     });
		
		    // Additional init code here
		
		  };
		
		  // Load the SDK Asynchronously
		  (function(d){
		     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement('script'); js.id = id; js.async = true;
		     js.src = "//connect.facebook.net/en_US/all.js";
		     ref.parentNode.insertBefore(js, ref);
		   }(document));
		   
		</script>
		<!-- end of Facebook crap -->
	
		<a href="#">Login with Facebook</a>
	</body>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
	<script>
		
	</script>
	
</html>