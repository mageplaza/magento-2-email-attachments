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

/**
 * Class Mail
 * @package Mageplaza\EmailAttachments\Model
 */
class Mail
{
    /**
     * @var array
     */
    private $templateVars;

    /**
     * @param array $templateVars
     */
    public function setTemplateVars($templateVars)
    {
        $this->templateVars = $templateVars;
    }

    /**
     * @return mixed
     */
    public function getTemplateVars()
    {
        return $this->templateVars;
    }
}
