<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\FrontModule\Presenters;

use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Presenter;
use Tracy\Debugger;

class ErrorPresenter extends Presenter {

	/**
	 * @param \Throwable $exception
	 * @throws AbortException
	 * @internal param $Exception
	 */
	public function renderDefault(\Throwable $exception): void {
		if ($this->isAjax()) { // AJAX request? Just note this error in payload.
			$this->payload->error = TRUE;
			$this->sendPayload();
		} elseif ($exception instanceof BadRequestException) {
			$code = $exception->getCode();

			$this->template->title = $this->template->h1 = 'Chyba č.: ' . $code;

			// load template 403.latte or 404.latte or ... 4xx.latte
			$this->setView(
				\in_array(
					$code,
					[
						403,
						404,
						405,
						410,
						500
					],
					TRUE
				) ? $code : '4xx'
			);
			// log to access.log
			Debugger::log(
				"HTTP code $code: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}",
				'access'
			);
		} else {
			$this->setView('500'); // load template 500.latte
			Debugger::log($exception, Debugger::ERROR); // and log exception
		}
	}
}