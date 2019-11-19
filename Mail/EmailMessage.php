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

use Magento\Framework\Mail\Address;
use Magento\Framework\Mail\AddressFactory;
use Magento\Framework\Mail\Exception\InvalidArgumentException;
use Magento\Framework\Mail\MimeMessageInterface;
use Magento\Framework\Mail\MimeMessageInterfaceFactory;
use Zend\Mail\Address as ZendAddress;
use Zend\Mail\AddressList;
use Zend\Mail\Message as ZendMessage;
use Zend\Mime\Message as ZendMimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\Mime\PartFactory;

/**
 * Class EmailMessage
 * @package Mageplaza\EmailAttachments\Mail
 */
class EmailMessage extends \Magento\Framework\Mail\EmailMessage
{
    /**
     * @var PartFactory
     */
    protected $partFactory;

    /**
     * @var Part[]
     */
    protected $parts = [];

    /**
     * @var ZendMessage
     */
    private $message;

    /**
     * @var MimeMessageInterfaceFactory
     */
    private $mimeMessageFactory;

    /**
     * @var AddressFactory
     */
    private $addressFactory;

    /**
     * EmailMessage constructor.
     *
     * @param PartFactory $partFactory
     * @param MimeMessageInterface $body
     * @param array $to
     * @param MimeMessageInterfaceFactory $mimeMessageFactory
     * @param AddressFactory $addressFactory
     * @param array|null $from
     * @param array|null $cc
     * @param array|null $bcc
     * @param array|null $replyTo
     * @param Address|null $sender
     * @param string|null $subject
     * @param string|null $encoding
     */
    public function __construct(
        PartFactory $partFactory,
        MimeMessageInterface $body,
        array $to,
        MimeMessageInterfaceFactory $mimeMessageFactory,
        AddressFactory $addressFactory,
        ?array $from = null,
        ?array $cc = null,
        ?array $bcc = null,
        ?array $replyTo = null,
        ?Address $sender = null,
        ?string $subject = '',
        ?string $encoding = ''
    ) {
        $this->message = new ZendMessage();
        $mimeMessage   = new ZendMimeMessage();
        $mimeMessage->setParts($body->getParts());
        $this->message->setBody($mimeMessage);
        if ($encoding) {
            $this->message->setEncoding($encoding);
        }
        if ($subject) {
            $this->message->setSubject($subject);
        }
        if ($sender) {
            $this->message->setSender($sender->getEmail(), $sender->getName());
        }
        if (count($to) < 1) {
            throw new InvalidArgumentException('Email message must have at list one addressee');
        }
        if ($to) {
            $this->message->setTo($this->convertAddressArrayToAddressList($to));
        }
        if ($replyTo) {
            $this->message->setReplyTo($this->convertAddressArrayToAddressList($replyTo));
        }
        if ($from) {
            $this->message->setFrom($this->convertAddressArrayToAddressList($from));
        }
        if ($cc) {
            $this->message->setCc($this->convertAddressArrayToAddressList($cc));
        }
        if ($bcc) {
            $this->message->setBcc($this->convertAddressArrayToAddressList($bcc));
        }
        $this->mimeMessageFactory = $mimeMessageFactory;
        $this->addressFactory     = $addressFactory;
        $this->partFactory        = $partFactory;
    }

    /**
     * @inheritDoc
     */
    public function getEncoding(): string
    {
        return $this->message->getEncoding();
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->message->getHeaders()->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getFrom(): ?array
    {
        return $this->convertAddressListToAddressArray($this->message->getFrom());
    }

    /**
     * @inheritDoc
     */
    public function getTo(): array
    {
        return $this->convertAddressListToAddressArray($this->message->getTo());
    }

    /**
     * @inheritDoc
     */
    public function getCc(): ?array
    {
        return $this->convertAddressListToAddressArray($this->message->getCc());
    }

    /**
     * @inheritDoc
     */
    public function getBcc(): ?array
    {
        return $this->convertAddressListToAddressArray($this->message->getBcc());
    }

    /**
     * @inheritDoc
     */
    public function getReplyTo(): ?array
    {
        return $this->convertAddressListToAddressArray($this->message->getReplyTo());
    }

    /**
     * @inheritDoc
     */
    public function getSender(): ?Address
    {
        /** @var ZendAddress $zendSender */
        if (!$zendSender = $this->message->getSender()) {
            return null;
        }

        return $this->addressFactory->create(
            [
                'email' => $zendSender->getEmail(),
                'name'  => $zendSender->getName()
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getSubject(): ?string
    {
        return $this->message->getSubject();
    }

    /**
     * @inheritDoc
     */
    public function getBody(): MimeMessageInterface
    {
        return $this->mimeMessageFactory->create(
            ['parts' => $this->message->getBody()->getParts()]
        );
    }

    /**
     * @inheritDoc
     */
    public function getBodyText(): string
    {
        return $this->message->getBodyText();
    }

    /**
     * @inheritdoc
     */
    public function getRawMessage(): string
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return $this->message->toString();
    }

    /**
     * Converts AddressList to array
     *
     * @param AddressList $addressList
     *
     * @return Address[]
     */
    private function convertAddressListToAddressArray(AddressList $addressList): array
    {
        $arrayList = [];
        foreach ($addressList as $address) {
            $arrayList[] =
                $this->addressFactory->create(
                    [
                        'email' => $address->getEmail(),
                        'name'  => $address->getName()
                    ]
                );
        }

        return $arrayList;
    }

    /**
     * Converts MailAddress array to AddressList
     *
     * @param Address[] $arrayList
     *
     * @return AddressList
     */
    private function convertAddressArrayToAddressList(array $arrayList): AddressList
    {
        $zendAddressList = new AddressList();
        foreach ($arrayList as $address) {
            $zendAddressList->add($address->getEmail(), $address->getName());
        }

        return $zendAddressList;
    }

    /**
     * @param $content
     * @param $fileName
     * @param $fileType
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
     * {@inheritdoc}
     */
    public function addCc($ccAddress)
    {
        $this->message->addCc($ccAddress);

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function addBcc($bccAddress)
    {
        $this->message->addBcc($bccAddress);

        return $this;
    }

    /**
     * @return $this
     */
    public function setPartsToBody()
    {
        $message       = ZendMessage::fromString($this->getRawMessage());
        $body          = new Part(quoted_printable_decode($message->getBody()));
        $body->type    = 'text/html';
        $body->charset = 'utf-8';
        $parts         = [$body];

        foreach ($this->parts as $part) {
            $parts[] = $part;
        }

        $bodyPart = new ZendMimeMessage();
        $bodyPart->setParts($parts);
        $this->message->setBody($bodyPart);

        return $this;
    }
}