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

namespace Mageplaza\EmailAttachments\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Mail\Message;
use Magento\Framework\ObjectManagerInterface;
use Mageplaza\EmailAttachments\Helper\Data;

class MailEvent
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
     * @var Mail
     */
    private $mail;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * MailEvent constructor.
     * @param Mail $mail
     * @param Data $dataHelper
     * @param Filesystem $filesystem
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        Mail $mail,
        Data $dataHelper,
        Filesystem $filesystem,
        ObjectManagerInterface $objectManager
    )
    {
        $this->mail          = $mail;
        $this->dataHelper    = $dataHelper;
        $this->filesystem    = $filesystem;
        $this->objectManager = $objectManager;
    }

    /**
     * @param Message $message
     * @throws \Zend_Pdf_Exception
     */
    public function dispatch(Message $message)
    {
        if ($templateVars = $this->mail->getTemplateVars()) {
            $storeId = isset($templateVars['store']) ? $templateVars['store']->getId() : null;
            if (!$this->dataHelper->isEnabled($storeId)) {
                return;
            }

            if ($emailType = $this->getEmailType($templateVars)) {
                $obj     = $templateVars[$emailType];
                $tacPath = $this->dataHelper->getTacFile($storeId);

                if ($this->dataHelper->isEnabledAttachPdf($storeId)
                    && in_array($emailType, $this->dataHelper->getAttachPdf($storeId))) {
                    $pdfModel = 'Magento\Sales\Model\Order\Pdf\\' . ucfirst($emailType);

                    /** @var \Zend_Pdf $pdf */
                    $pdf = $this->objectManager->create($pdfModel)->getPdf([$obj]);

                    $message->createAttachment(
                        $pdf->render(),
                        'application/pdf',
                        \Zend_Mime::DISPOSITION_ATTACHMENT,
                        \Zend_Mime::ENCODING_BASE64,
                        $emailType . $obj->getIncrementId() . '.pdf'
                    );
                }

                if ($this->dataHelper->isEnabledAttachTac($storeId)
                    && in_array($emailType, $this->dataHelper->getAttachTac($storeId)) && $tacPath) {
                    list($filePath, $ext, $mimeType) = $this->getTacFile($tacPath);

                    $message->createAttachment(
                        file_get_contents($filePath),
                        $mimeType,
                        \Zend_Mime::DISPOSITION_ATTACHMENT,
                        \Zend_Mime::ENCODING_BASE64,
                        'terms_and_conditions.' . $ext
                    );
                }

                foreach ($this->dataHelper->getCcTo($storeId) as $item) {
                    $message->addCc(trim($item));
                }

                foreach ($this->dataHelper->getBccTo($storeId) as $item) {
                    $message->addBcc(trim($item));
                }
            }

            $this->mail->setTemplateVars(null);
        }
    }

    /**
     * @param $templateVars
     * @return bool|string
     */
    private function getEmailType($templateVars)
    {
        $emailTypes = ['invoice', 'shipment', 'creditmemo', 'order'];

        foreach ($emailTypes as $emailType) {
            if (isset($templateVars[$emailType])) {
                return $emailType;
            }
        }

        return false;
    }

    /**
     * @param $tacPath
     * @return array
     */
    private function getTacFile($tacPath)
    {
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $filePath       = $mediaDirectory->getAbsolutePath('mageplaza/email_attachments/' . $tacPath);
        $ext            = substr($filePath, strrpos($filePath, '.') + 1);
        $mimeType       = self::$mimeTypes[$ext];

        return [$filePath, $ext, $mimeType];
    }
}
