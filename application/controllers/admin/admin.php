<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 管理员  控制器 by tangjian 

include 'content.php';

class admin extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'admin';
        $this->baseurl = 'index.php?d=admin&c=admin';
        $this->table = 'zy_admin';
        $this->list_view = 'admin_list';
        $this->add_view = 'admin_add';
    }
    
    // 首页
    public function index ()
    {
        $catid = intval($_REQUEST['catid']);
        $keywords = trim($_REQUEST['keywords']);
        $searchsql = '1';
        //         if ($catid) {
        //             $searchsql .= " AND catid=$catid ";
        //         }
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index&catid=$catid";
        } else {
        $searchsql .= " AND (username like '%{$keywords}%' OR truename like '%{$keywords}%' OR tel like '%{$keywords}%')";
        $config['base_url'] = $this->baseurl ."&m=index&catid=$catid&keywords=" . rawurlencode($keywords);
        }
        
        $data['list'] = array();
        $query = $this->db->query(
        "SELECT COUNT(*) AS num FROM $this->table WHERE  $searchsql");
        $count = $query->row_array();
        $data['count'] = $count['num'];
        $this->load->library('pagination');
        
        $config['total_rows'] = $count['num'];
        $config['per_page'] = 20;
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $offset = $_GET['per_page'] ? intval($_GET['per_page']) : 0;
        $per_page = $config['per_page'];
        $sql = "SELECT * FROM $this->table WHERE  $searchsql ORDER BY id ASC limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['list'] = $query->result_array();
        $data['catid'] = $catid;
        $data['group'] = $this->config->item('group');
    
        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";
        $this->load->view('admin/' . $this->list_view, $data);
    }
	
    // 保存 添加和修改都是在这里
    public function save ()
    {
        $id = intval($_POST['id']);
        $data = trims($_POST['value']);
        
        if($data[password]) {
            $data[password] = get_password($data[password]);
        } else {
            unset ($data[password]);
        }
        
        if ($id) { // 修改 ===========
            $this->db->where('id', $id);
            $query = $this->db->update($this->table, $data);
            show_msg('修改成功！', ($_SESSION['groupid']==1)?$_SESSION['url_forward']:'./cpi.php?d=admin&c=admin&m=index&m=edit&id='.$this->session->userdata('id'));
        } else { // ===========添加 ===========
            $data['addtime'] = time();
            $query = $this->db->insert($this->table, $data);
            show_msg('添加成功！', ($_SESSION['groupid']==1)?$_SESSION['url_forward']:'./cpi.php?d=admin&c=admin&m=index&m=edit&id='.$this->session->userdata('id'));
        }
    }
    
    // 删除
    public function delete ()
    {
        $id = $_GET['id'];
        $catid = $_REQUEST['catid'];
        
        if ($id == 1)
            show_msg('超级管理员不能删除', $_SESSION['url_forward']);
        
        parent::delete();
    }
}
