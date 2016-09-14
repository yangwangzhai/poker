<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 管理员  控制器 by tangjian 

include 'content.php';

class notice extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'admin';
        $this->baseurl = 'index.php?d=admin&c=notice';
        $this->table = 'zy_player';
        $this->list_view = 'notice_list';
        $this->add_view = 'admin_add';
    }
    


    public function index(){

        $keywords = trim($_REQUEST['keywords']);
        $searchsql = 'status = 0';
		$searchsql2 = 'status = 0';
        //         if ($catid) {
        //             $searchsql .= " AND catid=$catid ";
        //         }
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index&catid=$catid";
        } else {
            $searchsql .= " AND (openID like '%{$keywords}%' OR nickname like '%{$keywords}%' )";
			
            $config['base_url'] = $this->baseurl ."&m=index&catid=$catid&keywords=" . rawurlencode($keywords);
        }


        $query = $this->db->query(
            "SELECT COUNT(*) AS num FROM $this->table WHERE $searchsql ");

        //$query = $this->db->query("SELECT COUNT(*) AS num FROM zy_attend_log");
        $count = $query->row_array();
        $data['count'] = $count['num'];
        $config['total_rows'] = $count['num'];
        $config['per_page'] = 20;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $offset = $_GET['per_page'] ? intval($_GET['per_page']) : 0;
        $per_page = $config['per_page'];


	

		$rank_sql = 'select * FROM '.$this->table.' where '.$searchsql.'  ORDER BY total_gold DESC limit '. $offset . ',20';
		$query = $this->db->query( $rank_sql );
		$result = $query->result_array();	
		/*foreach($result as &$val){
			$val['score'] = $val['max_score'];
			$max_score = $val['max_score'];
			$query_pm = $this->db->query("SELECT COUNT(DISTINCT max_score) AS pm FROM zy_attend_user WHERE max_score>= $max_score AND status = 0");
			$row_pm = $query_pm->row_array();
			$val['pm'] = $row_pm['pm'];
		}*/

        $data['list'] = $result;
        $data['offset'] = $offset;
        $this->load->view('admin/' . $this->list_view, $data);
    }

}
