<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Integration\Model;

use Ajda2\WebsiteChecker\Model\WebsiteTestResultRepository;
use Ajda2\WebsiteChecker\Tests\Integration\Bootstrap;
use Ajda2\WebsiteChecker\Tests\Integration\DbTestCase;
use PHPUnit\DbUnit\DataSet\IDataSet;

class WebsiteTestResultRepositoryTest extends DbTestCase {

	/** @var WebsiteTestResultRepository */
	private $testResultRepository;

	protected function setUp(): void {
		parent::setUp();

		$container = Bootstrap::getContainer();

		$this->testResultRepository = $container->getByType(WebsiteTestResultRepository::class);
	}

	public function testRemoveWebsiteTests(): void {
		$this->markTestSkipped();
	}

	public function testGetResults(): void {
		$this->markTestSkipped();
	}

	/**
	 * Returns the test dataset.
	 *
	 * @return IDataSet
	 */
	protected function getDataSet() {
		return $this->createFlatXMLDataSet(__DIR__ . '/WebsiteTestResultRepositoryTest.xml');
	}
}
