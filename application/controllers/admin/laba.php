<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 管理员  控制器 by tangjian 

include 'content.php';

class laba extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'laba';
        $this->baseurl = 'index.php?d=admin&c=laba';
        $this->table = 'zy_xlb'; 
        $this->list_view = 'laba_list';   
        $this->add_view = 'laba_add';
    }
    


    public function index(){
		$keywords = trim($_REQUEST['keywords']);
        $searchsql = '1';
        
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index&catid=$catid";
        } else {
            $searchsql .= " AND (content like '%{$keywords}%' )";
            
            $config['base_url'] = $this->baseurl ."&m=index&catid=$catid&keywords=" . rawurlencode($keywords);
        }


        $query = $this->db->query(
            "SELECT COUNT(*) AS num FROM $this->table WHERE $searchsql ");

        $count = $query->row_array();
        $data['count'] = $count['num'];
        $config['total_rows'] = $count['num'];
        $config['per_page'] = 20;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $offset = $_GET['per_page'] ? intval($_GET['per_page']) : 0;
        $per_page = $config['per_page'];

        $rank_sql = 'select * FROM '.$this->table.' where '.$searchsql.'  ORDER BY id DESC limit '. $offset . ',20';
        $query = $this->db->query( $rank_sql );
        $result = $query->result_array();   
        

        $data['list'] = $result;
        $data['offset'] = $offset;
        $this->load->view('admin/' . $this->list_view, $data);
    
    }

    public function add(){
        $this->load->view('admin/'.$this->add_view);
    }

    public function edit(){
        $id = intval($_GET['id']);
        // 这条信息
        $query = $this->db->get_where($this->table, 'id = ' . $id, 1);
        $value = $query->row_array();
        $data['value'] = $value;
        
        $data['id'] = $id;
        $this->load->view('admin/'.$this->add_view,$data);
    }


    // 保存 添加和修改都是在这里
    public function save ()
    {
        $id = intval($_POST['id']);
        $data = trims($_POST['value']);
        
        
        
        if ($id) { // 修改 ===========
            $this->db->where('id', $id);
            $query = $this->db->update($this->table, $data);
            show_msg('修改成功！',$this->baseurl);
        } else { // ===========添加 ===========
            $data['addtime'] = time();
            $query = $this->db->insert($this->table, $data);
            show_msg('添加成功！',$this->baseurl);
        }
    }
    
    // 删除
    public function delete ()
    {
        $id = $_GET['id'];
        
        if ($id) {
            $this->db->query("delete from $this->table where id=$id");
        } else {
            $ids = implode(",", $_POST['delete']);
            $this->db->query("delete from $this->table where id in ($ids)");
        }
        
        show_msg('删除成功！',$this->baseurl); //comment-------------
    }

    public function changeStatus(){
        $id = intval($_GET['id']);
        $status = intval($_GET['status']);
        $this->db->where('id',$id);
        $this->db->update($this->table,array('status'=>!$status));
        
        if($this->db->affected_rows()){
            echo 1;
        }else{
            echo 0;
        }
    }
	
}
