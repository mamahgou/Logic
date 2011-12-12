<?php
/**
 * Access control list
 *
 * @author ben
 */
class Logic_Acl extends Zend_Acl
{
    /**
     * constructor
     *
     * @return  Logic_Acl
     */
    public function __construct()
    {
        //modules (resource)
        $this->add(new Zend_Acl_Resource('default'));
        $this->add(new Zend_Acl_Resource('admin'));

        //role
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('admin'), 'guest');

        //privilige
        $this->allow('guest', 'default');
        $this->allow('admin');

        //auth, error controller
        $this->allow(null, null, 'auth');
        $this->allow(null, null, 'error');
        $this->allow(null, null, 'upload');
        return $this;
    }
}