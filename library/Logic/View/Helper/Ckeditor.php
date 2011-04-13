<?php

class Logic_View_Helper_Ckeditor
{
    public function ckeditor()
    {
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($baseUrl . '/public/js/ckeditor/ckeditor.js');
        $script->appendFile($baseUrl . '/public/js/ckeditor/adapters/jquery.js');
    }
}