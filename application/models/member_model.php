<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
// 会员
include 'content_model.php';
class member_model extends content_model
{
    function __construct ()
    {
        parent::__construct();    
		
        $this->table = 'fly_member';
    }
	
    // 数据加上会员信息， 头像 昵称，返回二维数组
    function append_list($list, $uid = 'uid') {
    	foreach($list as $row) {
    		if(!empty($row[$uid])) $ids[] = $row[$uid];
    	}
    
    	// 获取 注册会员的昵称
    	$memberlist = getMemberNickname($ids);
    	 
    	foreach($list as &$row) {
    		$row['member_thumb'] = '';
    		$row['nickname'] = '游客';
    		foreach($memberlist as $member) {
    			if($row[$uid] == $member[id]) {
    				$row['nickname'] = $member[nickname];
    				$row['member_thumb'] = base_url().new_thumbname($member['avatar'],100,100);
    			}
    		}
    	}
    	
    	return $list;
    }
    
    // 添加会员的 昵称和头像
    public function append_one ($value)
    {
    	$query = $this->db->query("SELECT nickname,avatar FROM $this->table where id='$value[uid]' limit 1");
    	$member = $query->row_array();
    	if(!empty($member)) {
    		$value['nickname'] = $member['nickname'];
    		$value['member_thumb'] = base_url().new_thumbname($member['avatar'],100,100);
    	}
    	return $value;
    }
    
    // 根据群 获取会员列表
    public function list_by_groupname ($groupname)
    {    	
    	$query = $this->db->query("SELECT id,nickname,avatar FROM $this->table where status=1 and groupname='$groupname' ORDER BY sort, id DESC limit 100");
    	$list = $query->result_array();    	
    	return $list;
    }
    
}
