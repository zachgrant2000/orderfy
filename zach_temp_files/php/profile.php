<?php
/**
 * mySQL Enabled User
 *
 * This is a mySQL enabled container for Profile Set Up and Authentication at a major social media site that generates
 * a ton of annoying bird sounds. It can easily be extended to include more fields and mating calls as necessary.
 *
 * @author Zach Grant <zgrant22@hotmail.com>
 * @see Profile
 * @Date: 10/28/14
 * @Time: 11:06 AM
 */

class Profile {
	/**
	 * profile id for the User's profile Id; this is the primary key
	 **/
	private $profileId;
	/**
	 * id for the User; this is the foreign key
	 **/
	private $userId;
	/**
	 * first name of the user
	 **/
	private $firstName;
	/**
	 * last name of the user
	 **/
	private $lastName;


	/**
	 * constructor for Profile
	 *
	 * @param mixed $newProfileId profile id (or null if new object)
	 * @param mixed $newUserId user id
	 * @param string $newFirstName first name
	 * @param string $newLastName last name
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newProfileId, $newUserId, $newFirstName, $newLastName) {
		try {
			$this->setProfileId($newProfileId);
			$this->setUserId($newUserId);
			$this->setFirstName($newFirstName);
			$this->setLastName($newLastName);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct User", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct User", 0, $range));
		}
	}


	/**
	 * gets the value of profile id
	 *
	 * @return mixed profile id (or null if new object)
	 **/
	public function getProfileId() {
		return($this->profileId);
	}


	/**
	 * sets the value of profile id
	 *
	 * @param mixed $newProfileId profile id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if user id isn't positive
	 **/
	public function setProfileId($newProfileId) {
		// zeroth, set allow the profile id to be null if a new object
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}

		// first, ensure the profile id is an integer
		if(filter_var($newProfileId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("profile id $newProfileId is not numeric"));
		}

		// second, convert the profile id to an integer and enforce it's positive
		$newProfileId = intval($newProfileId);
		if($newProfileId <= 0) {
			throw(new RangeException("profile id $newProfileId is not positive"));
		}

		// finally, take the profile id out of quarantine and assign it
		$this->profileId = $newProfileId;
	}


	/**
	 * gets the value of user id
	 *
	 * @return mixed user id
	 **/
	public function getUserId() {
		return($this->userId);
	}

	/**
	 * sets the value of user id
	 *
	 * @param int $newUserId user id
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if user id isn't positive
	 **/
	public function setUserId($newUserId) {
		// first, ensure the user id is an integer
		if(filter_var($newUserId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("user id $newUserId is not numeric"));
		}

		// second, convert the user id to an integer and enforce it's positive
		$newUserId = intval($newUserId);
		if($newUserId <= 0) {
			throw(new RangeException("user id $newUserId is not positive"));
		}

		// finally, take the user id out of quarantine and assign it
		$this->userId = $newUserId;
	}


	/**
	 * gets the value of first name
	 *
	 * @return string value of first name
	 **/
	public function getFirstName() {
		return($this->firstName);
	}

	/**
	 * sets the value of first name
	 *
	 * @param string $newFirstName of the first name
	 * @throws UnexpectedValueException if the input doesn't appear to be a string
	 * @throws RangeException if not a string of 32 or less characters
	 **/
	public function setFirstName($newFirstName) {
		// verify the first name is a string with 32 or less characters
		$newFirstName   = trim($newFirstName);

		if(filter_var($newFirstName, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("first name $newFirstName does not appear to be a string"));
		}

		if(strlen($newFirstName) > 32) {
			throw(new RangeException("first name $newFirstName has to be less than 32 characters long"));
		}

		// finally, take the first name out of quarantine
		$this->firstName = $newFirstName;
	}


	/**
	 * gets the value of last name
	 *
	 * @return string value of last name
	 **/
	public function getLastName() {
		return($this->lastName);
	}


	/**
	 * sets the value of last name
	 *
	 * @param string $newLastName of the last name
	 * @throws UnexpectedValueException if the input doesn't appear to be a string
	 * @throws RangeException if not a string of 32 or less characters
	 **/
	public function setLastName($newLastName) {
		// verify the last name is 32 or less characters
		$newLastName   = trim($newLastName);

		if(filter_var($newLastName, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("last name $newLastName is not a string"));
		}

		if(strlen($newLastName) > 32) {
			throw(new RangeException("last name $newLastName has to be less than 32 characters long"));
		}

		// finally, take the last name out of quarantine
		$this->lastName = $newLastName;
	}




	/**
	 * inserts this Profile to mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the profileId is null (i.e., don't insert a user that already exists)
		if($this->profileId !== null) {
			throw(new mysqli_sql_exception("not a new profile"));
		}

		// create query template
		$query     = "INSERT INTO profile (userId, firstName, lastName) VALUES(?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("iss", $this->userId, $this->firstName,
			$this->lastName);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// update the null profileId with what mySQL just gave us
		$this->profileId = $mysqli->insert_id;
	}



	/**
	 * deletes this Profile from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is not null (i.e., don't delete a user that hasn't been inserted)
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("Unable to delete a user that does not exist"));
		}

		// create query template
		$query     = "DELETE FROM profile WHERE profileId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->profileId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates this Profile in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is not null (i.e., don't update a user that hasn't been inserted)
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("Unable to update a user that does not exist"));
		}

		// create query template
		$query     = "UPDATE profile SET userId = ?, firstName = ?, lastName = ? WHERE profileId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("sssi", $this->userId, $this->firstName,
			$this->lastName,
			$this->profileId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}



	/**
	 * gets the Profile by profileId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $profileId profile ID to search for
	 * @return mixed Profile found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getProfileByProfileId(&$mysqli, $profileId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}


		// first trim, then validate, then sanitize the profileId int before searching.
		$profileId = trim($profileId);

		if (filter_var($profileId, FILTER_SANITIZE_NUMBER_INT) === false) {
			throw (new UnexpectedValueException ("profile id $profileId does not appear to be an integer"));
		}
		else {
			$profileId = filter_var($profileId, FILTER_SANITIZE_NUMBER_INT);
		}

		// create query template
		$query = "SELECT profileId, userId, firstName, lastName FROM profile WHERE $profileId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the profileId to the place holder in the template
		$wasClean = $statement->bind_param("i", $profileId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query *pounds fists*
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// since this is a unique field, this will only return 0 or 1 results. So...
		// 1) if there's a result, we can make it into a Profile object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a Profile
		if($row !== null) {
			try {
				$profile = new Profile($row["profileId"], $row["userId"], $row["firstName"], $row["lastName"]);
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Profile", 0, $exception));
			}

			// if we got here, the Profile is good - return it
			return ($profile);
		} else {
			// 404 User not found - return null instead
			return (null);
		}
	}



	/**
	 * gets any existing Profile by lastName
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $lastName last name to search for
	 * @return mixed Profile found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getProfileByLastName(&$mysqli, $lastName)
	{
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// first trim, then validate, then sanitize the lastName string before searching.
		$lastName = trim($lastName);

		if (filter_var($lastName, FILTER_SANITIZE_STRING) === false) {
			throw (new UnexpectedValueException ("last name of $lastName does not appear to be a string"));
		}
		else {
			$lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
		}

		// create query template
		$query = "SELECT profileId, userId, firstName, lastName FROM profile WHERE lastName = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the last name to the place holder in the template
		$wasClean = $statement->bind_param("s", $lastName);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query *pounds fists*
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// since this is not a unique field, this will return as many results as there are profiles with  same last name.
		// 1) if there's no result, we can just return null
		// 2) if there's a result, we can make it into Profile objects normally
		// fetch_assoc() returns row as associative arr until row is null
//		$arrayCounter = 0;
		$profileArray = array();
		// convert the associative array to a Profile and repeat for all last names equal to lastName.
		while(($row = $result->fetch_assoc()) !== null) {

			// convert the associative array to a Profile for all last names equal to lastName.
			try {
				$profile = new Profile($row["profileId"], $row["userId"], $row["firstName"], $row["lastName"]);
				$profileArray[] = $profile;

			} catch(Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new mysqli_sql_exception("Unable to convert row to Profile", 0, $exception));
			}

		}

		if(empty($profileArray)) {
			// 404 User not found - return null
			return (null);
		}
		else {
			return ($profileArray);
		}
	}





	/**
	 * @return string describing info in class Profile
	 */
	public function __toString() {
		return ("<p>" . $this->firstName . " " . $this->lastName . "'s profile." . "<br/>" . "Profile ID: " . $this->profileId .
			"." . "<br/>" . "User ID: " . $this->userId . "." . "</p>");
	}


	/**
	 * @param $searchedField
	 * @returns searched field, or null if the needed array key does not exist in database
	 * @throws E_USER_NOTICE trigger error if searched field does not exist as an array key.
	 */
	public function __get($searchedField) {
		echo "Getting '$searchedField'\n";
		if (array_key_exists($searchedField, $this->data)) {
			return $this->data[$searchedField];
		}

		//if else question
		else {
			throw (new UnexpectedValueException ("We searched for $searchedField and it does not seem to be an appropriate array key"));
			return null;
		}
		//$trace = debug_backtrace();
		//trigger_error("Undefined property via __get(): " . $searchedField . " in " . $trace[0]['file'] . " on line " . $trace[0]["line"],
		//	E_USER_NOTICE);


	}
}

?>