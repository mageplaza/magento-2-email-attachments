<?php

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
