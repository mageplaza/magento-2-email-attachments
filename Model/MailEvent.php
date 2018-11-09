<?php

namespace Mageplaza\EmailAttachments\Model;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Mail\Message;
use Mageplaza\EmailAttachments\Helper\Data;

class MailEvent
{

    /**
     * @var Mail
     */
    private $mail;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * @var AttachmentContainer
     */
    private $attachmentContainer;

    /**
     * MailEvent constructor.
     * @param Mail $mail
     * @param Data $dataHelper
     * @param ManagerInterface $eventManager
     * @param AttachmentContainer $attachmentContainer
     */
    public function __construct(
        Mail $mail,
        Data $dataHelper,
        ManagerInterface $eventManager,
        AttachmentContainer $attachmentContainer
    )
    {
        $this->mail                = $mail;
        $this->dataHelper          = $dataHelper;
        $this->eventManager        = $eventManager;
        $this->attachmentContainer = $attachmentContainer;
    }

    /**
     * @param Message $message
     */
    public function dispatch(Message $message)
    {
        if ($templateVars = $this->mail->getTemplateVars()) {
            if ($emailType = $this->getEmailType($templateVars)) {
                $this->eventManager->dispatch(
                    'mageplaza_emailattachments_' . $emailType, [$emailType => $templateVars[$emailType]]
                );
            }

            /** @var Attachment $attachment */
            foreach ($this->attachmentContainer->getAttachments() as $attachment) {
                $message->createAttachment(
                    $attachment->getContent(),
                    $attachment->getMimeType(),
                    $attachment->getDisposition(),
                    $attachment->getEncoding(),
                    $attachment->getFilename()
                );
            }

            $this->attachmentContainer->resetAttachments();
            $this->mail->setTemplateVars(null);
        }
    }

    /**
     * @param $templateVars
     * @return bool|string
     */
    private function getEmailType($templateVars)
    {
        if (isset($templateVars['invoice'])) {
            return 'invoice';
        }

        if (isset($templateVars['shipment'])) {
            return 'shipment';
        }

        if (isset($templateVars['creditmemo'])) {
            return 'creditmemo';
        }

        if (isset($templateVars['order'])) {
            return 'order';
        }

        return false;
    }
}
