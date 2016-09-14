<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
// 聊天室 模型
class talk_model extends CI_Model
{
    function __construct ()
    {
        parent::__construct();
    }
    
    // 获取最新的一条 聊天信息
    function latest_one ($table, $time)
    {
    	$data = array('addtime'=>'', 'title'=>'', 'number'=>0);
    	$table = "fly_talk_{$table}";
    	
    	$query = $this->db->query("select addtime,message,thumb,audio from $table order by id DESC limit 1");
		$value = $query->row_array();
		if($value) {
			$data['addtime'] = timeFromNow($value['addtime']);
			if($value['message']) $data['title'] = $value['message'];
			if($value['thumb']) $data['title'] = '图片信息';
			if($value['audio']) $data['title'] = '语音信息';
		}
		if($time) {
			$data['number'] = $this->get_count($table,"addtime>$time");
		}		
		
		return $data;
    }
    
    // 获取一个群的信息
    function get_group ($type)
    {
    	$this->load->driver('cache',array('adapter'=>'file'));
    	return $this->cache->get('group_'.$type);   	
    }
    
    // 获取一个群的信息
    function group_save ($type, $data)
    {
    	$groupname = 'group_'.$_GET['type'];    	
    	$this->cache->save($groupname, $data, TENYEARTIME);
    }
    
    /**
     * 根据条件，获取记录条数
     *
     * @param string $where
     * @return array 二维数组
     */
    function get_count($table, $where = '') {
    	$wheresql = '';
    	if($where) {
    		$wheresql = "WHERE $where";
    	}
    	$query = $this->db->query ( "SELECT COUNT(*) AS num FROM $table $wheresql" );
    	$value = $query->row_array ();
    	return $value ['num'];
    }
    
    
    
}
