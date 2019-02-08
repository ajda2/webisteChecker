<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model;


use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentify;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentifyInterface;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Http\Url;
use Nette\NotImplementedException;
use Nette\SmartObject;

class WebsiteRepository {

	use SmartObject;

	/** @var string */
	private const TABLE_WEBSITE = 'website';

	/** @var string */
	private const COLUMN_WEBSITE_ID = 'id';

	/** @var string */
	private const COLUMN_WEBSITE_URL = 'url';

	/** @var string */
	private const COLUMN_WEBSITE_HAS_ERROR = 'has_error';

	/** @var string */
	private const COLUMN_WEBSITE_LAST_CHECK_AT = 'last_check_at';

	/** @var string */
	private const COLUMN_WEBSITE_RESPONSE_CODE = 'response_code';

	/** @var string */
	private const COLUMN_WEBSITE_RESPONSE_TIME = 'response_time';

	/** @var string */
	private const COLUMN_WEBSITE_TESTS_DATA = 'tests_data';

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	public function getWebsiteForTest(): ?WebsiteIdentifyInterface {
		$row = $this->database->table(self::TABLE_WEBSITE)->order(self::COLUMN_WEBSITE_LAST_CHECK_AT)->limit(1)->fetch();

		if (!$row instanceof ActiveRow) {
			return NULL;
		}

		return $this->fromRowFactory($row);
	}

	public function save(WebsiteIdentifyInterface $website): WebsiteIdentifyInterface {
		throw new NotImplementedException();
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