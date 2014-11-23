<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 10/30/14
 * Time: 11:03 AM
 */

//first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");

//then require the class under scrutiny
require_once("../php/profile.php");


//the ProfileTest is a container for all tests
class ProfileTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli 		= null;
	// variable to hold the test database row
	private $profile   	= null;

	// a few "global" variables for creating test data (not the primary key?!!?)
	private $USERID      = 1;
	private $FIRSTNAME   = "zwg";
	private $LASTNAME    = "onymous";

	// setUp() is a method that is run before each test
	// here, we use it to connect to mySQL to build
	public function setUp() {
		// connect to mySQL
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "store_dylan", "deepdive", "store_dylan");
	}


	// tearDown() is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown () {
	// delete the user if we can
		if ($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
		}
	}

	// test creating a new Profile and inserting it to mySQL
	public function testInsertNewProfile () {
		// first, verify mySQL connected OK
		$this->assertNotNull ($this->mysqli);

		// second, create a profile to post to mySQL
		$this->profile = new Profile (null, $this->USERID, $this->FIRSTNAME, $this->LASTNAME);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertIdentical($this->profile->getUserId(),				$this->USERID);
		$this->assertIdentical($this->profile->getFirstName(),			$this->FIRSTNAME);
		$this->assertIdentical($this->profile->getLastName(),				$this->LASTNAME);

	}

	// test updating a Profile in mySQL
	public function testUpdateProfile () {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a profile to post to mySQL
		$this->profile = new Profile (null, $this->USERID, $this->FIRSTNAME, $this->LASTNAME);

		// third insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, update the user and post the changes to mySQL
		$newLastName = "opolis";
		$this->profile->setLastName($newLastName);
		$this->profile->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertIdentical($this->profile->getUserId(),				$this->USERID);
		$this->assertIdentical($this->profile->getFirstName(),			$this->FIRSTNAME);
		$this->assertIdentical($this->profile->getLastName(),				$this->LASTNAME);
	}


	// test deleting a Profile
	public function testDeleteUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a profile to post to mySQL
		$this->profile = new Profile (null, $this->USERID, $this->FIRSTNAME, $this->LASTNAME);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, verify the profile was inserted
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId());

		// fifth, delete the profile
		$this->profile->delete($this->mysqli);
		$this->profile = null;

		// finally, try to get the profile and assert we didn't get a thing and that it is now null
		$hopefulProfile = Profile::getProfileByLastName($this->mysqli, $this->LASTNAME);
		$this->assertNull($hopefulProfile);
	}




	// test grabbing a Profile from mySQL
	public function testGetProfileByLastName() {
		// first, verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second, create a profile to post to mySQL
		$this->profile = new Profile(null, $this->USERID, $this->FIRSTNAME, $this->LASTNAME);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, get the profile using the static method
		$staticProfile = Profile::getProfileByLastName($this->mysqli, $this->LASTNAME);

		// finally, compare fields
		$this->assertNotNull($this->$staticProfile->getProfileId());
		$this->assertTrue($this->$staticProfile->getProfileId() > 0);
		$this->assertIdentical($this->$staticProfile->getUserId(),					$this->profile->getProfileId());
		$this->assertIdentical($this->$staticProfile->getFirstName(),				$this->FIRSTNAME);
		$this->assertIdentical($this->$staticProfile->getLastName(),				$this->LASTNAME);

	}
}