<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
	// 极光推送 模型
require_once ('vendor/autoload.php');
use JPush\Model as M;
use JPush\JPushClient;
use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class jpush_model extends CI_Model {
	private $jpush = '';	
	
	function __construct() {
		parent::__construct ();
		
		error_reporting(0);
		
		$this->jpush = config_item ( 'jpush' );		
	}
	
	function test() {
		echo "123";
	}
	
	
	function jpush_news($data) {
		
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
		
		return $ret2;
	}
	
	// 推送进出校门 刷卡信息
	function jpush_inout($data) {	
//  		$data['alias'] = array('888777');
// 		$data['notification'] = "你好呀22222";
		try {
		$client = new JPushClient($this->jpush['appKey'], $this->jpush['masterSecret']);		
		$result = $client->push()->setPlatform(M\all)
// 		->setAudience(M\audience(M\alias($data['alias'])))
// 		->setNotification(M\notification($data['notification']))
		->setAudience(M\audience(M\alias($data['uid'])))
		->setNotification(M\notification($data['title']))
		->send();
		} catch ( APIRequestException $e ) {
			echo "APIRequestException occur!";
		} catch ( APIConnectionException $e ) {
			echo "APIConnectionException occur!";
		}
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
	
		/**
	 * 私聊
	 * 
	 * @param array $data
	 * @return string
	 */
	function  jpush_private($data) {		
		for($i = 0; $i < count($data['to_uid']); $i++) {
			$data['to_uid'][$i] = $data['to_uid'][$i].'';
		}
		try {
			$client = new JPushClient ( $this->jpush ['appKey'], $this->jpush ['masterSecret'] );
			$result = $client->push ()
			->setPlatform ( M\all )			// 			->setPlatform ( M\platform("ios") )
			->setAudience ( M\audience ( M\alias ( $data ['to_uid'] ) ) )->setMessage ( M\message ( $data['addtime'], $data['title'], "content_type", $data) )->send ();
		} catch ( APIRequestException $e ) {
			$result = "APIRequestException occur!";
		} catch ( APIConnectionException $e ) {
			$result = "APIConnectionException occur!";
		}
		return $result;
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
		
		// android
		$mess = new Message ();
		$mess->setType ( Message::TYPE_MESSAGE );
		$mess->setExpireTime(24*60*60);
		$mess->setTitle ( $data ['title'].' ' );
		$mess->setContent ( $data ['addtime'].' ' );		
		$mess->setCustom ( $custom );
		$push = new XingeApp ( $this->xinge ['accessId'], $this->xinge ['secretKey'] );
		$ret1 = $push->PushSingleAccount ( XingeApp::DEVICE_ANDROID, '' . $data ['to_uid'], $mess );		
		$push = new XingeApp ( $this->xinge_teacher ['accessId'], $this->xinge_teacher ['secretKey'] );
		$ret2 = $push->PushSingleAccount ( XingeApp::DEVICE_ANDROID, '' . $data ['to_uid'], $mess );
		
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
		$push = new XingeApp ( $this->xinge_teacher ['accessIdIOS'], $this->xinge_teacher ['secretKeyIOS'] );
		$ret4 = $push->PushSingleAccount ( XingeApp::DEVICE_IOS, ''.$data ['to_uid'], $mess, XingeApp::IOSENV_PROD );
	
		return ($ret2);
	}
	
/**
	 * 群聊 推送
	 * 
	 * @param array $data
	 * @return string
	 */
	function jpush_group($data) {		
		for($i = 0; $i < count($data['group_tag']); $i++) {
			$data['group_tag'][$i] = $data['group_tag'][$i].'';
		}
		$tagList = array($data['group_tag']);
		try {
			$client = new JPushClient ( $this->jpush ['appKey'], $this->jpush ['masterSecret'] );
			$result = $client->push ()->setPlatform ( M\all )->setAudience ( M\audience ( M\tag ( $tagList ) ) )->setMessage ( M\message ( $data['addtime'], $data['title'], "content_type", $data ) )->send ();
		//	print_r ( $result );
		} catch ( APIRequestException $e ) {
			$result = "APIRequestException occur!";
		} catch ( APIConnectionException $e ) {
			$result = "APIConnectionException occur!";
		}
		return	$result;
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
