<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Integration;

use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\ITable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

abstract class DbTestCase extends TestCase {

	use TestCaseTrait {
		setUp as protected traitSetUp;
	}

	/**
	 * only instantiate pdo once for test clean-up/fixture load
	 * @var \PDO
	 */
	static private $pdo;

	/**
	 * only instantiate PHPUnit\DbUnit\Database\Connection once per test
	 * @var Connection
	 */
	private $conn;

	protected function setUp(): void {
		parent::setUp();
		$this->traitSetUp();
	}

	final public function getConnection(): Connection {
		if ($this->conn === NULL) {
			if (self::$pdo === NULL) {
				self::$pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
			}
			$this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
		}

		return $this->conn;
	}

	protected function getActualStateTable(string $tableName): ITable {
		return $this->getConnection()->createDataSet([$tableName])->getTable($tableName);
	}
}