<?php

namespace Mageplaza\EmailAttachments\Observer;

use Mageplaza\EmailAttachments\Model\Config\Source\AttachPdf;

class InvoiceObserver extends AbstractObserver
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Zend_Pdf_Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Api\Data\InvoiceInterface $obj */
        $obj = $observer->getInvoice();

        $filename = AttachPdf::INVOICE . $obj->getIncrementId() . '.';

        if (in_array(AttachPdf::INVOICE, $this->dataHelper->getAttachPdf($obj->getStoreId()))) {
            /** @var \Zend_Pdf $pdf */
            $pdf = $this->objectManager->create('Magento\Sales\Model\Order\Pdf\Invoice')->getPdf([$obj]);

            $this->attachmentContainer->addAttachment($pdf->render(), $filename . 'pdf', 'application/pdf');
        }

        if (in_array(AttachPdf::INVOICE, $this->dataHelper->getAttachTaC($obj->getStoreId()))) {
            list($filePath, $ext, $mimeType) = $this->getTacFile($obj);

            $this->attachmentContainer->addAttachment(file_get_contents($filePath), $filename . $ext, $mimeType);
        }
    }
}
