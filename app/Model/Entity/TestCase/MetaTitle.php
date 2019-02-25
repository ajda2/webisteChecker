<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity\TestCase;


use Ajda2\WebsiteChecker\Model\Entity\AbstractTest;
use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

class MetaTitle extends AbstractTest {


	/**
	 * @param \DOMDocument $document
	 * @return TestResultInterface
	 * @throws \Exception
	 */
	public function run(\DOMDocument $document): TestResultInterface {
		$value = NULL;

		/** @var \DOMNode $title */
		foreach ($document->getElementsByTagName('title') as $title) {
			$value = Strings::trim($title->textContent);

			break;
		}

		if ($value === NULL) {
			return new TestResult($this->getCode(), new DateTime(), FALSE, NULL, "Title element is missing.");
		}

		if (!Validators::isNone($value)) {
			return new TestResult($this->getCode(), new DateTime(), TRUE, $value);
		}

		return new TestResult($this->getCode(), new DateTime(), FALSE, $value);
	}
}