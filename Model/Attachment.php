<?php

namespace Mageplaza\EmailAttachments\Model;

class Attachment
{
    protected $content;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var string
     */
    protected $disposition;

    /**
     * @var string
     */
    protected $encoding;

    /**
     * @var string
     */
    protected $filename;

    /**
     * Attachment constructor.
     * @param $content
     * @param string $filename
     * @param string $mimeType
     * @param string $disposition
     * @param string $encoding
     */
    public function __construct(
        $content,
        $filename,
        $mimeType,
        $disposition = \Zend_Mime::DISPOSITION_ATTACHMENT,
        $encoding = \Zend_Mime::ENCODING_BASE64
    )
    {
        $this->content     = $content;
        $this->filename    = $filename;
        $this->mimeType    = $mimeType;
        $this->disposition = $disposition;
        $this->encoding    = $encoding;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getDisposition()
    {
        return $this->disposition;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }
}
