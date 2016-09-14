<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
// 聊天室 模型
class groups_model extends CI_Model
{
    function __construct ()
    {
        parent::__construct();
        $this->load->helper('url');
    }
	
    // 获取群列表，首页用，显示未查看聊天数
    function lists ()
    {
    	$result = array();
    	$query = $this->db->query("select id,title from  fly_groups order by id limit 200");
    	$list = $query->result_array();
    	foreach($list as $value) {
    		$result[$value['id']] = $value['title'];
    	}
    	
    	return $result;
    }
    
    
    // 获取群列表，首页用，显示未查看聊天数  
    function groups_list_home ($uid)
    {
    	
//     	$query = $this->db->query("select id,title,keywords,thumb,last_time,last_title from fly_groups where hot=1 ORDER BY type desc,last_time desc limit 30");
    	if( $uid > 0 ){
			$query = $this->db->query("select id,title,keywords,thumb,last_time,last_title,uid,type,url,isbase64,status from fly_groups where hot=1 AND (status=1 OR uid=$uid )  ORDER BY sort,last_time desc limit 100");
		}else{				
			$query = $this->db->query("select id,title,keywords,thumb,last_time,last_title,uid,type,url,isbase64,status from fly_groups where hot=1 AND status=1 ORDER BY sort,last_time desc limit 100");
				}
		
    	$list = $query->result_array();
    	foreach($list as &$value) {
			if($value['isbase64'] == 1) {
				$value['last_title'] = base64_decode($value['last_title']);				
				}
    		$value['last_time'] = timeFromNow($value['last_time']);
    		if(empty($value['last_time'])) {
    			$value['last_time'] = '';
    		}
    		$value['group_tag'] = 'group'.$value['id'];   
    		$value['thumb'] = base_url().$value['thumb'];
    	}
    	
    	return $list;
    }
    
    // 获取一个群的id
    function get_groupid($title)
    {
    	$query = $this->db->query("select id from fly_groups where title='$title' limit 1");
    	$value = $query->row_array();    	
    	return intval($value['id']);
    }
    
    // 获取一个群的信息
    function groups_detail($id)
    {
    	$query = $this->db->query("select * from fly_groups where id='$id' limit 1");
    	$value = $query->row_array();
    	$value['group_tag'] = 'group'.$value['id'];
    	return $value;
    }
    
    // 获取群对应的主播id (由于使用模糊匹配会员昵称，有可能匹配不到会员id的情况)
    function memberid_for_group($groupid) { 	
    	$query = $this->db->query("select title from fly_groups where id='$groupid' limit 1");
    	$value = $query->row_array();
    	$grouptitle = $value['title'];
    	$query = $this->db->query("select id from fly_member where nickname like '%$grouptitle%' limit 1");
    	$value = $query->row_array();
    	$uid = $value['id'];
    	return $uid;
    }
    
    // 修改群 比如 最后发言人  发言时间
    function groups_edit ($id, $data)
    {
    	$this->db->where('id', $id);
    	$this->db->set($data);    	
    	$this->db->update('fly_groups');
    }
    
    
    /**
     * 未查看聊天数
     *
     * @param string $where
     * @return array 二维数组
     */
    function chats_noread($groupid, $last_time) {    	
    	$query = $this->db->query ( "SELECT COUNT(*) AS num FROM fly_groups_chats where addtime>$last_time" );
    	$value = $query->row_array ();
    	return $value ['num'];
    }
    
    
    /**
     * 群聊信息保存, 同时修改群信息
     *
     * @param array $data
     * @return int 二$insert_id数组
     */
    function chats_save($data) {
    	//require_once('badword.php');
    	$data['title'] = mb_substr($data['title'], 0, 390);
		$old_title = $data['title'];
		$data['title'] = base64_encode( $data['title'] );
		$data['isbase64'] = 1;
    	//过滤敏感词
    	//$badword1 = array_combine($badword,array_fill(0,count($badword),'***'));
    	//$data['title'] = strtr($data['title'], $badword1);
    		
    	// 保存到群聊表
    	$this->db->insert ( 'fly_groups_chats', $data);
    	$insert_id = $this->db->insert_id();
    	
    	// 修改群  最后发布时间
    	$value['last_time'] = $data['addtime'];
    	if($data['title']) $value['last_title'] = base64_encode( mb_substr($old_title, 0, 100) );
    	if($data['thumb'] && empty($data['title'])) $value['last_title'] = base64_encode('图片信息');
    	if($data['audio']) $value['last_title'] = base64_encode('语音信息');
		$value['isbase64'] = 1;
    	$this->groups_edit($data['groupid'], $value);
    	
    	// 推送
    	$xinge = array (
    			'title' => $value['last_title'], 
    			'addtime' => $value['addtime'],
    			'group_tag'=> 'group'.$data ['groupid'],
    			'uid' => $data['uid'],
    			'id' => $insert_id);
    	$this->load->model('xinge_model');
    	$return = $this->xinge_model->xinge_group($xinge);
    	
    	return $insert_id;
    }
    
    /**
     * 检查微博是否已经保存过，没有的就保存起来
     *
     * @param int $id
     * @return boolean
     */
    function check_weibo($id) {
    	$result = true;
    	
    	$query = $this->db->query ( 'select id from fly_weibo where id='.$id.' limit 1');
    	$value = $query->row_array();
    	if(empty($value)) {
    		$this->db->insert('fly_weibo', array('id'=>$id));
    		$result = false;
    	}
    	
    	return $result;
    }
    
    /**
     * 检查微博是否已经保存过
     *
     * @param int $id
     * @return boolean
     */
    function check_weibo_exists($id) {
    	$result = true;
    	
    	$query = $this->db->query ( 'select id from fly_weibo where id='.$id.' limit 1');
    	$value = $query->row_array();
    	if(empty($value)) {
    		$result = false;
    	}
    	
    	return $result;
    }

    // 获取一条群聊的信息
    function chats_detail($id)
    {
    	$query = $this->db->query("select * from fly_groups_chats where id='$id' limit 1");
    	$value = $query->row_array();  
		if($value['isbase64'] == 1) $value['title'] = base64_decode($value['title']);	
    	$value = $this->member_model->append_one($value);
    	return $value;
    }

    // 删除一条群聊的信息
    function chats_delete($id)
    {
    	$this->db->where('id', $id);
    	$this->db->delete('fly_groups_chats');
    	$affect_rows =  $this->db->affected_rows();
    	return $affect_rows;
    }
}
