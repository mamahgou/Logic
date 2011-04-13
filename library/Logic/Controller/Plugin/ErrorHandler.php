<?php

class Logic_Controller_Plugin_ErrorHandler extends Zend_Controller_Plugin_Abstract
{
    /**
     * predispatch
     *
     * @param Zend_Controller_Request_Abstract $request
     * @see Zend_Controller_Plugin_Abstract::preDispatch()
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $errorHandler = Zend_Controller_Front::getInstance()->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        $errorHandler->setErrorHandlerModule($module);
    }
}