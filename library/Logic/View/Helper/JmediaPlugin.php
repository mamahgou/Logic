<?php

class Logic_View_Helper_JmediaPlugin
{
    public function jmediaPlugin()
    {
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($baseUrl . '/public/js/jquery.media.js');
        $script->appendFile($baseUrl . '/public/js/jquery.metadata.js');
    }
}