var googleUser = {};

var startApp = function()
{
	gapi.load('auth2', function() {
		// Retrieve the singleton for the GoogleAuth library and set up the client.
		auth2 = gapi.auth2.init({
			client_id: GOOGLE_CLIENT_ID,
			cookiepolicy: 'single_host_origin',
			// Request scopes in addition to 'profile' and 'email'
			//scope: 'additional_scope'
		});
		var google_login_elems = document.getElementsByClassName('google_signin');

		for(var i = 0; i < google_login_elems.length; i++) {
			attachSignin(google_login_elems.item(i));
		}

		attachSignin(document.getElementById('pop_google_login'));
	});
}

function attachSignin(element)
{
	auth2.attachClickHandler(element, {},function(googleUser) {
		var id_token = googleUser.getAuthResponse().id_token;
		window.location = APP_URL+'/googleAuthenticate?idtoken='+id_token;
	},
	function(error) {
		// 
	});
}

startApp();