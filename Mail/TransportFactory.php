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
