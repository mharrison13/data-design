-- this is my database
DROP TABLE IF EXISTS favorite;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS profile;

-- time to creat the table!
CREATE TABLE profile (
	profileID INT UNSIGNED AUTO_INCREMENT NOT NULL,
	profileActivationToken CHAR(32),
	profileAtHandle VARCHAR(32) NOT NULL,
	profileEmail VARCHAR (128) NOT NULL,
	profileHash CHAR (128) NOT NULL,
	profilePhone (32),
	profileSalt CHAR (64) NOT NULL,
	UNIQUE (profileEmail),
	UNIQUE (profileAtHandle),
	PRIMARY key (profileID)
);
