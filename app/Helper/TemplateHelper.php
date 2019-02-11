<?php

class TemplateHelper
{
    public static function renderHTML($bodyTemplate, $vars = [], $headerTemplate = null, $footerTemplate = null)
    {
        global $config;
        foreach ($vars as $key => $value) {
            $$key = $value;
        }
        unset($vars);
        
        if ($headerTemplate == null) {
            $headerTemplate = '/header';
        }
        if ($footerTemplate == null) {
            $footerTemplate = '/footer';
        }
        
        $bodyTemplate = self::checkTemplateFilename($bodyTemplate);
        $headerTemplate = self::checkTemplateFilename($headerTemplate);
        $footerTemplate = self::checkTemplateFilename($footerTemplate);
        
        if (!file_exists(TEMPLATEDIR . $bodyTemplate)) {
            echo 'BodyTemplate not found.<br>';
        }
        if (!file_exists(TEMPLATEDIR . $headerTemplate)) {
            echo 'HeaderTemplate not found.<br>';
        }
        if (!file_exists(TEMPLATEDIR . $footerTemplate)) {
            echo 'FooterTemplate not found.<br>';
        }
        
        require TEMPLATEDIR . $headerTemplate;
        require TEMPLATEDIR . $bodyTemplate;
        require TEMPLATEDIR . $footerTemplate;
        die;
    }
    
    private static function checkTemplateFilename($filename)
    {
        if (strpos($filename, '.tpl.php') === false) {
            return $filename . '.tpl.php';
        }
        return $filename;
    }
}