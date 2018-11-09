<?php

namespace Mageplaza\EmailAttachments\Model;

class AttachmentContainer
{
    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @var AttachmentFactory
     */
    protected $attachmentFactory;

    /**
     * AttachmentContainer constructor.
     * @param AttachmentFactory $attachmentFactory
     */
    public function __construct(
        AttachmentFactory $attachmentFactory
    )
    {
        $this->attachmentFactory = $attachmentFactory;
    }

    public function addAttachment($content, $filename, $mimeType)
    {
        $this->attachments[] = $this->attachmentFactory->create([
            'content'  => $content,
            'filename' => $filename,
            'mimeType' => $mimeType
        ]);
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return void
     */
    public function resetAttachments()
    {
        $this->attachments = [];
    }
}
