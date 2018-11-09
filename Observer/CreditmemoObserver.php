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

use Mageplaza\EmailAttachments\Model\Config\Source\AttachPdf;

class CreditmemoObserver extends AbstractObserver
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Zend_Pdf_Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Api\Data\CreditmemoInterface $obj */
        $obj = $observer->getCreditmemo();

        $filename = AttachPdf::CREDIT_MEMO . $obj->getIncrementId() . '.';

        if (in_array(AttachPdf::CREDIT_MEMO, $this->dataHelper->getAttachPdf($obj->getStoreId()))) {
            /** @var \Zend_Pdf $pdf */
            $pdf = $this->objectManager->create('Magento\Sales\Model\Order\Pdf\Creditmemo')->getPdf([$obj]);

            $this->attachmentContainer->addAttachment($pdf->render(), $filename . 'pdf', 'application/pdf');
        }

        if (in_array(AttachPdf::CREDIT_MEMO, $this->dataHelper->getAttachTaC($obj->getStoreId()))) {
            list($filePath, $ext, $mimeType) = $this->getTacFile($obj);

            $this->attachmentContainer->addAttachment(file_get_contents($filePath), $filename . $ext, $mimeType);
        }
    }
}
