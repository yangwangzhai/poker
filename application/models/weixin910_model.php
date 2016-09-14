<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * 微信接口
 * 
 * echo $_GET["echostr"]; 验证接口用的 
 * code by jaqy 最后修改日期 20141001            mod menu pro:index() getXML()
$wechatObj = new Apis();
$wechatObj->valid();
*/
class weixin910_model extends CI_Controller 
{
	private $token = 'weixin910xxx';
	private $url = 'http://910.bbrtv.com/weixin/';
	private $postObj; // 从微信传来的所有数据
	public $db = null;
    
	function __construct() 
	{
		parent:: __construct();		
		
		$this->db910 = $this->load->database('weixin910', TRUE);   
	}
	
	public function valid() {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
    
	// 验证是否来自微信服务器
	function checkSignature()
	{
	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];
	    
	    $tmpArr = array($this->token, $timestamp, $nonce);
	    sort($tmpArr);
	    $tmpStr = implode( $tmpArr );
	    $tmpStr = sha1( $tmpStr );
	    
	    if( $tmpStr == $signature ){
	        return true;
	    }else{
	        return false;
	    }
	}
	
    /**
	 * 获取事件类型，订阅 或 取消订阅时候调用
	 * @param $string
	 * @return string
	 */
	function getEvent($string) {
		$xmls = 0;
	    $result = '欢迎使用微信910！';
	    if ($string == 'subscribe') {
	        $result = $this->getSetData('wxsubscribe'); //用户关注回复
	    } elseif ($string == 'unsubscribe') {
	        $result = '感谢您的关注及评论！';
	    } elseif($string == 'CLICK') {
	    	$ekey = ''.$this->postObj->EventKey;
	    	$return = $this->getResource2($ekey);
	    	$result = $return['content'];
	    	//$result = '无事件:'.$ekey;
	    	//fwrite(fopen("wxlog101.txt","a"),$result."\r\n \r\n");
	    }
		
	    return array('result'=>$result,'xmls'=>$xmls);
	}
	
	// 默认 获取信息，返回信息
	function index() {
		if (empty ( $this->postObj )) {
			echo '';
		}
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
		$keywordsPlaceArr['xiaohua'] = array('/::)','/:,@P','/::D','/::>','/:B-)','/:X-)','继续','笑话');
		$keywordsPlaceArr['help']    = array('帮助','bz','help');

		//后台文本关键词
		$keyListArr = array();
		$query = $this->db910->query("select keyword,recontent from fly_wxkeywords");
        $list = $query->result_array();
        foreach($list as $k=>$value) {
        	$keyListArr[$k]['keyword'] = explode('##',$value['keyword']);
        	$keyListArr[$k]['recontent'] = $value['recontent'];
        }
		
	    $return = array('type'=>'text', 'content'=>'');

	    //天气关键词检查
	    preg_match("/(.*)天气/", $keywords, $match);
		$weather = str_replace($match[1],'',$keywords);

	    //回复用户信息
	    if(in_array($keywords,$keywordsPlaceArr['help'])) { //帮助
	    	$return['content'] = $this->getSetData('wxhelp');
	    } elseif(in_array($weather,$keywordsPlaceArr['weather'])) { //天气
	    	if($keywords == '天气') {
	    		$return['content'] = '请输入城市名+天气，如：南宁天气';
	    	} else {
	    		$return['content'] = $this->getWeather($keywords);
	    	}
	    } elseif(in_array($keywords,$keywordsPlaceArr['xiaohua'])) { //笑话
	    	$return['content'] = $this->getXiaohua($keywords);	
	    } else {
	    	foreach($keyListArr as $value) {
	    		if(in_array($keywords,$value['keyword'])) {
	    			$return['content'] = $value['recontent'];	
	    		}
	    	}
	    	
			//后台图文关键词
	    	if (empty ( $return ['content'] )) {
			
				$keyListArr = array();
				$query = $this->db910->query("select id,keyword,types from fly_wxrules where types='news' order by id desc");
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
	
	
	// 获取天气信息  返回字符串
	function getWeather( $keywords ) {
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
			$string = $obj->Weather->city . '今日天气' . PHP_EOL .
									'今天白天'.$obj->Weather->status1.'，'. $obj->Weather->temperature1 . '摄氏度。' . PHP_EOL .
									$obj->Weather->direction1 . '，' . $obj->Weather->power1 . PHP_EOL .
									'今天夜间'.$obj->Weather->status2.'，'. $obj->Weather->temperature2 . '摄氏度。' . PHP_EOL .
									$obj->Weather->direction2 . '，' . $obj->Weather->power2 . PHP_EOL ;
		}
	    return $string;
	}
	
	
	// 获取笑话信息  返回字符串
	function getXiaohua($keywords) {
		$page=rand(300,1);
		$url='http://www.jokeji.cn/list_'.$page.'.htm';
		$content = $this->curlGetData($url);
		if(strlen($content)>20) {
			$pattern='#<a href="(/jokehtml/.*?/[0-9]+\.htm)".*?>.*?</a>#';
			preg_match_all($pattern,$content,$m);
			$index=array_rand($m[1]);
			$aurl='http://www.jokeji.cn'.$m[1][$index];
			$content=str_replace("\n","",$this->curlGetData($aurl));
			$pattern='#<span id="text110">(.*?)</span>#';
			preg_match($pattern,$content,$m);
			$r=iconv('gbk','utf-8',trim(strip_tags(str_replace("</p>","\n",str_replace("<p>","\n",$m[1])))));
			$this->recordXiaohua($r);
			$r = str_replace('&nbsp;','',$r);
			return $r.' 输入笑话继续，或/::) /:,@P /::D /::> /:B-) /:X-)中任一表情继续';;
		}
	}
	
    
	//记录
	function record($postStr) {
		if(is_array($_GET)) foreach($_GET as $k=>$v)  $g.="&&{$k}={$v}";
		if(is_array($_POST)) foreach($_POST as $k=>$v) $p.="&&{$k}={$v}";
		$t = date('Y-m-d H:i:s')." ".$_SERVER[REMOTE_ADDR]."\r\nGET={$g} \r\nPOST={$p} \r\npostStr={$postStr}"; //记录
		fwrite(fopen("wxlog.txt","a"),$t."\r\n \r\n");
	}
	
	// 保存 文字回复信息 到服务器
	function saveText()
	{
	    if(!empty($this->postObj)){

	    	//更新用户基本信息
	    	$this->saveTextUsers();
	    	
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
	                'addtime' => time()
	        );
	        $this->db910->insert('fly_text', $data);
	        
	        //大平台同步
	        $content = ($data['Event']=='VIEW'||$data['Event']=='CLICK')?$this->getCusmenuName($data['EventKey']):$data['Content'];
	        $postUrl = 'http://116.10.196.141/www_media/api.php?ac=wxmsg';
			$postArr = array(
				'key' => '098f6bcd4621d373cade4e832627b4f9',
				'radiotype' => '910',
				'CreateTime' => $data['CreateTime'],
				'Event' => $data['Event'],
				'EventKey' => $data['EventKey'],
				'MsgType' => $data['MsgType'],
				'ToUserName' => $data['ToUserName'],
				'FromUserName' => $data['FromUserName'],
				'Content' => $content,
				'MsgId' => $data['MsgId'], 
			);
			$this->curlPost($postUrl , $postArr);
			
	    } else {
	        echo 0;
	    }
	}
	
	//获取自定义菜单名称
	function getCusmenuName($keyurl='') {
		$query = $this->db910->query("select * from fly_sets where key1='cusmenu' limit 1");
		$data['value'] = $query->row_array();
		$inarr  = json_decode($data['value']['values'],true);
		//print_r($inarr);
		if(is_array($inarr['button'])) {
	        foreach($inarr['button'] as $k1=>$val1) {
	        	$kc1 = $val1[type]=='click'?'key':'url';
	        	if(!count($val1['sub_button'])) {
	        		if($keyurl == $val1[$kc1]) return $val1[name];
	        	} else {
	        		$str.= "|-name:{$val1[name]}<br>";
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
		$query = $this->db910->query("select * from fly_sets where key2='{$key2}' limit 1");
        $data = $query->row_array();
        if($data['values']) {	
			return $data['values'];
        }
	}
	
	//记录笑话
	function recordXiaohua($string) {
		$string = trim($string);
		$query = $this->db910->query("select * from fly_xiaohua where content='{$string}' limit 1");
        $data = $query->row_array();
        if(!$data['content']) {	
			$this->db910->insert('fly_xiaohua', array('content'=>$string , 'addtime'=>time()));
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
			$query = $this->db910->query("select * from fly_text_users where openid='{$openid}' limit 1");
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
			    	$this->db910->insert('fly_text_users', $bdata);
		    	}
			}
	}
	
	//通过OPENID取用户基本信息
	function getWeixinUserBaseInfo($openid) {
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->getAccessToken()."&openid={$openid}&lang=zh_CN";
		//fwrite(fopen("wxu.txt","a"),"url:$url"."\r\n \r\n");
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
		$opstr = @implode(",",$chkarr);
		fwrite(fopen("wxu.txt","a"),"opstr:$opstr"."\r\n \r\n");
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
        $query = $this->db910->query("select * from fly_sets where key1='wxappsecret' limit 1");
		$data['value1'] = $query->row_array();
		return json_decode($data['value1']['values'],true);
	}
	
	
	// ================================
	// 获取群列表，首页用，显示未查看聊天数
	function menu()
	{
		$result = array();
		$query = $this->db910->query("select * from fly_sets where key1='cusmenu' limit 1");
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

?>