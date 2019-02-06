<?php declare(strict_types = 1);


namespace Ajda2\WebsiteChecker\Tests\Unit\Model;

use Ajda2\WebsiteChecker\Model\Entity\TestInterface;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Ajda2\WebsiteChecker\Model\Entity\Website;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteInterface;
use Ajda2\WebsiteChecker\Model\Tester;
use Nette\Http\Url;
use Nette\Utils\ArrayHash;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class TesterTest extends TestCase {

	/** @var Tester */
	private $tester;

	/** @var float */
	private $requestTimeout = 0.0001;

	/** @var bool */
	private $eventFired;

	public function setUp() {
		parent::setUp();

		$this->tester = new Tester();
		$this->eventFired = FALSE;
	}

	public function test__construct() {
		$this->tester = new Tester();

		$this->assertInstanceOf(Tester::class, $this->tester);
		$this->assertInstanceOf(ArrayHash::class, $this->tester->getTests());
	}

	public function testRunTests() {
		$code = 'test1';
		$this->tester->addTest($this->createMockTest($code, TRUE));
		$website = new Website(0, new Url('https://www.surface.cz/'));

		$this->assertTrue($this->tester->runTests($website, $this->requestTimeout));
	}

	public function testOnTestFail() {
		$code = 'test1';
		$website = new Website(0, new Url('https://www.zonaholesov.cz/admin/'));
		$this->tester->addTest($this->createMockTest($code, FALSE));

		$this->tester->onTestFail[] = function (
			Tester $tester,
			WebsiteInterface $website,
			TestInterface $test,
			TestResultInterface $testResult
		): void {
			$this->expectNotToPerformAssertions();
			$this->eventFired = TRUE;

			return;
		};

		$this->tester->runTests($website, $this->requestTimeout);

		if (!$this->eventFired) {
			$this->fail("Event 'onTestFail' was not triggered");
		}
	}

	public function testOnTestSuccess() {
		$code = 'test1';
		$website = new Website(0, new Url('https://www.surface.cz/'));
		$this->tester->addTest($this->createMockTest($code, TRUE));

		$this->tester->onTestSuccess[] = function (
			Tester $tester,
			WebsiteInterface $website,
			TestInterface $test,
			TestResultInterface $testResult
		): void {
			$this->expectNotToPerformAssertions();
			$this->eventFired = TRUE;

			return;
		};

		$this->tester->runTests($website, $this->requestTimeout);

		if (!$this->eventFired) {
			$this->fail("Event 'onTestSuccess' was not triggered");
		}
	}

	public function testOnWebResponse() {
		$code = 'test1';
		$website = new Website(0, new Url('https://www.surface.cz/'));
		$this->tester->addTest($this->createMockTest($code, TRUE));

		$this->tester->onWebResponse[] = function (
			Tester $tester,
			WebsiteInterface $website,
			ResponseInterface $response,
			float $responseTime
		): void {
			$this->expectNotToPerformAssertions();
			$this->eventFired = TRUE;

			return;
		};

		$this->tester->runTests($website, $this->requestTimeout);

		if (!$this->eventFired) {
			$this->fail("Event 'onWebResponse' was not triggered");
		}
	}

	public function testOnWebResponseFail() {
		$code = 'test1';
		$website = new Website(0, new Url('https://www.notexists.cze/'));
		$this->tester->addTest($this->createMockTest($code, TRUE));

		$this->tester->onWebResponseFail[] = function (
			Tester $tester,
			WebsiteInterface $website
		): void {
			$this->expectNotToPerformAssertions();
			$this->eventFired = TRUE;

			return;
		};

		$this->tester->runTests($website, 10);

		if (!$this->eventFired) {
			$this->fail("Event 'onWebResponseFail' was not triggered");
		}
	}

	public function testAddTest() {
		$code = 'test1';
		$this->tester->addTest($this->createMockTest($code, TRUE));

		$this->assertInstanceOf(ArrayHash::class, $this->tester->getTests());
		$this->assertSame(1, $this->tester->getTests()->count());
	}


	public function testRemoveTest() {
		$code = 'test1';
		$this->tester->addTest($this->createMockTest($code, TRUE));
		$this->tester->removeTest($code);

		$this->assertInstanceOf(ArrayHash::class, $this->tester->getTests());
		$this->assertSame(0, $this->tester->getTests()->count());
	}

	/**
	 * @param string $code
	 * @param bool   $testSuccess
	 * @return MockObject|TestInterface
	 */
	private function createMockTest(string $code, bool $testSuccess): MockObject {
		$mock = $this->createMock(TestInterface::class);
		$mock->method('getCode')->willReturn($code);
		$mockResult = $this->createMock(TestResultInterface::class);

		if ($testSuccess) {
			$mockResult->method('isSuccess')->willReturn(TRUE);
			$mockResult->method('isFail')->willReturn(FALSE);
		} else {
			$mockResult->method('isSuccess')->willReturn(FALSE);
			$mockResult->method('isFail')->willReturn(TRUE);
		}

		$mock->method('run')->willReturn($mockResult);


		return $mock;
	}
}
