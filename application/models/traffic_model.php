<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
	// 路况模型 地图模型
class traffic_model extends CI_Model {
	
	function __construct() {
		parent::__construct ();
	}
	
	// 列表页
	public function lists($where='', $start=0, $offset=20) {
		$list = array();
		$sql = "SELECT id,uid,title,thumb,audio,audio_time,district,street,longlat,comments,typename,addtime FROM fly_traffictext WHERE status=1 $where ORDER BY id DESC limit $start,$offset";
		$query = $this->db->query($sql);
		$districts = config_item('district');
		while ($row = $query->_fetch_assoc()) {
			$row['title'] = $districts[$row['district']] . ' ' .
					$row['street'] . ' ' . $row['typename'] . ' ' .
					$row['title'];			
			$row['addtime'] = timeFromNow($row['addtime']);
			$row['avatar'] = new_thumbname($row['avatar'], 100, 100);
			$list[] = $row;
		}
		
		return html_escape( addMember($list) );				
	}
	
	
}
