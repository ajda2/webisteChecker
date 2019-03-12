<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Mail;


use Nette\Mail\Message;
use Ublaboo\Mailing\IComposableMail;
use Ublaboo\Mailing\Mail;

class FailingTests extends Mail implements IComposableMail {

	/**
	 * @param Message            $message
	 * @param array|mixed[]|null $params
	 */
	public function compose(Message $message, $params = NULL): void {
		$this->setTemplateFile(__DIR__ . '/templates/failingTests.latte');

		$message->setFrom('Website Tester <no-reply@surface.cz>');
		$message->addTo('michal.tichy@surface.cz');

		$message->setSubject("Web '{$params['website']->getUrl()}' obsahuje chyby");
	}
}