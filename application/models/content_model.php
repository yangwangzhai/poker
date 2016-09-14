<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 模型 基类，其他模型需要先继承本类
 */
class Content_model extends CI_Model {
	public $table = ''; // 数据库表名称
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取一条信息
	 *
	 * @param int $id        	
	 * @return array 一维数组
	 */
	function get_one($id) {
		$this->db->where ( 'id', $id );
		$query = $this->db->get ( $this->table, 1 );
		$value = $query->row_array ();		
		return $value;
	}

	/**
	 * 根据条件，获取记录条数
	 *
	 * @param string $where        	
	 * @return array 二维数组
	 */
	function get_count($where = '') {
		$wheresql = '';
		if($where) {
			$wheresql = "WHERE $where";
		}
		$query = $this->db->query ( "SELECT COUNT(*) AS num FROM $this->table $wheresql" );
		$value = $query->row_array ();
		return $value ['num'];
	}

	/**
	 * 获取一组信息
	 *
	 * @param
	 *        	多个参数
	 * @return array 二维数组
	 */
	function get_list($field = '*', $where = '', $offset = 0, $limit = 20) {
		$wheresql = '';
		if($where) {
			$wheresql = "WHERE $where";
		}
		$sql = "SELECT $field FROM $this->table $wheresql ORDER BY id DESC limit $offset,$limit";
		$query = $this->db->query ( $sql );
		$list = $query->result_array ();
		foreach ( $list as &$value ) {			
			if($value ['thumb']) {
				$value ['thumb'] = base_url().new_thumbname ( $value ['thumb']);
			}
		}
		return $list;
	}

	/**
	 * 获取一组信息
	 *
	 * @param array $data        	
	 * @return array 二维数组
	 */
	function insert($data) {
		$query = $this->db->insert ( $this->table, $data );
		return $this->db->affected_rows ();
	}

	/**
	 * 删除一条或多条信息
	 *
	 * @param mix $ids
	 *        	整数或者数组
	 * @return array 二维数组
	 */
	function delete($ids) {
		if (is_numeric ( $ids )) {
			$this->db->query ( "delete from $this->table where id=$ids" );
		} else {
			$ids = implode ( ",", $ids );
			$this->db->query ( "delete from $this->table where id in ($ids)" );
		}
	}

	/**
	 * 获取一组信息
	 *
	 * @param int $id        	
	 * @return array 二维数组
	 */
	function update($data, $id) {
		if (empty ( $id ))
			return 0;
		
		$this->db->where ( 'id', $id );
		$query = $this->db->update ( $this->table, $data );
		return $this->db->affected_rows ();
	}

	/**
	 * 更新 访问量
	 *
	 * @param int $id        	
	 * @return array 二维数组
	 */
	function update_visits($id) {
		if ($id == 0)
			return false;
		$query = $this->db->query ( "update $this->table set visits=visits+1 where id='$id' limit 1" );
	}

	/**
	 * 更新 访问量
	 *
	 * @param int $id        	
	 * @param int $status        	
	 * @return array 二维数组
	 */
	function update_status($id, $status) {
		if ($id == 0)
			return false;
		$query = $this->db->query ( "update $this->table set status='$status' where id='$id' limit 1" );
	}
}
