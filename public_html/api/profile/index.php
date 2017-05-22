<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "php/lib/xsrf.php";
require_once dirname("/etc/apache2/captstone0mysql/enrypted-config.php");

use VerybadetsyHttp\DataDesign\{
	Profile
};

/**
 * api for the Profile Class
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
	//$_SESSION["profile"] = Profile::getProductByProductProfileId($pdo, 732);

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$profileId = filter_input(INPUT_GET, "profileId", FILTER_VALIDATE_INT);
	$profileAtHandle = filter_input(INPUT_GET, "profileAtHandle", FILTER_VALIDATE_INT);
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_VALIDATE_INT);
	$profilePhone = filter_input(INPUT_GET, profilePhone, FILTER_VALIDATE_INT);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request - if id is present, that product is returned, otherwise all products items are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific profile and update
		if(empty($id) === false) {
			$product - Profile::getProfileByProfileId($pdo, $id);
			if($product !== null) {
				$reply->data = $product;
			}
		} else if(empty($productProfileId) === false) {
			$product = Profile::getProfileByProfileAtHandle($pdo, $productProfileId)->toArray();
			if($product !== null) {
				$reply->data = $product;
			}
		} else if(empty($productPrice) === false) {
			$product = Profile::getProfilebyProfileEmail($pdo, $productPrice)->toArray();
			if($product !== null) {
				$reply->data = $product;
			}
		} else if(empty($productPrice) === false) {
			$product = Profile::getProfilebyProfilePhone($pdo, $productPrice)->toArray();
			if($product !== null) {
				$reply->data = $product;
			}
		} else {
			$product = Profile::getAllProfiles($pdo)->toArray();
			if($product !== null) {
				$reply->data = $product;
			}
		}
	}else if($method === "put" || $method === "post") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestObject
		$requestObject = json_decode($requestContent);
		// This code will decode the JSON package and store it in the $requestObject

		// Make sure the profile is available (required field)
		if(empty($requestObject->ProfileId) === true) {
			throw(new InvalidArgumentException("Profile does not exist", 405));
		}

		// Make sure profileId is accurate (optional field)
		if(empty($requestObject->profileId) === true) {
			$requestObject->profileId = null;
		}

		//perform the actual put or post
		if($method === "put") {

			//enforce that the end user has a XSRF token.
			verifyXsrf();

			// retrieve that profile is up to date
			$product = Profile::getProfileByProfileId($pdo, $id);
			if($product === null) {
				throw(new InvalidArgumentException("Profile does not exist", 404));
			}

			//enforce the user is signed in and only trying to change their own product
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfilId() !== $product->getProductProfileId()) {
				throw(new InvalidArgumentException("You are not allowed to edit this product", 403));
			}

			// update all profile
			$product->setProfileAtHandle($requestObject->productProfileId);
			$product->setProfileEmail($requestObject->ProductPrice);
			$product->setProductProfilePhone($requestObject->productProfileId);
			#product->update($pdo);

			// update reply
			$reply->message = "Product updated ok";

		} else if($method === "POST") {

			//enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new InvalidArgumentException("you must be logged in to create a profile", 403));
			}

			//create a profile and insert it into the database
			$Profile = new Profile(null, $requestObject->profileAtHandle, $requestObject->profileEmail, $requestObject->profilePhone = null);
			$Profile->insert($pdo);

			//update reply
			$reply->message = "profile created OK";
		}

	} else if($method === "DELETE") {

		//enforce that the end user has a XSRF token.
		verifyXsrf();

		//retrieve the profile to be deleted
		$product = Profile::getProfilebyProfileId($pdo, $id);
		if($profile === null) {
			throw(new InvalidArgumentException("profile does not exist", 404));
		}

		//enforce the user is signed in and only trying to edit their own product
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProductProfileId() !== $product->getProductProfileId()) {
			throw(new InvalidArgumentException("you are not allowed to delete this profile", 403));
		}

		//delete product
		$product->delete($pdo);
		//update reply
		$reply->message = "Product Deleted OK";
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