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

class WebsiteRepository {

	use SmartObject;

	/** @var string */
	public const TABLE_WEBSITE = 'website';

	/** @var string */
	public const COLUMN_WEBSITE_ID = 'id';

	/** @var string */
	public const COLUMN_WEBSITE_URL = 'url';

	/** @var string */
	public const COLUMN_WEBSITE_HAS_FAILING_TEST = 'has_failing_test';

	/** @var string */
	public const COLUMN_WEBSITE_LAST_CHECK_AT = 'last_check_at';

	/** @var string */
	public const COLUMN_WEBSITE_RESPONSE_CODE = 'response_code';

	/** @var string */
	public const COLUMN_WEBSITE_RESPONSE_TIME = 'response_time';

	/** @var Context */
	private $database;

	/** @var ILogger */
	private $logger;

	public function __construct(Context $database, ILogger $logger) {
		$this->database = $database;
		$this->logger = $logger;
	}

	public function getWebsiteForTest(): ?WebsiteIdentifyInterface {
		$row = $this->database->table(self::TABLE_WEBSITE)->order(self::COLUMN_WEBSITE_LAST_CHECK_AT)->limit(1)->fetch();

		if (!$row instanceof ActiveRow) {
			return NULL;
		}

		return $this->fromRowFactory($row);
	}

	/**
	 * @param WebsiteIdentifyInterface $website
	 * @return WebsiteIdentifyInterface
	 * @throws PersistException
	 */
	public function save(WebsiteIdentifyInterface $website): WebsiteIdentifyInterface {
		$data = [
			self::COLUMN_WEBSITE_URL              => $website->getUrl(),
			self::COLUMN_WEBSITE_HAS_FAILING_TEST => $website->hasFailingTest(),
			self::COLUMN_WEBSITE_LAST_CHECK_AT    => $website->getLastCheckAt(),
			self::COLUMN_WEBSITE_RESPONSE_CODE    => $website->getResponseCode(),
			self::COLUMN_WEBSITE_RESPONSE_TIME    => $website->getResponseTime()
		];

		try {
			$this->database->table(self::TABLE_WEBSITE)->where([self::COLUMN_WEBSITE_ID => $website->getId()])->update($data);
		} catch (DriverException $e) {
			$this->logger->log($e, $this->logger::ERROR);

			throw new PersistException();
		}

		return $website;
	}

	public function gridData(): Selection {
		$tableName = self::TABLE_WEBSITE;

		$columns = [
			"{$tableName}.response_time",
			"{$tableName}.response_code",
			"{$tableName}.last_check_at",
			"{$tableName}.has_failing_test",
			"{$tableName}.url",
			"{$tableName}.id",
			"1 AS robots",
			"1 AS robotsDescription",
		];

		return $this->database->table($tableName)
			->select(\implode(", ", $columns));
	}

	private function fromRowFactory(ActiveRow $row): WebsiteIdentifyInterface {
		$url = new Url($row->offsetGet(self::COLUMN_WEBSITE_URL));

		return new WebsiteIdentify(
			$row->offsetGet(self::COLUMN_WEBSITE_ID),
			$url,
			$row->offsetGet(self::COLUMN_WEBSITE_LAST_CHECK_AT),
			$row->offsetGet(self::COLUMN_WEBSITE_RESPONSE_CODE),
			$row->offsetGet(self::COLUMN_WEBSITE_RESPONSE_TIME)
		);
	}
}