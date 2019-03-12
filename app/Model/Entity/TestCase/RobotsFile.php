<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity\TestCase;


use Ajda2\WebsiteChecker\Model\Entity\AbstractTest;
use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Nette\Http\Url;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;

class RobotsFile extends AbstractTest {

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

		$robotsUrl = new Url((string)$url);
		$robotsUrl->setPath('robots.txt');

		try {
			$fileContent = \file_get_contents((string)$robotsUrl);

			if ($fileContent === FALSE) {
				return new TestResult($this->getCode(), new DateTime(), FALSE, NULL, "Cannot read file");
			}

			if (Strings::contains(
				"User-agent: *
Disallow: /
",
				$fileContent
			)) {
				return new TestResult($this->getCode(), new DateTime(), FALSE, $fileContent, "All user agents are disabled");
			}

			return new TestResult($this->getCode(), new DateTime(), TRUE, $fileContent);
		} catch (\Throwable $e) {
			return new TestResult($this->getCode(), new DateTime(), FALSE, "test.robotsFile.file.notExists");
		}
	}
}