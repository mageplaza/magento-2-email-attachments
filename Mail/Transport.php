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

declare(strict_types=1);

namespace Mageplaza\EmailAttachments\Mail;

use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MailMessageInterface;
use Magento\Store\Model\ScopeInterface;
use Traversable;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

/**
 * Class that responsible for filling some message data before transporting it.
 * @see \Zend\Mail\Transport\Sendmail is used for transport
 */
class Transport extends \Magento\Email\Model\Transport
{
    /**
     * Whether return path should be set or no.
     *
     * Possible values are:
     * 0 - no
     * 1 - yes (set value as FROM address)
     * 2 - use custom value
     *
     * @var int
     */
    private $isSetReturnPath;

    /**
     * @var string|null
     */
    private $returnPathValue;

    /**
     * @var Sendmail
     */
    private $zendTransport;

    /**
     * @var MailMessageInterface
     */
    private $message;

    /**
     * Transport constructor.
     *
     * @param MailMessageInterface $message Email message object
     * @param ScopeConfigInterface $scopeConfig Core store config
     * @param null|string|array|Traversable $parameters Config options for sendmail parameters
     */
    public function __construct(
        $message,
        ScopeConfigInterface $scopeConfig,
        $parameters = null
    ) {
        $this->isSetReturnPath = (int)$scopeConfig->getValue(
            self::XML_PATH_SENDING_SET_RETURN_PATH,
            ScopeInterface::SCOPE_STORE
        );
        $this->returnPathValue = $scopeConfig->getValue(
            self::XML_PATH_SENDING_RETURN_PATH_EMAIL,
            ScopeInterface::SCOPE_STORE
        );

        $this->zendTransport = new Sendmail($parameters);
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function sendMessage()
    {
        try {
            $zendMessage = Message::fromString($this->message->getRawMessage())->setEncoding('utf-8');
            if ($this->isSetReturnPath === 2 && $this->returnPathValue) {
                $zendMessage->setSender($this->returnPathValue);
            } elseif ($this->isSetReturnPath === 1 && $zendMessage->getFrom()->count()) {
                $fromAddressList = $zendMessage->getFrom();
                $fromAddressList->rewind();
                $zendMessage->setSender($fromAddressList->current()->getEmail());
            }

            $this->zendTransport->send($zendMessage);
        } catch (Exception $e) {
            throw new MailException(__($e->getMessage()), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function getMessage()
    {
        return $this->message;
    }
}
