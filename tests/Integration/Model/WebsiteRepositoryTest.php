<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Integration\Model;

use Ajda2\WebsiteChecker\Model\Entity\WebsiteInterface;
use Ajda2\WebsiteChecker\Model\WebsiteRepository;
use Ajda2\WebsiteChecker\Tests\Integration\Bootstrap;
use Ajda2\WebsiteChecker\Tests\Integration\DbTestCase;
use PHPUnit\DbUnit\DataSet\IDataSet;

class WebsiteRepositoryTest extends DbTestCase {

	/** @var WebsiteRepository */
	private $websiteRepository;

	protected function setUp(): void {
		parent::setUp();

		$container = Bootstrap::getContainer();

		$this->websiteRepository = $container->getByType(WebsiteRepository::class);
	}

	public function testGetWebsiteForTest(): void {
		$result = $this->websiteRepository->getWebsiteForTest();

		$this->assertInstanceOf(WebsiteInterface::class, $result);
	}

	/**
	 * Returns the test dataset.
	 *
	 * @return IDataSet
	 */
	protected function getDataSet() {
		return $this->createFlatXMLDataSet(__DIR__ . '/WebsiteRepositoryTest.xml');
	}
}
