<?php declare(strict_types = 1);


namespace Ajda2\WebsiteChecker\Tests\Unit\Model\Entity;

use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Nette\Utils\DateTime;
use PHPUnit\Framework\TestCase;

class TestResultTest extends TestCase {

	/** @var TestResult */
	private $item;

	/**
	 * @throws \Exception
	 */
	public function setUp() {
		parent::setUp();

		$testCode = 'test code';
		$runAt = new DateTime();
		$isSuccess = TRUE;
		$value = 'Value';
		$description = 'Description';

		$this->item = new TestResult($testCode, $runAt, $isSuccess, $value, $description);
	}

	/**
	 * @throws \Exception
	 */
	public function testConstruct(): void {
		$testCode = 'test code';
		$runAt = new DateTime();
		$isSuccess = TRUE;
		$value = 'Value';
		$description = 'Description';

		$this->item = new TestResult($testCode = 'test code', $runAt, $isSuccess, $value, $description);

		$this->assertInstanceOf(TestResult::class, $this->item);
		$this->assertSame($testCode, $this->item->getTestCode());
		$this->assertSame($isSuccess, $this->item->isSuccess());
		$this->assertSame(!$isSuccess, $this->item->isFail());
		$this->assertSame($value, $this->item->getValue());
		$this->assertSame($description, $this->item->getDescription());
		$this->assertSame($runAt->getTimestamp(), $this->item->getRunAt()->getTimestamp());
	}
}
