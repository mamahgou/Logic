<?php
/**
 * Bootstap
 *
 * @author ben
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Zend_Controller_Front
	 *
	 * @var Zend_Controller_Front
	 */
	protected $_fontController;

	/**
	 * configuration array
	 *
	 * @var array
	 */
	protected $_config;

	/**
	 * Zend_Cache_Core
	 *
	 * @var Zend_Cache_Core
	 */
	protected $_cache;

	/**
	 * ACL
	 *
	 * @var Logic_Acl
	 */
	protected $_acl;

	/**
	 * router
	 *
	 * @var Zend_Controller_Router_Abstract
	 */
	protected $_router;


	/**
	 * constructor
	 *
	 * @param  Zend_Application|Zend_Application_Bootstrap_Bootstrapper $application
	 * @return void
	 */
	public function __construct($application)
	{
		parent::__construct($application);

		$this->_config = $this->getOptions();
	}


	/**
	 * set default time zone
	 *
	 * @return void
	 */
	protected function _initTimeZone()
	{
		if (isset($this->_config['configuration']['timezone'])) {
			date_default_timezone_set($this->_config['configuration']['timezone']);
		}
	}

	/**
     * initial session
     *
     * @return void
     */
    protected function _initSession()
    {
        Zend_Session::start($this->_config['configuration']['session']);
    }

	/**
     * initialize Zend_Cache
     *
     * @return  Zend_Cache_Core
     */
    protected function _initCache()
    {
        $frontend = $this->_config['configuration']['cache']['frontend'];
        $backend = $this->_config['configuration']['cache']['backend'];
        if (empty($backend['cache_dir'])) {
        	$backend['cache_dir'] = CACHE_DIR;
        }
        //$this->_cache = Zend_Cache::factory('Core', 'Memcached', $frontend, $backend);
        $this->_cache = Zend_Cache::factory('Core', 'File', $frontend, $backend);
        Zend_Registry::set('cache', $this->_cache);
        return $this->_cache;
    }

	/**
     * initial error log
     *
     * @return Zend_Log
     */
    protected function _initLog()
    {
        $log = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream(LOG_PATH . DS . TODAY . '.log');
        $format = '%timestamp% [%priorityName%] : %message%' . PHP_EOL;
        $writer->setFormatter(new Zend_Log_Formatter_Simple($format));
        $log->addWriter($writer);
        Zend_Registry::set('log', $log);
        return $log;
    }

	/**
     * initial Zend_Db_Adapter
     *
     * @return Zend_Db_Adapter_Abstract
     */
    protected function _initDb()
    {
    	$options = $this->_config['configuration']['db']['params'] + array(
            'driver_options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;'
            )
        );

        $db = Zend_Db::factory(
            $this->_config['configuration']['db']['adapter'],
            $options
        );

        Zend_Registry::set('db', $db);
        return $db;
    }

	/**
     * initial layout
     *
     * @return Zend_Layout
     */
    protected function _initLayout()
    {
        $layout = Zend_Layout::startMvc();
        return $layout;
    }

	/**
     * initial Zend_View & Zend_Controller_Action_Helper_ViewRenderer
     *
     * @uses Zend_Controller_Action_HelperBroker
     * @return Zend_View_Abstract
     */
    protected function _initView()
    {
        $options = $this->_config['configuration']['view'];
        $view = new Zend_View($options);
        if (isset($options['doctype'])) {
            $view->doctype()->setDoctype(strtoupper($options['doctype']));
            if (isset($options['charset']) && $view->doctype()->isHtml5()) {
                $view->headMeta()->setCharset($options['charset']);
            }
        }
        if (isset($options['contentType'])) {
            $view->headMeta()->appendHttpEquiv('Content-Type', $options['contentType']);
        }

        $view->addHelperPath(LIB_PATH . '/Logic/View/Helper', 'Logic_View_Helper')
             ->addHelperPath(LIB_PATH . '/ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        return $view;
    }

	/**
     * initial Logic_Acl
     *
     * @return Logic_Acl
     */
    protected function _initAcl()
    {
    	$this->_acl = new Logic_Acl();
        Zend_Registry::set('acl', $this->_acl);
        return $this->_acl;
    }

	/**
     * initial router
     *
     * @return Zend_Controller_Router_Rewrite
     */
    protected function _initRouter()
    {
        $this->_router = new Zend_Controller_Router_Rewrite();
        $config = new Zend_Config_Ini(CONFIG_PATH . DS . 'router.ini', APP_ENV);
        $this->_router->addConfig($config->router);
        return $this->_router;
    }

	/**
     * initial Zend_Controller_Front instance
     *
     * @uses Logic_Controller_Plugin_Auth
     * @uses Logic_Controller_Plugin_Layout
     * @return Zend_Controller_Front
     */
    protected function _initFrontController()
    {
    	if (null === $this->_fontController) {
            $this->_fontController = Zend_Controller_Front::getInstance();
        }
        $auth = Zend_Auth::getInstance();
        $this->_fontController->addModuleDirectory(MODULES_PATH)
            ->setRouter($this->_router)
            ->registerPlugin(new Logic_Controller_Plugin_Auth($auth, $this->_acl))
            ->registerPlugin(new Logic_Controller_Plugin_Layout())
            ->setParam('env', APP_ENV)
            ->throwExceptions(false);

        return $this->_fontController;
    }

}

