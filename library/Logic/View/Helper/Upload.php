<?php

class Logic_View_Helper_Uploadify extends Zend_View_Helper_Abstract
{
    public function uploadify()
    {
        $headLink = new Zend_View_Helper_HeadLink();
        $headLink->appendStylesheet($this->view->baseUrl('/js/uploadify/uploadify.css'), 'all');

        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($this->view->baseUrl('/js/uploadify/swfobject.js'))
            ->appendFile($this->view->baseUrl('/js/uploadify/jquery.uploadify.v2.1.4.min.js'));
    }
}