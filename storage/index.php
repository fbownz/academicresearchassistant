<?php 
session_id('Twitter');
session_start();

require "twitteroauth/autoloader.php";

use Abraham\TwitterOAuth\TwitterOAuth;

require ('config.php');

if(empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])){
	header('Location: clearsession.php');

}
$access_token = $_SESSION['access_token'];

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$userDetails = $connection->get('account/verify_credentials');

echo '<pre>', print_r($userDetails, true ), '</pre>';


?>