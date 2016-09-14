<?php
/**
 * 常用函数，通用函数
 * by tangjian 
 */

	

/*
* array unique_rand( int $min, int $max, int $num )
* 生成一定数量的不重复随机数
* $min 和 $max: 指定随机数的范围
* $num: 指定生成数量
*/
function unique_rand( $num , $min = 10000000, $max = 99999999) {
    $count = 0;
    $return = array();
    while ($count < $num) {
		$rand_num = mt_rand($min, $max);
		$CI = &get_instance();
		$query = $CI->db->query("select count(*) as num from wx_coupon_code where code='$rand_num'");
		$list = $query->row_array();
		if($list['num'] > 0) break;
        $return[] = $rand_num;
      //  $return = array_flip(array_flip($return)); //删除数组中重复的
        $count = count($return);
    }
    shuffle($return);
    return $return;
}
function type_list(){
	$CI = &get_instance();
	$query = $CI->db->get_where( 'wx_coupon_type');
    $list = $query->result_array(); 
	return $list;
}

//判断图片是否存在
function show_thumb($url){
		
		$returnurl = 'static/images/nophoto.png';
		if(empty($url))  return $returnurl;
		if(file_exists('..'.$url)){
			$returnurl = $url;
		}
		$length = strlen($url);
		if($length > 0 && $str1 = substr($url,0,1) == '/'){
			$url = substr($url,1,($length-1));		
		}		
		
		if(file_exists($url)){
			$returnurl = $url;
		}	
		return $returnurl;
}

?>
