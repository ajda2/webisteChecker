<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


interface TestInterface {

	public function run(\DOMDocument $document): TestInterface;

	public function getCode(): string;

	public function getName(): string;
}