<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;

use Nette\Http\Url;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;

interface WebsiteInterface {

	public function getUrl(): Url;

	public function getLastCheckAt(): ?DateTime;

	public function setLastCheckAt(DateTime $lastCheckAt): WebsiteInterface;

	public function hasFailingTest(): bool;

	public function addTestResult(string $code, TestResultInterface $testResult): WebsiteInterface;

	public function getTestResults(): ArrayHash;

	public function getFailingTestResults(): ArrayHash;

	public function clearTestResults(): WebsiteInterface;

	public function getResponseTime(): ?int;

	public function setResponseTime(?int $responseTime): WebsiteInterface;

	public function getResponseCode(): ?int;

	public function setResponseCode(?int $responseCode): WebsiteInterface;

	/**
	 * Clean all results and all other tests data
	 * @return WebsiteInterface
	 */
	public function resetTests(): WebsiteInterface;
}