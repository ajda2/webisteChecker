<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


interface TestResultInterface {

	public function isSuccess(): bool;

	public function isFail(): bool;

	public function getValue(): ?string;

	public function getDescription(): ?string;
}