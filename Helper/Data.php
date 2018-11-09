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

namespace Mageplaza\EmailAttachments\Helper;

use Mageplaza\Core\Helper\AbstractData;

class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'mp_email_attachments';

    /**
     * @param null $store
     * @return array
     */
    public function getAttachPdf($store = null)
    {
        $attachPdf = $this->getConfigGeneral('attach_pdf', $store);

        return explode(',', $attachPdf);
    }

    /**
     * @param null $store
     * @return array
     */
    public function getAttachTaC($store = null)
    {
        $attachTaC = $this->getConfigGeneral('attach_tac', $store);

        return explode(',', $attachTaC);
    }

    /**
     * @param null $store
     * @return string
     */
    public function getTaCFile($store = null)
    {
        return $this->getConfigGeneral('tac_file', $store);
    }
}
