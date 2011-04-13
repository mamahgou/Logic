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
        $page = intval($page);
        $this->_page = (empty($page)) ? 1 : $page;
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
        $limit = intval($limit);
        $this->_limit = (empty($limit)) ? null : $limit;
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
     * @param string|null $filter
     * @return false|array
     */
    public function getAll($filter = null)
    {
        $select = $this->_db->select()
            ->from($this->_name, '*')
            ->order('modify_time DESC');

        if (!empty($filter)) {
            $select->where('name LIKE ?', '%' . trim($filter) . '%');
        }

        return $this->_db->fetchAll($select);
    }

    /**
     * get published rows
     *
     * @return array|false
     */
    public function getPublished()
    {
        $select = $this->_db->select()
            ->from($this->_name, '*')
            ->where('is_publish = 1')
            ->order('modify_time DESC');

        return $this->_db->fetchAll($select);
    }

    /**
     * get next sort id
     *
     * @param null|string $categoryId
     * @return integer
     */
    public function getNextSortId($categoryId = null)
    {
        $select = $this->_db->select()
            ->from($this->_name, new Zend_Db_Expr('MAX(`sort_id`) + 1'));

        if ($categoryId) {
            $select->where('category_id = ?', $categoryId);
        }

        $sortId = (int)$this->_db->fetchOne($select);

        if ($sortId < 1) {
            $sortId = 1;
        }

        return $sortId;
    }

    /**
     * order the sorting seq
     *
     * @param integer $id
     * @param integer $position
     * @throws Zend_Db_Exception
     * @return boolean
     */
    public function sort($id, $position)
    {
        //get current sort id
        $select = $this->_db->select()
            ->from($this->_name, '*')
            ->where('id = ?', $id);
        $row = $this->_db->fetchRow($select);

        //consider the relationship of category exist or not
        $withCategory = (isset($row['category_id'])) ? " AND category_id = '{$row['category_id']}'" : '';

        $this->_db->beginTransaction();
        try {
            //upgrade
            if ($row['sort_id'] > $position) {
                $this->_db->update(
                    $this->_name,
                    array('sort_id' => new Zend_Db_Expr('sort_id + 1')),
                    "sort_id >= {$position} AND sort_id <= {$row['sort_id']}" . $withCategory
                );
                $this->_db->update(
                    $this->_name,
                    array('sort_id' => $position),
                    "id = {$id}"
                );
            } else {
                //downgrade
                $this->_db->update(
                    $this->_name,
                    array('sort_id' => new Zend_Db_Expr('sort_id - 1')),
                    "sort_id >= {$row['sort_id']} AND sort_id <= {$position}" . $withCategory
                );
                $this->_db->update(
                    $this->_name,
                    array('sort_id' => $position),
                    "id = {$id}"
                );
            }
            $this->_db->commit();
        } catch (Zend_Db_Exception $e) {
            $this->_db->rollback();
            throw $e;
        }
        return true;
    }
}