<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model;


use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentifyInterface;
use Nette\Database\Context;
use Nette\Database\Table\Selection;
use Nette\InvalidStateException;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;

class WebsiteFacade {

	use SmartObject;

	/** @var Context */
	private $database;

	/** @var WebsiteRepository */
	private $websiteRepository;

	/**
	 * @var WebsiteTestResultRepository
	 */
	private $websiteTestResultRepository;

	/**
	 * WebsiteFacade constructor.
	 * @param WebsiteRepository           $websiteRepository
	 * @param Context                     $database
	 * @param WebsiteTestResultRepository $websiteTestResultRepository
	 */
	public function __construct(WebsiteRepository $websiteRepository, Context $database, WebsiteTestResultRepository $websiteTestResultRepository) {
		$this->database = $database;
		$this->websiteRepository = $websiteRepository;
		$this->websiteTestResultRepository = $websiteTestResultRepository;
	}

	/**
	 * @param int $websiteId
	 * @return ArrayHash|TestResultInterface[]
	 */
	public function getTestResults(int $websiteId): ArrayHash{
		$results = $this->websiteTestResultRepository->getResults([$websiteId]);

		if(!$results->offsetExists($websiteId)){
			throw new InvalidStateException();
		}

		return $results->offsetGet($websiteId);
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
			"1 AS metaDescription",
			"1 AS h1",
			"1 AS sitemap",
			"1 AS robotsFile",
		];

		return $this->database->table($tableName)
			->select(\implode(", ", $columns));
	}

	/**
	 * @param int $id
	 * @return WebsiteIdentifyInterface
	 * @throws InvalidStateException
	 */
	public function get(int $id): WebsiteIdentifyInterface {
		return $this->websiteRepository->getById($id);
	}

	/**
	 * @param WebsiteIdentifyInterface $website
	 * @return WebsiteIdentifyInterface
	 * @throws PersistException
	 */
	public function persistWebsite(WebsiteIdentifyInterface $website): WebsiteIdentifyInterface {
		return $this->websiteRepository->save($website);
	}
}