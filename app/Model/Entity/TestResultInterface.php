<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


use Nette\Utils\DateTime;

interface TestResultInterface {

	public function getTestCode(): string;

	public function isSuccess(): bool;

	public function isFail(): bool;

	public function getValue(): ?string;

	public function getDescription(): ?string;

	public function getRunAt(): DateTime;
}