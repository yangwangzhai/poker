<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 管理员  控制器 by tangjian 

include 'content.php';

class play_title extends Content
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
        $this->baseurl = 'index.php?d=admin&c=play_title';
        $this->table = 'zy_player';
        $this->list_view = 'play_title_list';
        $this->add_view = '';
    }
    


    public function index(){
		
		//更新在线人数
		$this->db->query("UPDATE zy_player SET is_online=1 WHERE openID IN (SELECT openid FROM zy_online)");   
		$this->db->query("UPDATE zy_player SET is_online=0 WHERE openID NOT IN (SELECT openid FROM zy_online)");     
		
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
            $searchsql .= " AND (openID like '%{$keywords}%' OR nickname like '%{$keywords}%') ";
            
            $config['base_url'] = $this->baseurl ."&m=index&catid=$catid&keywords=" . rawurlencode($keywords);
        }

		//$searchsql .= " AND openID IN(SELECT openid FROM zy_online) OR  openID NOT IN(SELECT openid FROM zy_online) ";
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


    

        $rank_sql = 'select * FROM '.$this->table.' where '.$searchsql.' ORDER BY is_online desc,lasttime desc limit '. $offset . ',20';
        $query = $this->db->query( $rank_sql );
        $result = $query->result_array();   
        foreach( $result as &$val){
			$val['isonline'] = '否';
			$query = $this->db->query("SELECT * FROM zy_online WHERE openid= '".$val['openID']."' ");        
        	$row = $query->row_array();       
			if($row) $val['isonline'] = '是';
		}

        $data['list'] = $result;
		$_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";
        $this->load->view('admin/' . $this->list_view, $data);
    }
	
	//充值烟豆
	function rechargeByMy(){
		$key = $this->zlkey;
		$data['orderno'] = 'racedog_' . time(); //流水号
		$data['qrcodeNo'] = '';//二维码的值（没有则为空）
		$data['smokeBeans'] = isset($_GET['num']) ? $_GET['num'] : 5000;
		$data['smokeBrand'] = '蓝龙';//香烟牌子
		$data['type'] = '疯狂赛狗'; //获取烟豆的途径
		$data['desc'] = '人工充值';
		$data['app_id'] = $this->zlapp_id;
		$data['openid'] = isset($_GET['openid']) ? $_GET['openid'] : 'ooypltz6-F70mRaSv1nFJkRrcaHs';
		$data['sign'] = md5($data['app_id'] . $data['qrcodeNo']. $data['orderno'] .$data['openid']. $data['smokeBeans'] .$data['smokeBrand'] .$data['desc'] .$key);
		$url = 'http://'. $this->yrurl .'/integral/integralManage!addIntegral.action';
				
		$return = $this->curlPost($url,$data);
		$return = json_decode($return,true);
		if($return['status'] == 0){
			
			$data_log['uid'] = $_SESSION['id'];
			$data_log['ip'] = ip();
			$data_log['num'] = $data['smokeBeans'];
			$data_log['addtime'] = time();
			$data_log['openid'] = $data['openid'];
			//插入记录表
			$this->db->insert('zy_recharge_by_us', $data_log);
		
			$this->db->query( "UPDATE zy_player SET total_gold = ". $return['smokeBeansCount'] ." WHERE  openid= '".$data['openid'] ."'");
			
			show_msg('充值['.$data['smokeBeans'].']成功！', $_SESSION['url_forward']);
		}else{
			 show_msg('充值['.$data['smokeBeans'].']失败！', $_SESSION['url_forward']);
		}
		
	}
	
	
	
	function lookyd(){
		$openid = $_POST['openid'];
		if(empty($openid)){
			echo json_encode('openid为空') ;
			return;
		}
		$key = $this->zlkey;
		$data['app_id'] = $this->zlapp_id;
		$data['openid'] = $openid;
		$data['sign'] = md5($data['app_id'].$data['openid'].$key);
		$url = 'http://'. $this->yrurl .'/integral/integralManage!getUserIntegral.action';
		$return = $this->curlPost($url,$data);
		
		
		echo $return ;
	}
	
	
		//接口POST
	function curlPost($postUrl , $postArr=array()) {
		$curl = curl_init($postUrl);
		$cookie = dirname(__FILE__).'/cache/cookie.txt';
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT,10); //超时设置 (秒)
		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); // ?Cookie
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postArr));
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}
	

}
