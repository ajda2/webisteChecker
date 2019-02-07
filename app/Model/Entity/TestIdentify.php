<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;



abstract class TestIdentify extends AbstractTest implements TestIdentifyInterface {

	use EntityIdentity;

	public function __construct(int $id, string $code, string $name) {
		parent::__construct($code, $name);

		$this->id = $id;
	}
}