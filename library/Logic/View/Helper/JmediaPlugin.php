<?php

class Logic_View_Helper_Jmedia extends Zend_View_Helper_Abstract
{
    /**
     * load jquery media plugin
     *
     * @return void
     */
    public function jmedia()
    {
        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($this->view->baseUrl('/public/js/jquery.media.js'))
            ->appendFile($this->view->baseUrl('/public/js/jquery.metadata.js'));
    }
}