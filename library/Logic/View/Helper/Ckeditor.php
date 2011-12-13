<?php

class Logic_View_Helper_Ckeditor extends Zend_View_Helper_Abstract
{
    public function ckeditor()
    {
        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile($this->view->baseUrl('/js/ckeditor/ckeditor.js'))
            ->appendFile($this->view->baseUrl('/js/ckeditor/adapters/jquery.js'));
    }
}