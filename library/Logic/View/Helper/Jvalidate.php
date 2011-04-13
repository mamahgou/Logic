<?php

class Logic_View_Helper_Jvalidate
{
    public function jvalidate()
    {
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($baseUrl . '/public/js/jquery.validate.js');
        $script->appendFile($baseUrl . '/public/js/jquery.validate.default.js');
    }
}