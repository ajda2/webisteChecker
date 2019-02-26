<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


use Nette\Http\Url;

interface TestInterface {

	public function run(Url $url, \DOMDocument $document): TestResultInterface;

	public function getCode(): string;

	public function getName(): string;
}