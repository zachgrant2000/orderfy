<?php
if($_POST['submit'] == "Submit Order")
{
	$formOrderPrice = $_POST['orderPrice'];
	$formDeliveryBid = $_POST['deliveryBid'];
	$formDescribeOrder = $_POST['describeOrder'];
	$formLocation 		= $_POST['location'];
	$formPhoneNumber = $_POST['phoneNumber'];
	$errorMessage = "";

	if(empty($formOrderPrice)) {
		$errorMessage .= "<li>You forgot to enter a price!</li>";
	}
	if(empty($formDeliveryBid)) {
		$errorMessage .= "<li>You forgot to enter a delivery bid!</li>";
	}
	if(empty($formDescribeOrder)) {
		$errorMessage .= "<li>You forgot to describe your order!</li>";
	}
	if(empty($formLocation)) {
		$errorMessage .= "<li>You forgot to set your location!</li>";
	}
	if(empty($formPhoneNumber)) {
		$errorMessage .= "<li>You forgot to tell us your phone number!</li>";
	}
}
