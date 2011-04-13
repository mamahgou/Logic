<?php

class Logic_View_Helper_Upload
{
    public function upload()
    {
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        $headLink = new Zend_View_Helper_HeadLink();
        $headLink->appendStylesheet($baseUrl . '/public/js/uploadify/uploadify.css');

        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($baseUrl . '/public/js/uploadify/swfobject.js');
        $script->appendFile($baseUrl . '/public/js/uploadify/jquery.uploadify.v2.1.4.min.js');
    }
}