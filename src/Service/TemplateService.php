<?php

namespace App\Service;

class TemplateService
{
    private $templatesPath;

    public function __construct($templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    public function renderTemplate($vars = [])
    {
        $html = $this->getPageTemplate();

        foreach ($vars as $key => $value) {
            $html = str_replace('{' . $key . '}', $value, $html);
        }

        return $html;
    }

    private function getPageTemplate()
    {
        return file_get_contents($this->templatesPath . DIRECTORY_SEPARATOR . 'page.html');
    }
}
