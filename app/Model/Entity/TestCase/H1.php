<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity\TestCase;


use Ajda2\WebsiteChecker\Model\Entity\AbstractTest;
use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Nette\Http\Url;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

class H1 extends AbstractTest {


	/**
	 * @param Url          $url
	 * @param \DOMDocument $document
	 * @return TestResultInterface
	 * @throws \Exception
	 */
	public function run(Url $url, \DOMDocument $document): TestResultInterface {
		$value = NULL;

		/** @var \DOMNode $element */
		foreach ($document->getElementsByTagName('h1') as $element) {
			$value = Strings::trim($element->textContent);

			break;
		}

		if ($value === NULL) {
			return new TestResult($this->getCode(), new DateTime(), FALSE, NULL, "H1 element is missing.");
		}

		if (!Validators::isNone($value)) {
			return new TestResult($this->getCode(), new DateTime(), TRUE, $value);
		}

		return new TestResult($this->getCode(), new DateTime(), FALSE, $value);
	}
}