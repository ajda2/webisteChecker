<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model;


use Nette\Database\Context;
use Nette\Database\Table\Selection;
use Nette\SmartObject;

class WebsiteFacade {

	use SmartObject;

	/** @var Context */
	private $database;

	/** @var WebsiteRepository */
	private $websiteRepository;

	/**
	 * WebsiteFacade constructor.
	 * @param WebsiteRepository $websiteRepository
	 * @param Context           $database
	 */
	public function __construct(WebsiteRepository $websiteRepository, Context $database) {
		$this->database = $database;
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
			"1 AS h1",
		];

		return $this->database->table($tableName)
			->select(\implode(", ", $columns));
	}
}