<?php

class IndexController extends Logic_Controller_Action_Default
{
    public function indexAction()
    {
        $this->_redirect('/admin/');
    }
}

