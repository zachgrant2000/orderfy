<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 11/13/14
 * Time: 10:26 AM
 */



// open weather map's API key
// ironically, they make us sign up but never verify it...
$apiKey = "702996d67fc1a20224e4507045a67124";

// base URL is the basis for *ALL* API calls
$baseUrl = "http://api.openweathermap.org/data/2.5/weather";

// input to the API: direct search
//filter input is to filter stuff from a SUPER GLOBAL variable as opposed to declared variables within the class

if(empty($_GET["city"]) === false && ($city = filter_input(INPUT_GET, "city", FILTER_SANITIZE_ENCODED)) !== false) {
	$query = "?q=$city";
}


// input to the API: GPS
if(isset($_GET["useGps"]) && $_GET["useGps"] === "on"
	&& empty($_GET["latitude"]) === false && empty($_GET["longitude"]) === false) {
	// convert the cooridinates & build the query
	$latitude = filter_input(INPUT_GET, "latitude", FILTER_VALIDATE_FLOAT);
	$longitude = filter_input(INPUT_GET, "longitude", FILTER_VALIDATE_FLOAT);

	//fixme there's no range verification on the coordinates to see if the values are valid
	if($latitude === false || $longitude === false) {
		throw(new RuntimeException("invalid latitude or longitude"));
	}

	$query = "?lat=$latitude&lon=$longitude";
}


// defeat malicious & incompetent users
if(empty($query) === true) {
	throw(new RuntimeException("Invalid city detected"));
	exit;
}

// final URL to get data from
$urlGlue = "$baseUrl$query";

// fetch the raw JSON data
// as assignment you can try to re-write this php code file_get_contents to see the work php is doing behind you
$jsonData = @file_get_contents($urlGlue);
if($jsonData === false) {
	throw(new RuntimeException("Unable to download weather data"));
}

// convert the JSON data into a big associative array
$weatherData = json_decode($jsonData, true);


// now do "useful" stuff with the data...
// ...as a test, var dump it!
/* echo "<pre>";
var_dump($weatherData);
echo "</pre>"; */

// echo select fields from the array (cut superflous data)
if($weatherData["cod"] == 200)
{
	// get the image icon URL
	$imageIcon = "http://openweathermap.org/img/w/" . $weatherData["weather"][0]["icon"] . ".png";

	// as a preprocessing step, format the date
	$dateTime = new DateTime();
	$dateTime->setTimestamp($weatherData["dt"]);
	$formattedDate = $dateTime->format("Y-m-d H:i:s");

	// convert the temperature
	$kevlin  = floatval($weatherData["main"]["temp"]);
	$celsius = $kevlin - 273.15;

	echo "<p><img src=\"$imageIcon\" style=\"float: left;\" alt=\"" . $weatherData["weather"][0]["description"] ."\" />"
		. $weatherData["name"]             . ", "
		. $weatherData["sys"]["country"]   . "<br />"
		. $celsius                         . " &deg;C<br />"
		. $weatherData["main"]["pressure"] . " hPa<br />"
		. $formattedDate                   . "</p>";
}
else
{
	echo "<p>Unable to get weather data: " . $weatherData["message"] . "</p>";
}
?>


//var_dump($weatherData);

