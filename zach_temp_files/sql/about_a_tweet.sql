DROP TABLE IF EXISTS favTweet;
DROP TABLE IF EXISTS embedTweet;
DROP TABLE IF EXISTS replyTweet;
DROP TABLE IF EXISTS reTweet;
DROP TABLE IF EXISTS tweet;

DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS user;

create TABLE user (
-- AUTO_INCREMENT is for system assigned numbers and counts 1,2,3, ...
-- NOT NULL means the field can *NEVER* be empty
-- no comma after last field because like writing a list in prose.


	userId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	email VARCHAR(64) NOT NULL,
	passwordHash CHAR(128) NOT NULL,
	salt CHAR(64) NOT NULL,
	authToken CHAR(32),

-- a primary key is the unique identifier for the table
	PRIMARY KEY(userId),

-- a unique field enforces that duplicates may not exist
	UNIQUE(email)

);

-- create profile table
CREATE TABLE profile (
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	userId INT UNSIGNED NOT NULL,
	firstName VARCHAR(32) NOT NULL,
	lastName VARCHAR(32) NOT NULL,
	PRIMARY KEY (profileId),
	UNIQUE(userId),
	INDEX (lastName),
-- this establishes a 1-1 relationship with user.  Once we have them attached, as an example, you can't run the
-- following command without deleting or detaching profile first: DELETE FROM user WHERE userID = 1;
-- other option to delete after this point would be DELETE CASCADE (or CASCADE ON DELETE) user WHERE userID=1
-- There are also cases where you would want to not use the Foreign Key default option to connect tables but other
-- options that enable you to link data on a conditional basis?  Not important for now but be aware.
	FOREIGN KEY(userId) REFERENCES user(userId)

);

CREATE TABLE tweet (
	tweetId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	profileId INT UNSIGNED NOT NULL,
	tweetContent VARCHAR (140) NOT NULL,
	PRIMARY KEY (tweetId),

	INDEX(profileId),
	FOREIGN KEY(profileId) REFERENCES profile (profileId)


);


CREATE TABLE reTweet (
	profileId INT UNSIGNED NOT NULL,
	tweetId INT UNSIGNED NOT NULL,
	reTweetContent VARCHAR (140) NOT NULL,

	INDEX(tweetId),
	INDEX(profileId),

	PRIMARY KEY (tweetId,profileId),

	FOREIGN KEY(profileId) REFERENCES profile (profileId),
	FOREIGN KEY(tweetId) REFERENCES tweet (tweetId)

);


CREATE TABLE replyTweet (
	profileId INT UNSIGNED NOT NULL,
	tweetId INT UNSIGNED NOT NULL,
	replyTweetContent VARCHAR (140) NOT NULL,

	INDEX(tweetId),
	INDEX(profileId),

	PRIMARY KEY (tweetId,profileId),

	FOREIGN KEY(profileId) REFERENCES profile (profileId),
	FOREIGN KEY(tweetId) REFERENCES tweet (tweetId)
);


CREATE TABLE embedTweet (
	profileId INT UNSIGNED NOT NULL,
	tweetId INT UNSIGNED NOT NULL,
	codeToEmbed VARCHAR(100) NOT NULL,

	INDEX(tweetId),
	INDEX(profileId),

	PRIMARY KEY (tweetId,profileId),

	FOREIGN KEY(profileId) REFERENCES profile (profileId),
	FOREIGN KEY(tweetId) REFERENCES tweet (tweetId)

);


CREATE TABLE favTweet (
	profileId INT UNSIGNED NOT NULL,
	tweetId INT UNSIGNED NOT NULL,

	INDEX(tweetId),
	INDEX(profileId),

	PRIMARY KEY (tweetId,profileId),

	FOREIGN KEY(profileId) REFERENCES profile (profileId),
	FOREIGN KEY(tweetId) REFERENCES tweet (tweetId)

);



