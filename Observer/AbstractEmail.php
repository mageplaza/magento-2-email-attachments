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

namespace Mageplaza\EmailAttachments\Observer;

use Mageplaza\EmailAttachments\Model\Mail;

class AbstractEmail implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var Mail
     */
    private $mail;

    /**
     * TransportBuilder constructor.
     * @param Mail $mail
     */
    public function __construct(
        Mail $mail
    )
    {
        $this->mail = $mail;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->mail->setTemplateVars($observer->getTransport());
    }
}
