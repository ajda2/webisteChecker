<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\FrontModule\Components;


use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Ajda2\WebsiteChecker\Model\WebsiteRepository;
use Ajda2\WebsiteChecker\Model\WebsiteTestResultRepository;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Ublaboo\DataGrid\DataGrid;

class WebsiteGridFactory {

	use SmartObject;

	/** @var WebsiteRepository */
	private $websiteRepository;

	/** @var WebsiteTestResultRepository */
	private $testResultRepository;

	/**
	 * WebsiteGridFactory constructor.
	 * @param WebsiteRepository           $websiteRepository
	 * @param WebsiteTestResultRepository $testResultRepository
	 */
	public function __construct(WebsiteRepository $websiteRepository, WebsiteTestResultRepository $testResultRepository) {
		$this->websiteRepository = $websiteRepository;
		$this->testResultRepository = $testResultRepository;
	}

	/**
	 * @param array|string[] $resultCodes
	 * @return DataGrid
	 * @throws \Ublaboo\DataGrid\Exception\DataGridException
	 */
	public function create(array $resultCodes): DataGrid {
		$grid = new DataGrid();
		$grid->setDefaultSort(
			[
				"has_failing_test" => "DESC",
				"start_at"         => "ASC"
			]
		);
		$grid->setItemsPerPageList(
			[
				500,
				1000
			]
		);
		$grid->setDefaultPerPage(500);

		$dataSource = $this->websiteRepository->gridData();
		$websiteIds = [];

		/** @var ActiveRow $row */
		foreach ($dataSource as $row) {
			$websiteIds[] = $row->offsetGet('id');
		}

		$testResults = $this->testResultRepository->getResults($websiteIds);

		$grid->setDataSource($dataSource);

		$grid->addColumnText('url', 'Web')
			->setSortable()
			->setFilterText();

		$grid->addColumnDateTime('last_check_at', 'Kontrolováno')
			->setFormat('j.n.Y G:i:s')
			->setSortable();

		$grid->addColumnNumber('response_time', 'Čas odpovědi [s]')
			->setFormat(5)
			->setSortable();
		$grid->addColumnNumber('response_code', 'HTTP Kód');


		foreach ($resultCodes as $testCode => $name) {
			$grid->addColumnText($testCode, $name)
				->setRenderer(
					function (ActiveRow $row) use ($testResults, $testCode): string {
						$websiteId = $row->offsetGet('id');

						if (!$testResults->offsetExists($websiteId)) {
							return '';
						}

						/** @var ArrayHash $results */
						$results = $testResults->offsetGet($websiteId);

						if (!$results->offsetExists($testCode)) {
							return '';
						}

						/** @var TestResultInterface $result */
						$result = $results->offsetGet($testCode);

						return (string)$result->getValue();
					}
				);
		}


		$grid->addColumnText('has_failing_test', 'Stav')
			->setSortable()
			->setReplacement(
				[
					NULL => '-',
					0    => 'OK',
					1    => 'CHYBA'
				]
			)
			->setFilterSelect(
				[
					NULL => '-',
					0    => 'OK',
					1    => 'CHYBA'
				]
			);


		return $grid;
	}
}