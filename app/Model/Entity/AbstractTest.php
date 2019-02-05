<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


use Nette\SmartObject;

abstract class AbstractTest implements TestInterface {

	use SmartObject;

	/** @var string */
	private $code;

	/** @var string */
	private $name;

	/**
	 * @param string $code
	 * @param string $name
	 */
	public function __construct(string $code, string $name) {
		$this->code = $code;
		$this->name = $name;
	}

	public function getCode(): string {
		return $this->code;
	}

	public function getName(): string {
		return $this->name;
	}
}