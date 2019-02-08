<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model;


use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentifyInterface;
use Nette\Database\Context;
use Nette\NotImplementedException;
use Nette\SmartObject;

class WebsiteRepository {

	use SmartObject;

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	public function getWebsiteForTest(): ?WebsiteIdentifyInterface {
		$this->database->table('website');

		return NULL;
		throw new NotImplementedException();
	}

	public function save(WebsiteIdentifyInterface $website): WebsiteIdentifyInterface {
		throw new NotImplementedException();
	}
}