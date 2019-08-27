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
use Magento\Framework\ObjectManagerInterface;
use Mageplaza\EmailAttachments\Helper\Data;
use Mageplaza\EmailAttachments\Mail\Message;

/**
 * Class MailEvent
 * @package Mageplaza\EmailAttachments\Model
 */
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
     *
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
    ) {
        $this->mail = $mail;
        $this->dataHelper = $dataHelper;
        $this->filesystem = $filesystem;
        $this->objectManager = $objectManager;
    }

    /**
     * @param Message $message
     *
     * @throws \Zend_Pdf_Exception
     * @throws \Zend_Mime_Exception
     */
    public function dispatch(Message $message)
    {
        $templateVars = $this->mail->getTemplateVars();
        if (!$templateVars) {
            return;
        }
        /** @var \Magento\Store\Model\Store $store */
        $store = $templateVars['store'];
        $storeId = isset($store) ? $store->getId() : null;

        if (!$this->dataHelper->isEnabled($storeId)) {
            return;
        }

        if ($emailType = $this->getEmailType($templateVars)) {
            /** @var \Magento\Sales\Model\Order|\Magento\Sales\Model\Order\Invoice|\Magento\Sales\Model\Order\Shipment|\Magento\Sales\Model\Order\Creditmemo $obj */
            $obj = $templateVars[$emailType];
            if (in_array($emailType, $this->dataHelper->getAttachPdf($storeId))) {
                $pdfAttachment = $this->setPdfAttachment($emailType, $message, $obj);

                if ($this->dataHelper->versionCompare('2.3')) {
                    $message->setBodyAttachment($pdfAttachment->getContent(), $pdfAttachment->filename,
                        $pdfAttachment->type, \Zend_Mime::ENCODING_BASE64);
                }
            }

            $tacPath = $this->dataHelper->getTacFile($storeId);
            if (in_array($emailType, $this->dataHelper->getAttachTac($storeId)) && $tacPath) {
                $TACAttachment = $this->setTACAttachment($message, $tacPath);
                if ($this->dataHelper->versionCompare('2.3')) {
                    $message->setBodyAttachment($TACAttachment->getContent(), $TACAttachment->filename,
                        $TACAttachment->type, \Zend_Mime::ENCODING_BASE64);
                }
            }

            foreach ($this->dataHelper->getCcTo($storeId) as $email) {
                $message->addCc(trim($email));
            }

            foreach ($this->dataHelper->getBccTo($storeId) as $email) {
                $message->addBcc(trim($email));
            }
            $message->setPartsToBody();
        }

        $this->mail->setTemplateVars(null);
    }

    /**
     * @param $templateVars
     *
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
     * @param string $emailType
     * @param Message $message
     * @param \Magento\Sales\Model\Order|\Magento\Sales\Model\Order\Invoice|\Magento\Sales\Model\Order\Shipment|\Magento\Sales\Model\Order\Creditmemo $obj
     *
     * @return \Zend_Mime_Part
     * @throws \Zend_Pdf_Exception
     */
    private function setPdfAttachment($emailType, Message $message, $obj)
    {
        $pdfModel = 'Magento\Sales\Model\Order\Pdf\\' . ucfirst($emailType);
        /** @var \Zend_Pdf $pdf */
        $pdf = $this->objectManager->create($pdfModel)->getPdf([$obj]);

        if ($this->dataHelper->versionCompare("2.3")) {
            $attachment = new \Zend_Mime_Part($pdf->render());
            $attachment->type = 'application/pdf';
            $attachment->disposition = \Zend_Mime::DISPOSITION_ATTACHMENT;
            $attachment->filename = $emailType . $obj->getIncrementId() . '.pdf';

            return $attachment;
        }
        $message->createAttachment(
            $pdf->render(),
            'application/pdf',
            \Zend_Mime::DISPOSITION_ATTACHMENT,
            \Zend_Mime::ENCODING_BASE64,
            $emailType . $obj->getIncrementId() . '.pdf'
        );

        return null;
    }

    /**
     * @param Message $message
     * @param string $tacPath
     *
     * @return \Zend_Mime_Part
     */
    private function setTACAttachment(Message $message, $tacPath)
    {
        list($filePath, $ext, $mimeType) = $this->getTacFile($tacPath);
        if ($this->dataHelper->versionCompare('2.3')) {
            $attachment = new \Zend_Mime_Part(file_get_contents($filePath));
            $attachment->type = $mimeType;
            $attachment->disposition = \Zend_Mime::DISPOSITION_ATTACHMENT;
            $attachment->filename = 'terms_and_conditions.' . $ext;

            return $attachment;
        }
        $message->createAttachment(
            file_get_contents($filePath),
            $mimeType,
            \Zend_Mime::DISPOSITION_ATTACHMENT,
            \Zend_Mime::ENCODING_BASE64,
            'terms_and_conditions.' . $ext
        );

        return null;
    }

    /**
     * @param string $tacPath
     *
     * @return array
     */
    private function getTacFile($tacPath)
    {
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $filePath = $mediaDirectory->getAbsolutePath('mageplaza/email_attachments/' . $tacPath);
        $ext = substr($filePath, strrpos($filePath, '.') + 1);
        $mimeType = self::$mimeTypes[$ext];

        return [$filePath, $ext, $mimeType];
    }
}
