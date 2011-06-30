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

        //html head link
        $this->view->headLink()
        	->prependStylesheet($this->view->baseUrl('/css/style.css'), 'all')
        	->appendStylesheet($this->view->baseUrl('/css/handheld.css?v=2'), 'handheld');

        //html head script
        /*$this->view->headScript()
        	->appendFile($this->view->baseUrl('/public/js/chief.js'));*/
    }
}