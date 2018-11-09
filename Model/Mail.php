<?php

namespace Mageplaza\EmailAttachments\Model;

class Mail
{
    private $templateVars;

    public function setTemplateVars($templateVars)
    {
        $this->templateVars = $templateVars;
    }

    public function getTemplateVars()
    {
        return $this->templateVars;
    }
}
