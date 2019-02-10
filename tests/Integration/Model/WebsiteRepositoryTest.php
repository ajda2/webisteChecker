<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Integration\Model;

use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentify;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentifyInterface;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteInterface;
use Ajda2\WebsiteChecker\Model\WebsiteRepository;
use Ajda2\WebsiteChecker\Tests\Integration\Bootstrap;
use Ajda2\WebsiteChecker\Tests\Integration\DbTestCase;
use Nette\Http\Url;
use Nette\Utils\DateTime;
use PHPUnit\DbUnit\DataSet\IDataSet;

class WebsiteRepositoryTest extends DbTestCase {

	/** @var WebsiteRepository */
	private $websiteRepository;

	/** @var WebsiteIdentifyInterface */
	private $item;

	protected function setUp(): void {
		parent::setUp();

		$container = Bootstrap::getContainer();

		$this->websiteRepository = $container->getByType(WebsiteRepository::class);

		$this->item = new WebsiteIdentify(1, new Url("https://www.surface.cz/"));
	}

	public function testGetWebsiteForTest(): void {
		$result = $this->websiteRepository->getWebsiteForTest();

		$this->assertInstanceOf(WebsiteInterface::class, $result);
		$this->assertSame(2, $result->getId());
	}

	/**
	 * @throws \Exception
	 */
	public function testSave(): void {
		$responseTime = 666.0;
		$responseCode = 123;
		$lastCheckAt = new DateTime();

		$this->item->setResponseTime($responseTime);
		$this->item->setResponseCode($responseCode);
		$this->item->setLastCheckAt($lastCheckAt);

		$this->websiteRepository->save($this->item);

		$this->assertSame($responseTime, $this->item->getResponseTime());
		$this->assertSame($responseCode, $this->item->getResponseCode());
		$this->assertSame($lastCheckAt->getTimestamp(), $this->item->getLastCheckAt()->getTimestamp());
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
