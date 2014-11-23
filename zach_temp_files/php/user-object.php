<?php
//phpinfo();
//in order to create the object, we need to first require the class file
require_once("user.php");

//NOTES ON HOW TO DO THE PASSWORD FOR USER CLASS and TABLE for future use
//how to store a password!  first, generate teh salt and authentication tokens

$authToken 				= bin2hex(openssl_random_pseudo_bytes(16));
$salt 					= bin2hex(openssl_random_pseudo_bytes(32));

//second, hash the cleartext password using PBKDF2
$clearTextPassword 	= "geckoMyEcho";
$pbkdf2Hash 			= hash_pbkdf2("sha512", $clearTextPassword, $salt, 2048, 128);

// now that PHP knows what to build, we use the new keyword to create an object
// the new keyword automatically runs the __construct method
$user = new User(null, "z@xy.com", $pbkdf2Hash, $salt, $authToken);





mysqli_report(MYSQLI_REPORT_STRICT);

////OK, now we can *try* connecting to mySQL -- get it? -- it's a pun
try {
//	// parameters: hostname, username, password, database
	$mysqli = new mysqli ("localhost", "store_zach", "deepdive", "store_zach");
} catch (mysqli_sql_exception $sqlException) {
	echo "Unable to connect to mySQL: " . $sqlException->getMessage();
}

var_dump($user);
?>

