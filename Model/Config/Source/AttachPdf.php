<?php

namespace Mageplaza\EmailAttachments\Model\Config\Source;

class AttachPdf implements \Magento\Framework\Option\ArrayInterface
{
    const INVOICE     = 'invoice';
    const SHIPMENT    = 'shipment';
    const CREDIT_MEMO = 'creditmemo';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::INVOICE     => __('Invoice'),
            self::SHIPMENT    => __('Shipment'),
            self::CREDIT_MEMO => __('Credit Memo')
        ];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function toOptionArray()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }
}
