<?php

abstract class Logic_Model_Abstract
{
    /**
     * database instance
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;

    /**
     * error log
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     * 分頁的項次
     *
     * @var integer
     */
    protected $_page = 1;

    /**
     * limit for each page
     *
     * @var integer|null
     */
    protected $_limit = null;

    /**
     * table name
     *
     * @var string
     */
    protected $_name;

    /**
     * construct
     *
     * @return  void
     */
    public function __construct()
    {
        $this->_db = Zend_Registry::get('db');
        $this->_log = Zend_Registry::get('log');
    }

    /**
     * get page
     *
     * @return  integer
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * set page
     *
     * @param   integer    $page
     * @return  void
     */
    public function setPage($page)
    {
        $this->_page = (empty($page)) ? 1 : (int) $page;
    }

    /**
     * get limit
     *
     * @return  integer
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * set limit
     *
     * @param   integer|null  $limit
     * @return  void
     */
    public function setLimit($limit)
    {
        $this->_limit = (empty($limit)) ? null : (int) $limit;
    }

    /**
     * set limit & page
     *
     * @param   integer     $page
     * @param   integer     $limit
     * @return  void
     */
    public function setLimitPage($page, $limit)
    {
        $this->setPage($page);
        $this->setLimit($limit);
    }

    /**
     * get db profiler
     *
     * @return  Zend_Db_Profiler
     */
    public function getProfiler()
    {
        return $this->_db->getProfiler();
    }

    /**
     * get row by id
     *
     * @param integer $id
     * @return array|false
     */
    public function get($id)
    {
        $select = $this->_db->select()
            ->from($this->_name, '*')
            ->where('id = ?', $id);
        return $this->_db->fetchRow($select);
    }

    /**
     * create new row
     *
     * @param array $options
     * @return integer
     */
    public function create(array $options)
    {
        try {
            $this->_db->insert($this->_name, $options);
            $id = $this->_db->lastInsertId();
        } catch (Zend_Db_Exception $e) {
            throw $e;
        }
        return $id;
    }

    /**
     * update row
     *
     * @param integer $id
     * @param array $options
     * @return boolean
     */
    public function update($id, array $options)
    {
        try {
            $this->_db->update($this->_name, $options, "id = " . $this->_db->quote($id, Zend_Db::INT_TYPE));
        } catch (Zend_Db_Exception $e) {
            throw $e;
        }
        return true;
    }

    /**
     * delete row
     *
     * @param integer $id
     * @return boolean
     */
    public function delete($id)
    {
        try {
            $this->_db->delete($this->_name, "id = " . $this->_db->quote($id, Zend_Db::INT_TYPE));
        } catch (Zend_Db_Exception $e) {
            throw $e;
        }
        return true;
    }

    /**
     * get all rows
     *
     * @param array|null $filter
     * @param string $order
     * @param string $sort
     * @return false|array
     */
    public function getAll($condition = array(), $order = 'id', $sort = 'desc')
    {
        $select = $this->_db->select()
            ->from($this->_name, '*')
            ->order($order . ' ' . $sort);

        //limit
        if ($this->getLimit()) {
            $select->limitPage($this->_page, $this->_limit);
        }

        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $select->where('`' . $key . '` LIKE ?', '%' . trim($value) . '%');
            }
        }

        return $this->_db->fetchAll($select);
    }
}