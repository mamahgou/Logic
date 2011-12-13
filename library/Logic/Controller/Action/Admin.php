<?php

class Logic_Controller_Action_Admin extends Logic_Controller_Action
{
    /**
     * construct
     *
     * @param   Zend_Controller_Request_Abstract    $request
     * @param   Zend_Controller_Response_Abstract   $response
     * @param   array   $invokeArgs
     * @return  void
     */
    public function __construct(
        Zend_Controller_Request_Abstract $request,
        Zend_Controller_Response_Abstract $response,
        array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);

        //html title
        $this->view->headTitle('Management');

        //html head link
        $this->view->headLink()
        	->appendStylesheet($this->view->baseUrl('/css/admin.css'), 'all');
    }

    public function postDispatch()
    {
        parent::postDispatch();

        //sidebar
        if ($this->_sidebar === true) {
            $this->getResponse()->insert('sidebar', $this->view->render($this->getSidebarName()));
        }
    }
}