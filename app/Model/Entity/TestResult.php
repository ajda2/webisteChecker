<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


use Nette\SmartObject;
use Nette\Utils\DateTime;

class TestResult implements TestResultInterface {

	use SmartObject;

	/** @var bool */
	private $isSuccess;

	/** @var string|null */
	private $value;

	/** @var string|null */
	private $description;

	/** @var string */
	private $testCode;

	/** @var DateTime */
	private $runAt;

	/**
	 * @param string      $testCode
	 * @param DateTime    $runAt
	 * @param bool        $isSuccess
	 * @param string|null $value
	 * @param string|null $description
	 */
	public function __construct(string $testCode, DateTime $runAt, bool $isSuccess, ?string $value = NULL, ?string $description = NULL) {
		$this->isSuccess = $isSuccess;
		$this->value = $value;
		$this->description = $description;
		$this->testCode = $testCode;
		$this->runAt = $runAt;
	}

	public function isSuccess(): bool {
		return $this->isSuccess;
	}

	public function isFail(): bool {
		return !$this->isSuccess;
	}

	public function getDescription(): ?string {
		return $this->description;
	}

	public function getValue(): ?string {
		return $this->value;
	}

	public function getTestCode(): string {
		return $this->testCode;
	}

	public function getRunAt(): DateTime {
		return clone $this->runAt;
	}
}