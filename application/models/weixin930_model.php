<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * 微信接口
 * 
 * echo $_GET["echostr"]; 验证接口用的 
 * code by jaqy 最后修改日期 20141001            mod menu pro:index() getXML()
 * */
 
//$wechatObj = new Api();
//$wechatObj->valid();

class weixin930_model extends CI_Controller 
{
	private $token = 'bbrtv930token001';
	private $url = 'http://116.10.196.141/weixin930/';
	private $postObj; // 从微信传来的所有数据
	private $db930=null;	
    
	function __construct()
	{
		parent:: __construct();		
		$this->db930 = $this->load->database('weixin930', TRUE);
	}	
	
	
    /**
	 * 获取事件类型，订阅 或 取消订阅时候调用
	 * @param $string
	 * @return string
	 */
	function getEvent($string) {
		$xmls = 0;
	    $result = '欢迎使用私家车930微信！';
	    if ($string == 'subscribe') {
	        $result = $this->getSetData('wxsubscribe'); //用户关注回复
	    } elseif ($string == 'unsubscribe') {
	        $result = '感谢您的关注及评论！';
	    } elseif($string == 'CLICK') {
	    	$ekey = ''.$this->postObj->EventKey;
	    	$return = $this->getResource($ekey);
	    	$result = $return['content'];
	    	//$result = '无事件:'.$ekey;
	    	//fwrite(fopen("wxlog11.txt","a"),$result."\r\n \r\n");
	    }
	
	    return array('result'=>$result,'xmls'=>$xmls);
	}	
	
	// 默认 获取信息，返回信息
	function index() {
		//记录接口反馈
		$this->saveText();		    		
		if (!empty($this->postObj)){
		    $postObj = $this->postObj;
		    $fromUsername = $postObj->FromUserName;
		    $toUsername = $postObj->ToUserName;
		    $event = $postObj->Event;
		    //$eventKey = $postObj->EventKey;
		    $keyword = trim($postObj->Content);
		   
		   if (! empty ( $keyword )) { // 发送关键词类型
			$return = $this->getResource ( '' . $keyword );
			// 记录 到数据库
			$this->saveText ( '' . $keyword, '' . $postObj->FromUserName );
			} else if ($event == 'CLICK') { // 自定义菜单点击 传值
				$return = $this->getResource ( '' . $postObj->EventKey );
			} else if ($event == 'subscribe') { // 用户关注
				$return ['type'] = 'text';
				$return ['content'] = $this->getSetData ( 'wxsubscribe' );
			} else if ($event == 'unsubscribe') { // 用户取消关注
				$return ['type'] = 'text';
				$return ['content'] = $this->getSetData ( 'wxsubscribe' );
			}
			
			// 最终输出
			echo $this->getXML ( $postObj, $return );
		 }	
		
	}
	
	// 统一回复格式化的内容
	function getXML($postObj, $return) {
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$time = time ();
		if ($return ['type'] == 'text') {
			$Tpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
			$resultStr = sprintf ( $Tpl, $fromUsername, $toUsername, $time, $return ['type'], $return ['content'] );
		} elseif ($return ['type'] == 'music') {
			$Tpl = "<xml>
                             <ToUserName><![CDATA[%s]]></ToUserName>
                             <FromUserName><![CDATA[%s]]></FromUserName>
                             <CreateTime>%s</CreateTime>
                             <MsgType><![CDATA[%s]]></MsgType>
                             <Music>
                             <Title><![CDATA[%s]]></Title>
                             <Description><![CDATA[%s]]></Description>
                             <MusicUrl><![CDATA[%s]]></MusicUrl>
                             <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                             </Music>
                             <FuncFlag>0</FuncFlag>
                             </xml>";
			$resultStr = sprintf ( $Tpl, $fromUsername, $toUsername, $time, $return ['type'], $return ['title'], $return ['info'], $return ['url'], $return ['url'] );
		} elseif ($return ['type'] == 'news') {
			$Tpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>%s</ArticleCount>
						<Articles>%s</Articles>
						</xml>";
			$resultStr = sprintf ( $Tpl, $fromUsername, $toUsername, $time, $return ['type'], $return ['count'], $return ['content'] );
		}
		
		return $resultStr;
	}
	
	// 通过关键词 匹配获取 信息 返回数组
	function getResource($keywords) {	
		$keywordsPlaceArr = array(); 
		$keywordsPlaceArr['weather'] = array('天气');
		$keywordsPlaceArr['road'] = array('路况');
		$keywordsPlaceArr['air']  = array('航班');
		$keywordsPlaceArr['hotel']  = array('酒店','餐馆');
		$keywordsPlaceArr['train']  = array('火车票');
		$keywordsPlaceArr['mms']  = array('直播','mms');
		$keywordsPlaceArr['play']  = array('点播');
		$keywordsPlaceArr['help']    = array('帮助','bz','help');

		//后台文本关键词
		$keyListArr = array();
		$query = $this->db930->query("select keyword,recontent from maps_wxkeywords");
        $list = $query->result_array();
        foreach($list as $k=>$value) {
        	$keyListArr[$k]['keyword'] = explode('##',$value['keyword']);
        	$keyListArr[$k]['recontent'] = $value['recontent'];
        }
		
	    $return = array('type'=>'text', 'content'=>'');

	    //天气关键词检查
	    preg_match("/(.*)天气/", $keywords, $match); //天气+
		$weather  = str_replace($match[1],'',$keywords); 
		preg_match("/路况(.*)/", $keywords, $match); //路况+
		$p_road   = str_replace($match[1],'',$keywords);
		preg_match("/航班(.*)/", $keywords, $match); //航班 ZH1925
		$p_air    = str_replace($match[1],'',$keywords);
		preg_match("/酒店(.*)/", $keywords, $match); //酒店+
		$p_hotel1  = str_replace($match[1],'',$keywords);
		preg_match("/餐馆(.*)/", $keywords, $match); //餐馆+
		$p_hotel2  = str_replace($match[1],'',$keywords);
		preg_match("/火车票(.*)/", $keywords, $match); //火车票+
		$p_train  = str_replace($match[1],'',$keywords);
		preg_match("/点播(.*)/", $keywords, $match); //点播+
		$p_play   = str_replace($match[1],'',$keywords);
	
	    //回复用户信息
	    if(in_array($keywords,$keywordsPlaceArr['help'])) { //帮助
	    	$return['content'] = $this->getSetData('wxhelp');
	    } elseif(in_array($weather,$keywordsPlaceArr['weather'])) { //天气
	    	$keywordsadd = ($keywords == '天气')?'南宁天气':$keywords;
	    	$return['content'] = $this->getWeather($keywordsadd);
	    	if(!$return['content']) {
	    		$return['content'] = '请输入城市名+天气，如：南宁天气';
	    	}
	    } elseif(in_array($p_road,$keywordsPlaceArr['road'])) { //路况
	    	$return['content'] = $this->getRoad($keywords);	
	    } elseif(in_array($p_air,$keywordsPlaceArr['air'])) { //机票
	    	$return['content'] = $this->getAirplane($keywords);	
	    } elseif(in_array($p_hotel1,$keywordsPlaceArr['hotel']) || in_array($p_hotel2,$keywordsPlaceArr['hotel'])) { //酒店,餐馆
	    	$return['content'] = $this->getHotel($keywords);
	    } elseif(in_array($p_train,$keywordsPlaceArr['train'])) { //火车票
	    	$return['content'] = $this->getTrain($keywords);
	    } elseif(in_array($keywords,$keywordsPlaceArr['mms'])) { //直播
	    	$return['content'] = '<a href=\"http://930.bbrtv.com/weixin/zhibo/zhibo.php?id=61537\">点击这里收听FM930广播 </a>';
	    } elseif(in_array($p_play,$keywordsPlaceArr['play'])) { //点播
	    	$audio = $this->getAudio($keywords);
	        if (empty($audio)) {
                 $return['type'] = 'text';
                 $return['content'] = '没有找到点播信息';
             } else {
                 $return = $audio;
                 $return['type'] = 'music';
             }
	    } else {
	    	foreach($keyListArr as $value) {
	    		if(in_array($keywords,$value['keyword'])) {
	    			$return['content'] = $value['recontent'];	
	    		}
	    	}
	    	
	    	if(!$return['content']) {
	    		//后台图文关键词
				$keyListArr = array();
				$query = $this->db930->query("select id,keyword,types from maps_wxrules where types='news' order by id desc");
		        $list = $query->result_array();
		        foreach($list as $k=>$value) {
		        	$keyarr = explode('##',$value['keyword']);
		        	if (in_array ( $keywords, $keyarr )) {
    					// 图文item
    					$newsItemArr = getRuleNewsItem ( $value ['id'] );
    					if ($newsItemArr [$value ['id']]) {
    						$count = 0;
    						foreach ( $newsItemArr [$value ['id']] as $ks => $iarr ) {
    							$pic = $this->config->item ( 'base_url' ) . $iarr [PicUrl];
    							$tos = $iarr [Url] ? 1 : 0;
    							$url = $this->config->item ( 'base_url' ) . "/index.php?d=weixin&c=page&m=news_show&id=" . $iarr ['id'] . "&tourl=" . $tos;
    							$item .= "<item>
    							<Title><![CDATA[{$iarr[Title]}]]></Title>
    							<Description><![CDATA[{$iarr[Description]}]]></Description>
    							<PicUrl><![CDATA[{$pic}]]></PicUrl>
    							<Url><![CDATA[{$url}]]></Url>
    							</item>";
    							break;
    						}
    						$return ['type'] = 'news';    						
    						$return ['content'] = $iarr[Title];
    						$return ['pic'] = $pic;
    						$return ['url'] = $url;
    					}
    					break;
    				}
		        	
		        }
	    	}
	    	
	    	
	    	//未匹配关键词的回复
	    	if(!$return['content']) {	    	
	    		$return['content'] = $this->getSetData('wxnoreply');
	    	}
	    }	 
	    
	    return $return;
	}
	
	    // 获取路况信息 返回字符串
    function getRoad ($keywords)
    {
        $string = '';
        $i = 1;
        $keywords = trim(mb_substr($keywords, 2, 10, 'utf8'));
        
        // 去掉 路
        $temp = mb_substr($keywords, - 1, 1, 'utf8');
        if (mb_strlen($keywords, 'utf8') >= 3 && $temp == '路') {
            $keywords = str_replace('路', '', $keywords);
        }
        // 去掉 大道
        $temp = mb_substr($keywords, - 2, 2, 'utf8');
        if (mb_strlen($keywords, 'utf8') >= 3 && $temp == '大道') {
            $keywords = str_replace('大道', '', $keywords);
        }
        // 显示多少分钟内的信息 默认30分钟
        $website = get_cache("website");
        $weixintime = intval($website['weixintime']);
        if ($weixintime == 0)
            $weixintime = 30;
        $showtime = time() - ($weixintime * 60);
        
        if(@strstr($keywords,'城区')) {
			$types = 1;
        	$wsql = " and types='1'";
        } elseif(@strstr($keywords,'高速')) {
			$types = 2;
        	$wsql = " and types='2' ";
        } elseif(empty($keywords)) {
			$types = 0;
        	$wsql = "and info like '%{$keywords}%'";
        }
        //echo "select id,info,updatetime from maps_bobao where updatetime>=$showtime {$wsql} order by id desc limit 5";
		
        $query = $this->db930->query(
                "select id,info,updatetime from maps_bobao where 1 {$wsql} order by id desc limit 3");
        $list = $query->result_array();
        foreach ($list as $rs) {
            $string .= '•' . $rs['info'] . "\r\n";
            $string .= $this->getBobaoLonglat($rs['id']);
            $i ++;
        }
        
        if (empty($string) && $types==1) {//无城区路况
            $string = $website['bobao_null_msg_city'];
        } elseif(empty($string) && $types==2) { //高速路况
			$string = $website['bobao_null_msg_gaosu'];
		} elseif(empty($string)) { //无路况
			$string = $website['bobao_null_msg'];
		}
		if(empty($string)) $string = "暂无路况"; 
        return $string;
    }

    //连接地图显示页面
    function getBobaoLonglat($bid) {
    	$query = $this->db930->get_where('maps_bobao_longlat', "bid ='$bid'", 1);
		$data = $query->row_array();
		if($data['longlat']) {
    		return '<a href="'.$this->config->item('base_url').'/index.php?c=page&m=longlat&bid='.$bid.'">点击这里查看地图</a>'."\r\n";
		}
    } 
    
	// 获取天气信息  返回字符串
	function getWeather( $keywords ) {
		$string = '';
		/**
		 * 处理用户发送来的内容信息，这里的需求是需要取出包含的城市信息
		 * 还有很多种处理方式，根据自己设定的关键字来处理。
		 */
		preg_match('/(.*)天气/', $keywords, $match);
		$city = $match[1];
		
		//调用接口
		$url = 'http://php.weather.sina.com.cn/xml.php?city=%s&password=DJOYnieT8234jlsK&day=0';
		$url = sprintf($url, urlencode(iconv('utf-8', 'gb2312', $city)));		
		$data = $this->curlGetData($url);
		
		//返回的消息记录，如果是本地文件必须构造一个response数组变量，并填充相关信息。
		//如果是远程URL，返回的数据可以是response数组的json串，也可以是微信公众平台的标准XML数据接口。
		
		$response = array();
		if ($status['http_code'] = 200) {
			$obj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
			$string = "【".$obj->Weather->city . '今日天气】' . PHP_EOL .
									'今天白天'.$obj->Weather->status1.'，'. $obj->Weather->temperature1 . '摄氏度。' . PHP_EOL .
									$obj->Weather->direction1 . '，' . $obj->Weather->power1 . PHP_EOL .
									'今天夜间'.$obj->Weather->status2.'，'. $obj->Weather->temperature2 . '摄氏度。' . PHP_EOL .
									$obj->Weather->direction2 . '，' . $obj->Weather->power2 . PHP_EOL ;
		}
		//$string.= "\r\n".'可回复”城市名+天气“查询其他地区的天气，如：”上海天气“';
	    return $string;
	}
	
	
    // 根据航班号 获取机票信息 返回字符串
    function getAirplane ($keywords)
    {
        $string = '';
        $planeNumber = trim(mb_substr($keywords, 2, 10, 'utf8'));
        if (empty($planeNumber)) {
            $string = '没有找到相关航班信息，输入格式如：”航班 ZH1925“';
            return $string;
        }
        
        $url = "http://www.veryzhun.com/searchnum.asp?flightnum=" . $planeNumber;
        $subject = mb_convert_encoding($this->curlGetData($url), "UTF-8", 
                "gb2312");
        ;
        
        // 起飞地
        preg_match('|class=num_here>(.*?)</li>|is', $subject, $startcity);
        // 降落地
        preg_match('|class=numarr><ul><li class=num_here>(.*?)</li>|is', 
                $subject, $endcity);
        // 飞机状态
        preg_match('|<span class=red>(.*?)</span>|is', $subject, $status);
        preg_match('|class="planestate">(.*?)</p>|is', $subject, $remarks);
        // 计划起飞时间
        preg_match('|计划起飞时间：(.*?)</p>|is', $subject, $starttime);
        preg_match('|预计起飞时间：(.*?)</p>|is', $subject, $predicttime);
        preg_match('|实际起飞时间：(.*?)</p>|is', $subject, $realtime);
        // 到达 时间
        preg_match('|计划到达时间：(.*?)</p>|is', $subject, $endtime);
        preg_match('|预计到达时间：(.*?)</p>|is', $subject, $predictendtime);
        preg_match('|实际到达时间：(.*?)</p>|is', $subject, $realendtime);
        
        $string .= "航班号：$planeNumber\n";
        $string .= "$startcity[1] 飞往 $endcity[1]\n";
        $string .= "飞机状态：$status[1]\n";
        $string .= strip_tags($remarks[1]) . "\n";
        
        $string .= "计划起飞时间：$starttime[1]\n";
        $string .= "预计起飞时间：$predicttime[1]\n";
        $string .= "实际起飞时间：$realtime[1]\n";
        
        $string .= "计划到达时间：$endtime[1]\n";
        $string .= "预计到达时间：$predictendtime[1]\n";
        $string .= "实际到达时间：$realendtime[1]\n";
        
		$string .= "\n\n<a href=\"http://touch.qunar.com/h5/flight/\">点击这里购买机票</a>\n";
		
        return $string;
    }
    
    // 根据城市 获取机票信息 返回字符串
    function getAirplaneByCity ($keywords)
    {
        $string = '';
        $keywords = explode(' ', trim(mb_substr($keywords, 2, 30, 'utf8')));
        
        if (count($keywords) >= 3) {
            $from = escape($keywords[0]);
            $to = escape($keywords[1]);
            $date = date('Y-m-d', strtotime($keywords[2]));
            
            $url = "http://flights.ctrip.com/booking/NNG-KWL/?DCityName1=$from&ACityName1=$to&DDatePeriod1=$date&sourcepage=openbaidu";
            
            $subject = file_get_contents($url);
            
            // 总的数据
            preg_match_all('|data="(.*?)"|is', $subject, $all);
            // 航空公司
            preg_match_all('| flight_name">(.*?)</span>|is', $subject, 
                    $aircompany);
            
            $i = 1;
            foreach ($all[1] as $key => $value) {
                $array = explode('|', $value);
                $array[0] = substr($array[0], 11, 5);
                $array[1] = substr($array[1], 11, 5);
                $company = strip_tags(
                        mb_convert_encoding($aircompany[1][$key], "UTF-8", 
                                "gb2312"));
                $company = str_replace(array(
                        "\r\n",
                        "\r",
                        "\n",
                        " "
                ), '', $company);
                $string .= $i . '. ' . $array[0] . '-' . $array[1] . ' ' .
                         $company . ' ￥' . $array[6] . "\r\n";
                $i ++;
            }
        }
        
        return $string;
    }
    
    // 获取火车票信息 返回字符串
    function getTrain ($keywords)
    {
        $string = '';
        $keywords = str_replace('到',' ',$keywords);
        $keywords = explode(' ', trim(mb_substr($keywords, 3, 30, 'utf8')));
        
        if (count($keywords) >= 2) {
            $from = urlencode(
                    mb_convert_encoding($keywords[0], "gb2312", "UTF-8"));
            $to = urlencode(mb_convert_encoding($keywords[1], "gb2312", 
                    "UTF-8"));
            // $date = date('Y-m-d', strtotime($keywords[2]));
            $url = "http://search.huochepiao.com/chaxun/result.asp?txtChuFa=$from&txtDaoDa=$to";
            
            $subject = file_get_contents($url);
            // echo $subject;exit;
            
            // 总的数据
            preg_match_all(
                    '|onMouseOut="this.bgColor=\'#ffffff\';">(.*?)</tr>|is', 
                    $subject, $all);
            // 编号
            preg_match_all('| flight_name">(.*?)</span>|is', $subject, 
                    $aircompany);
            
            $i = 1;
            foreach ($all[1] as $key => $value) {
                $value = str_replace(array(
                        "\r\n",
                        "\r",
                        "\n"
                ), '', $value);
                $value = (mb_convert_encoding($value, "UTF-8", "gb2312"));
                $array = explode('<td align="center">', $value);
                foreach ($array as &$value) {
                    $value = strip_tags($value);
                }
                // print_r($array);
                
                $string .= $i . '.' . $array[0] . ' 出发' . $array[3] . ' 到达' .
                         $array[5] . ' 用时' . $array[6] . ' 硬座价￥' . $array[8] .
                         "\r\n";
                $i ++;
            }
        }
        
        return $string;
    }
    
    // 获取汽车票信息 返回字符串
    function getBus ($keywords)
    {
        $string = '';
        $keywords = explode(' ', trim(mb_substr($keywords, 3, 30, 'utf8')));
        
        if (count($keywords) >= 2) {
            $from = urlencode($keywords[0]);
            $to = urlencode($keywords[1]);
            // $date = date('Y-m-d', strtotime($keywords[2]));
            $url = "http://www.trip8080.com/chashike.jsp?s1={$from}&s2={$to}";
            $subject = file_get_contents($url);
            
            // 总的数据
            preg_match_all('|array_all =(.*?);|is', $subject, $all);
            $data = json_decode($all[1][0], true);
            
            $i = 1;
            foreach ($data as $key => $value) {
                $value = str_replace(array(
                        "\r\n",
                        "\r",
                        "\n"
                ), '', $value);
                $station = urldecode(
                        substr($value[0], 0, 
                                strpos($value[0], "%3Cdiv%20class%3D")));
                $starttime = urldecode(
                        substr($value[1], 0, 
                                strpos($value[1], "%3Cdiv%20class%3D")));
                $price = urldecode(
                        substr($value[4], 0, 
                                strpos($value[4], "%3Cdiv%20class%3D")));
                $string .= $i . '.' . $station . ' 发车时间' . $starttime . ' 参考价￥' .
                         $price . "\r\n";
                $i ++;
            }
        }
        
        return ($string);
    }
    
    // 获取酒店餐馆信息 返回字符串
    function getHotel ($keywords)
    {
        $string = '';
        $i = 1;
        $keywords = trim(mb_substr($keywords, 2, 10, 'utf8'));
        
        // 去掉 路
        $temp = mb_substr($keywords, - 1, 1, 'utf8');
        if (mb_strlen($keywords, 'utf8') >= 3 && $temp == '路') {
            $keywords = str_replace('路', '', $keywords);
        }
        // 去掉 大道
        $temp = mb_substr($keywords, - 2, 2, 'utf8');
        if (mb_strlen($keywords, 'utf8') >= 3 && $temp == '大道') {
            $keywords = str_replace('大道', '', $keywords);
        }
        
        $query = $this->db930->query(
                "select info from maps_hotel where info like '%{$keywords}%' order by id desc limit 10");
        $list = $query->result_array();
        
        if (empty($list)) {
            $website = get_cache("website");
            $string = $website['hotel_null_msg'];
        } else {
            foreach ($list as $rs) {
                $string .= '•' . $rs['info'] . "\r\n";
                $i ++;
            }
        }
        
        return $string;
    }
	
    // 获取音频 返回数组
    function getAudio ($keywords)
    {
        $data = array();
        $keywords = trim(mb_substr($keywords, 0, 20, 'utf8'));
        
        $query = $this->db930->query(
                "select title,anchors,url,info from maps_audio where title like '%{$keywords}%' or anchors like '%{$keywords}%' order by id desc limit 1");
        $data = $query->row_array();
        if (! empty($data))
            $data['url'] = base_url() . $data['url'];
        
        return $data;
    }
    
	//记录
	function record($postStr) {
		if(is_array($_GET)) foreach($_GET as $k=>$v)  $g.="&&{$k}={$v}";
		if(is_array($_POST)) foreach($_POST as $k=>$v) $p.="&&{$k}={$v}";
		$t = date('Y-m-d H:i:s')." ".$_SERVER[REMOTE_ADDR]."\r\nGET={$g} \r\nPOST={$p} \r\npostStr={$postStr}"; //记录
		//fwrite(fopen("wxlog.txt","a"),$t."\r\n \r\n");
	}
	
	// 保存 文字回复信息 到服务器
	function saveText()
	{
	    if(!empty($this->postObj)){

	    	//更新用户基本信息
	    	$userdata = $this->saveTextUsers();
	    	
	    	//记录操作
	        $data = array(
	                'ToUserName' => ''.$this->postObj->ToUserName,
	                'FromUserName' => ''.$this->postObj->FromUserName,
	        		'CreateTime' => ''.$this->postObj->CreateTime,
	        		'Event' => ''.$this->postObj->Event,
	        		'EventKey' => ''.$this->postObj->EventKey,
	        		'MsgType' => ''.$this->postObj->MsgType,
	        		'Content' => ''.$this->postObj->Content,
	        		'MsgId' => ''.$this->postObj->MsgId,
					'MediaId' => ''.$this->postObj->MediaId,
					'Recognition' => ''.$this->postObj->Recognition,
					'PicUrl' => ''.$this->postObj->PicUrl,
	                'addtime' => time()
	        );
	        $this->db930->insert('maps_text', $data);

	        //大平台同步
			$content = ($data['Event']=='VIEW'||$data['Event']=='CLICK')?$this->getCusmenuName($data['EventKey']):$data['Content'];
	        $postUrl = 'http://116.10.196.141/www_media/api.php?ac=wxmsg';
			$postArr = array(
				'key' => '098f6bcd4621d373cade4e832627b4f9',
				'radiotype' => '930',
				'CreateTime' => $data['CreateTime'],
				'Event' => $data['Event'],
				'EventKey' => $data['EventKey'],
				'MsgType' => $data['MsgType'],
				'ToUserName' => $data['ToUserName'],
				'FromUserName' => $data['FromUserName'],
				'Content' => $content,
				'MsgId' => $data['MsgId'], 
				'MediaId' => $data['MediaId'], 
				'PicUrl' => $data['PicUrl'], 
				'NickName' => $userdata[nickname],
				'Recognition' => $data['Recognition'], 
			);
			$this->curlPost($postUrl , $postArr);			
			
	    } else {
	        echo 0;
	    }
	}

	//获取自定义菜单名称
	function getCusmenuName($keyurl='') {
		$query = $this->db930->query("select * from maps_sets where key1='cusmenu' limit 1");
		$data['value'] = $query->row_array();
		$inarr  = json_decode($data['value']['values'],true);
		//print_r($inarr);
		if(is_array($inarr['button'])) {
	        foreach($inarr['button'] as $k1=>$val1) {
	        	$kc1 = $val1[type]=='click'?'key':'url';
	        	if(!count($val1['sub_button'])) {
	        		if($keyurl == $val1[$kc1]) return $val1[name];
	        	} else {
	        		foreach($val1['sub_button'] as $k2=>$val2) {
	        			$kc2 = $val2[type]=='click'?'key':'url';
	        			if($keyurl == $val2[$kc2]) return $val2[name];
	        		}
	        	}
	        }
	     }
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
	
	
	//记录读取
	function getSetData($key2) {
		$query = $this->db930->query("select * from maps_sets where key2='{$key2}' limit 1");
        $data = $query->row_array();
        if($data['values']) {	
			return $data['values'];
        }
	}
	
	//记录笑话
	function recordXiaohua($string) {
		$string = trim($string);
		$query = $this->db930->query("select * from maps_xiaohua where content='{$string}' limit 1");
        $data = $query->row_array();
        if(!$data['content']) {	
			$this->db930->insert('maps_xiaohua', array('content'=>$string , 'addtime'=>time()));
        }
	}
	
	//curl
	function curlGetData($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
		$data = curl_exec($ch);
		$status = curl_getinfo($ch);
		$errno = curl_errno($ch);
		curl_close($ch);
		return $data;
	}

	//保存用户基本信息
	function saveTextUsers() {
			$openid = ''.$this->postObj->FromUserName;
			$query = $this->db930->query("select * from maps_text_users where openid='{$openid}' limit 1");
			$data = $query->row_array();
			//fwrite(fopen("wxu.txt","a"),"openid:$openid"."\r\n \r\n");
			if(!$data) {
				//更新用户基本信息
		    	$binfo = $this->getWeixinUserBaseInfo($openid);
		    	//$opstr = @implode(",",$binfo);
		    	//fwrite(fopen("wxu.txt","a"),"opstr:$opstr"."\r\n \r\n");
		    	if($binfo['nickname']) {
			    	$bdata = array(
			    		'openid' => $openid,
			    		'nickname' => $binfo['nickname'],
			    		'sex' => $binfo['sex'],
			    		'headimgurl' => $binfo['headimgurl'],
			    		'country' => $binfo['country'],
			    		'province' => $binfo['province'],
			    		'city' => $binfo['city'],
			    		'subscribe_time' => $binfo['subscribe_time'],
			    		'addtime' => time(),
			    	);
			    	$this->db930->insert('maps_text_users', $bdata);
					return $bdata;
		    	}
			}
	}
	
	//通过OPENID取用户基本信息
	function getWeixinUserBaseInfo($openid) {
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->getAccessToken()."&openid={$openid}&lang=zh_CN";
		$json=$this->curlGetData($url);
		return json_decode($json,true);
		/*
		 {
		    "subscribe": 1, 
		    "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M", 
		    "nickname": "Band", 
		    "sex": 1, 
		    "language": "zh_CN", 
		    "city": "广州", 
		    "province": "广东", 
		    "country": "中国", 
		    "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0", 
		   "subscribe_time": 1382694957
		}*/
	}
	
	//自定义菜单中获取access_token
	function getAccessToken() {
		$chkarr = $this->getAccInfos();
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$chkarr['appID']."&secret=".$chkarr['appsecret'];
		$json=$this->curlGetData($url);//这个地方不能用file_get_contents
		$data=json_decode($json,true);
		if($data['access_token']){
			return $data['access_token'];
		}else{
			return "获取access_token错误";
		}		
	}
	
	//账号信息 $chkarr['appID'],$chkarr['appsecret']
	function getAccInfos() {
        $query = $this->db930->query("select * from maps_sets where key1='wxappsecret' limit 1");
		$data['value1'] = $query->row_array();
		return json_decode($data['value1']['values'],true);
	}
	
	
	// ================================
	// 获取群列表，首页用，显示未查看聊天数
	function menu()
	{
		$result = array();
		$query = $this->db930->query("select * from maps_sets where key1='cusmenu' limit 1");
		$value = $query->row_array();
		$value = json_decode($value['values'],true);
		return $value['button'];
	}
	
	
	//  组成一条 聊天信息 微路 群里需要
	function formartForVlook($uid, $data) {
		$member = $this->member_model->get_one($uid);
		if($member['avatar']) {
			$member['avatar'] = base_url() . $member['avatar'];
		}
	
		// 组成一条 聊天信息
		$chats = array (
				"uid" => $uid,
				"title" => $data['content'].' '.$data['url'],
				"thumb" => $data['pic'],
				"audio" => "",
				"audio_time" => "0",
				"addtime" => "现在",
				"nickname" => $member['nickname'],
				"member_thumb" => $member['avatar']
		);
			
		return $chats;
	}
}

//$tt = new Apis();
//print_r($tt->getResource('bz'));

?>