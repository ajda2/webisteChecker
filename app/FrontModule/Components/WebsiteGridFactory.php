<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\FrontModule\Components;


use Ajda2\WebsiteChecker\Model\WebsiteRepository;
use Nette\SmartObject;
use Ublaboo\DataGrid\DataGrid;

class WebsiteGridFactory {

	use SmartObject;

	/** @var WebsiteRepository */
	private $websiteRepository;

	public function __construct(WebsiteRepository $websiteRepository) {
		$this->websiteRepository = $websiteRepository;
	}

	public function create(): DataGrid {
		$grid = new DataGrid();
		//		$grid->setDefaultSort(["start_at" => "DESC"]);

		$grid->setDataSource($this->websiteRepository->gridData());

		$grid->addColumnText('url', 'Web')
			->setSortable()
			->setFilterText();

		$grid->addColumnDateTime('last_check_at', 'Kontrolováno')
			->setFormat('j.n.Y G:i:s')
			->setSortable();

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