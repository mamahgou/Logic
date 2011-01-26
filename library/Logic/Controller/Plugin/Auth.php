<?php
/**
 * auth
 *
 * @author ben
 */
class Logic_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * 認證
     *
     * @var Zend_Auth
     */
    private $_auth;

    /**
     * 權限設定
     *
     * @var Logic_Acl
     */
    private $_acl;

    /**
     * construct
     *
     * @param   Zend_Auth     $auth
     * @param   Logic_Acl       $acl
     */
    public function __construct(Zend_Auth $auth, Logic_Acl $acl)
    {
        $this->_auth = $auth;
        $this->_acl = $acl;
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $controller = $privilege = $request->getControllerName();
        $action = $request->getActionName();
        $module = $resource =$request->getModuleName();

        if (!$this->_acl->has($resource)) {
            $resource = null;
        }

        //如果該頁面不允許瀏覽
        if (!$this->_acl->isAllowed($this->_getRole($module), $resource, $privilege)) {
            //是否登入
            $identiry = $this->_auth->getIdentity();
            if (!isset($identiry[$module])) { //未登入
                $controller = 'auth';
                $action = 'login';
            } else { //已登入但是權限不足
                $controller = 'error';
                $action = 'privilege';
            }
        }
        $request->setModuleName($module);
        $request->setControllerName($controller);
        $request->setActionName($action);
    }

    /**
     * 取得登入角色所使用的「身份」
     *
     * get role from identity
     *
     * @param string $module
     * @return string
     */
    private function _getRole($module)
    {
        $role = 'guest'; //預設為 guest
        if ($this->_auth->hasIdentity()) {
            $identity = $this->_auth->getIdentity();
            if (!isset($identity[$module])) {
            	return strtolower($role);
            }
            $role = $identity[$module]['role'];
        }
        return strtolower($role);
    }
}