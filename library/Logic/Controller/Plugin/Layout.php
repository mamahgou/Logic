<?php
/**
 * layout plugin
 *
 * @author ben
 */
class Logic_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $options = array(
            'layout' => 'main',
            'layoutPath' => MODULES_PATH . DS . $request->getModuleName() . DS . 'layouts' . DS . 'scripts',
            'contentKey' => 'content',
        );
        $layout = Zend_Layout::getMvcInstance();
        $layout->setOptions($options);
    }
}