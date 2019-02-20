<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Unit\Model\Entity\TestCase;

use Ajda2\WebsiteChecker\Model\Entity\TestCase\RobotsEnable;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use PHPUnit\Framework\TestCase;

class RobotsEnableTest extends TestCase {

	/** @var RobotsEnable */
	private $item;

	public function setUp() {
		parent::setUp();

		$code = 'Code';
		$name = 'Name';

		$this->item = new RobotsEnable($code, $name);
	}

	public function testConstructor(): void {
		$code = 'Code';
		$name = 'Name';

		$this->item = new RobotsEnable($code, $name);

		$this->assertInstanceOf(RobotsEnable::class, $this->item);
		$this->assertSame($code, $this->item->getCode());
		$this->assertSame($name, $this->item->getName());
	}

	public function testRunSuccess(): void {
		$contents = [
			'all',
			'index',
			'index,follow',
			'follow,index',
			'index, follow',
			'follow, index',
		];
		$format = '<!DOCTYPE html><html lang="cs"><head><meta name="robots" content="%s"></head><body></body></html>';

		foreach ($contents as $content) {
			$source = \sprintf($format, $content);
			$document = new \DOMDocument();
			$document->loadHTML($source);

			$result = $this->item->run($document);

			$this->assertInstanceOf(TestResultInterface::class, $result);
			$this->assertSame(\str_replace(" ", "", $content), $result->getValue());
			$this->assertTrue($result->isSuccess());
			$this->assertFalse($result->isFail());
		}
	}

	public function testRunFail(): void {
		$contents = [
			'noindex',
			'noindex,nofollow',
			'nofollow,noindex',
			'noindex, nofollow',
			'nofollow, noindex',
		];
		$format = '<!DOCTYPE html><html lang="cs"><head><meta name="robots" content="%s"></head><body></body></html>';

		foreach ($contents as $content) {
			$source = \sprintf($format, $content);
			$document = new \DOMDocument();
			$document->loadHTML($source);

			$result = $this->item->run($document);

			$this->assertInstanceOf(TestResultInterface::class, $result);
			$this->assertSame(\str_replace(" ", "", $content), $result->getValue());
			$this->assertFalse($result->isSuccess());
			$this->assertTrue($result->isFail());
		}
	}

	public function testRunNoTag(): void {
		$source = '<!DOCTYPE html><html lang="cs"><head></head><body></body></html>';
		$document = new \DOMDocument();
		$document->loadHTML($source);

		$result = $this->item->run($document);

		$this->assertInstanceOf(TestResultInterface::class, $result);
		$this->assertNull($result->getValue());
		$this->assertTrue($result->isSuccess());
		$this->assertFalse($result->isFail());
	}

	public function testRunNoContentAttr(): void {
		$source = '<!DOCTYPE html><html lang="cs"><head><meta name="robots"></head><body></body></html>';
		$document = new \DOMDocument();
		$document->loadHTML($source);

		$result = $this->item->run($document);

		$this->assertInstanceOf(TestResultInterface::class, $result);
		$this->assertNull($result->getValue());
		$this->assertFalse($result->isSuccess());
		$this->assertTrue($result->isFail());
	}
}
