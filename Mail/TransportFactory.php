<?php

namespace Mageplaza\EmailAttachments\Mail;

use Mageplaza\EmailAttachments\Model\MailEvent;

class TransportFactory
{
    /**
     * @var MailEvent
     */
    private $mailEvent;

    /**
     * TransportFactory constructor.
     * @param MailEvent $mailEvent
     */
    public function __construct(
        MailEvent $mailEvent
    )
    {
        $this->mailEvent = $mailEvent;
    }

    public function aroundCreate(
        \Magento\Framework\Mail\TransportInterfaceFactory $subject,
        \Closure $proceed,
        array $data = []
    )
    {
        if (isset($data['message'])) {
            $this->mailEvent->dispatch($data['message']);
        }

        return $proceed($data);
    }
}
