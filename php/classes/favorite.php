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
class Profile implements \JsonSerializable {
	use ValidateDate;
	/**
	 * id for this favorite ProductId; this is a primary key
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
	 * @var \dateTime $dateTime
	 **/
	private $FavoriteDate;

	/**
	 * constructor for this favorite
	 *
	 * @param int|null $newFavoriteProductId of this favorite or null if a new favorite
	 * @param int $newFavoriteProfileId id fo the profile that favorites an item
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

}