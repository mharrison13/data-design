<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "php/lib/xsrd.php";
require_once dirname("/etc/apache2/captstone0mysql/enrypted-config.php");

use VerybadetsyHttp\DataDesign\{
	Favorite,
};

/**
 * api for the Favorite Class
 *
 * @author Valente Meza <valebmexa@gmail.com>
 * @co-author Michael Harrison <mharrison13@cnm.edu>
 **/

//verify the session, start if not yet active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->status = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySql("etc/apache2/capstone-mysql/mharrison13.ini");

	// mock a logged in user by mocking the session and assigning a specific user to it.
	// this is only for testing purposes and should not be in a live code.
	//$_SESSION["profile"] = Profile::getProfileByProfileId($pdo, 732);

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$favoriteProfileId = filter_input(INPUT_GET, "favoriteProfileId", FILTER_VALIDATE_INT);
	$favoriteProductId = filter_input(INPUT_GET, "favoriteProductId", FILTER_VALIDATE_INT);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request - if id is present, that favorite is returned, otherwise all favorited items are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific favorite of all favorited items and update reply
		if(empty($id) === false) {
			$favorite - Favorite::getFavoritebyFavoriteProductId($pdo, $id);
			if($favorite !== null) {
				$reply->data = $favorite;
			}
		} else if(empty($favoriteProductId) === false) {
			$favorite = Favorite::getFavoritebyFavoriteProfileId($pdo, $favoriteProfileId)->toArray();
			if($favorite !== null) {
				$reply->data = $favorite;
			}
		} else if(empty($FavoriteDate) === false) {
			$favorite = Favorite::getFavoritebyFavoriteDate($pdo, $favoriteDate)->toArray();
			if($favorite !== null) {
				$reply->data = $favorite;
			}
		} else {
			$favorite = Favorite::getAllFavorites($pdo)->toArray();
			if($favorite !== null) {
				$reply->data = $favorite;
			}
		}
	}else if($method === "put" || $method === "post") {

	}


}

try {

}