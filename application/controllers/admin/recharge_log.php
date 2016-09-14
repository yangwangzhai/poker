<?php

if (! defined('BASEPATH'))   exit('No direct script access allowed');

include 'content.php';
class recharge_log extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'admin';
        $this->baseurl = 'index.php?d=admin&c=recharge_log';
        $this->table = 'zy_recharge_log';
        $this->list_view = 'recharge_log_list';
    }
    


    public function index(){

        $keywords = trim($_REQUEST['keywords']);
        $searchsql = '1 AND gameid !=0 ';
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index&catid=$catid";
        } else {
            $searchsql .= " AND (openid like '%{$keywords}%' or gameid = '{$keywords}')";
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

        $sql="select * from  $this->table WHERE $searchsql  order by id desc limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['list'] = $query->result_array();
        foreach($data['list'] as &$val){
            $row = $this->db->query("SELECT head_img FROM zy_player WHERE openID= '".$val['openid'] ."'")->row_array();
            $val['head_img'] = $row['head_img'];
            $val['desc'] = array2string(json_decode($val['desc'],true));
        }

        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";
        $this->load->view('admin/' . $this->list_view, $data);
    }

}
