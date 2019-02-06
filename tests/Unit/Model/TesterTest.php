<?php declare(strict_types = 1);


namespace Ajda2\WebsiteChecker\Tests\Unit\Model;

use Ajda2\WebsiteChecker\Model\Entity\TestInterface;
use Ajda2\WebsiteChecker\Model\Entity\Website;
use Ajda2\WebsiteChecker\Model\Tester;
use Nette\Http\Url;
use Nette\Utils\ArrayHash;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TesterTest extends TestCase {

	/** @var Tester */
	private $tester;

	public function setUp() {
		parent::setUp();

		$this->tester = new Tester();
	}

	public function test__construct() {
		$this->tester = new Tester();

		$this->assertInstanceOf(Tester::class, $this->tester);
		$this->assertInstanceOf(ArrayHash::class, $this->tester->getTests());
	}

	public function testRunTests() {
		$code = 'test1';
		$this->tester->addTest($this->createMockTest($code));
		$website = new Website(0, new Url('https://www.surface.cz/'));
		$requestTimeout = 4000.0;

		$this->assertTrue($this->tester->runTests($website, $requestTimeout));
	}

	public function testOnTestFail() {
		$this->markTestSkipped();
	}

	public function testOnWebResponse() {
		$this->markTestSkipped();
	}

	public function testOnWebResponseFail() {
		$this->markTestSkipped();
	}

	public function testAddTest() {
		$code = 'test1';
		$this->tester->addTest($this->createMockTest($code));

		$this->assertInstanceOf(ArrayHash::class, $this->tester->getTests());
		$this->assertSame(1, $this->tester->getTests()->count());
	}

	public function testOnTestSuccess() {
		$this->markTestSkipped();
	}

	public function testRemoveTest() {
		$code = 'test1';
		$this->tester->addTest($this->createMockTest($code));
		$this->tester->removeTest($code);

		$this->assertInstanceOf(ArrayHash::class, $this->tester->getTests());
		$this->assertSame(0, $this->tester->getTests()->count());
	}

	/**
	 * @param string $code
	 * @return MockObject|TestInterface
	 */
	private function createMockTest(string $code): MockObject {
		$mock = $this->createMock(TestInterface::class);
		$mock->method('getCode')->willReturn($code);

		return $mock;
	}
}
