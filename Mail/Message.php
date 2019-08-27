<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_EmailAttachments
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\EmailAttachments\Mail;

use Magento\Framework\Mail\MailMessageInterface;
use Zend\Mail\MessageFactory as MailMessageFactory;
use Zend\Mime\MessageFactory as MimeMessageFactory;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\Mime\PartFactory;

/**
 * Class Message
 * @package Mageplaza\EmailAttachments\Mail
 */
class Message implements MailMessageInterface
{
    /**
     * @var PartFactory
     */
    protected $partFactory;

    /**
     * @var MimeMessageFactory
     */
    protected $mimeMessageFactory;

    /**
     * @var \Zend\Mail\Message
     */
    private $zendMessage;

    /**
     * Message type
     *
     * @var string
     */
    private $messageType = self::TYPE_TEXT;

    /**
     * @var Part[]
     */
    protected $parts = [];

    /**
     * Message constructor.
     *
     * @param PartFactory $partFactory
     * @param MimeMessageFactory $mimeMessageFactory
     * @param string $charset
     */
    public function __construct(PartFactory $partFactory, MimeMessageFactory $mimeMessageFactory, $charset = 'utf-8')
    {
        $this->partFactory = $partFactory;
        $this->mimeMessageFactory = $mimeMessageFactory;
        $this->zendMessage = MailMessageFactory::getInstance();
        $this->zendMessage->setEncoding($charset);
    }

    /**
     * Add the HTML mime part to the message.
     *
     * @param string $content
     *
     * @return $this
     */
    public function setBodyText($content)
    {
        $this->setMessageType(self::TYPE_TEXT);
        $textPart = $this->partFactory->create();

        $textPart->setContent($content)
            ->setType(Mime::TYPE_TEXT)
            ->setCharset($this->zendMessage->getEncoding());

        $this->parts[] = $textPart;

        return $this;
    }

    /**
     * Add the text mime part to the message.
     *
     * @param string $content
     *
     * @return $this
     */
    public function setBodyHtml($content)
    {
        $this->setMessageType(self::TYPE_HTML);
        $htmlPart = $this->partFactory->create();

        $htmlPart->setContent($content)
            ->setType(Mime::TYPE_HTML)
            ->setCharset($this->zendMessage->getEncoding());

        $this->parts[] = $htmlPart;

        $mimeMessage = new \Zend\Mime\Message();
        $mimeMessage->addPart($htmlPart);
        $this->zendMessage->setBody($mimeMessage);

        return $this;
    }

    /**
     * Add the attachment mime part to the message.
     *
     * @param string $content
     * @param string $fileName
     * @param string $fileType
     * @param string $encoding
     *
     * @return $this
     */
    public function setBodyAttachment($content, $fileName, $fileType, $encoding = '8bit')
    {
        $attachmentPart = $this->partFactory->create();

        $attachmentPart->setContent($content)
            ->setType($fileType)
            ->setFileName($fileName)
            ->setDisposition(Mime::DISPOSITION_ATTACHMENT)
            ->setEncoding($encoding);

        $this->parts[] = $attachmentPart;

        return $this;
    }

    /**
     * Set parts to Zend message body.
     *
     * @return $this
     */
    public function setPartsToBody()
    {
        $mimeMessage = $this->mimeMessageFactory->create();
        $mimeMessage->setParts($this->parts);
        $this->zendMessage->setBody($mimeMessage);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        if (is_string($body) && $this->messageType === self::TYPE_HTML) {
            $body = self::createHtmlMimeFromString($body);
        }
        $this->zendMessage->setBody($body);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->zendMessage->setSubject($subject);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->zendMessage->getSubject();
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->zendMessage->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function setFrom($fromAddress)
    {
        $this->setFromAddress($fromAddress, null);

        return $this;
    }

    /**
     * {@inheritdoc}
     */

    public function setFromAddress($fromAddress, $fromName = null)
    {
        $this->zendMessage->setFrom($fromAddress, $fromName);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addTo($toAddress)
    {
        $this->zendMessage->addTo($toAddress);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addCc($ccAddress)
    {
        $this->zendMessage->addCc($ccAddress);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBcc($bccAddress)
    {
        $this->zendMessage->addBcc($bccAddress);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setReplyTo($replyToAddress)
    {
        $this->zendMessage->setReplyTo($replyToAddress);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawMessage()
    {
        return $this->zendMessage->toString();
    }

    /**
     * Create HTML mime message from the string.
     *
     * @param string $htmlBody
     *
     * @return \Zend\Mime\Message
     */
    private function createHtmlMimeFromString($htmlBody)
    {
        $htmlPart = new Part($htmlBody);
        $htmlPart->setCharset($this->zendMessage->getEncoding());
        $htmlPart->setType(Mime::TYPE_HTML);
        $mimeMessage = new \Zend\Mime\Message();
        $mimeMessage->addPart($htmlPart);

        return $mimeMessage;
    }

    /**
     * @inheritDoc
     */
    public function setMessageType($type)
    {
        $this->messageType = $type;

        return $this;
    }
}
