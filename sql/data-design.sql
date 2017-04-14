-- this is my database
DROP TABLE IF EXISTS favorite;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS profile;

-- time to creat the table!
CREATE TABLE profile (
	profileID INT UNSIGNED AUTO_INCREMENT NOT NULL,
	profileActivationToken CHAR(32),
	profileAtHandle VARCHAR(32) NOT NULL,
	profileEmail VARCHAR(128) NOT NULL,
	profileHash CHAR(128) NOT NULL,
	profilePhone VARCHAR(32),
	profileSalt CHAR(64) NOT NULL,
	UNIQUE (profileEmail),
	UNIQUE (profileAtHandle),
	PRIMARY KEY (profileID)
);

CREATE TABLE product (
	productID INT UNSIGNED AUTO_INCREMENT NOT NULL,
	productProfileID INT UNSIGNED NOT NULL,
	productPrice VARCHAR (32),
	INDEX(productProfileID)
	FOREIGN KEY (productProfileID) REFERENCES profile(proFileId),
	PRIMARY KEY (productID)
);

CREATE TABLE favorite (
	favoriteID INT UNSIGNED NOT NUll,
	favoriteProfileID INT UNSIGNED NOT NULL,
	favoriteDate DATETIME(6) NOT NULL,
	INDEX(favoriteID),
	INDEX(favoriteProfileID),
	FOREIGN KEY()

)
