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

/**
 * Class Data
 * @package Mageplaza\EmailAttachments\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'mp_email_attachments';

    /**
     * @param null $store
     *
     * @return array
     */
    public function getCcTo($store = null)
    {
        $ccTo = $this->getConfigGeneral('cc_to', $store);

        return $ccTo ? explode(',', trim($ccTo)) : [];
    }

    /**
     * @param null $store
     *
     * @return array
     */
    public function getBccTo($store = null)
    {
        $bccTo = $this->getConfigGeneral('bcc_to', $store);

        return $bccTo ? explode(',', trim($bccTo)) : [];
    }

    /**
     * @param null $store
     *
     * @return bool
     */
    public function isEnabledAttachPdf($store = null)
    {
        return (bool)$this->getConfigGeneral('is_enabled_attach_pdf', $store);
    }

    /**
     * @param null $store
     *
     * @return array
     */
    public function getAttachPdf($store = null)
    {
        $attachPdf = $this->getConfigGeneral('attach_pdf', $store);

        return explode(',', $attachPdf);
    }

    /**
     * @param null $store
     *
     * @return bool
     */
    public function isEnabledAttachTac($store = null)
    {
        return (bool)$this->getConfigGeneral('is_enabled_attach_tac', $store);
    }

    /**
     * @param null $store
     *
     * @return array
     */
    public function getAttachTac($store = null)
    {
        $attachTaC = $this->getConfigGeneral('attach_tac', $store);

        return explode(',', $attachTaC);
    }

    /**
     * @param null $store
     *
     * @return string
     */
    public function getTacFile($store = null)
    {
        return $this->getConfigGeneral('tac_file', $store);
    }

    /**
     * @return bool
     */
    public function checkPdfInvoiceIsEnable()
    {
        return $this->_moduleManager->isEnabled('Mageplaza_PdfInvoice')
            && $this->getConfigValue('pdfinvoice/general/enabled');
    }
}
