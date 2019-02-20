<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Integration\Model;

use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Ajda2\WebsiteChecker\Model\WebsiteTestResultRepository;
use Ajda2\WebsiteChecker\Tests\Integration\Bootstrap;
use Ajda2\WebsiteChecker\Tests\Integration\DbTestCase;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;
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
		$websiteId = 1;
		$result = $this->testResultRepository->removeWebsiteResults($websiteId);

		$this->assertTrue($result);
		$this->assertSame(0, $this->testResultRepository->getResults([$websiteId])->count());
	}

	public function testGetResults(): void {
		$websiteIds = [
			1,
			2
		];

		$result = $this->testResultRepository->getResults($websiteIds);

		$this->assertInstanceOf(ArrayHash::class, $result);
		$this->assertSame(2, $result->count());
		$this->assertInstanceOf(ArrayHash::class, $result->offsetGet(1));
		$this->assertInstanceOf(TestResultInterface::class, $result->offsetGet(1)->offsetGet('test1'));
	}

	/**
	 * @throws \Exception
	 */
	public function testStoreResult(): void {
		$testCode = 'new test';
		$runAt = new DateTime();
		$isSuccess = TRUE;
		$value = 'test value';
		$description = 'test description';
		$websiteId = 2;

		$item = new TestResult($testCode, $runAt, $isSuccess, $value, $description);

		$result = $this->testResultRepository->storeResult($item, $websiteId);

		$this->assertInstanceOf(TestResultInterface::class, $result);
		$this->assertSame($testCode, $result->getTestCode());
		$this->assertSame($runAt->getTimestamp(), $result->getRunAt()->getTimestamp());
		$this->assertSame($isSuccess, $result->isSuccess());
		$this->assertSame($value, $result->getValue());
		$this->assertEquals($description, $result->getDescription());
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
