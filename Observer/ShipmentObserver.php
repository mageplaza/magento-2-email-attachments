<?php

namespace Mageplaza\EmailAttachments\Observer;

use Mageplaza\EmailAttachments\Model\Config\Source\AttachPdf;

class ShipmentObserver extends AbstractObserver
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Zend_Pdf_Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Api\Data\ShipmentInterface $obj */
        $obj = $observer->getShipment();

        $filename = AttachPdf::SHIPMENT . $obj->getIncrementId() . '.';

        if (in_array(AttachPdf::SHIPMENT, $this->dataHelper->getAttachPdf($obj->getStoreId()))) {
            /** @var \Zend_Pdf $pdf */
            $pdf = $this->objectManager->create('Magento\Sales\Model\Order\Pdf\Shipment')->getPdf([$obj]);

            $this->attachmentContainer->addAttachment($pdf->render(), $filename . 'pdf', 'application/pdf');
        }

        if (in_array(AttachPdf::SHIPMENT, $this->dataHelper->getAttachTaC($obj->getStoreId()))) {
            list($filePath, $ext, $mimeType) = $this->getTacFile($obj);

            $this->attachmentContainer->addAttachment(file_get_contents($filePath), $filename . $ext, $mimeType);
        }
    }
}
