<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Custom_model extends CI_Model {
	public function __construct() {
		parent::__construct();

	}

	/**
	 * getRows() get multiple rows as per given parameter
	 * @param  string $table       table name
	 * @param  array  $where       where condition
	 * @param  array  $or_where    or where condition
	 * @param  string $group_by    group by column name
	 * @param  string $columns     commaseperated columns
	 * @param  string $result_type object or array
	 *
	 * @returns array
	 */
	public function getRows($table, $where = array(), $or_where = array(), $group_by = NULL, $columns = "*", $result_type = "object") {

		$this->db->select($columns);
		$this->db->where($where);
		if (is_array($or_where)) {
			$this->db->or_where($or_where);
		}
		if (!is_null($group_by)) {
			$this->db->group_by($group_by);
		}
		$query = $this->db->get($table);
		if ($result_type == "array") {
			return $query->result_array();
		} else {
			return $query->result();
		}
		return $query->result();
	}

	/**
	 * getRowsSorted() get sorting rows
	 * @param  string $table       table name
	 * @param  array  $where       where condition
	 * @param  array  $or_where    or_where condition
	 * @param  string $sort_column column name
	 * @param  string $sort        order ASC/DESC
	 * @param  int $limit          limits
	 * @return array
	 */
	public function getRowsSorted($table, $where = array(), $or_where = array(), $sort_column = "id", $sort = "ASC", $limit = "") {
		
		$this->db->select("*");
		$this->db->where($where);
		if (is_array($or_where)) {
			$this->db->or_where($or_where);
		}
		if ($limit != "") {
			$this->db->limit($limit);
		}
		$this->db->order_by($sort_column . ' ' . $sort);
		$query = $this->db->get($table);
		return $query->result();
	}

	/**
	 * getRowsWhereInLike() get rows as per given parameter
	 * @param  [type] $table           table name
	 * @param  array  $where           condition array
	 * @param  string $in_method       where_in() method of db_query_builder
	 * @param  string $where_in_column column name which is used find data with where_in()
	 * @param  array  $where_in        The values searched on
	 * @param  string $like_column     like column name
	 * @param  string $like_value      like value searched on
	 * @param  array  $or_where        or condition array
	 * @param  string $columns         comma seperated values
	 * @param  string $result_type     type of result object/array
	 * @return array
	 */
	public function getRowsWhereInLike($table, $where = array(), $in_method = "where_in", $where_in_column = "id", $where_in = array(), $like_column = "id", $like_value = "", $or_where = array(), $columns = "*", $result_type = "object") {

		$this->db->select($columns);
		$this->db->where($where);
		if (!empty($where_in)) {
			$this->db->$in_method($where_in_column, $where_in);
		}
		if (is_array($or_where)) {
			$this->db->or_where($or_where);
		}
		if (!empty($like_value)) {
			$this->db->like($like_column, $like_value);
		}
		$query = $this->db->get($table);
		if ($result_type == "array") {
			return $query->result_array();
		} else {
			return $query->result();
		}
		return $query->result();
	}

	/**
	 * getRowsWhereJoin() get rows with join tables
	 * @param  string $table         table name
	 * @param  array $where          where array condition
	 * @param  array $join           join table name
	 * @param  array $join_condition join condition
	 * @return array                 [description]
	 */
	public function getRowsWhereJoin($table, $where, $join, $join_condition) {
		$this->db->select(" * ")->from($table);
		for ($i = 0; $i < count($join); $i++) {
			$this->db->join($join[$i], $join_condition[$i]);
		}
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
		//d($this->db->last_query());
	}

	/**
	 * getDistinctRows() get distinct/unique rows from table
	 * @param  string $table           table name
	 * @param  array  $where           where condition array
	 * @param  array  $or_where        or_where condition array
	 * @param  string $distinct_column column name
	 * @return array
	 */
	public function getDistinctRows($table, $where = array(), $or_where = array(), $distinct_column) {
		$this->db->select("DISTINCT($distinct_column)");
		//$this->db->select("*");
		$this->db->where($where);
		if (is_array($or_where)) {
			$this->db->or_where($or_where);
		}

		$query = $this->db->get($table);

		return $query->result();
	}

	/**
	 * getSingleRow() get single row as per condition
	 * @param  string $table       table name
	 * @param  array  $where       where condition array
	 * @param  string $return_type type of return value
	 * @return array/object
	 */
	public function getSingleRow($table, $where = array(), $return_type = "object") /* get a single row from a table */ {
		$query = $this->db->select("*")->from($table)->where($where)->get();
		// d($this->db->last_query());
		if ($return_type == "array") {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	/**
	 * getTotalCount() get total number of rows
	 * @param  string $table table name
	 * @return number
	 */
	public function getTotalCount($table) {
		/* get total no.of records count of given table */
		return $this->db->count_all_results($table);
	}

	/**
	 * getCount() get count of rows as per where condition
	 * @param  string $table  table name
	 * @param  array  $where  where condition array
	 * @return number
	 */
	public function getCount($table, $where = array()) {
		$query = $this->db->from($table)->where($where)->get();
		return $query->num_rows();
	}

	/**
	 * insert new row into a table
	 * @param  string  $table table name
	 * @param  array   $data  associative array for data
	 * @param  boolean $batch insert batch or single row
	 * @return int/string     successfully insert retuns id else string
	 */
	public function insertRow($table, $data, $batch = false) {
		if ($batch) {
			$result = $this->db->insert_batch($table, $data);
		} else {
			$result = $this->db->insert($table, $data);
		}
		if ($result) {
			return $this->db->insert_id();
		} else {
			return "error";
		}
	}

	/**
	 * update single row
	 * @param  string $table    table name
	 * @param  array  $data     associative array of data
	 * @param  array  $where    condition for row update
	 * @param  array  $or_where condition for row update
	 * @return string
	 */
	public function updateRow($table, $data, $where, $or_where = array()) /* update existing row in a table */ {
		$this->db->where($where);
		$this->db->or_where($or_where);
		$result = $this->db->update($table, $data);
		// d($this->db->last_query());
		if ($result) {
			return "updated";
		} else {
			return "error";
		}
	}

	/**
	 * delete single row
	 * @param  string $table        table name
	 * @param  array  $where        condition array
	 * @param  array  $or_where     or condition array
	 * @return string
	 */
	public function deleteRow($table, $where, $or_where = array()) /* delete a row from table */ {
		$this->db->where($where);
		$this->db->or_where($or_where);
		$result = $this->db->delete($table);
		if ($result) {
			return "deleted";
		} else {
			return "error";
		}
	}

	/**
	 * get single column value
	 * @param  string $table  table name
	 * @param  string $column column name
	 * @param  array $where   condition array
	 * @return string
	 */
	public function getSingleValue($table, $column, $where) /* get single column value from table */ {
		$query = $this->db->select($column)->from($table)->where($where)->get();
		$res = !empty($query->result());
		if ($res) {
			return $query->row()->$column;
		} else {
			return NULL;
		}
	}

	/**
	 * executes custom query
	 * @param  string  $query         custom sql query
	 * @param  boolean $return_result return result or query
	 * @return array/string           depends on $return_result
	 */
	public function customQuery($query, $return_result = false) /* runs custom query*/ {
		$qry = $this->db->query($query);
		if ($return_result) {

			return $this->getResult($qry);
		} else {
			return $qry;
		}

	}

	/**
	 * get result from query
	 * @param  string  $query_result query string
	 * @param  boolean $array        return array or object
	 * @return array                 result array
	 */
	public function getResult($query_result, $array = false) {
		if (!empty($query_result->result())) {
			if ($array) {
				return $query_result->result_array();
			} else {
				return $query_result->result();
			}

		} else {
			return NULL;
		}
	}

	/**
	 * check if row already exist or not
	 * @param  string $table   table name
	 * @param  array  $where   condition array
	 * @return boolean
	 */
	public function checkAvailability($table, $where) {
		$query = $this->db->select("*")->from($table)->where($where)->get();
		$count = $query->num_rows();
		if ($count >= 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * search data from columns with findInset() form multiple/comma separated value
	 * @param  string $table  table name
	 * @param  string $column column name
	 * @param  string $value  searchable value
	 * @param  array  $where  condition array
	 * @return array
	 */
	public function findInSet($table, $column, $value, $where = null) {
		$this->db->select("*");
		$this->db->from($table);
		$find_in_set = array(
			"FIND_IN_SET('$value',$column) !=" => "0",
		);
		if ($where) {

			$where = array_merge($where, $find_in_set);
		} else {
			$where = $find_in_set;
		}
		$this->db->where($where);
		$query = $this->db->get();
		//return $this->db->last_query();
		return $query->result();

	}

	/**
	 * create dropdown option
	 * @param  string $table       table name
	 * @param  array  $columns     columns array
	 * @param  string $caption     caption for first option
	 * @param  array  $separator   separator array
	 * @param  array  $where       condition array
	 * @param  array  $selected    selected values of dropdown option
	 * @param  string $sort_column sorting column name
	 * @param  string $sort        sorting order
	 * @return string
	 */
	public function createDropdownSelect($table, $columns = array(), $caption = "Value", $separator = array(' '), $where = null, $selected = array(), $sort_column = "", $sort = "ASC") {
		// d($selected);
		// d($where != null);
		if ($sort_column == "") {
			$sort_column = $columns[1];
		}
		if ($where != null && !is_null($where)) {
			$query = $this->db->select($columns)->from($table)->where($where)->order_by($sort_column . ' ' . $sort)->get();
		} else {
			$query = $this->db->select($columns)->from($table)->order_by($sort_column . ' ' . $sort)->get();
		}
		// echo $this->db->last_query();
		$id = $columns[0];
		unset($columns[0]);
		$rows = $this->getResult($query);
		$drop_options = "";
		if ($caption != "") {
			$drop_options .= "<option value=''>-- Select " . $caption . " --</option>";
		}
		if ($rows) {
			if (!empty($selected)) {
				foreach ($rows as $row) {
					if (in_array($row->$id, $selected)) {
						$drop_options .= "<option value='" . $row->$id . "' selected='selected'>";
						for ($i = 1; $i <= count($columns); $i++) {
							$col_value = $columns[$i];
							$col_sep = $separator[$i - 1];
							$drop_options .= " " . $row->$col_value . " $col_sep";
						}
						$drop_options .= "</option>";
					} else {
						$drop_options .= "<option value='" . $row->$id . "' >";
						for ($i = 1; $i <= count($columns); $i++) {
							$col_value = $columns[$i];
							$col_sep = $separator[$i - 1];
							$drop_options .= " " . $row->$col_value . " $col_sep";
						}
						$drop_options .= "</option>";
					}

				}
			} else {
				foreach ($rows as $row) {
					$drop_options .= "<option value='" . $row->$id . "'>";
					for ($i = 1; $i <= count($columns); $i++) {
						$col_value = $columns[$i];
						$col_sep = $separator[$i - 1];
						$drop_options .= " " . $row->$col_value . " $col_sep";
					}
					$drop_options .= "</option>";
				}
			}
		}
		return $drop_options;
	}

	/**
	 * set auto-increment in table
	 * @param string $table           table name
	 * @param string $table_id        table id
	 * @param int    $increment_value increment value
	 */
	public function setAutoIncrement($table, $table_id, $increment_value = null) {
		$value = 1;
		if ($increment_value == null) {
			$result = $this->db->select_max($table_id, 'value')->get($table)->row();
			if ($result) {
				$value = $result->value + 1;
			}
		} else {
			$value = $increment_value;
		}
		$alterQry = "ALTER TABLE $table AUTO_INCREMENT = $value";
		return $this->customQuery($alterQry);
	}

	/**
	 * get aggregate of single column
	 * @param  aggregate  $aggregate  aggregate method name (avg , max , min , sum)
	 * @param  table  $table  table name
	 * @param  string $column column name
	 * @param  array $where   condition array
	 * @return object
	 */
	public function select_aggregate($aggregate, $table, $column, $where) {
		$aggregate = "select_" . $aggregate;
		$this->db->$aggregate($column);
		$this->db->where($where);
		$sum_result = $this->db->from($table)->get();
		if (!empty($sum_result->result())) {
			return $sum_result->row($column);
		} else {
			return NULL;
		}
	}
}
/* End of file Custom_model.php */
/* Location: ./application/modules/models/Custom_model.php */