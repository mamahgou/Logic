<?php

class Logic_Controller_Action_Default extends Zend_Controller_Action
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

        //html head meta
        $this->view->headMeta()
            ->prependHttpEquiv('Content-Type', 'text/html; charset=UTF-8');

        //html head script
        $this->view->headScript()
        	->prependFile('https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js');
            //->appendFile($this->view->baseUrl('/public/js/chief.js'));

        //favorite icon
        $this->view->headLink()->headLink(
            array(
                'rel' => 'shortcut icon',
                'href' => $this->view->baseUrl('/public/images/favicon.ico'),
                'type' => 'image/x-icon'
            ),
            'APPEND'
        );
        $this->view->headLink()->headLink(
            array(
                'rel' => 'icon',
                'href' => $this->view->baseUrl('/public/images/favicon.ico'),
                'type' => 'image/x-icon'
            ),
            'APPEND'
        );

        //html title
        $this->view->headTitle('');
        $this->view->headTitle()->setSeparator(' - ');

        //layout
        $options = array(
            'layout' => 'main',
            'layoutPath' => MODULES_PATH . DS . 'default' . DS . 'layouts' . DS .'scripts',
            'contentKey' => 'content',
            'view' => $this->view,
        );
        $layout = Zend_Layout::getMvcInstance();
        $layout->setOptions($options);
    }
}