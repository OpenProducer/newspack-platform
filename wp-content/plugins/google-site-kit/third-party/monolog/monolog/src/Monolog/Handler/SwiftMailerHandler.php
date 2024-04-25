<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Google\Site_Kit_Dependencies\Monolog\Handler;

use Google\Site_Kit_Dependencies\Monolog\Logger;
use Google\Site_Kit_Dependencies\Monolog\Utils;
use Google\Site_Kit_Dependencies\Monolog\Formatter\FormatterInterface;
use Google\Site_Kit_Dependencies\Monolog\Formatter\LineFormatter;
use Google\Site_Kit_Dependencies\Swift_Message;
use Google\Site_Kit_Dependencies\Swift;
/**
 * SwiftMailerHandler uses Swift_Mailer to send the emails
 *
 * @author Gyula Sallai
 *
 * @phpstan-import-type Record from \Monolog\Logger
 * @deprecated Since Monolog 2.6. Use SymfonyMailerHandler instead.
 */
class SwiftMailerHandler extends \Google\Site_Kit_Dependencies\Monolog\Handler\MailHandler
{
    /** @var \Swift_Mailer */
    protected $mailer;
    /** @var Swift_Message|callable(string, Record[]): Swift_Message */
    private $messageTemplate;
    /**
     * @psalm-param Swift_Message|callable(string, Record[]): Swift_Message $message
     *
     * @param \Swift_Mailer          $mailer  The mailer to use
     * @param callable|Swift_Message $message An example message for real messages, only the body will be replaced
     */
    public function __construct(\Google\Site_Kit_Dependencies\Swift_Mailer $mailer, $message, $level = \Google\Site_Kit_Dependencies\Monolog\Logger::ERROR, bool $bubble = \true)
    {
        parent::__construct($level, $bubble);
        @\trigger_error('The SwiftMailerHandler is deprecated since Monolog 2.6. Use SymfonyMailerHandler instead.', \E_USER_DEPRECATED);
        $this->mailer = $mailer;
        $this->messageTemplate = $message;
    }
    /**
     * {@inheritDoc}
     */
    protected function send(string $content, array $records) : void
    {
        $this->mailer->send($this->buildMessage($content, $records));
    }
    /**
     * Gets the formatter for the Swift_Message subject.
     *
     * @param string|null $format The format of the subject
     */
    protected function getSubjectFormatter(?string $format) : \Google\Site_Kit_Dependencies\Monolog\Formatter\FormatterInterface
    {
        return new \Google\Site_Kit_Dependencies\Monolog\Formatter\LineFormatter($format);
    }
    /**
     * Creates instance of Swift_Message to be sent
     *
     * @param  string        $content formatted email body to be sent
     * @param  array         $records Log records that formed the content
     * @return Swift_Message
     *
     * @phpstan-param Record[] $records
     */
    protected function buildMessage(string $content, array $records) : \Google\Site_Kit_Dependencies\Swift_Message
    {
        $message = null;
        if ($this->messageTemplate instanceof \Google\Site_Kit_Dependencies\Swift_Message) {
            $message = clone $this->messageTemplate;
            $message->generateId();
        } elseif (\is_callable($this->messageTemplate)) {
            $message = ($this->messageTemplate)($content, $records);
        }
        if (!$message instanceof \Google\Site_Kit_Dependencies\Swift_Message) {
            $record = \reset($records);
            throw new \InvalidArgumentException('Could not resolve message as instance of Swift_Message or a callable returning it' . ($record ? \Google\Site_Kit_Dependencies\Monolog\Utils::getRecordMessageForException($record) : ''));
        }
        if ($records) {
            $subjectFormatter = $this->getSubjectFormatter($message->getSubject());
            $message->setSubject($subjectFormatter->format($this->getHighestRecord($records)));
        }
        $mime = 'text/plain';
        if ($this->isHtmlBody($content)) {
            $mime = 'text/html';
        }
        $message->setBody($content, $mime);
        /** @phpstan-ignore-next-line */
        if (\version_compare(\Google\Site_Kit_Dependencies\Swift::VERSION, '6.0.0', '>=')) {
            $message->setDate(new \DateTimeImmutable());
        } else {
            /** @phpstan-ignore-next-line */
            $message->setDate(\time());
        }
        return $message;
    }
}
