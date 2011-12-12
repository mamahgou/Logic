<?php

class Logic_View_Helper_JqueryUI
{
    public function jqueryUI()
    {
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        $headLink = new Zend_View_Helper_HeadLink();
        $headLink->appendStylesheet($baseUrl . '/css/jquery-ui.css');

        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
    }
}