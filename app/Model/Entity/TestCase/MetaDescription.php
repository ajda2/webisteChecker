<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity\TestCase;


use Ajda2\WebsiteChecker\Model\Entity\AbstractTest;
use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

class MetaDescription extends AbstractTest {

	/** @var int */
	private $maxLength = 160;

	/**
	 * @param \DOMDocument $document
	 * @return TestResultInterface
	 * @throws \Exception
	 */
	public function run(\DOMDocument $document): TestResultInterface {
		$value = NULL;

		/** @var \DOMNode $meta */
		foreach ($document->getElementsByTagName('meta') as $meta) {
			$nameAttr = $meta->attributes->getNamedItem('name');

			if ($nameAttr === NULL || $nameAttr->textContent !== 'description') {
				continue;
			}

			$contentAttr = $meta->attributes->getNamedItem('content');
			if ($contentAttr === NULL) {
				return new TestResult($this->getCode(), new DateTime(), FALSE, NULL, "'content' attribute for element meta description is missing.");
			}

			$value = Strings::trim($contentAttr->textContent);

			break;
		}

		if ($value === NULL) {
			return new TestResult($this->getCode(), new DateTime(), FALSE, NULL, "Meta description is not set.");
		}

		if (Validators::isNone($value)) {
			return new TestResult($this->getCode(), new DateTime(), FALSE, $value, "Meta description is empty");
		}

		if (Strings::length($value) > $this->maxLength) {
			return new TestResult($this->getCode(), new DateTime(), FALSE, $value, "Meta description is longer than {$this->maxLength} characters");
		}

		return new TestResult($this->getCode(), new DateTime(), TRUE, $value);
	}
}