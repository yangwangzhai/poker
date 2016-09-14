<?php
/**
 * 常用函数，通用函数
 * by tangjian 
 */


/**
 * 字符截取 支持UTF8/GBK
 *
 * @param
 *            $string
 * @param
 *            $length
 * @param
 *            $dot
 */
function str_cut ($string, $length, $dot = '', $charset = 'utf-8')
{
    $strlen = strlen($string);
    if ($strlen <= $length)
        return $string;
    $string = str_replace(
            array(
                    ' ',
                    '&nbsp;',
                    '&amp;',
                    '&quot;',
                    '&#039;',
                    '&ldquo;',
                    '&rdquo;',
                    '&mdash;',
                    '&lt;',
                    '&gt;',
                    '&middot;',
                    '&hellip;'
            ), 
            array(
                    '∵',
                    ' ',
                    '&',
                    '"',
                    "'",
                    '"',
                    '"',
                    '—',
                    '<',
                    '>',
                    '·',
                    '…'
            ), $string);
    $strcut = '';
    if ($charset == 'utf-8') {
        $length = intval($length - strlen($dot) - $length / 3);
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n ++;
                $noc ++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n ++;
            }
            if ($noc >= $length) {
                break;
            }
        }
        if ($noc > $length) {
            $n -= $tn;
        }
        $strcut = substr($string, 0, $n);
        $strcut = str_replace(
                array(
                        '∵',
                        '&',
                        '"',
                        "'",
                        '"',
                        '"',
                        '—',
                        '<',
                        '>',
                        '·',
                        '…'
                ), 
                array(
                        ' ',
                        '&amp;',
                        '&quot;',
                        '&#039;',
                        '&ldquo;',
                        '&rdquo;',
                        '&mdash;',
                        '&lt;',
                        '&gt;',
                        '&middot;',
                        '&hellip;'
                ), $strcut);
    } else {
        $dotlen = strlen($dot);
        $maxi = $length - $dotlen - 1;
        $current_str = '';
        $search_arr = array(
                '&',
                ' ',
                '"',
                "'",
                '"',
                '"',
                '—',
                '<',
                '>',
                '·',
                '…',
                '∵'
        );
        $replace_arr = array(
                '&amp;',
                '&nbsp;',
                '&quot;',
                '&#039;',
                '&ldquo;',
                '&rdquo;',
                '&mdash;',
                '&lt;',
                '&gt;',
                '&middot;',
                '&hellip;',
                ' '
        );
        $search_flip = array_flip($search_arr);
        for ($i = 0; $i < $maxi; $i ++) {
            $current_str = ord($string[$i]) > 127 ? $string[$i] . $string[++ $i] : $string[$i];
            if (in_array($current_str, $search_arr)) {
                $key = $search_flip[$current_str];
                $current_str = str_replace($search_arr[$key], 
                        $replace_arr[$key], $current_str);
            }
            $strcut .= $current_str;
        }
    }
    return $strcut . $dot;
}

/**
 * 显示信息
 *
 * @param string $message
 *            内容
 * @param string $url_forward
 *            跳转的网址
 * @param string $title
 *            标题
 * @param int $second
 *            停留的时间
 * @return
 *
 *
 *
 */
function show_msg ($message, $url_forward = '', $title = '提示信息', $second = 3, $error = false )
{
    include (APPPATH . 'views/show_msg.php');
    exit();
}
function show_msg_new ($message, $url_forward = '', $title = '提示信息', $second = 3)
{
    include (APPPATH . 'views/show_msg_new.php');
    exit();
}
/**
 * 图片上传函数
 *
 * @param
 *            string 上传文本框的名称
 * @return string 图片保存在数据库里的路径
 */
function uploadFile ($filename, $dir_name = 'image')
{
    // 有上传文件时
    if (empty($_FILES)) return ''; 
   
    $save_path = 'uploads/' .$dir_name . '/';
    $max_size = 5000 * 1024; // 最大文件大小5M
    $AllowedExtensions = array('jpg','jpeg','png','bmp','gif','3gp','amr','aac'); // 允许格式
    
    $file_size = $_FILES[$filename]['size'];
    if ($file_size > $max_size) {
        return '';
    }
    $Extensions = fileext($_FILES[$filename]['name']);
    if (! in_array($Extensions, $AllowedExtensions)) {
        return '';
    }
    if (! file_exists($save_path)) { // 创建文件夹          
        mkdir($save_path);
    }   
    $save_path .= date("Ymd") . "/";
    if (! file_exists($save_path)) {
        mkdir($save_path);
    }    
    $file_name = date('YmdHis') . '_' . rand(10000, 99999) . '.' . $Extensions;
    $upload_file = $save_path . $file_name;   
    if (move_uploaded_file($_FILES[$filename]['tmp_name'], $upload_file)===false) {
        return '';
    }
    
    return $upload_file;
}

/**
 * 生成缩略图函数
 *
 * @param $imgurl 图片路径            
 * @param $width 缩略图宽度            
 * @param $height 缩略图高度            
 * @return string 生成图片的路径 类似：./uploads/201203/img_100_80.jpg
 */
function mythumb ($imgurl, $width = 200, $height = 200)
{
    $fileext = fileext($imgurl);
    $num = strlen($imgurl) - strlen($fileext) - 1;
    $newimg = substr($imgurl, 0, $num) . "_{$width}_{$height}.{$fileext}";
    
    if (file_exists($newimg))
        return $newimg; // 有，返回
    
    if (file_exists($imgurl)) { // 没有，开始生成
        include_once APPPATH . '/libraries/My_image_class.php';
        $object = new My_image_class();
        $px = getimagesize($imgurl);
        if ($px[0] > 10) {
            $object->imageCustomSizes($imgurl, $newimg, $width, $height);
            return $newimg;
        }
    }
}

/**
 * 生成缩略图函数  剪切
 *
 * @param $imgurl 图片路径            
 * @param $width 缩略图宽度            
 * @param $height 缩略图高度            
 * @return string 生成图片的路径 类似：./uploads/201203/img_100_80.jpg
 */
function thumb ($imgurl, $width = 100, $height = 100)
{
    if (empty($imgurl))
        return '不能为空';

    include_once 'application/libraries/image_moo.php';
    $moo = new Image_moo();
    $moo->load($imgurl);
    $moo->resize_crop($width, $height);
    $moo->save_pa("","_100_100");    
}

/**
 * 生成缩略图函数 用CI的  同时生成两张图片  100和720像素的
 *
 * @param $imgurl 图片路径
 * @return void
 */
function thumb2($imgurl)
{
    if (empty($imgurl))
        return '不能为空';
	
    include 'application/libraries/image_moo.php';
    $moo = new Image_moo();
    $moo->load($imgurl);
    $moo->resize_crop(100, 100);
    $moo->save_pa("","_100_100");    
    $moo->resize(720, 1280);
    $moo->save_pa("","_720_720");
}

/**
 * 取得文件扩展 不包括 点
 *
 * @param $filename 文件名            
 * @return 扩展名
 */
function fileext ($filename)
{
    // 获得文件扩展名
    $temp_arr = explode(".", $filename);
    $file_ext = array_pop($temp_arr);
    $file_ext = trim($file_ext);
    $file_ext = strtolower($file_ext);
    
    return $file_ext;
}

/**
 * 返回新名词 uploads/201203/img_100_80.jpg
 *
 * @param $filename 文件名
 * @return 扩展名
 */
function new_thumbname ($imgurl,$width,$height)
{
    if(empty($imgurl)) return '';
    
    $fileext = fileext($imgurl);
    $num = strlen($imgurl) - strlen($fileext) - 1;
    $newimg = substr($imgurl, 0, $num) . "_{$width}_{$height}.{$fileext}";
    return $newimg;
}



/**
 * 获取请求ip
 *
 * @return ip地址
 */
function ip ()
{
    if (getenv('HTTP_CLIENT_IP') &&
             strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') &&
             strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') &&
             strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] &&
             strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : '';
}

//获取用户浏览器信息
function getbrowser() { 
    $browser = $_SERVER['HTTP_USER_AGENT']; 
   
    return $browser; 
} 

//获取用户系统信息
function getos() { 
    $os = $_SERVER['HTTP_USER_AGENT']; 
    if (preg_match('/win/i',$os)) { 
        $os = 'windows'; 
    } 
    elseif (preg_match('/mac/i',$os)) { 
        $os = 'mac'; 
    } 
    elseif (preg_match('/linux/i',$os)) { 
        $os = 'linux'; 
    } 
    elseif (preg_match('/unix/i',$os)) { 
        $os = 'unix'; 
    } 
    elseif (preg_match('/bsd/i',$os)) { 
        $os = 'bsd'; 
    } 
    else { 
        $os = 'other'; 
    } 
    return $os; 
}  

//判断是否手机访问
function is_mobile_request()  
{  
 $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
 $mobile_browser = '0';  
 if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
  $mobile_browser++;  
 if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
  $mobile_browser++;  
 if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
  $mobile_browser++;  
 if(isset($_SERVER['HTTP_PROFILE']))  
  $mobile_browser++;  
 $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
 $mobile_agents = array(  
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
    'wapr','webc','winw','winw','xda','xda-'
    );  
 if(in_array($mobile_ua, $mobile_agents))  
  $mobile_browser++;  
 if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
  $mobile_browser++;  
 // Pre-final check to reset everything if the user is on Windows  
 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
  $mobile_browser=0;  
 // But WP7 is also Windows, with a slightly different characteristic  
 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
  $mobile_browser++;  
 if($mobile_browser>0)  
  return true;  
 else
  return false;
}

/**
 * 写入缓存
 * $name 文件名
 * $data 数据数组
 *
 * @return ip地址
 */
function set_cache ($name, $data)
{
    
    // 检查目录写权限
    if (@is_writable(APPPATH . 'cache/') === false) {
        return false;
    }
    file_put_contents(APPPATH . 'cache/' . $name . '.php', 
            '<?php return ' . var_export($data, TRUE) . ';');
    return true;
}

/**
 * 获取缓存
 * $name 文件名
 *
 * @return array
 */
function get_cache ($name)
{
    $ret = array();
    $filename = APPPATH . 'cache/' . $name . '.php';
    if (file_exists($filename)) {
        $ret = include $filename;
    }
    
    return $ret;
}

/**
 * 对数据执行 trim 去左右两边空格
 * mixed $data 数组或者字符串
 *
 * @return mixed
 */
function trims ($data)
{
    if (is_array($data)) {
        foreach ($data as &$r) {
            $r = trims($r);
        }
    } else {
        $data = trim($data);
    }
    
    return $data;
}

/**
 * 时间处理
 */
function times ($time, $type = 0)
{
    if ($type == 0) {
        return date('Y-m-d', $time);
    } else {
        return date('Y-m-d H:i:s', $time);
    }
}

/**
 * 获取分类 指定id 的信息
 */
function category ($catid, $type = 'name')
{
    $a = get_cache('category');
    return $a[$catid][$type];
}

/**
 * 获取分类 指定id 的信息
 */
function getcitys ($catid, $type = 'name')
{
    $a = get_cache('citys');
    return $a[$catid][$type];
}

/**
 * 后去加密后的 字符
 *
 * @param
 *            string
 * @return string
 */
function get_password ($password)
{
    return md5('gfdgd5454_' . $password);
}

/**
 * 取消反引用 返回经stripslashes处理过的字符串或数组 
 *
 * @param $string 需要处理的字符串或数组            
 * @return mixed
 */
function new_stripslashes ($string)
{
    if (! is_array($string))
        return stripslashes($string);
    foreach ($string as $key => $val)
        $string[$key] = new_stripslashes($val);
    return $string;
}

/**
 * 将字符串转换为数组
 *
 * @param string $data            
 * @return array
 *
 */
function string2array ($data)
{
    if ($data == '')
        return array();
    @eval("\$array = $data;");
    return $array;
}

/**
 * 将数组转换为字符串
 *
 * @param array $data            
 * @param bool $isformdata            
 * @return string
 *
 */
function array2string ($data, $isformdata = 1)
{
    if ($data == '')
        return '';
    if ($isformdata)
        $data = new_stripslashes($data);
    return (var_export($data, TRUE)); // addslashes
}

/**
 * 得到子级 id 包括自己
 *
 * @param
 *            int
 * @return string
 *
 */
function get_child ($myid)
{
    $ret = $myid;
    $data = get_cache('category');
    foreach ($data as $id => $a) {
        if ($a['parentid'] == $myid) {
            $ret .= ',' . $id;
        }
    }
    
    return $ret;
}

/**
 * 得到子级 id 包括自己
 *
 * @param
 *            int
 * @return array
 *
 */
function get_childarray ($myid)
{
    $return = array();
    $data = get_cache('category');
    foreach ($data as $id => $a) {
        if ($a['parentid'] == $myid) {
            $return[$id] = $a;
        }
    }
    
    return $return;
}

// 获取限制条件 返回数组
function getwheres ($intkeys, $strkeys, $randkeys, $likekeys, $pre = '')
{
    $wherearr = array();
    $urls = array();
    
    foreach ($intkeys as $var) {
        $value = isset($_GET[$var]) ? stripsearchkey($_GET[$var]) : '';
        if (strlen($value)) {
            $wherearr[] = "{$pre}{$var}='" . intval($value) . "'";
            $urls[] = "$var=$value";
        }
    }
    
    foreach ($strkeys as $var) {
        $value = isset($_GET[$var]) ? stripsearchkey($_GET[$var]) : '';
        if (strlen($value)) {
            $wherearr[] = "{$pre}{$var}='$value'";
            $urls[] = "$var=" . rawurlencode($value);
        }
    }
    
    foreach ($randkeys as $vars) {
        $value1 = isset($_GET[$vars[1] . '1']) ? $vars[0]($_GET[$vars[1] . '1']) : '';
        $value2 = isset($_GET[$vars[1] . '2']) ? $vars[0]($_GET[$vars[1] . '2']) : '';
        if ($value1) {
            $wherearr[] = "{$pre}{$vars[1]}>='$value1'";
            $urls[] = "{$vars[1]}1=" . rawurlencode($_GET[$vars[1] . '1']);
        }
        if ($value2) {
            $wherearr[] = "{$pre}{$vars[1]}<='$value2'";
            $urls[] = "{$vars[1]}2=" . rawurlencode($_GET[$vars[1] . '2']);
        }
    }
    
    foreach ($likekeys as $var) {
        $value = isset($_GET[$var]) ? stripsearchkey($_GET[$var]) : '';
        if (strlen($value) > 1) {
            $wherearr[] = "{$pre}{$var} LIKE BINARY '%$value%'";
            $urls[] = "$var=" . rawurlencode($value);
        }
    }
    
    return array(
            'wherearr' => $wherearr,
            'urls' => $urls
    );
}

// 获取下拉框 选项信息
function getSelect ($data, $value = '', $type = 'key')
{
    $str = '';
    foreach ($data as $k => $v) {
        if ($type == 'key') {
            $seled = ($value == $k && $value) ? 'selected="selected"' : '';
            $str .= "<option value=\"{$k}\" {$seled}>{$v}</option>";
        } else {
            $seled = ($value == $v && $value) ? 'selected="selected"' : '';
            $str .= "<option value=\"{$v}\" {$seled}>{$v}</option>";
        }
    }
    return $str;
}

// 显示友好的时间格式
function timeFromNow($dateline) {
    if(empty($dateline)) return false;
    $seconds = time() - $dateline;
    if($seconds<=3 ) {
        $times = "现在";
    }elseif($seconds>3 && $seconds<60 ) {
        $times = "{$seconds}秒前";
    } elseif ($seconds < 60) {
        $times = "1分钟前";
    }elseif($seconds < 3600){
        $times = floor($seconds/60)."分钟前";
    }elseif($seconds  < 24*3600){
        $times = floor($seconds/3600)."小时前";
    }elseif($seconds < 48*3600){
        $times = date("昨天 H:i", $dateline)."";
    }else{
        $times = date('m-d', $dateline);
    }
	if(@strstr($times,'现在')) $re = 'red';
	if(@strstr($times,'秒')) $re = 'red';
	if(@strstr($times,'分')) $re = 'green';
	if(@strstr($times,'时')) $re = 'blue';
	return "<font color={$re}>{$times}</font>";
}




// 输出 错误，退出程序 返回 json
function error ($code=0, $msg='have some error')
{
    $error = array( // 返回的错误码
            'error_code' => $code,
            'error_msg' => $msg
    );
   echo json_encode($error);    
   exit;
}


//检查邮箱是否有效
function isemail($email) {
    return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

// 异步执行
function async_request($host, $file, $method='get') {

	$fp = fsockopen($host, 80, $errno, $errstr, 30);
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		$out = "GET $file / HTTP/1.1\r\n";
		$out .= "Host: www.example.com\r\n";
		$out .= "Connection: Close\r\n\r\n";

		fwrite($fp, $out);
		/*忽略执行结果
		 while (!feof($fp)) {
		echo fgets($fp, 128);
		}*/
		fclose($fp);
	}
}



// 管理员 后台操作记录
function adminlog($title) {
	if(empty($title)) return '';
	
	$CI = &get_instance();
	$uid = $CI->session->userdata('id');
	$CI->load->library('user_agent');
	$browser = $CI->agent->browser().$CI->agent->version();
	// 插入数据
	$data = array(
			'adminid' => $uid,
			'title' => $title,
			'ip' => ip(),
			'addtime' => time(),
			'browser' => $browser
	);
	$CI->db->insert('fly_adminlog', $data);	
}

// 成功后，输出 json
function show ($status=0, $msg='ok',$id=0)
{
	$error = array(
			'status'=>$status,
			'msg' => $msg,
			'id' => $id,
			'time' => time()
	);
	echo json_encode($error);
	exit;
}


function replaceBad($str){
	if(empty($str)) return '';
	$CI = &get_instance();
	$query = $CI->db->query("select * from fly_bad_word  order by id asc");
	$list = $query->result_array();
	$strArr = array();
	foreach($list as $value) {
		$badword[] = $value['find'];
		$replaceword[] = $value['replacement'];
	}
	$badwordArr = array_combine($badword,$replaceword);
	$newstr = strtr($str, $badwordArr);
	return $newstr;
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

function curlGetData($url) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
    curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1' );
    $data = curl_exec ( $ch );
    $status = curl_getinfo ( $ch );
    $errno = curl_errno ( $ch );
    curl_close ( $ch );
    return $data;
}	




function cut_html_str($str, $lenth, $replace='', $anchor='<!-- break -->'){ 
    $_lenth = mb_strlen($str, "utf-8"); // 统计字符串长度（中、英文都算一个字符）
    if($_lenth <= $lenth){
        return $str;    // 传入的字符串长度小于截取长度，原样返回
    }
    $strlen_var = strlen($str);     // 统计字符串长度（UTF8编码下-中文算3个字符，英文算一个字符）
    if(strpos($str, '<') === false){ 
        return mb_substr($str, 0, $lenth);  // 不包含 html 标签 ，直接截取
    } 
    if($e = strpos($str, $anchor)){ 
        return mb_substr($str, 0, $e);  // 包含截断标志，优先
    } 
    $html_tag = 0;  // html 代码标记 
    $result = '';   // 摘要字符串
    $html_array = array('left' => array(), 'right' => array()); //记录截取后字符串内出现的 html 标签，开始=>left,结束=>right
    /*
    * 如字符串为：<h3><p><b>a</b></h3>，假设p未闭合，数组则为：array('left'=>array('h3','p','b'), 'right'=>'b','h3');
    * 仅补全 html 标签，<? <% 等其它语言标记，会产生不可预知结果
    */
    for($i = 0; $i < $strlen_var; ++$i) { 
        if(!$lenth) break;  // 遍历完之后跳出
        $current_var = substr($str, $i, 1); // 当前字符
        if($current_var == '<'){ // html 代码开始 
            $html_tag = 1; 
            $html_array_str = ''; 
        }else if($html_tag == 1){ // 一段 html 代码结束 
            if($current_var == '>'){ 
                $html_array_str = trim($html_array_str); //去除首尾空格，如 <br / > < img src="" / > 等可能出现首尾空格
                if(substr($html_array_str, -1) != '/'){ //判断最后一个字符是否为 /，若是，则标签已闭合，不记录
                    // 判断第一个字符是否 /，若是，则放在 right 单元 
                    $f = substr($html_array_str, 0, 1); 
                    if($f == '/'){ 
                        $html_array['right'][] = str_replace('/', '', $html_array_str); // 去掉 '/' 
                    }else if($f != '?'){ // 若是?，则为 PHP 代码，跳过
                        // 若有半角空格，以空格分割，第一个单元为 html 标签。如：<h2 class="a"> <p class="a"> 
                        if(strpos($html_array_str, ' ') !== false){ 
                        // 分割成2个单元，可能有多个空格，如：<h2 class="" id=""> 
                        $html_array['left'][] = strtolower(current(explode(' ', $html_array_str, 2))); 
                        }else{ 
                        //若没有空格，整个字符串为 html 标签，如：<b> <p> 等，统一转换为小写
                        $html_array['left'][] = strtolower($html_array_str); 
                        } 
                    } 
                } 
                $html_array_str = ''; // 字符串重置
                $html_tag = 0; 
            }else{ 
                $html_array_str .= $current_var; //将< >之间的字符组成一个字符串,用于提取 html 标签
            } 
        }else{ 
            --$lenth; // 非 html 代码才记数
        } 
        $ord_var_c = ord($str{$i}); 
        switch (true) { 
            case (($ord_var_c & 0xE0) == 0xC0): // 2 字节 
                $result .= substr($str, $i, 2); 
                $i += 1; break; 
            case (($ord_var_c & 0xF0) == 0xE0): // 3 字节
                $result .= substr($str, $i, 3); 
                $i += 2; break; 
            case (($ord_var_c & 0xF8) == 0xF0): // 4 字节
                $result .= substr($str, $i, 4); 
                $i += 3; break; 
            case (($ord_var_c & 0xFC) == 0xF8): // 5 字节 
                $result .= substr($str, $i, 5); 
                $i += 4; break; 
            case (($ord_var_c & 0xFE) == 0xFC): // 6 字节
                $result .= substr($str, $i, 6); 
                $i += 5; break; 
            default: // 1 字节 
                $result .= $current_var; 
        } 
    } 
    if($html_array['left']){ //比对左右 html 标签，不足则补全
        $html_array['left'] = array_reverse($html_array['left']); //翻转left数组，补充的顺序应与 html 出现的顺序相反
        foreach($html_array['left'] as $index => $tag){ 
            $key = array_search($tag, $html_array['right']); // 判断该标签是否出现在 right 中
            if($key !== false){ // 出现，从 right 中删除该单元
                unset($html_array['right'][$key]); 
            }else{ // 没有出现，需要补全 
                $result .= '</'.$tag.'>'; 
            } 
        } 
    } 
    return $result.$replace; 
}

//获取角色分组
function getGroupList()
{
    $CI = &get_instance();
    return $CI->db->query("SELECT * FROM zy_sys_group")->result_array();
}

//获取角色名称
function getGroupName($id)
{
    if(!$id){
        return '';
    }
    $CI = &get_instance();
    $res = $CI->db->query("SELECT GroupName FROM zy_sys_group WHERE GroupID = $id")->row_array();
    return $res['GroupName'];
}

//获取用户名
function getUserName($uid)
{
    if(!$uid){
        return '';
    }
    $CI = &get_instance();
    $res = $CI->db->query("SELECT Username FROM zy_sys_manager WHERE UID = $uid")->row_array();
    return $res['Username'];
}

//获取游戏名
function getGameName($roomid)
{
    if(!$roomid){
        return '';
    }
    $CI = &get_instance();
    $res = $CI->db->query("SELECT GameName FROM zy_game_room WHERE RoomID = $roomid")->row_array();
    return $res['GameName'];
}

//获取游戏列表
function getGameList()
{
    $CI = &get_instance();
    return $CI->db->query("SELECT * FROM zy_game_room")->result_array();
}

function checkAccess($access){
		$CI = &get_instance();
		$hasAccess = false;
		$gid = $CI->session->userdata('GroupID');
		if($gid == 1) return true;
		if(empty($gid)) return $hasAccess;
		$query = $CI->db->query("SELECT Pid FROM zy_sys_privicy WHERE   Psign = '".$access."'");
		$row = $query->row_array();	
		if($row){		
			$query_row = $CI->db->query("SELECT * FROM zy_sys_group WHERE  FIND_IN_SET(".$row['Pid'].",Pids) AND GroupID=$gid");
			$get_row= $query_row->row_array();	
			if($get_row){
				$hasAccess = true;
			}
		}
		return $hasAccess;
}



if(!function_exists('str_exists'))
{
		/**
		 * 查询字符是否存在于某字符串
		 * 
		 * @param $haystack 字符串
		 * @param $needle 要查找的字符
		 * @return bool
		 */
		function str_exists($haystack, $needle)
		{
			return !(strpos($haystack, $needle) === FALSE);
		}
}

if(!function_exists('zy_a'))
	{
		/**
		 * ACI UI A
		 * 		
		 * @param $psign 权限标识
		 * @param $args GET 要有问号
		 * @param $attr 按钮内属性
		 * @param $html 按钮内文字内容
		 * @return bool
		 *zy_a('channel_list','链接','index.php?d=admin&c=admin&m=index','onclick="return confirm(\'确定要删除吗？\');"');
		 */
		function zy_a($psign, $html, $url = '', $attr = '', $is_return=false)
		{
			if(empty($psign)) return '';
			if(checkAccess($psign)){
				$url = trim($url)!=""?"href=".base_url($url):"href='#'";					
				if(!$is_return)
					echo sprintf("<a %s %s>%s</a>",$url,$attr,$html);
				else
					return sprintf("<a %s %s>%s</a>",$url,$attr,$html);
				
			}else{
				if(!$is_return)
					echo "";
				else
					return "";
			}


		}
}

if(!function_exists('zy_btn'))
	{
		/**
		 * ACI UI A
		 * 		
		 * @param $psign 权限标识
		 * @param $args GET 要有问号
		 * @param $attr 按钮内属性
		 * @param $html 按钮内文字内容
		 * @return bool
		 *zy_btn('channel_list','删除','  onclick="location.href=\'index.php?d=admin&c=group&m=add\'" ');
		 */
		function zy_btn($psign,$html,$attr = '', $type='button')
		{
			if(empty($psign)) return '';
			if(checkAccess($psign)){									
				if(!$is_return)
					echo sprintf("<input  type='%s' value='%s' %s  />",$type,$html,$attr);
				else
					echo sprintf("<input  type='%s' value='%s' %s  />",$type,$html,$attr);
				
			}else{
				if(!$is_return)
					echo "";
				else
					return "";
			}


		}
}

if(!function_exists('zy_li'))
	{
		/**
		 * ACI UI A
		 * 		
		 * @param $psign 权限标识
		 * @param $args GET 要有问号
		 * @param $attr 按钮内属性
		 * @param $html 按钮内文字内容
		 * @return bool
		 *zy_li('channel_list','链接','index.php?d=admin&c=admin&m=index','onclick="return confirm(\'确定要删除吗？\');"');
		 */
		function zy_li($psign,$html,$url = '', $a_attr = '',$li_attr = '', $is_return=false)
		{
			if(empty($psign)) return '';
			if(checkAccess($psign)){
				$url = trim($url)!=""?"href=".base_url(trim($url)):"href='#'";					
				if(!$is_return)
					echo sprintf("<li %s ><a %s %s>%s</a></li>",$li_attr,$url,$a_attr,$html);
				else
					return sprintf("<li %s ><a %s %s>%s</a></li>",$li_attr,$url,$a_attr,$html);
				
			}else{
				if(!$is_return)
					echo "";
				else
					return "";
			}


		}
}

//文件大小B,KB,MB单位转换
function formatBytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
} 

	
	//生成二维码
function phpqrcode($text, $filename){		
  include_once 'application/libraries/phpqrcode.php';
  $value = $text; //二维码内容   
  $errorCorrectionLevel = 'L';//容错级别   
  $matrixPointSize = 6;//生成图片大小   
  //生成二维码图片   
  QRcode::png($value, 'ewm/'.$filename, $errorCorrectionLevel, $matrixPointSize, 2, true);   
}


