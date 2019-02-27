<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Integration\Model;

use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentify;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentifyInterface;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteInterface;
use Ajda2\WebsiteChecker\Model\WebsiteRepository;
use Ajda2\WebsiteChecker\Tests\Integration\Bootstrap;
use Ajda2\WebsiteChecker\Tests\Integration\DbTestCase;
use Nette\Http\Url;
use Nette\InvalidStateException;
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
	 * @throws \Ajda2\WebsiteChecker\Model\PersistException
	 */
	public function testSaveInsert(): void {
		$nextId = 3;
		$responseTime = 54.45;
		$responseCode = 85;
		$lastCheckAt = new DateTime();
		$url = new Url('https://www.tichy-vyvojar.cz/');

		$website = new WebsiteIdentify(0, $url, $lastCheckAt, $responseCode, $responseTime);

		$result = $this->websiteRepository->save($website);

		$this->assertInstanceOf(WebsiteIdentifyInterface::class, $result);
		$this->assertSame($nextId, $result->getId());
		$this->assertSame($responseTime, $result->getResponseTime());
		$this->assertSame($responseCode, $result->getResponseCode());
		$this->assertSame($lastCheckAt->getTimestamp(), $result->getLastCheckAt()->getTimestamp());
		$this->assertSame((string)$url, (string)$result->getUrl());
	}

	public function testDelete(): void {
		$id = 1;

		$result = $this->websiteRepository->delete($id);

		$this->assertTrue($result);
		try {
			$this->websiteRepository->getById($id);
			$this->fail("Website with ID: {$id} was not deleted");
		} catch (InvalidStateException $e) {
		}
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
