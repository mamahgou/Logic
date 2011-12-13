<?php

class Logic_View_Helper_JqueryUI extends Zend_View_Helper_Abstract
{
    /**
     * loading jquery ui
     *
     * @return void
     */
    public function jqueryUI()
    {
        $headLink = new Zend_View_Helper_HeadLink();
        $headLink->appendStylesheet($this->view->baseUrl('/css/jquery-ui.css'), 'all');

        $headScript = new Zend_View_Helper_HeadScript();
        $headScript->offsetSetFile(2, '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
    }
}