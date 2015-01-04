<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Родитель для классов словарика
 */
class Dict_model extends CI_Model {

    /** @var string */
    protected $tbl;

    /** @var null */
    protected $_tableName = null;

    /** @var string */
    protected $_order_by = 'name';

    /** @var string  */
    protected $_filed_id = 'id';

    /** @var null */
    protected $_depends = null;

    public function __construct() {
        parent::__construct();
        if (!is_null($this->_tableName)) {
            $this->tbl = $this->db->dbprefix($this->_tableName);
        }

        if (!is_null($this->_depends)) {
            $this->load->model($this->_depends);
        }
    }

    /**
     * @param array $conditions
     */
    protected function _applyConditions($conditions = array()) {
        if (!empty($conditions)) {
            foreach($conditions as $key => $value) {
                if (is_null($value)) {
                    $this->db->where($key);
                } else {
                    if (is_array($value)) {
                        $this->db->where_in($key, $value);
                    } else {
                        $this->db->where($key, $value);
                    }
                }
            }
        }
    }

    /**
     * @param array $newData
     * @return int
     */
    public function create($newData = array()) {
        $this->db->insert($this->tbl, $newData);
        return $this->db->insert_id();
    }

    /**
     * @param $id
     * @param array $newData
     * @return mixed
     */
    public function update($id, $newData = array()) {
        $this->db->where($this->_filed_id, $id);
        return $this->db->update($this->tbl, $newData);
    }

    /**
     * @param $newData
     * @param array $conditions
     * @return bool
     */
    public function updateList($newData, $conditions = array()) {
        $this->_applyConditions($conditions);
        return (boolean) $this->db->update($this->tbl, $newData);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return (boolean) $this->db->delete($this->tbl);
    }

    /**
     * @param array $conditions
     * @return bool
     */
    public function deleteByConditions($conditions = array()) {
        $this->_applyConditions($conditions);
        return (boolean) $this->db->delete($this->tbl);
    }

    /**
     * @param $id
     * @param array $entities
     * @return mixed
     */
    public function get($id, $entities = array()) {
        $this->db->where($this->_filed_id, $id);
        $row = $this->db->get($this->tbl, 1)->row_array();

        if (!empty($row) && !empty($entities)) {
            $rows = $this->_setEntities(array($row), $entities);
            return array_shift($rows);
        }

        return $row;
    }

    /**
     * @param $conditions
     * @return mixed
     */
    public function getByCondition($conditions) {
        $this->_applyConditions($conditions);
        return $this->db->get($this->tbl, 1)->row_array();
    }

    /**
     * @param $ids
     * @return array|bool
     */
    public function getByIds($ids) {
        $ids = array_unique($ids);
        sort($ids);

        $rows = $this->getList($this->_filed_id . ' ASC', array($this->_filed_id => $ids));

        if (!empty($rows)) {
            $return = array();
            foreach ($rows as $row) {
                $return[$row[$this->_filed_id]] = $row;
            }

            return $return;
        }

        return false;
    }

    /**
     * @param bool $ordering
     * @param array $conditions
     * @param bool $limit
     * @param int $offset
     * @param array $entities
     * @return mixed
     */
    public function getList($ordering = false, $conditions = array(), $limit = false, $offset = 0, $entities = array()) {
        if ($ordering) {
            $this->db->order_by($ordering);
        } else {
            $this->db->order_by($this->_order_by);
        }

        $this->_applyConditions($conditions);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $rows = $this->db->get($this->tbl)->result_array();

        return $this->_setEntities($rows, $entities);
    }

    /**
     * @param $column
     * @param array $conditions
     * @return array
     */
    public function getColumn($column, $conditions = array()) {
        $this->db->select($column);
        $this->_applyConditions($conditions);
        $rows = $this->db->get($this->tbl)->result_array();
        return get_array_column($column, (array) $rows);
    }

    /**
     * @param $rows
     * @param array $entities
     * @return mixed
     */
    protected function _setEntities($rows, $entities = array()) {

        return $rows;
    }

    /**
     * @param array $conditions
     * @return int
     */
    public function getCount($conditions = array()) {
        $this->_applyConditions($conditions);
        return (int) $this->db->count_all_results($this->tbl);
    }

    /**
     * @return string
     */
    public function getTable() {
        return $this->tbl;
    }

}