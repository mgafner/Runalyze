<?php

namespace Runalyze\Model\Route;

/**
 * Generated by hand
 */
class DeleterTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \PDO
	 */
	protected $PDO;

	protected function setUp() {
		$this->PDO = \DB::getInstance();
	}

	protected function tearDown() {
		$this->PDO->exec('TRUNCATE TABLE `'.PREFIX.'route`');
	}

	/**
	 * @param array $data
	 * @return int
	 */
	protected function insert(array $data) {
		$Inserter = new Inserter($this->PDO, new Object($data));
		$Inserter->setAccountID(0);
		$Inserter->insert();

		return $Inserter->insertedID();
	}

	/**
	 * @param int $id
	 */
	protected function delete($id) {
		$Deleter = new Deleter($this->PDO, new Object($this->fetch($id)));
		$Deleter->setAccountID(0);
		$Deleter->delete();
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	protected function fetch($id) {
		return $this->PDO->query('SELECT * FROM `'.PREFIX.'route` WHERE `id`="'.$id.'" AND `accountid`=0')->fetch();
	}

	/**
	 * @expectedException \PHPUnit_Framework_Error
	 */
	public function testWrongObject() {
		new Deleter($this->PDO, new \Runalyze\Model\Trackdata\Object);
	}

	public function testSimpleDeletion() {
		$idToDelete = $this->insert(array(
			Object::NAME => 'Route to go away'
		));
		$idToKeep = $this->insert(array(
			Object::NAME => 'Route to stay'
		));
		$this->delete($idToDelete);

		$this->assertEquals(false, $this->fetch($idToDelete));
		$this->assertNotEquals(false, $this->fetch($idToKeep));
	}

}
