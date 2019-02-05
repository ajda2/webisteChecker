<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;

use Nette\Http\Url;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;

interface WebsiteInterface {

	public function getId(): int;

	public function getUrl(): Url;

	public function getLastCheckAt(): ?DateTime;

	public function setLastCheckAt(DateTime $lastCheckAt): WebsiteInterface;

	public function hasFailingTest(): bool;

	public function addTestResult(string $code, TestResultInterface $testCaseResult): WebsiteInterface;

	public function getTestResults(): ArrayHash;
}