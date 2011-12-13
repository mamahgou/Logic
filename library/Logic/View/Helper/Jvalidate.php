<?php

class Logic_View_Helper_Jvalidate extends Zend_View_Helper_Abstract
{
    /**
     * load jquery validation
     *
     * @return void
     */
    public function jvalidate()
    {
        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($this->view->baseUrl('/js/jquery.validate.min.js'));
        //$script->appendFile($this->view->baseUrl('/js/jquery.validate.default.js'));
    }
}