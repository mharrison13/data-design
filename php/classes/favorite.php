<?php
namespace VerybadetsyHttp\DataDesign;
require("autoload.php");
/**
 * Profile Class for Very Bad Etsy
 *
 * This class is a collection of profile data collected by users of the website Very Bad Etsy. Hopefully it works!
 *
 * @author Michael Harrison <mharrison13@cnm.edu>
 * @version 0.0.1
 **/
class Favorite implements \JsonSerializable {
	use ValidateDate;
	/**
	 * id for this favorite ProductId; this is a foreign key
	 * @var int $favoriteProductId
	 **/
	private $favoriteProductId;

	/**
	 * id for favorite profile id; this is a foreign key
	 * @var int $favoriteProfileId
	 **/
	private $favoriteProfileId;

	/**
	 * id for dateTime
	 * @var \dateTime $favoriteDate
	 **/
	private $favoriteDate;

	/**
	 * constructor for this favorite
	 *
	 * @param int|null $newFavoriteProductId of this favorite or null if a new favorite
	 * @param int $newFavoriteProfileId id for the profile that favorites an item
	 * @param \dateTime | string | null $newFavoriteDate dat and time Favorite was sent
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @documentation https://php.net/manual/en/language.oop5.decon.php
	 */
	public function __construct(?int $newFavoriteProductId, int $newFavoriteProfileId, $newFavoriteDate = null) {
		try {
			$this->setFavoriteProductId($newFavoriteProductId);
			$this->setFavoriteProfileId($newFavoriteProfileId);
			$this->setFavoriteDate($newFavoriteDate);
		}
		//determine what exception was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \Exception | \TypeError $exception) {
			$exceptionType = get_class ($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for favoriteProductId
	 *
	 * @return int|null value favorite product id
	 **/
	public function getFavoriteProductId() : ?int {
		return($this->favoriteProductId);
	}

	/**
	 * mutator method for favoriteProductId
	 *
	 * @param int|null $newFavoriteProductId new value of favorite product Id
	 * @throw \RangeException if $newFavoriteProductId is not positive
	 * @throw \TypeError if $newFavoriteProductId is not an integer
	 **/

	public function setFavoriteProductId(?int $newFavoriteProductId) : void {
		//if favorite product id is null immediately return it
		if($newFavoriteProductId === null) {
			$this->favoriteProductId = null;
			return;
		}
		//verify the favorite product id is positive
		if($newFavoriteProductId <=0) {
			throw (new \RangeException("tweet id is not a positive"));
		}
		//convert and store the favorite product id
		$this->favoriteProductId = $newFavoriteProductId;
	}

	/**
	 * accessor method for favoriteProfileId
	 *
	 * @return int value of favorite profile id
	 **/
	public function getFavoriteProfileId() : int {
		return($this->favoriteProfileId);
	}
	/**
	 * mutator method for favorite profile id
	 *
	 * @param int $newFavoriteProfileId new value for favorite profile Id
	 * @throws \RangeException if $newFavoriteProfileId is not positive
	 * @throws \TypeError if $newFavoriteProfileId is not an integer
	 **/
	public function setFavoriteProfileId(int $newFavoriteProfileId) : void {
		//verify the favorite profile id is positive
		if($newFavoriteProfileId <=0) {
			throw (new \RangeException("Profile Id is not positive"));
		}
		// convert and store the favorite profile id
		$this->favoriteProfileId = $newFavoriteProfileId;
	}

	/**
	 * accessor method for favorite date
	 *
	 * @return \DateTime value of favorite date
	 **/
	public function getFavoriteDate() : \DateTime {
		return($this->favoriteDate);
	}
	/**
	 * mutator method for favoriteDate
	 *
	 * @param \DateTime|string|null $newFavoriteDate favorite date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newFavoriteDate is not a valid object or string
	 * @throws \RangeException if $newFavoriteDate is a date that does not exist
	 **/
	public function setFavoriteDate($newFavoriteDate = null) : void {
		// base case: if the date is null, use the current date and time
		if($newFavoriteDate === null) {
			$this->favoriteDate = new \DateTime();
			return;
		}
		//store the like date using the ValidateDate trait
		try {
			$newFavoriteDate = self::ValidateDate($newFavoriteDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->favoriteDate = $newFavoriteDate;
	}

	/**
	 * inserts this Favorite into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		//enforce the favoriteProductId is null (i.e., don't insert a favorite that already has a favorite)
		if($this->favoriteProductId !== null) {
			throw(new \PDOException("this already has a favorite"));
		}
		// create query template
		$query = "INSERT INTO favorite(favoriteProductId, favoriteProfileId, favoriteDate) VALUES(:favoriteProductId, :favoriteProfileId, favoriteDate)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$formattedDate = $this->favoriteDate->format("Y-m-d H:i:s");
		$paramater = ["favoriteProductId" => $this->favoriteProductId, "favoriteProfileId" => $this->favoriteProfileId, "favoriteDate" => $formattedDate];
		$statement->execute($parameters);
		// update the null favoriteProductId with what mySQL just gave us
		$this->favoriteProductId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes this favorite from mySQL
	 *
	 * @param \PDO $pdo PDO connections object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		//enforce the favoriteProductId is not null (i.e., don't unfavorite a favorite that does not exist)
		if($this->favoriteProductId === null) {
			throw(new \PDOException("no favorite exists to remove"));
		}
		// create query template
		$query = "DELETE FROM favorite WHERE favoriteProductId = :favoriteProductId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["favoriteProductId" => $this->favoriteProductId];
		$statement->execute($parameters);
	}




	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		//format the date so that the front end can consume it
		$fields["favoriteDate"] = round(floatval($this->favoriteDate->format("U.u")) * 1000);
		return($fields);
	}
}