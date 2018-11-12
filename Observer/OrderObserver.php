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

use Mageplaza\EmailAttachments\Model\Config\Source\AttachTaC;

class OrderObserver extends AbstractObserver
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Api\Data\OrderInterface $obj */
        $obj = $observer->getOrder();

        $filename = AttachTaC::ORDER . $obj->getIncrementId() . '.';
        $storeId  = $obj->getStoreId();
        $tacPath  = $this->dataHelper->getTaCFile($storeId);

        if (in_array(AttachTaC::ORDER, $this->dataHelper->getAttachTaC($storeId)) && $tacPath) {
            list($filePath, $ext, $mimeType) = $this->getTacFile($tacPath);

            $this->attachmentContainer->addAttachment(file_get_contents($filePath), $filename . $ext, $mimeType);
        }
    }
}
