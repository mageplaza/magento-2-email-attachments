<?php

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

    /**
     * @param null $store
     * @return bool
     */
    public function isCompatibleWithPdfInvoice($store = null)
    {
        return $this->getConfigGeneral('compatible_with_pdf_invoice', $store);
    }
}
