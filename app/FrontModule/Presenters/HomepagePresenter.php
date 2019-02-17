<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\FrontModule\Presenters;


use Ajda2\WebsiteChecker\FrontModule\Components\WebsiteGridFactory;
use Nette\Application\UI\Presenter;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridColumnStatusException;

class HomepagePresenter extends Presenter {

	/** @var WebsiteGridFactory @inject */
	public $gridFactory;

	/**
	 * @return DataGrid
	 * @throws DataGridColumnStatusException
	 */
	protected function createComponentGrid(): DataGrid {
		return $this->gridFactory->create();
	}
}