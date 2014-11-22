<?php
/**
 * Order form for users
 * This will allow users to submit orders and their delivery bid price
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

class ConsumerOrder {
	/**
	 * This will process the primary key for the mySQL database
	 */
	private $orderId;
	/**
	 * This will store the order price
	 */
	private $orderPrice;
	/**
	 * This will store the delivery bid
	 */
	private $deliveryBid;
	/**
	 * This will store the order description
	 */
	private $description;
	/**
	 * This will store the location of the user
	 */
	private $location;
	/**
	 * This will store their phone number inforamtion
	 */
	private $phoneNumber;


	/**
	 * Creates the Event object
	 *
	 * @param mixed $newOrderId the primary key for the SQL table
	 * @param string $newOrderPrice the price of the order
	 * @param string $newDeliveryBid
	 * @param string $newDescription
	 * @param string $newLocation
	 * @param string $newPhoneNumber
	 **/
	public function __construct ($newOrderId,$newOrderPrice, $newDeliveryBid, $newDescription, $newLocation, $newPhoneNumber){
		try {
			$this->orderPrice = $newOrderPrice;
			$this->deliveryBid = $newDeliveryBid;
			$this->description = $newDescription;
			$this->location = $newLocation;
			$this->phoneNumber = $newPhoneNumber;
		} catch (UnexpectedValueException $unexpectedValue){
			throw(new UnexpectedValueException("Cannot construct ConsumerOrder object", 0, $unexpectedValue));
		} catch (RangeException $rangeException){
			throw(new RangeException("Cannot construct ConsumerOrder object", 0, $rangeException));
		}
	}

	//todo set the call function

	public function setOrderId($newOrderId){
		if(new $newOrderId === null){
			$this->orderId = null;
			return;
		}


		if(filter_var($newOrderId, FILTER_VALIDATE_INT) === false){
			throw(new UnexpectedValueException("orderId $newOrderId is not numeric"));
		}
		$newOrderId = intval($newOrderId);
		if($newOrderId <= 0) {
			throw(new RangeException("orderId $newOrderId is not positive"));
		}
		$this->orderId = $newOrderId;
	}

	public function setEventTitle($newEventTitle){
		$newEventTitle = trim($newEventTitle);
		$newEventTitle = filter_var($newEventTitle, FILTER_SANITIZE_STRING);
		$this->eventTitle = $newEventTitle;
	}
}