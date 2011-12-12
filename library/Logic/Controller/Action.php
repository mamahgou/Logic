<?php
class Logic_Controller_Action extends Zend_Controller_Action
{
    /**
     * using sidebar?
     *
     * @var boolean
     */
    protected $_sidebar = true;

    /**
     * sidebar name
     *
     * @var string
     */
    protected $_sidebarName;

    /**
     * using layout
     *
     * @var boolean
     */
    protected $_layout = true;

    /**
     * layout name
     *
     * @var string
     */
    protected $_layoutName;

    /**
     * using view renderer?
     *
     * @var boolean
     */
    protected $_render = true;

    /**
     * auth storage
     *
     * @var array
     */
    protected $_storage;

    /**
     * breadcrumb
     *
     * @var array
     */
    protected $_breadcrumb = array();

    /**
     * bootstrap
     *
     * @var Zend_Application_Bootstrap_BootstrapAbstract
     */
    protected $_bootstrap;

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

        $this->_bootstrap = $this->getInvokeArg('bootstrap');
        $this->view->storage = $this->_storage = Zend_Auth::getInstance()->getStorage()->read();
        $module = $request->getModuleName();
        $frontController = $this->getFrontController();
        $baseUrl = $frontController->getBaseUrl();

        $dirs = $frontController->getControllerDirectory();
        if (empty($module) || !isset($dirs[$module])) {
            $module = $frontController->getDefaultModule();
        }

        if (!defined('LAYOUT_PATH')) {
            define('LAYOUT_PATH', MODULES_PATH . DS . $module . DS . 'layouts' . DS . 'scripts');
        }

        //settings
        $settings = $this->_bootstrap->getOptions();
        $settings = $settings['configuration']['settings'];

        //html head meta
        $this->view->headMeta()
            ->appendHttpEquiv('X-UA-Compatible', 'IE=edge,chrome=1')
        	->appendName('description', $settings['description'])
        	->appendName('author', $settings['author'])
        	->appendName('keywords', $settings['keywords'])
        	->appendName('viewport', 'width=device-width, initial-scale=1.0');

        //favorite icon
        $this->view->headLink()->headLink(
            array(
                'rel' => 'shortcut icon',
                'href' => $this->view->baseUrl('favicon.ico'),
            ),
            'APPEND'
        );
        /*$this->view->headLink()->headLink(
            array(
                'rel' => 'apple-touch-icon',
                'href' => $this->view->baseUrl('apple-touch-icon.png'),
            ),
            'APPEND'
        );*/

        //jquery & google analytics
        if (APP_ENV == 'production' && $module != 'admin') {
            $gaq = $settings['ga'];
        	$script = <<<SCRIPT
var _gaq=[['_setAccount','$gaq'],['_trackPageview']];
(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
s.parentNode.insertBefore(g,s)}(document,'script'));
SCRIPT;

        	$this->view->inlineScript()->appendScript($script);
        }

        $this->view->headTitle()->setSeparator(' | ');
    }

    /**
     * post dispatch
     *
     * @return void
     */
    public function postDispatch()
    {
        //layout
        $options = array(
            'layout' => $this->getLayoutName(),
            'layoutPath' => LAYOUT_PATH,
            'contentKey' => 'content',
            'view' => $this->view,
        );
        $layout = Zend_Layout::getMvcInstance();
        $layout->setOptions($options);

        //if AJAX request, no layout, no sidebar
        if ($this->isAjax()) {
            $this->_layout = false;
            $this->_sidebar = false;

            //if POST with AJAX，no view renderer
            if ($this->isPost()) {
                $this->_render = false;
            }
        }

        if ($this->_render === false) {
            $this->getHelper('viewRenderer')->setNoRender();
        }

        if ($this->_layout === false) {
            $this->getHelper('layout')->disableLayout();
        }
    }

    /**
     * disable sidebar
     *
     * @return void
     */
    public function noSideBar()
    {
        $this->_sidebar = false;
    }

    /**
     * disable layout
     *
     * @return void
     */
    public function noLayout()
    {
        $this->_layout = false;
    }

    /**
     * disable view renderer
     *
     * @return void
     */
    public function noRender()
    {
        $this->_render = false;
    }

    /**
     * render blank page, no sidebar, no layout, no view renderer
     *
     * @return void
     */
    public function blankPage()
    {
        $this->_sidebar = false;
        $this->_layout = false;
        $this->_render = false;
    }

    /**
     * is POST request?
     *
     * @return  boolean
     */
    public function isPost()
    {
        return (bool) $this->_request->isPost();
    }

    /**
     * is GET request?
     *
     * @return  boolean
     */
    public function isGet()
    {
        return (bool) $this->_request->isGet();
    }

    /**
     * is AJAX request?
     *
     * @return boolean
     */
    public function isAjax()
    {
        return (bool) $this->_request->isXmlHttpRequest();
    }

    /**
     * set layout name
     *
     * @param   string      $name
     * @return  void
     */
    public function setLayoutName($name = null)
    {
        $this->_layoutName = (null === $name) ? 'main' : $name;
    }

    /**
     * get layout name
     *
     * @return string
     */
    public function getLayoutName()
    {
        if (null == $this->_layoutName) {
            $this->setLayoutName();
        }
        return $this->_layoutName;
    }

    /**
     * set sidebar name
     *
     * @param string $name
     */
    public function setSidebarName($name = null)
    {
        $this->_sidebarName = (null === $name) ? 'sidebar.phtml' : $name;
    }

    /**
     * get sidebar name
     *
     * @return string
     */
    public function getSidebarName()
    {
        if (null === $this->_sidebarName) {
            $this->setSidebarName();
        }
        return $this->_sidebarName;
    }

    /**
     * initial pagination
     *
     * @param mixed $result
     * @return Zend_Paginator
     */
    public function pagination($result)
    {
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $paginator->setView($this->view);

        return $paginator;
    }
}