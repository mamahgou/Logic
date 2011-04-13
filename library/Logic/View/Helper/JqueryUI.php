<?php

class Logic_View_Helper_JqueryUI
{
    public function jqueryUI()
    {
        $script = new Zend_View_Helper_HeadScript();
        $script->appendFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js');
    }
}