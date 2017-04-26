<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "php/lib/xsrd.php";
require_once dirname("/etc/apache2/captstone0mysql/enrypted-config.php");

use VerybadetsyHttp\DataDesign\{
	Favorite
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

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestObject
		$requestObject = json_decode($requestContent);
		// This code will decode the JSON package and store it in the $requestObject

		// Make sure the favorite is available (required field)
		if(empty($requestObject->favoriteProductId) === true) {
			throw(new InvalidArgumentException("favorite does not exist", 405));
		}

		// Make sure favorite date is accurate (optional field)
		if(empty($requestObject->favoriteDate) === true) {
			$requestObject->favoriteDate = null;
		}

		// Make sure profileId is available
		if(empty($requestObject->favoriteProfileId) === true) {
			throw(new InvalidArgumentException("No Profile Id", 405));
		}

		//perform the actual put or post
		if($method === "put") {

			//enforce that the end user has a XSRF token.
			verifyXsrf();

			// retrieve that favorite is up to date
			$favorite = Tweet::getFavoritebyFavoriteProductId($pdo, $id);
			if($favorite === null) {
				throw(new InvalidArgumentException("favorite does not exist", 404));
			}

			//enforce the user is signed in and only trying to change their own favorite
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfilId() !== $favorite->getFavoriteProfileId()) {
				throw(new InvalidArgumentException("You are not allowed to edit this favorite", 403));
			}

			// update all favorites
			$favorite->setFavoriteDate($requestObject->favoriteDate);
			$favorite->setFavoriteProductId($requestObject->favoriteProfileId);
			#favorite->update($pdo);

			// update reply
			$reply->message = "Favorite updated ok";

		} else if($method === "POST") {

			//enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new InvalidArgumentException("you must be logged in to add a favorite", 403));
			}

			//create a favorite and insert it into the database
			$favorite = new Favorite(null, $requestObject->favoriteProfileId, $requestObject->FavoriteDate, null);
			$favorite->insert($pdo);

			//update reply
			$reply->message = "favorite created OK";
		}

	} else if($method === "DELETE") {

		//enforce that the end user has a XSRF token.
		verifyXsrf();

		//retrieve the favorite to be deleted
		$favorite = Favorite::getFavoritebyFavoriteProductId($pdo, $id);
		if($favorite === null) {
			throw(new InvalidArgumentException("favorite does not exist", 404));
		}

		//enforce the user is signed in and only trying to edit their own favorite
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $favorite->getFavoriteProfileId()) {
			throw(new InvalidArgumentException("you are not allowed to delete this favorite", 403));
		}

		//delete favorite
		$favorite->delete($pdo);
		//update reply
		$reply->message = "Favorite Deleted OK";
	} else {
		throw(new InvalidArgumentException("Invalid HTTP method request"));
	}
	// update the $reply->status $reply->message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);