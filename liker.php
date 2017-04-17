<?php
header("Content-Type: text/html;charset=utf-8");

session_start();
//require_once 'class.user.php';
//$reg_user = new USER();
require_once __DIR__ . '/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '240647883009465',
  'app_secret' => '94d445e5ad50f850115fcc1766a6f1d2',
  'default_graph_version' => 'v2.8',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // optional	
try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();

  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }

if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;

	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();

		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}

	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		header('Location: ./');
	}

	// getting basic info about user
	try {
		//$profile_request = $fb->get('spinera/posts');//'/me?fields=name,first_name,last_name,email');
		//$profile = $profile_request->getGraphEdge()->asArray();
			// getting likes data of recent 100 posts by user
		$getPostsLikes = $fb->get('/spinera/posts?fields=likes.limit(10){name,id}&limit=2');
		$getPostsLikes = $getPostsLikes->getGraphEdge()->asArray();
		// printing likes data as per requirements
		foreach ($getPostsLikes as $key) {
			if (isset($key['likes'])) {
				echo count($key['likes']) . '<br>';
				foreach ($key['likes'] as $key) {
					$spineraEmotion = 'like';
					$spineraName = $key['name'];
					$spineraFbId = $key['id'];
					//$reg_user->regSpinera($spineraFbId,$spineraName,$spineraEmotion);

					echo $key['name'] . ' ';
					echo $key['id'] . '<br>';
				}
			}
		}



	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		// redirecting user back to app login page
		header("Location: ./");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	
	// printing $profile array on the screen which holds the basic info about user
	//print_r($profile);

  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl('https://visener.com/liker.php', $permissions);
	echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}