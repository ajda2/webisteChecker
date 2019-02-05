<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


use Nette\Http\Url;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;

class Website implements WebsiteInterface {

	use SmartObject;

	/** @var int */
	private $id;

	/** @var Url */
	private $url;

	/** @var DateTime|null */
	private $lastCheckAt;

	/** @var int|null */
	private $responseCode;

	/** @var int|null */
	private $responseTime;

	/** @var ArrayHash|TestResultInterface[] */
	private $testResults;

	/** @var bool */
	private $hasFailingTest = FALSE;

	/**
	 * @param int           $id
	 * @param Url           $url
	 * @param DateTime|null $lastCheckAt
	 * @param int|null      $responseCode
	 * @param int|null      $responseTime
	 */
	public function __construct(
		int $id,
		Url $url,
		?DateTime $lastCheckAt = NULL,
		?int $responseCode = NULL,
		?int $responseTime = NULL
	) {
		$this->id = $id;
		$this->url = $url;
		$this->lastCheckAt = $lastCheckAt;
		$this->responseCode = $responseCode;
		$this->responseTime = $responseTime;
		$this->testResults = new ArrayHash();
	}

	public function getId(): int {
		return $this->id;
	}

	public function getUrl(): Url {
		return $this->url;
	}

	public function getLastCheckAt(): ?DateTime {
		if ($this->lastCheckAt !== NULL) {
			return clone $this->lastCheckAt;
		}

		return NULL;
	}

	public function setLastCheckAt(DateTime $lastCheckAt): WebsiteInterface {
		$this->lastCheckAt = $lastCheckAt;

		return $this;
	}

	public function hasFailingTest(): bool {
		return $this->hasFailingTest;
	}

	public function addTestResult(string $code, TestResultInterface $testResult): WebsiteInterface {
		if ($testResult->isFail()) {
			$this->hasFailingTest = TRUE;
		}

		$this->testResults->offsetSet($code, $testResult);

		return $this;
	}

	public function getTestResults(): ArrayHash {
		return clone $this->testResults;
	}

	public function getFailingTestResults(): ArrayHash {
		$failing = new ArrayHash();

		foreach ($this->testResults as $code => $result) {
			if ($result->isFail()) {
				$failing->offsetSet($code, $result);
			}
		}

		return $failing;
	}

	public function clearTestResults(): WebsiteInterface {
		$this->testResults = new ArrayHash();
		$this->hasFailingTest = FALSE;

		return $this;
	}

	public function getResponseTime(): ?int {
		return $this->responseTime;
	}

	public function setResponseTime(?int $responseTime): WebsiteInterface {
		$this->responseTime = $responseTime;

		return $this;
	}

	public function getResponseCode(): ?int {
		return $this->responseCode;
	}

	public function setResponseCode(?int $responseCode): WebsiteInterface {
		$this->responseCode = $responseCode;

		return $this;
	}

	public function resetTests(): WebsiteInterface {
		$this->clearTestResults();
		$this->setResponseTime(NULL);
		$this->setResponseCode(NULL);

		return $this;
	}
}