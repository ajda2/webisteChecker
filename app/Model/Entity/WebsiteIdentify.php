<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity;


use Nette\Http\Url;
use Nette\Utils\DateTime;

class WebsiteIdentify extends Website implements WebsiteIdentifyInterface {

	use EntityIdentity;

	public function __construct(int $id, Url $url, ?DateTime $lastCheckAt = NULL, ?int $responseCode = NULL, ?int $responseTime = NULL) {
		parent::__construct($url, $lastCheckAt, $responseCode, $responseTime);

		$this->id = $id;
	}
}