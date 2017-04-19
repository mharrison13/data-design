<?php
/**
 * Profile Class for Very Bad Etsy
 *
 * This class is a collection of profile data collected by users of the website Very Bad Etsy. Hopefully it works!
 *
 * @author Michael Harrison <mharrison13@cnm.edu>
 * @version 0.0.1
 **/
class Profile {
	/**
	 * id for this Profile; this is the primary key
	 * @var int $profileId
	 **/
	private $profileId;
	/**
	 * this is the Profile activation token for profileId
	 * @var string $profileActivationToken
	 **/
	private $profileActivationToken;
	/**
	 * this is the handle for the users profile
	 * @var string $profileAtHandle
	 **/
	private $profileAtHandle;
	/**
	 * this is the email address for the users profile
	 * @var string $profileEmail
	 **/
	private $profileEmail;
	/**
	 * this is the hash for the users profile
	 * @var string $profileHash
	 **/
	private $profileHash;
	/**
	 * this is the phone number for the users profile
	 * @var string $profilePhone
	 **/
	private $profilePhone;
	/**
	 * this is the salt for the users profile
	 * @var string $profileSalt
	 **/
	private $profileSalt;

	/**
	 * accessor method for profile id
	 *
	 * @return int|null value of profile id
	 **/
	public function getProfileId():?int {
		return ($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param int|null $newProfileId new value of profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not an integer
	 **/
	public function setProfileId(?int $newProfileId): void {
		//if profile id is null immediately return it
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}
		//verify the profile id is positive
		if($newProfileId <= 0) {
			throw(new \RangeException("profile id is not positive"));
		}
		// convert and store the profile id
		$this->profileId = $newProfileId;
	}
	/*
	 * accessor method for profile activation token
	 *
	 * @return string value of profile activation token
	 */
	public function getProfileActivationToken(): string {
		return ($this->profileActivationToken);
	}
	/**
	 * mutator method for profile activation token
	 *
	 * @param string $newProfileActivationToken new value of profile activation token
	 * @throws \InvalidArgumentException if the Activation Token is not secure
	 * @throws \RangeException if the Activation token is not 32 characters
	 * @throws \TypeError if $newProfileActivationToken is not a string
	 **/
	public function setProfileActivationToken(string $newProfileActivationToken): void {
		//enforce that the activation token is properly formatted
		$newProfileActivationToken = trim($newProfileActivationToken);
		$newProfileActivationToken = strtolower($newProfileActivationToken);
		if(empty($newProfileActivationToken) === true) {
			throw(new \InvalidArgumentException("profile activation token is empty or insecure"));
		}
		//enforce that the activation token is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileActivationToken)) {
			throw(new \InvalidArgumentException("profile activation token is empty or insecure"));
		}
		//enforce that the activation token is exactly 32 characters.
		if(strlen($newProfileHash) !== 32) {
			throw(new \RangeException("profile activation token must be 32 characters"));
		}
		//store the hash
		$this->profileActivationToken = $newProfileActivationToken;
	}
	//store the activation token
		//$this->profileActivationToken = $newProfileActivationToken;


/**
 * accessor method for profile at handle
 *
 * @return string value of the at handle
 **/
public
function getProfileAtHandle(): string {
	return ($this->profileAtHandle);
	}

	/**
	 * mutator method for at handle
	 *
	 * @param string $newProfileAtHandle new value of at handle
	 * @throws \InvalidArgumentException if $newAtHandle is not a string or insecure
	 * @throws \RangeException if $newAtHandle is > 32 characters
	 * @throws \TypeError if $newAtHandle is not a string
	 **/
	public
	function setProfileAtHandle(string $newProfileAtHandle): void {
		//verify the at handle is a secure string
		$newProfileAtHandle = trim($newProfileAtHandle);
		$newProfileAtHandle = filter_var($newProfileAtHandle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileAtHandle) === true) {
			throw(new \InvalidArgumentException("profile at handle is empty or insecure"));
		}

		// verify the at handle will fit in the database
		if(strlen($newProfileAtHandle) > 32) {
			throw(new \RangeException("profile at handle is too large"));
		}

		// store the at handle
		$this->profileAtHandle = $newProfileAtHandle;
	}

	/**
	 * accessor method for email
	 *
	 * @return string value of the user email
	 */
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newProfileEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		// verify the email is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("profile email too long"));
		}
		// store the email
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * accessor method for the profile hash
	 *
	 * @return string value of hash
	 */

	public function getProfileHash(): string {
		return $this->profileHash;
	}
	/**
	 * mutator method for profile hash password
	 *
	 * @param string $newProfileHash
	 * @throws \InvalidArgumentException if the hash has is not secure
	 * @throws \RangeException if the hass is not 128 characters
	 * @throws \TypeError if profile hash is not 128 characters
	 */
	public function setProfileHash(string $newProfileHash): void {
		//enforce that the has is properly formatted
		$newProfileHash = trim($newProfileHash);
		$newProfileHash = strtolower($newProfileHash);
		if(empty($newProfileHash) === true) {
			throw(new \InvalidArgumentException("profile hash empty or insecure"));
		}

		//enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileHash)) {
			throw(new \RangeException("profile hash must be 128 character"));
		}

		//enforce that the hash is exactly 128 characters
		if(strlen($newProfileHash) !==128) {
			throw(new \RangeException("profile hash must be 128 characters"));
		}

		//store the hash
		$this->profileHash = $newProfileHash;
	}

	/**
	 * accessor method for phone
	 *
	 * @return string value of phone or null
	 */
	public function getProfilePhone(): ?string {
		return ($this->profilePhone);
	}

	/**
	 * mutator method for phone number
	 *
	 * @param string $newProfilePhone new value of phone
	 * @throws \InvalidArgumentException if the $newPhone is not a string or insecure
	 * @throws \RangeException if the $newPhone is > 32 characters
	 * @throws \TypeError if $newPhone is not a string
	 */

	public function setProfilePhone(?string $newProfilePhone) {
		//if $profilePhone is null return it right away
		if($newProfilePhone === null) {
			$this->profilePhone = null;
			return;
		}

		// verify the phone is secure
		$newProfilePhone = trim($newProfilePhone);
		$newProfilePhone = filter_var($newProfilePhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfilePhone) === true ) {
			throw(new \InvalidArgumentException("profile phone is empty or insecure"));
		}

		// verify the phone will fit in the database
		if(strlen($newProfilePhone) > 32) {
			throw(new \InvalidArgumentException("profile phone is too large"))
		}

		/**
		 * accessor method for salt
		 *
		 * @return string representation of the salt hexadecimal
		 */
		public function getProfileSalt(): string {
			return $this->profileSalt;
		}
		/**
		 * mutator method for profile salt
		 *
		 * @param string $newProfileSalt
		 * @throws \InvalidArgumentException if the salt is not secure
		 * @throws \RangeException if the salt is > 64 characters
		 * @throws \TypeError if the salt is not a string
		 */
		public function setProfileSalt(string $newProfileSalt): void {
			//enforce that the salt is properly formatted
			$newProfileSalt = trim($newProfileSalt);
			$newProfileSalt = strlen($newProfileSalt);

			//enforce that the salt is properly formatted
			if(!ctype_xdigit($newProfileSalt)) {
				throw(new \InvalidArgumentException("profile password hash is empty or insecure"));
			}

			//enforce that the salt length is exactly 64 characters
			if(strlen($newProfileSalt) !==64) {
				throw(new \RangeException("profile salt must be 64 characters"))
			}
		}


	}
}

