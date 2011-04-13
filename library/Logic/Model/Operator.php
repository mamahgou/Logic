<?php

class Logic_Model_Operator extends Logic_Model_Abstract
{
    protected $_name = 'operator';

    /**
     * get row by account
     *
     * @param string $account
     * @return false|array
     */
    public function getByAccount($account)
    {
        $select = $this->_db->select()
            ->from($this->_name, '*')
            ->where('account = ?', $account);

        return $this->_db->fetchRow($select);
    }
}