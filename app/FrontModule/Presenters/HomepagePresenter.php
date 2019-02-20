<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\FrontModule\Presenters;


use Ajda2\WebsiteChecker\FrontModule\Components\WebsiteGridFactory;
use Ajda2\WebsiteChecker\Model\Entity\TestInterface;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteIdentifyInterface;
use Ajda2\WebsiteChecker\Model\Entity\WebsiteInterface;
use Ajda2\WebsiteChecker\Model\PersistException;
use Ajda2\WebsiteChecker\Model\Tester;
use Ajda2\WebsiteChecker\Model\WebsiteRepository;
use Ajda2\WebsiteChecker\Model\WebsiteTestResultRepository;
use Nette\Application\UI\Presenter;
use Nette\InvalidStateException;
use Nette\Utils\DateTime;
use Psr\Http\Message\ResponseInterface;
use Tracy\ILogger;
use Ublaboo\DataGrid\DataGrid;

class HomepagePresenter extends Presenter {

	/** @var WebsiteGridFactory @inject */
	public $gridFactory;

	/** @var Tester @inject */
	public $tester;

	/** @var WebsiteRepository @inject */
	public $websiteRepository;

	/** @var WebsiteTestResultRepository @inject */
	public $websiteTestRepository;

	/** @var ILogger @inject */
	public $logger;

	/** @var float */
	private $requestTimeout = 4.0;

	public function startup(): void {
		parent::startup();

		$this->tester->onWebResponseFail[] = [
			$this,
			'onWebResponseFail'
		];
		$this->tester->onWebResponse[] = [
			$this,
			'onWebResponse'
		];
		$this->tester->onTestSuccess[] = [
			$this,
			'onTestSuccess'
		];
		$this->tester->onTestFail[] = [
			$this,
			'onTestFail'
		];
		$this->tester->onTestComplete[] = [
			$this,
			'onTestComplete'
		];
	}

	/**
	 * @throws PersistException
	 */
	public function handleRunTest(): void {
		$website = $this->websiteRepository->getWebsiteForTest();

		if ($website === NULL) {
			throw new InvalidStateException();
		}

		$website->resetState();
		$this->websiteRepository->save($website);
		$this->websiteTestRepository->removeWebsiteResults($website->getId());

		$this->tester->runTests($website, $this->requestTimeout);

		$this->websiteRepository->save($website);

		if ($this->isAjax()) {
			$this->redrawControl();
		}
	}

	/**
	 * @param Tester           $tester
	 * @param WebsiteInterface $website
	 * @throws \Exception
	 */
	public function onWebResponseFail(Tester $tester, WebsiteInterface $website): void {
		$website->setLastCheckAt(new DateTime());
		$website->setResponseCode(500);

		if ($website instanceof WebsiteIdentifyInterface) {
			try {
				$this->websiteRepository->save($website);
			} catch (PersistException $e) {
			}
		}
	}

	/**
	 * @param Tester            $tester
	 * @param WebsiteInterface  $website
	 * @param ResponseInterface $response
	 * @param float             $responseTime
	 * @throws \Exception
	 */
	public function onWebResponse(Tester $tester, WebsiteInterface $website, ResponseInterface $response, float $responseTime): void {
		$website->setLastCheckAt(new DateTime());
		$website->setResponseCode($response->getStatusCode());
		$website->setResponseTime($responseTime);

		if ($website instanceof WebsiteIdentifyInterface) {
			try {
				$this->websiteRepository->save($website);
			} catch (PersistException $e) {
			}
		}
	}

	public function onTestFail(Tester $tester, WebsiteInterface $website, TestInterface $test, TestResultInterface $testResult): void {

	}

	public function onTestSuccess(Tester $tester, WebsiteInterface $website, TestInterface $test, TestResultInterface $testResult): void {

	}

	public function onTestComplete(Tester $tester, WebsiteInterface $website, TestInterface $test, TestResultInterface $testResult): void {
		if ($website instanceof WebsiteIdentifyInterface) {
			try {
				$website->addTestResult($testResult->getTestCode(), $testResult);

				$this->websiteTestRepository->storeResult($testResult, $website->getId());
			} catch (PersistException $e) {
				$this->logger->log($e, $this->logger::ERROR);
			}
		}
	}

	/**
	 * @return DataGrid
	 * @throws \Ublaboo\DataGrid\Exception\DataGridException
	 */
	protected function createComponentGrid(): DataGrid {
		$tests = [];

		foreach ($this->tester->getTests() as $test) {
			$tests[$test->getCode()] = $test->getName();
		}

		return $this->gridFactory->create($tests);
	}
}