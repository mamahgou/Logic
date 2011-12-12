<?php

class Logic_View_Helper_Jvalidate
{
    public function jvalidate()
    {
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($baseUrl . '/js/jquery.validate.min.js');
        //$script->appendFile($baseUrl . '/js/jquery.validate.default.js');
    }
}