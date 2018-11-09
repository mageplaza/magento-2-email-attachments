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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\ObjectManagerInterface;
use Mageplaza\EmailAttachments\Helper\Data;
use Mageplaza\EmailAttachments\Model\AttachmentContainer;

abstract class AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var array
     */
    protected static $mimeTypes = [
        'txt'  => 'text/plain',
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/msword',
    ];

    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var AttachmentContainer
     */
    protected $attachmentContainer;

    /**
     * AbstractObserver constructor.
     * @param Data $dataHelper
     * @param Filesystem $filesystem
     * @param ObjectManagerInterface $objectManager
     * @param AttachmentContainer $attachmentContainer
     */
    public function __construct(
        Data $dataHelper,
        Filesystem $filesystem,
        ObjectManagerInterface $objectManager,
        AttachmentContainer $attachmentContainer
    )
    {
        $this->dataHelper          = $dataHelper;
        $this->filesystem          = $filesystem;
        $this->objectManager       = $objectManager;
        $this->attachmentContainer = $attachmentContainer;
    }

    public function getTacFile($obj)
    {
        $tacFile        = $this->dataHelper->getTaCFile($obj->getStoreId());
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $filePath       = $mediaDirectory->getAbsolutePath('mageplaza/email_attachments/' . $tacFile);
        $ext            = substr($filePath, strrpos($filePath, '.') + 1);
        $mimeType       = self::$mimeTypes[$ext];

        return [$filePath, $ext, $mimeType];
    }
}
