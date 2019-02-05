<?php declare(strict_types = 1);


namespace Ajda2\WebsiteChecker\Tests\Unit\Model\Entity;

use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use PHPUnit\Framework\TestCase;

class TestResultTest extends TestCase {

	/** @var TestResult */
	private $item;

	public function setUp() {
		parent::setUp();

		$isSuccess = TRUE;
		$value = 'Value';
		$description = 'Description';

		$this->item = new TestResult($isSuccess, $value, $description);
	}

	public function testConstruct(): void {
		$isSuccess = TRUE;
		$value = 'Value';
		$description = 'Description';

		$this->item = new TestResult($isSuccess, $value, $description);

		$this->assertInstanceOf(TestResult::class, $this->item);
		$this->assertSame($isSuccess, $this->item->isSuccess());
		$this->assertSame(!$isSuccess, $this->item->isFail());
		$this->assertSame($value, $this->item->getValue());
		$this->assertSame($description, $this->item->getDescription());
	}
}
