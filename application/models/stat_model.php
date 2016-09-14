<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
// 统计模型
class stat_model extends CI_Model
{
    function __construct ()
    {
        parent::__construct();
    }
    
    // 每日访问量统计加1
    function day_save ($type)
    {
        if (empty($type)) {
            return;
        }
        
        $dates = date('Y-m-d'); //日期
        $hours = date('G');     // 小时
    
        $this->db->query(
                "update fly_stat_day set {$type}={$type}+1 where dates='$dates' AND hours='$hours' LIMIT 1");
    }
 
    
    // 初始化 每日访问表，每天分24个时段  // 存插入数据的操作，最好用 MyISAM
    function init_stat_day ()
    {
       // 开始日期
        $start_day = strtotime('2013-12-20');
        $data = array();
        
        echo '数据库初始化开始:';
        for($i=0; $i<=500; $i++) {
            $dates = date( 'Y-m-d', $start_day+(24*3600*$i) );
            $data['dates'] = $dates;
            
            for($n=0; $n<=23; $n++) {
                $data['hours'] = $n;               
                $this->db->insert('fly_stat_day', $data);
            }  
            echo '.';
        }
        echo 'ok';
    }
    
}
