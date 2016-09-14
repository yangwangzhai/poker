<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
	// 信鸽推送 模型
require_once (APPPATH . 'libraries/XingeApp.php');
class xinge_model extends CI_Model {
	private $xinge = '';	
	
	function __construct() {
		parent::__construct ();
		
		error_reporting(0);
		
		$this->xinge = config_item ( 'xinge' );		
		
        $this->load->model('jpush_model');
	}
	
	// 推送通知 分家长端 教师端 或者同时
	function xinge_news($data) {
		
		$tagList = array ('notice_parents','notice_teacher');		
		
		$custom = array (
				'type' => 'notice',
				'id' => $data ['id']);
		
		// android客户端		
		$mess = new Message ();
		$mess->setType ( Message::TYPE_NOTIFICATION );
		$mess->setExpireTime(24*60*60);
		$mess->setTitle ( ''.$data ['title'] );
		$mess->setContent ( ''.$data ['addtime'] );
		$style = new Style ( 0 );
		// 义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style ( 0, 1, 1, 1, 0 );
		$action = new ClickAction ();
		$action->setActionType ( ClickAction::TYPE_ACTIVITY );	
		$mess->setStyle ( $style );
		$mess->setAction ( $action );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$push = new XingeApp ( $this->xinge ['accessId'], $this->xinge ['secretKey'] );
		$ret1 = $push->PushTags ( XingeApp::DEVICE_ANDROID, $tagList, 'OR', $mess );		
		$push = new XingeApp ( $this->xinge_teacher ['accessId'], $this->xinge_teacher ['secretKey'] );
		$ret2 = $push->PushTags ( XingeApp::DEVICE_ANDROID, $tagList, 'OR', $mess );
		
		// ios 客户端		
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		$mess->setAlert ( $data ['title'] );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$push = new XingeApp ( $this->xinge ['accessIdIOS'], $this->xinge ['secretKeyIOS'] );
		$ret = $push->PushTags ( XingeApp::DEVICE_IOS, $tagList, 'OR', $mess, XingeApp::IOSENV_PROD );
		$push = new XingeApp ( $this->xinge_teacher ['accessIdIOS'], $this->xinge_teacher ['secretKeyIOS'] );
		$ret = $push->PushTags ( XingeApp::DEVICE_IOS, $tagList, 'OR', $mess, XingeApp::IOSENV_PROD );
		
		
		// 极光推送
		//$this->jpush_model->push($data);
		return $ret2;
	}
	
	
	// 推送进出校门 刷卡信息
	function xinge_inout($data) {
		$custom = array ('type' => 'inout' );
		
		// android
		$push = new XingeApp ( $this->xinge ['accessId'], $this->xinge ['secretKey'] );
		$mess = new Message ();
		$mess->setType ( Message::TYPE_NOTIFICATION );
		$mess->setExpireTime(24*60*60);
		$mess->setTitle ( $data ['title'].' ' );
		$mess->setContent ( $data ['addtime'].' ' );
		$style = new Style ( 0 );
		// 义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style ( 0, 1, 1, 1, 0 );
		$action = new ClickAction ();
		$action->setActionType ( ClickAction::TYPE_ACTIVITY );
		$action->setActivity('com.edu.activity.AttendanceActivity');
		$mess->setStyle ( $style );
		$mess->setAction ( $action );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret1 = $push->PushSingleAccount ( XingeApp::DEVICE_ANDROID, '' . $data ['uid'], $mess );
		
		// ios
		$push = new XingeApp ( $this->xinge ['accessIdIOS'], $this->xinge ['secretKeyIOS'] );
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		$mess->setAlert ( $data ['title'] );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret = $push->PushSingleAccount ( XingeApp::DEVICE_IOS, ''.$data ['uid'], $mess, XingeApp::IOSENV_PROD );
		
		return ($ret1);
	}
	
	// 统计全班的 刷卡情况，推送给这个班主任
	function push_inout_stat($data) {
		$custom = array ('type' => 'inout_stat' );
		
		// android
		$push = new XingeApp ( $this->xinge_teacher ['accessId'], $this->xinge_teacher ['secretKey'] );
		$mess = new Message ();
		$mess->setType ( Message::TYPE_NOTIFICATION );
		$mess->setExpireTime(24*60*60);
		$mess->setTitle ( $data ['title'].' ' );
		$mess->setContent ( $data ['addtime'].' ' );
		$style = new Style ( 0 );
		// 义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style ( 0, 1, 1, 1, 0 );
		$action = new ClickAction ();
		$action->setActionType ( ClickAction::TYPE_ACTIVITY );
		$action->setActivity('com.edu.activity.TeacherClassActivity');
		$mess->setStyle ( $style );
		$mess->setAction ( $action );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret1 = $push->PushSingleAccount ( XingeApp::DEVICE_ANDROID, '' . $data ['uid'], $mess );
	
		// ios
		$push = new XingeApp ( $this->xinge_teacher ['accessIdIOS'], $this->xinge_teacher ['secretKeyIOS'] );
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		$mess->setAlert ( $data ['title'] );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret = $push->PushSingleAccount ( XingeApp::DEVICE_IOS, ''.$data ['uid'], $mess, XingeApp::IOSENV_PROD );
	
		return ($ret1);
	}
	
	// 私聊
	function xinge_private($data) {
		if(empty($data) || empty($data['to_uid'])) {
			return false;
		}
		$custom = array (
				'type' => 'private',
				'title' => $data ['title'],
				'addtime' => $data ['addtime'],
				'thumb' => $data ['thumb'],
				'audio' => $data ['audio'],
				'audio_time' => $data ['audio_time'],
				'from_uid' => $data ['from_uid']);				
		$custom = array (
				'type' => 'private',
				'from_uid' => $data ['from_uid'],
				'id' => $data ['id']);
		
		$this->db->where ( 'id', $data ['id'] );
		$query = $this->db->get ( 'fly_groups_chats', 1 );
		$value = $query->row_array ();	
		if($value['isbase64'] == 1){
			$data['title'] = base64_decode($data['title']);	
		}
		
		// android
		$mess = new Message ();
		$mess->setType ( Message::TYPE_MESSAGE );
		$mess->setExpireTime(24*60*60);
		$mess->setTitle ( $data ['title'].' ' );
		$mess->setContent ( $data ['addtime'].' ' );		
		$mess->setCustom ( $custom );
		$push = new XingeApp ( $this->xinge ['accessId'], $this->xinge ['secretKey'] );
		$ret1 = $push->PushSingleAccount ( XingeApp::DEVICE_ANDROID, '' . $data ['to_uid'], $mess );		
		//$push = new XingeApp ( $this->xinge_teacher ['accessId'], $this->xinge_teacher ['secretKey'] );
		//$ret2 = $push->PushSingleAccount ( XingeApp::DEVICE_ANDROID, '' . $data ['to_uid'], $mess );
		
		// ios		
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		$mess->setAlert ( $data ['title'].' ' );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$push = new XingeApp ( $this->xinge ['accessIdIOS'], $this->xinge ['secretKeyIOS'] );
		$ret3 = $push->PushSingleAccount ( XingeApp::DEVICE_IOS, ''.$data ['to_uid'], $mess, XingeApp::IOSENV_PROD );
		//$push = new XingeApp ( $this->xinge_teacher ['accessIdIOS'], $this->xinge_teacher ['secretKeyIOS'] );
		//$ret4 = $push->PushSingleAccount ( XingeApp::DEVICE_IOS, ''.$data ['to_uid'], $mess, XingeApp::IOSENV_PROD );
	
		//极光推送
		//$this->jpush_model->jpush_private($data);
		
		return ($ret2);
	}
	
	// 群聊 推送
	function xinge_group($data) {
		if(empty($data) || empty($data['group_tag'])) {
			return false;
		}
		
		$custom = array (
				'type' => 'group',
				'tag'=> $data ['group_tag'],
				'uid'=> $data ['uid'],
				'id' => $data ['id']);
		$tagList = array($data['group_tag']);
		
		$this->db->where ( 'id', $data ['id'] );
		$query = $this->db->get ( 'fly_groups_chats', 1 );
		$value = $query->row_array ();	
		if($value['isbase64'] == 1){
			$data['title'] = base64_decode($data['title']);	
		}
		
		
		// android
		$mess = new Message ();
		$mess->setExpireTime(24*60*60);
		$mess->setType ( Message::TYPE_MESSAGE );
		$mess->setTitle ( $data ['title'].' ' ); // 多个空间，不然只发图片就出问题
		$mess->setContent ( $data ['addtime'].' ' );		
		$mess->setCustom ( $custom );
		$push = new XingeApp ( $this->xinge ['accessId'], $this->xinge ['secretKey'] );
		$ret1 = $push->PushTags ( XingeApp::DEVICE_ANDROID, $tagList, 'OR', $mess );		
		
		// ios		
		//判断包含的才推送
		$textarr = array('#1003路况#','#930出行提示#');
		$str = $data ['title'];
		$isexist = false;
		foreach($textarr as $t){
			if(strstr($str,$t)){
				$isexist = true;
			}
		}
		
					
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		if($isexist){
		$mess->setAlert ( $data ['title'].' ' );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		}
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$push = new XingeApp( $this->xinge ['accessIdIOS'], $this->xinge ['secretKeyIOS'] );
		$ret2 = $push->PushTags ( XingeApp::DEVICE_IOS, $tagList, 'OR', $mess, XingeApp::IOSENV_PROD );
		
		if($data['type'] != 'weibo' ){
		//极光推送
		//	$jpush_re = $this->jpush_model->jpush_group($data);
		}
		
		return ($ret1);
	}	
	
	//路况推送
	function xinge_traffictext($data) {
		if(empty($data)) {
			return false;
		}
		
		$custom = array (
				'type' => 'traffictext',
				);
		
		// android
		$push = new XingeApp ( '2100086465', '77ae427b040e23530b862f97fb2570a5' );
		$mess = new Message ();
		$mess->setType ( Message::TYPE_NOTIFICATION );
		$mess->setExpireTime(24*60*60);
		//$mess->setTitle ( $data ['title'].' ' );
		$mess->setContent ( $data ['content'].' ' );
		$style = new Style ( 0 );
		// 义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style ( 0, 1, 1, 1, 0 );
		$action = new ClickAction ();
		$action->setActionType ( ClickAction::TYPE_ACTIVITY );
		$action->setActivity('com.edu.activity.AttendanceActivity');
		$mess->setStyle ( $style );
		$mess->setAction ( $action );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret1 = $push->PushAllDevices ( XingeApp::DEVICE_ANDROID, $mess );
		
		// ios					
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		$mess->setAlert ( $data ['content'].' ' );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$push = new XingeApp( $this->xinge ['accessIdIOS'], $this->xinge ['secretKeyIOS'] );
		$ret2 = $push->PushAllDevices ( XingeApp::DEVICE_IOS, $mess, XingeApp::IOSENV_PROD );
	}
	
	
	
	
	
	// 家长请假即时推送给班主任
	function leave2teacher($data) {
		$custom = array ('type' => 'leave' );
		
		// android
		$push = new XingeApp ( $this->xinge_teacher ['accessId'], $this->xinge_teacher ['secretKey'] );
		$mess = new Message ();
		$mess->setType ( Message::TYPE_NOTIFICATION );
		$mess->setExpireTime(24*60*60);
		$mess->setTitle ( $data ['title'].' ' );
		$mess->setContent ( $data ['addtime'].' ' );
		$style = new Style ( 0 );
		// 义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style ( 0, 1, 1, 1, 0 );
		$action = new ClickAction ();
		$action->setActionType ( ClickAction::TYPE_ACTIVITY );
		$action->setActivity('com.jnxx.teacher.activity.AskLeaveInfoActivity');
		$mess->setStyle ( $style );
		$mess->setAction ( $action );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret1 = $push->PushSingleAccount ( XingeApp::DEVICE_ANDROID, '' . $data ['uid'], $mess );
		
		// ios
		$push = new XingeApp ( $this->xinge_teacher ['accessIdIOS'], $this->xinge_teacher ['secretKeyIOS'] );
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		$mess->setAlert ( $data ['title'] );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret = $push->PushSingleAccount ( XingeApp::DEVICE_IOS, ''.$data ['uid'], $mess, XingeApp::IOSENV_PROD );
	
		return ($ret1);
	}
	
	
	// 班主任回复家长的请假信息 
	function leave2parents($data) {
		$custom = array ('type' => 'leave' );
		
		// android
		$push = new XingeApp ( $this->xinge['accessId'], $this->xinge ['secretKey'] );
		$mess = new Message ();
		$mess->setType ( Message::TYPE_NOTIFICATION );
		$mess->setExpireTime(24*60*60);
		$mess->setTitle ( $data ['title'].' ' );
		$mess->setContent ( $data ['addtime'].' ' );
		$style = new Style ( 0 );
		// 义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style ( 0, 1, 1, 1, 0 );
		$action = new ClickAction ();
		$action->setActionType ( ClickAction::TYPE_ACTIVITY );
		$action->setActivity('com.edu.activity.AskLeaveInfoActivity');
		$mess->setStyle ( $style );
		$mess->setAction ( $action );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret1 = $push->PushSingleAccount ( XingeApp::DEVICE_ANDROID, '' . $data ['uid'], $mess );
	
		// ios
		$push = new XingeApp ( $this->xinge ['accessIdIOS'], $this->xinge ['secretKeyIOS'] );
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		$mess->setAlert ( $data ['title'] );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$ret = $push->PushSingleAccount ( XingeApp::DEVICE_IOS, ''.$data ['uid'], $mess, XingeApp::IOSENV_PROD );
	
		return ($ret1);
	}
	
	// 课程表注意事项变更后，推送给该班家长们
	function push_timetable_tips($data) {
		if(empty($data) || empty($data['classname'])) {
			return false;
		}		
		$custom = array (
				'type' => 'timetable_tips',   
				'tag'=> $data ['classname'],
				'id' => $data ['id']);
		$tagList = array($data['classname']);
		
		// android
		$mess = new Message ();
		$mess->setType ( Message::TYPE_NOTIFICATION );
		$mess->setExpireTime(24*60*60);
		$mess->setTitle ( $data ['title'].' ' );
		$mess->setContent ( $data ['addtime'].' ' );
		$style = new Style ( 0 );
		// 义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style ( 0, 1, 1, 1, 0 );
		$action = new ClickAction ();
		$action->setActionType ( ClickAction::TYPE_ACTIVITY );
		$action->setActivity('com.edu.activity.CourseTableActivity');
		$mess->setStyle ( $style );
		$mess->setAction ( $action );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$push = new XingeApp ( $this->xinge ['accessId'], $this->xinge ['secretKey'] );
		$ret1 = $push->PushTags ( XingeApp::DEVICE_ANDROID, $tagList, 'OR', $mess );		
		
		// ios
		$mess = new MessageIOS ();
		$mess->setExpireTime(24*60*60);
		$mess->setAlert ( $data ['title'].' ' );
		// $mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge ( 1 );
		$mess->setSound ( "beep.wav" );
		$mess->setCustom ( $custom );
		$acceptTime1 = new TimeInterval ( 0, 0, 23, 59 );
		$mess->addAcceptTime ( $acceptTime1 );
		$push = new XingeApp( $this->xinge ['accessIdIOS'], $this->xinge ['secretKeyIOS'] );
		$ret2 = $push->PushTags ( XingeApp::DEVICE_IOS, $tagList, 'OR', $mess, XingeApp::IOSENV_PROD );
		
		return ($ret1);
	}	
	
}
