<?php

class Admin_ErrorController extends Logic_Controller_Action_Admin
{
    public function init()
    {
        $this->view->headTitle('發生錯誤');
    }

    public function indexAction()
    {
        $this->_forward('error');
    }

    public function privilegeAction()
    {
        $this->getResponse()->setHttpResponseCode(500);
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors) {
            $this->view->exception = new Exception('錯誤顯示頁面');
            return;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->err($errors->exception);
        }

        // conditionally display exceptions
        $this->view->exception = $errors->exception;

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller, action or route not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->render('404');
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
        }
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}

