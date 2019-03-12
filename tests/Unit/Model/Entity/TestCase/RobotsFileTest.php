<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Unit\Model\Entity\TestCase;

use Ajda2\WebsiteChecker\Model\Entity\TestCase\RobotsFile;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Nette\Http\Url;
use PHPUnit\Framework\TestCase;

class RobotsFileTest extends TestCase {

	/** @var RobotsFile */
	private $item;

	/** @var Url */
	private $url;

	public function setUp() {
		parent::setUp();

		$code = 'Code';
		$name = 'Name';

		$this->item = new RobotsFile($code, $name);
		$this->url = new Url('https://www.surface.cz');
	}

	public function testConstructor(): void {
		$code = 'Code';
		$name = 'Name';

		$this->item = new RobotsFile($code, $name);

		$this->assertInstanceOf(RobotsFile::class, $this->item);
		$this->assertSame($code, $this->item->getCode());
		$this->assertSame($name, $this->item->getName());
	}

	/**
	 * @throws \Exception
	 */
	public function testRunSuccess(): void {
		$document = new \DOMDocument();
		$url = new Url('https://www.numismatika-zlin.cz/');

		$result = $this->item->run($url, $document);

		$this->assertInstanceOf(TestResultInterface::class, $result);
		$this->assertTrue($result->isSuccess());
		$this->assertFalse($result->isFail());
	}

	/**
	 * @throws \Exception
	 */
	public function testRobotsDisabled(): void{
		$document = new \DOMDocument();
		$url = new Url('http://www.autodilyhorak.cz/');

		$result = $this->item->run($url, $document);

		$this->assertInstanceOf(TestResultInterface::class, $result);
		$this->assertFalse($result->isSuccess());
		$this->assertTrue($result->isFail());
	}

	/**
	 * @throws \Exception
	 */
	public function testFileNotExists(): void {
		$document = new \DOMDocument();
		$url = new Url('http://tester.surface.cz');

		$result = $this->item->run($url, $document);

		$this->assertInstanceOf(TestResultInterface::class, $result);
		$this->assertFalse($result->isSuccess());
		$this->assertTrue($result->isFail());
	}
}
