<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model;


use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentify;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentifyInterface;
use Nette\Database\Context;
use Nette\Database\DriverException;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Http\Url;
use Nette\SmartObject;
use Tracy\ILogger;

class WebsiteFacade {

	use SmartObject;

	/** @var Context */
	private $database;

	/** @var ILogger */
	private $logger;

	/** @var WebsiteRepository */
	private $websiteRepository;

	/**
	 * WebsiteFacade constructor.
	 * @param WebsiteRepository $websiteRepository
	 * @param Context           $database
	 * @param ILogger           $logger
	 */
	public function __construct(WebsiteRepository $websiteRepository, Context $database, ILogger $logger) {
		$this->database = $database;
		$this->logger = $logger;
		$this->websiteRepository = $websiteRepository;
	}


	public function gridData(): Selection {
		$tableName = $this->websiteRepository::TABLE_WEBSITE;

		$columns = [
			"{$tableName}.response_time",
			"{$tableName}.response_code",
			"{$tableName}.last_check_at",
			"{$tableName}.has_failing_test",
			"{$tableName}.url",
			"{$tableName}.id",
			"1 AS robots",
			"1 AS robotsDescription",
			"1 AS metaTitle",
			"1 AS metaTitleDescription",
		];

		return $this->database->table($tableName)
			->select(\implode(", ", $columns));
	}
}