<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\FrontModule\Components;


use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Ajda2\WebsiteChecker\Model\WebsiteFacade;
use Ajda2\WebsiteChecker\Model\WebsiteTestResultRepository;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Url;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\Html;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\InlineEdit\InlineEdit;

class WebsiteGridFactory {

	use SmartObject;

	/** @var WebsiteFacade */
	private $websiteFacade;

	/** @var WebsiteTestResultRepository */
	private $testResultRepository;

	/**
	 * WebsiteGridFactory constructor.
	 * @param WebsiteFacade               $websiteFacade
	 * @param WebsiteTestResultRepository $testResultRepository
	 */
	public function __construct(WebsiteFacade $websiteFacade, WebsiteTestResultRepository $testResultRepository) {
		$this->websiteFacade = $websiteFacade;
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

		$dataSource = $this->websiteFacade->gridData();
		$websiteIds = [];

		/** @var ActiveRow $row */
		foreach ($dataSource as $row) {
			$websiteIds[] = $row->offsetGet('id');
		}

		$testResults = $this->testResultRepository->getResults($websiteIds);

		$grid->setDataSource($dataSource);

		$grid->addColumnText('url', 'Web')
			->setRenderer(
				function (ActiveRow $row): Html {
					$url = $row->offsetGet('url');
					$linkElem = Html::el('a')
						->setText($url)
						->href($url)
						->setAttribute('target', '_blank');

					return $linkElem;
				}
			)
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


		$grid->setRowCallback(
			function (ActiveRow $item, $tr): void {
				if ((bool)$item->offsetGet('has_failing_test')) {
					$tr->addClass('error');
				}
			}
		);

		$grid->addAction('delete', 'Smazat', 'delete!')
			->setIcon('trash')
			->setTitle('Smazat')
			->setClass('btn btn-danger ajax')
			->setConfirm('Opravdu smazat URL %s?', 'url'); // Second parameter is optional

		$grid->addAction('test', "Zkontrolovat", "testWeb!")
			->setClass('btn btn-primary ajax');


		/** @var InlineEdit $inlineEdit */
		$inlineEdit = $grid->addInlineEdit();
		$inlineEdit->setText('Editovat')->setClass('btn btn-secondary ajax');
		$inlineEdit->onControlAdd[] = function ($container): void {
			$container->addText('url', '');
		};

		$inlineEdit->onSetDefaults[] = function ($container, ActiveRow $item): void {
			$container->setDefaults(
				[
					'url' => $item->offsetGet('url'),
				]
			);
		};

		$inlineEdit->onSubmit[] = function (int $id, ArrayHash $values): void {
			$website = $this->websiteFacade->get($id);
			$website->resetState();
			$website->setUrl(new Url($values->offsetGet('url')));

			$this->websiteFacade->persistWebsite($website);
			$this->testResultRepository->removeWebsiteResults($website->getId());
		};

		return $grid;
	}
}