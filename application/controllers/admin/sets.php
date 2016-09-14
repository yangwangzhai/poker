<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 管理员  控制器 by tangjian 

include 'content.php';

class sets extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'admin';
        $this->baseurl = 'index.php?d=admin&c=sets';
        $this->table = 'zy_game';
        $this->list_view = 'sets_list';
        $this->add_view = 'admin_add';
    }
    


    public function index(){

        $keywords = trim($_REQUEST['keywords']);
        $searchsql = '1';
        //         if ($catid) {
        //             $searchsql .= " AND catid=$catid ";
        //         }
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index&catid=$catid";
        } else {
            $searchsql .= " AND (game_owner like '%{$keywords}%' OR id = {$keywords} )";
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

        $sql="select * from  $this->table WHERE $searchsql  order by addtime desc limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['list'] = $query->result_array();
        //print_r($data['list']);exit;
        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";
        $this->load->view('admin/' . $this->list_view, $data);
    }

}
