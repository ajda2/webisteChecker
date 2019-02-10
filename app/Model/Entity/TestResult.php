<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


use Nette\SmartObject;

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

	/**
	 * @param string      $testCode
	 * @param bool        $isSuccess
	 * @param string|null $value
	 * @param string|null $description
	 */
	public function __construct(string $testCode, bool $isSuccess, ?string $value = NULL, ?string $description = NULL) {
		$this->isSuccess = $isSuccess;
		$this->value = $value;
		$this->description = $description;
		$this->testCode = $testCode;
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
}