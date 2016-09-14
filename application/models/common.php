<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 通用模型
class Common extends CI_Model
{
    function __construct ()
    {
        parent::__construct();
    }
    
    // 指定会员的 路况发布数字段加1
    function addTrafficCount($uid)
    {
        if($uid>0) {
            $this->db->query("update fly_member set traffic_count=traffic_count+1 where id=$uid limit 1");
        }
    }
    
    
}
