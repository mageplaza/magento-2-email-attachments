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

namespace Mageplaza\EmailAttachments\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class AttachPdf
 * @package Mageplaza\EmailAttachments\Model\Config\Source
 */
class AttachPdf implements OptionSourceInterface
{
    const INVOICE = 'invoice';
    const SHIPMENT = 'shipment';
    const CREDIT_MEMO = 'creditmemo';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::INVOICE => __('Invoice'),
            self::SHIPMENT => __('Shipment'),
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
