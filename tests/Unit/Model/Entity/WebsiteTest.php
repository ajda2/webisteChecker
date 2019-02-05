<?php declare(strict_types = 1);


namespace Ajda2\WebsiteChecker\Tests\Unit\Model\Entity;

use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Ajda2\WebsiteChecker\Model\Entity\Website;
use Nette\Http\Url;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;
use PHPUnit\Framework\TestCase;

class WebsiteTest extends TestCase {

	/** @var Website */
	private $item;

	public function setUp() {
		parent::setUp();

		$id = 1;
		$url = new Url("https://www.surface.cz/");
		$lastCheckAt = new DateTime("2019-06-02 0:23:00");
		$responseCode = 200;
		$responseTime = 3500;

		$this->item = new Website($id, $url, $lastCheckAt, $responseCode, $responseTime);
	}

	public function test__construct(): void {
		$id = 1;
		$url = new Url("https://www.surface.cz/");
		$lastCheckAt = new DateTime("2019-06-02 00:23:00");
		$responseCode = 200;
		$responseTime = 3500;

		$this->item = new Website($id, $url, $lastCheckAt, $responseCode, $responseTime);

		$this->assertInstanceOf(Website::class, $this->item);
		$this->assertSame($id, $this->item->getId());
		$this->assertSame($url, $this->item->getUrl());
		$this->assertSame($lastCheckAt->getTimestamp(), $this->item->getLastCheckAt()->getTimestamp());
		$this->assertSame($responseCode, $this->item->getResponseCode());
		$this->assertSame($responseTime, $this->item->getResponseTime());
		$this->assertInstanceOf(ArrayHash::class, $this->item->getTestResults());
		$this->assertInstanceOf(ArrayHash::class, $this->item->getFailingTestResults());
		$this->assertSame(0, $this->item->getFailingTestResults()->count());
		$this->assertSame(0, $this->item->getTestResults()->count());
	}

	public function testSetters(): void {
		$lastCheckAt = new DateTime("2018-05-01 18:16:54");
		$responseCode = 404;
		$responseTime = 10;

		$this->item->setLastCheckAt($lastCheckAt);
		$this->item->setResponseCode($responseCode);
		$this->item->setResponseTime($responseTime);

		$this->assertSame($lastCheckAt->getTimestamp(), $this->item->getLastCheckAt()->getTimestamp());
		$this->assertSame($responseCode, $this->item->getResponseCode());
		$this->assertSame($responseTime, $this->item->getResponseTime());
	}

	public function testClearTestResults(): void {
		$testResult = new TestResult(TRUE);
		$code = 'code1';
		$this->item->addTestResult($code, $testResult);

		$testResult = new TestResult(FALSE);
		$code = 'code2';
		$this->item->addTestResult($code, $testResult);

		$this->item->clearTestResults();

		$this->assertInstanceOf(ArrayHash::class, $this->item->getTestResults());
		$this->assertInstanceOf(ArrayHash::class, $this->item->getFailingTestResults());
		$this->assertSame(0, $this->item->getTestResults()->count());
		$this->assertSame(0, $this->item->getFailingTestResults()->count());
		$this->assertFalse($this->item->hasFailingTest());
	}

	public function testAddTestResult(): void {
		$testResult = new TestResult(TRUE);
		$code = 'code1';
		$this->item->addTestResult($code, $testResult);

		$this->assertInstanceOf(ArrayHash::class, $this->item->getTestResults());
		$this->assertSame(1, $this->item->getTestResults()->count());
		$this->assertFalse($this->item->hasFailingTest());
		$this->assertInstanceOf(ArrayHash::class, $this->item->getFailingTestResults());
		$this->assertSame(0, $this->item->getFailingTestResults()->count());

		$testResult = new TestResult(FALSE);
		$code = 'code2';
		$this->item->addTestResult($code, $testResult);

		$this->assertInstanceOf(ArrayHash::class, $this->item->getTestResults());
		$this->assertSame(2, $this->item->getTestResults()->count());
		$this->assertTrue($this->item->hasFailingTest());
		$this->assertInstanceOf(ArrayHash::class, $this->item->getFailingTestResults());
		$this->assertSame(1, $this->item->getFailingTestResults()->count());
	}

	public function testResetTests(): void {
		$testResult = new TestResult(FALSE);
		$code = 'code1';
		$this->item->addTestResult($code, $testResult);

		$this->item->resetTests();

		$this->assertNull($this->item->getResponseCode());
		$this->assertNull($this->item->getResponseTime());
		$this->assertFalse($this->item->hasFailingTest());
		$this->assertInstanceOf(ArrayHash::class, $this->item->getTestResults());
		$this->assertInstanceOf(ArrayHash::class, $this->item->getFailingTestResults());
		$this->assertSame(0, $this->item->getTestResults()->count());
		$this->assertSame(0, $this->item->getFailingTestResults()->count());
	}
}
