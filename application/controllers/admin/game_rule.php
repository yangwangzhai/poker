<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 管理员  控制器 by tangjian 

include 'content.php';

class game_rule extends Content
{
	private $zlkey = '123456';
	private $zlapp_id = '123456';   //111111
	
	private $url_appid = 'wxaa13dc461510723a';//'wxb22508fbae4f4ef4'; //wx5442329a3bf072a0
	private $yrurl = 'wx.zhenlong.wang';  //生产环境    wx93024a4137666ab3
	//private $yrurl = 'zl.haiyunzy.com';  //测试环境   wxb22508fbae4f4ef4
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'admin';
        $this->baseurl = 'index.php?d=admin&c=game_rule';
        $this->table = 'zy_game_rule';
        $this->list_view = 'game_rule';       
    }
    


    public function index(){
        $rank_sql = 'select * FROM '.$this->table.' ORDER BY dog,yandou  ';
        $query = $this->db->query( $rank_sql );
        $result = $query->result_array();          
		
		$data['dog2'] = array();
		$data['dog3'] = array();
		$data['dog4'] = array();
		$data['dog5'] = array();
		foreach($result as $val){
			array_push($data['dog'.$val['dog']] ,$val);			
		}	
		$data['status'] = 0;
		if(!empty($result))	$data['status'] = $result[0]['status'];
		
		$_SESSION['url_forward'] =  $this->baseurl ;
        $this->load->view('admin/' . $this->list_view, $data);
    }
	
	
	
	function save(){
		$this->db->query(' truncate ' . $this->table);
		$value = $_POST['value'];
		$status = 0;
		if(isset($_POST['status']) && $_POST['status'] == 1)$status = 1;
		foreach($value as $key => $val){
			$dog = $key ;
			$data = array();
			foreach($val as $v){
				$data['dog'] = $dog;
				$data['yandou'] = $v['yandou'];
				$data['gailv'] = $v['gailv'];
				$data['status'] = $status;
				$data['addtime'] = time();
				if(!empty($data['yandou'])  && $data['gailv'] >=0 && $data['gailv'] != ''){
					 $this->db->insert($this->table,$data) . '<br>';
				}
			}
		}
		
		show_msg('设置成功！', $_SESSION['url_forward']);
	}
	
	
	
	

}
