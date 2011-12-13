<?php

class Logic_Auth_Default implements Zend_Auth_Adapter_Interface
{
    /**
     * Db Adapter
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private $_db = null;

    /**
     * auth identity result
     *
     * @var array
     */
    private $_result = array();

    /**
     * account name
     *
     * @var string
     */
    private $_account = null;

    /**
     * password
     *
     * @var string
     */
    private $_password = null;

    /**
     * treatment for password
     *
     * @var string
     */
    private $_treatment = null;

    /**
     * construct
     *
     * @param   Zend_Db_Adapter_Abstract    $db
     * @return  void
     */
    public function __construct(Zend_Db_Adapter_Abstract $db)
    {
        $this->_db = $db;
        $this->_result = array(
            'code' => Zend_Auth_Result::FAILURE,
            'identity' => null,
            'messages' => array(
                '帳號或密碼錯誤'
            )
        );
    }

    /**
     * authenticate
     *
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        //get info from db
        $info = $this->_getInfo();

        if ($info['credential'] == '1') { //認證通過
            if ($info['status'] == '1') { //帳號狀態 enable
                $this->_result['code'] = Zend_Auth_Result::SUCCESS;
                $this->_result['identity'] = array(
                    'id' => $info['id'],
                    'account' => $info['account'],
                    'name' => $info['name'],
                    'role' => 'member'
                );
                $this->result['messages'][] = '認證成功';
                //update last login time
                $this->_updateLoginTime($info['id']);
            } else { //帳號狀態 disable
                $this->_result['code'] = Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS;
                $this->result['messages'][] = '帳號目前停用中';
            }
        } else { //認證失敗
            $this->_result['code'] = Zend_Auth_Result::FAILURE;
            $this->result['messages'][] = '帳號或密碼錯誤';
        }

        return new Zend_Auth_Result($this->_result['code'], $this->_result['identity'], $this->_result['messages']);
    }

    /**
     * set account for authenticate
     *
     * @param   string      $account
     * @return  Logic_Auth_Default
     */
    public function setAccount($account)
    {
        $this->_account = $account;
        return $this;
    }

    /**
     * set password for authenticate
     *
     * @param   string      $password
     * @return  Logic_Auth_Default
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }

    /**
     * set treatment for passwrod
     *
     * EXAMPLE :
     * MD5(?)
     * PASSWORD(?)
     *
     * @param   string
     * @return  Logic_Auth_Admin
     */
    public function setTreatment($treatment)
    {
        $this->_treatment = $treatment;
        return $this;
    }

    /**
     * get operator info from db
     *
     * @return array
     */
    private function _getInfo()
    {
        if (empty($this->_treatment) || (strpos($this->_treatment, "?") === false)) {
            $this->_treatment = '?';
        }

        $credential = '(CASE WHEN ' . $this->_db->quoteInto('`password` = ' . $this->_treatment, $this->_password)
                    . ' THEN 1 ELSE 0 END) AS ' . $this->_db->quoteIdentifier('credential');
        $select = $this->_db->select()
            ->from('member', array('*', new Zend_Db_Expr($credential)))
            ->where('LOWER(`account`) = ?', strtolower($this->_account));
        return $this->_db->fetchRow($select);
    }

    /**
     * get authenticate result
     *
     * @return array
     */
    public function getAuthResult()
    {
        return $this->_result['identity'];
    }

    /**
     * update opertor last login time
     *
     * @param   integer     $id
     * @return  boolean
     */
    private function _updateLoginTime($id)
    {
        try {
            $this->_db->update(
                'member',
                array('last_login' => date('c')),
                "id = '{$id}'"
            );
        } catch (Zend_Db_Exception $e) {
            throw $e;
        }
        return true;
    }
}