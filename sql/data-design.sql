DROP TABLE IF EXISTS favorite;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS profile;

CREATE TABLE profile (
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	profileActivationToken CHAR(32),
	profileAtHandle VARCHAR(32) NOT NULL,
	profileEmail VARCHAR(128) NOT NULL,
	profileHash CHAR(128) NOT NULL,
	profilePhone VARCHAR(32),
	profileSalt CHAR(64) NOT NULL,
	UNIQUE(profileEmail),
	UNIQUE(profileAtHandle),
	PRIMARY KEY(profileId)
);

CREATE TABLE product (
	productId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	productProfileId INT UNSIGNED NOT NULL,
	productPrice VARCHAR(32),
	INDEX(productProfileId),
	FOREIGN KEY(productProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(productId)
);

CREATE TABLE favorite (
	favoriteId INT UNSIGNED NOT NUll,
	favoriteProfileId INT UNSIGNED NOT NULL,
	favoriteDate DATETIME(6) NOT NULL,
	INDEX(favoriteId),
	INDEX(favoriteProfileId),
	FOREIGN KEY(favoriteId) REFERENCES profile(profileId),
	FOREIGN KEY(favoriteProfileId) REFERENCES product(productId),
	PRIMARY KEY(favoriteId, favoriteProfileId)
);
