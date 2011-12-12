<?php

class Logic_Controller_Action_Default extends Logic_Controller_Action
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
        $settings = $this->_bootstrap->getOptions();
        $settings = $settings['configuration']['settings'];
        $this->view->headTitle($settings['title']);

        //html head link
        $this->view->headLink()
        	->prependStylesheet($this->view->baseUrl('/css/style.css'), 'all')
        	->appendStylesheet($this->view->baseUrl('/css/960.css'), 'all')
        	->appendStylesheet($this->view->baseUrl('/css/default.css'), 'all');
    }
}