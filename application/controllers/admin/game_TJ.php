<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 游戏统计  控制器 by tangjian 

include 'content.php';

class game_TJ extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'game_TJ';
        $this->baseurl = 'index.php?d=admin&c=game_TJ';
        $this->table = 'zy_player_result';
        $this->list_view = 'game_TJ_list';
        //$this->add_view = 'admin_add';
    }
    


    public function index(){

        $keywords = trim($_REQUEST['keywords']);
        $searchsql = '1';
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index&catid=$catid";
        } else {
            $sou_res = $this->db->query("SELECT openID FROM zy_player WHERE nickname like '%{$keywords}%'")->row_array();
            $searchsql .= " AND (r.openid = '{$sou_res['openID']}')";
            $config['base_url'] = $this->baseurl ."&m=index&catid=$catid&keywords=" . rawurlencode($keywords);
        }

        if($_GET['day'] == 'today' || !$_GET['day']){
            //默认显示今天的统计
            $startTime = strtotime(date('Y-m-d 0:0:0'));
            $endTime = strtotime(date('Y-m-d 23:59:59'));
        }else if($_GET['day'] == 'yesterday'){
            $startTime = strtotime(date('Y-m-d 0:0:0',time()-86400));
            $endTime = strtotime(date('Y-m-d 23:59:59',time()-86400));
            
        }
        //echo date('Y-m-d H:i:s',$startTime),'<br/>';
        //echo date('Y-m-d H:i:s',$endTime),'<br/>';  

        if($_GET['starttime'] && $_GET['endtime']){
            $startTime = strtotime($_GET['starttime']);
            $endTime = strtotime($_GET['endtime']);
            $config['base_url'] .= "&starttime=".date("Y-m-d H:i:s",$startTime)."&endtime=".date("Y-m-d H:i:s",$endTime);
        }

        //echo $startTime,'<br/>';
        //echo $endTime,'<br/>';

        $data['startTime'] = $startTime;
        $data['endTime'] = $endTime;

        
        $query = $this->db->query(
            "SELECT count(*) as num FROM (SELECT openid FROM $this->table WHERE addtime > $startTime and addtime < $endTime  group by openid) as temp");
        $count = $query->row_array();
        $data['count'] = $count['num'];
        if($keywords){
            $data['count'] = 1;
        }
        $config['total_rows'] = $count['num'];
        $config['per_page'] = 20;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();

        $offset = $_GET['per_page'] ? intval($_GET['per_page']) : 0;
        $per_page = $config['per_page'];

        $sql="select r.openid,sum(r.total) as total,p.nickname,p.head_img,p.total_gold from $this->table as r LEFT JOIN zy_player as p ON (r.openid=p.openID) WHERE r.openid <> 'zyracedog20160418' and r.addtime > $startTime and r.addtime < $endTime and $searchsql group by r.openid order by ABS(sum(r.total)) desc limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['list'] = $query->result_array();

        foreach ($data['list'] as $k => &$v) {
            $sql = "SELECT count(*) game_num from $this->table where openid = '{$v['openid']}' and addtime > $startTime and addtime < $endTime";
            $res = $this->db->query($sql)->row_array();
            $v['game_num'] = $res['game_num'];
			
			
        }

        //狗庄
        $sql="select r.openid,sum(r.total) as total,p.nickname,p.head_img,p.total_gold from $this->table as r LEFT JOIN zy_player as p ON (r.openid=p.openID) WHERE r.openid = 'zyracedog20160418' and r.addtime > $startTime and r.addtime < $endTime and $searchsql group by r.openid order by ABS(sum(r.total)) desc limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['zuang'] = $query->row_array();

        $sql = "SELECT count(*) game_num from $this->table where openid = 'zyracedog20160418' and addtime > $startTime and addtime < $endTime";
        $res = $this->db->query($sql)->row_array();
        $data['zuang']['game_num'] = $res['game_num'];

		
		if($_GET['starttime'] && $_GET['endtime']){
            $startTime = strtotime($_GET['starttime']);
            $endTime = strtotime($_GET['endtime']);            
        }else{
			$startTime = strtotime(date('2016-07-20 0:0:0'));
            $endTime = strtotime(date('Y-m-d 23:59:59'));	
		}
		
		$data['Stime'] =  $startTime;
		$data['Etime'] =  $endTime;
		
		//系统赢得
		$win_sql = "SELECT sum(total) as total  FROM `zy_player_result` where openid!='zyracedog20160418' and openid!='systemPlayer'    and total< 0 and addtime > $startTime and addtime < $endTime";
        $query = $this->db->query($win_sql);
        $win_row = $query->row_array();
		$data['win_total'] = '+' . abs($win_row['total']);
		//系统输
        $lost_sql = "SELECT sum(total) as total  FROM `zy_player_result` where openid!='zyracedog20160418' and openid!='systemPlayer'    and total> 0 and addtime > $startTime and addtime < $endTime";
        $query = $this->db->query($lost_sql);
        $lost_row = $query->row_array();
		$data['lost_total'] = -$lost_row['total'];
		
		//今日系统赢得
		$win_sql = "SELECT sum(total) as total  FROM `zy_player_result` where openid!='zyracedog20160418' and openid!='systemPlayer'    and total< 0 and addtime > ".strtotime(date('Y-m-d 0:0:0'))." and addtime < ".strtotime(date('Y-m-d 23:59:59'));
        $query = $this->db->query($win_sql);
        $win_row = $query->row_array();
		$data['win_today_total'] = '+' . abs($win_row['total']);
		//今日系统输
        $lost_sql = "SELECT sum(total) as total  FROM `zy_player_result` where openid!='zyracedog20160418' and openid!='systemPlayer'    and total> 0 and addtime > ".strtotime(date('Y-m-d 0:0:0'))." and addtime < $endTime".strtotime(date('Y-m-d 23:59:59'));;
        $query = $this->db->query($lost_sql);
        $lost_row = $query->row_array();
		$data['lost_today_total'] = -$lost_row['total'];
		

        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";
        $this->load->view('admin/' . $this->list_view, $data);
    }

    function getMore(){

        
        
        $openid = $_GET['openid'];
        $startTime = $_GET['starttime'];
        $endTime = $_GET['endtime'];

        $config['base_url'] = $this->baseurl . "&m=getMore&starttime=".$startTime."&endtime=".$endTime."&openid=".$openid;
        
        $query = $this->db->query(
            "SELECT COUNT(*) AS num FROM zy_bet_on WHERE openid = '$openid' and addtime > $startTime and addtime < $endTime");
        $count = $query->row_array();
        $data['count'] = $count['num'];
        $config['total_rows'] = $count['num'];
        $config['per_page'] = 20;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();

        $offset = $_GET['per_page'] ? intval($_GET['per_page']) : 0;
        $per_page = $config['per_page'];

        $sql="select * from zy_bet_on WHERE openid = '$openid' and addtime > $startTime and addtime < $endTime order by addtime desc limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['list'] = $query->result_array();
        foreach($data['list'] as &$val){
            $row = $this->db->query("SELECT head_img FROM zy_player WHERE openID= '".$val['openid'] ."'")->row_array();
            $val['head_img'] = $row['head_img'];
        }

        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";
        $this->load->view('admin/betlog_list', $data);
    }



}
