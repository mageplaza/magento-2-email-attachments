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

use Magento\Framework\Mail\TransportInterfaceFactory;
use Mageplaza\EmailAttachments\Model\MailEvent;
use Zend_Pdf_Exception;

/**
 * Class TransportFactory
 * @package Mageplaza\EmailAttachments\Mail
 */
class TransportFactory
{
    /**
     * @var MailEvent
     */
    private $mailEvent;

    /**
     * TransportFactory constructor.
     *
     * @param MailEvent $mailEvent
     */
    public function __construct(MailEvent $mailEvent)
    {
        $this->mailEvent = $mailEvent;
    }

    /**
     * @param TransportInterfaceFactory $subject
     * @param array $data
     *
     * @return mixed
     * @throws Zend_Pdf_Exception
     */
    public function beforeCreate(TransportInterfaceFactory $subject, array $data = [])
    {
        if (isset($data['message'])) {
            $this->mailEvent->dispatch($data['message']);
        }

        return [$data];
    }
}
