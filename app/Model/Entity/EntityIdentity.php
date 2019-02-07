<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


trait EntityIdentity {

	/** @var int */
	private $id;

	public function getId(): int {
		return $this->id;
	}

}