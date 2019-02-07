<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Model\Entity\TestCase;


use Ajda2\WebsiteChecker\Model\Entity\AbstractTest;
use Ajda2\WebsiteChecker\Model\Entity\TestResult;
use Ajda2\WebsiteChecker\Model\Entity\TestResultInterface;

class RobotsEnable extends AbstractTest {

	/** @var array|string[] */
	private $allowed = [
		'all',
		'index',
		'index,follow',
		'follow,index'
	];

	public function run(\DOMDocument $document): TestResultInterface {
		$value = NULL;

		/** @var \DOMNode $meta */
		foreach ($document->getElementsByTagName('meta') as $meta) {
			$nameAttr = $meta->attributes->getNamedItem('name');

			if ($nameAttr !== NULL && $nameAttr->textContent !== 'robots') {
				continue;
			}

			$contentAttr = $meta->attributes->getNamedItem('content');
			if ($contentAttr === NULL) {
				return new TestResult(FALSE, NULL, "'content' attribute for element meta robots is missing.");
			}

			$value = \str_replace(" ", "", $contentAttr->textContent);

			break;
		}

		if ($value === NULL) {
			return new TestResult(TRUE, NULL, "Meta robots is not set. Indexing is enabled by default.");
		}

		if (\in_array($value, $this->allowed, TRUE)) {
			return new TestResult(TRUE, $value);
		}

		return new TestResult(FALSE, $value);
	}
}