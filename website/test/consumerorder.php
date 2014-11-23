<?php
/**
 * Order form for users
 * This will allow users to submit orders and their delivery bid price
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

class ConsumerOrder
{
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
	 * @param mixed  $newOrderId    the primary key for the SQL table
	 * @param string $newOrderPrice the price of the order
	 * @param string $newDeliveryBid
	 * @param string $newDescription
	 * @param string $newLocation
	 * @param string $newPhoneNumber
	 **/
	public function __construct($newOrderId, $newOrderPrice, $newDeliveryBid, $newDescription, $newLocation, $newPhoneNumber)
	{
		try {
			$this->orderPrice = $newOrderPrice;
			$this->deliveryBid = $newDeliveryBid;
			$this->description = $newDescription;
			$this->location = $newLocation;
			$this->phoneNumber = $newPhoneNumber;
		} catch(UnexpectedValueException $unexpectedValue) {
			throw(new UnexpectedValueException("Cannot construct ConsumerOrder object", 0, $unexpectedValue));
		} catch(RangeException $rangeException) {
			throw(new RangeException("Cannot construct ConsumerOrder object", 0, $rangeException));
		}
	}

	//todo set the call function

	public function setOrderId($newOrderId)
	{
		if(new $newOrderId === null) {
			$this->orderId = null;
			return;
		}


		if(filter_var($newOrderId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("orderId $newOrderId is not numeric"));
		}
		$newOrderId = intval($newOrderId);
		if($newOrderId <= 0) {
			throw(new RangeException("orderId $newOrderId is not positive"));
		}
		$this->orderId = $newOrderId;
	}

	public function setOrderPrice($newOrderPrice)
	{
		if(filter_var($newOrderPrice, FILTER_VALIDATE_FLOAT) === false) {
			throw(new UnexpectedValueException("Order price $newOrderPrice is not a float"));
		}

		$newOrderPrice = floatval($newOrderPrice);
		if($newOrderPrice <= 0) {
			throw(new RangeException("Order price $newOrderPrice is not positive"));
		}

		$this->orderPrice = $newOrderPrice;
	}

	public function setPrice($newDeliveryBid)
	{
		if(filter_var($newDeliveryBid, FILTER_VALIDATE_FLOAT) === false) {
			throw(new UnexpectedValueException("Delivery bid $newDeliveryBid is not a float"));
		}

		$newDeliveryBid = floatval($newDeliveryBid);
		if($newDeliveryBid <= 0) {
			throw(new RangeException("product id $newDeliveryBid is not positive"));
		}

		$this->deliveryBid = $newDeliveryBid;
	}

	public function setNewLocation($newLocation){
		$newLocation = trim($newLocation);
		$newLocation = filter_var($newLocation, FILTER_SANITIZE_STRING);
		$this->location = $newLocation;
	}

	public function setPhoneNumber($newPhoneNumber){
		$newPhoneNumber = trim($newPhoneNumber);
		$newPhoneNumber = filter_var($newPhoneNumber, FILTER_SANITIZE_STRING);
		$this->phoneNumber = $newPhoneNumber;
	}

	public function insert(&$mysqli)	{
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("Input is not a mysqli object"));
		}
		if($this->orderId !== null) {
			throw(new mysqli_sql_exception("Not a new event"));
		}
		$query = "INSERT INTO order(orderPrice, deliveryBid, description, location, phoneNumber) VALUES(?,?,?,?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$wasClean = $statement->bind_param("ddsss", $this->orderPrice, $this->deliveryBid, $this->description,
			$this->location, $this->phoneNumber);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw (new mysqli_sql_exception("Unable to execute mySQL insert statement"));
		}
		$this->orderId = $mysqli->insert_id;
	}

	public function delete($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}
		if($this->orderId === null) {
			throw(new mysqli_sql_exception("Unable to delete an event that does not exist"));
		}
		$query		="DELETE FROM order WHERE orderId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}
		$wasClean = $statement->bind_param("i", $this->orderId);
		if($wasClean === false){
			throw (new mysqli_sql_exception("Unable to bind parameters"));
		}
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	public function update($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}
		if($this->eventId === null){
			throw (new mysqli_sql_exception("Cannot update object that does not exist"));
		}
		// Convert date to strings
		$query		="UPDATE order SET orderId = ?, orderPrice = ?, deliveryBid = ?, description = ?, location = ?, phoneNumber = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		$wasClean = $statement->bind_param("isss", $this->orderId, $this->orderPrice, $this->deliveryBid, $this->description,
			$this->location, $this->phoneNumber);
		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		if($statement->execute() === false){
			throw(new mysqli_sql_exception("Unable to execute mySQL statement."));
		}
	}
}