<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 11/5/14
 * Time: 8:50 AM
 * This is NOTES code or proof of concept about how to create and use date objects.
 */



// simple case: getting a correctly formatted date
$goodDate = DateTime::createFromFormat("Y-m-d H:i:s", "2012-07-13 15:18:42");

// tougher case: getting from predictable user input
$toughDate = DateTime::createFromFormat("m/d/y", "7/3/92");


// good news is that no matter format that comes in, if you can limit it on front end using bootstrap, you can convert
// to what you need on the backend.

echo $toughDate->format("Y-m-d H:i:s");


// DESIGN PROBLEM IN PHP...SERIOUSLY@@@   WTF!?
$thisIsValid = DateTime::createFromFormat("Y-m-d H:i:s", "2014-39-47 57:94:82");

//JAVA would throw a DateFormatException here; PHP carries the excess over.



// toughest case: getting date from unpredictable user input
// panic();  nothing we can do. have to normalize it somewhere. solution is the front end, way we design user interface.




?>