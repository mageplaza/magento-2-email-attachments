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

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Mail\Address;
use Magento\Framework\Mail\AddressFactory;
use Magento\Framework\Mail\Exception\InvalidArgumentException;
use Magento\Framework\Mail\MimeMessageInterface;
use Magento\Framework\Mail\MimeMessageInterfaceFactory;
use Zend\Mail\Address as ZendAddress;
use Zend\Mail\AddressList;
use Zend\Mail\Message as ZendMessage;
use Zend\Mime\Message as ZendMimeMessage;

/**
 * Class EmailMessage
 * Replacement for \Magento\Framework\Mail\EmailMessage
 * @package Mageplaza\EmailAttachments\Mail
 */
class EmailMessage
{
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
     * EmailMessage constructor
     *
     * @param MimeMessageInterface $body
     * @param array $to
     * @param MimeMessageInterfaceFactory $mimeMessageFactory
     * @param AddressFactory $addressFactory
     * @param Address[]|null $from
     * @param Address[]|null $cc
     * @param Address[]|null $bcc
     * @param Address[]|null $replyTo
     * @param Address|null $sender
     * @param string|null $subject
     * @param string|null $encoding
     *
     * @throws InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function __construct(
        $body,
        array $to,
        $mimeMessageFactory = null,
        $addressFactory = null,
        array $from = null,
        array $cc = null,
        array $bcc = null,
        array $replyTo = null,
        $sender = null,
        $subject = '',
        $encoding = ''
    ) {
        $this->message = new ZendMessage();
        $mimeMessage = new ZendMimeMessage();
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
        if (!$mimeMessageFactory) {
            $mimeMessageFactory = ObjectManager::getInstance()->create(MimeMessageInterfaceFactory::class);
        }
        $this->mimeMessageFactory = $mimeMessageFactory;
        if (!$addressFactory) {
            $addressFactory = ObjectManager::getInstance()->create(AddressFactory::class);
        }
        $this->addressFactory = $addressFactory;
    }

    /**
     * @inheritDoc
     */
    public function getEncoding()
    {
        return $this->message->getEncoding();
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->message->getHeaders()->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getFrom()
    {
        return $this->convertAddressListToAddressArray($this->message->getFrom());
    }

    /**
     * @inheritDoc
     */
    public function getTo()
    {
        return $this->convertAddressListToAddressArray($this->message->getTo());
    }

    /**
     * @inheritDoc
     */
    public function getCc()
    {
        return $this->convertAddressListToAddressArray($this->message->getCc());
    }

    /**
     * @inheritDoc
     */
    public function getBcc()
    {
        return $this->convertAddressListToAddressArray($this->message->getBcc());
    }

    /**
     * @inheritDoc
     */
    public function getReplyTo()
    {
        return $this->convertAddressListToAddressArray($this->message->getReplyTo());
    }

    /**
     * @inheritDoc
     */
    public function getSender()
    {
        /** @var ZendAddress $zendSender */
        if (!$zendSender = $this->message->getSender()) {
            return null;
        }

        return $this->addressFactory->create(
            [
                'email' => $zendSender->getEmail(),
                'name' => $zendSender->getName()
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getSubject()
    {
        return $this->message->getSubject();
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->mimeMessageFactory->create(
            ['parts' => $this->message->getBody()->getParts()]
        );
    }

    /**
     * @inheritDoc
     */
    public function getBodyText()
    {
        return $this->message->getBodyText();
    }

    /**
     * @inheritdoc
     */
    public function getRawMessage()
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function toString()
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
    private function convertAddressListToAddressArray(AddressList $addressList)
    {
        $arrayList = [];
        foreach ($addressList as $address) {
            $arrayList[] =
                $this->addressFactory->create(
                    [
                        'email' => $address->getEmail(),
                        'name' => $address->getName()
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
     * @param $bodyPart
     *
     * @return $this
     */
    public function setBody($bodyPart)
    {
        $this->message->setBody($bodyPart);

        return $this;
    }

    /**
     * @param string|ZendAddress|array|AddressList $emailOrAddressOrList
     * @param string|null $name
     *
     * @return EmailMessage
     */
    public function addCc($emailOrAddressOrList, $name = null)
    {
        $this->message->addCc($emailOrAddressOrList, $name);

        return $this;
    }

    /**
     * @param string|ZendAddress|array|AddressList $emailOrAddressOrList
     * @param string|null $name
     *
     * @return EmailMessage
     */
    public function addBcc($emailOrAddressOrList, $name = null)
    {
        $this->message->addBcc($emailOrAddressOrList, $name);

        return $this;
    }
}
