<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity\TestCase;


use Ajda2\WebsiteChecker\Model\Entity\AbstractTest;
use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Nette\Http\Url;
use Nette\Utils\DateTime;
use SimpleXMLElement;

class Sitemap extends AbstractTest {


	/**
	 * @param Url          $url
	 * @param \DOMDocument $document
	 * @return TestResultInterface
	 * @throws \Exception
	 */
	public function run(Url $url, \DOMDocument $document): TestResultInterface {
		if ($url->getPath() !== '/') {
			return new TestResult($this->getCode(), new DateTime(), TRUE);
		}

		$sitemapUrl = new Url((string)$url);
		$sitemapUrl->setPath('sitemap.xml');

		try {
			$xml = new SimpleXMLElement((string)$sitemapUrl, 0, TRUE);
			$nodeCount = $xml->count();

			if ($nodeCount > 0) {
				return new TestResult($this->getCode(), new DateTime(), TRUE, "{$nodeCount} pages");
			}

			return new TestResult($this->getCode(), new DateTime(), FALSE, "empty");
		} catch (\Throwable $e) {
			return new TestResult($this->getCode(), new DateTime(), FALSE, "test.sitemap.file.notExist");
		}
	}
}