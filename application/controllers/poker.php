<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 通用页 默认控制器

class poker extends CI_Controller
{

	function index() {
	    $data = array();
	    if(isset($_GET['test'])){
	        $data['wx_info'] = array(
	            'Openid' => "lkl",
	            'NickName' => '测试',
	            'HeadImg' => './res/oREekjljkTwZVmxiNYUHMkDxQjPc.jpg'
	        );
	    }else{
	        $data['wx_info'] = array(
                'Openid' => trim($_REQUEST['openid']),
                'NickName' => $_REQUEST['nickName'],
                'HeadImg' => $_REQUEST['headPhoto']
            );
	    }

	    if(!$data['wx_info']['Openid']){
	        $this->load->view('tip',array('msg'=>'没有获取到用户信息！'));
	        return;
	    }


        //获取烟豆信息并跟新数据库
        //$my_YD = $this->getYD($data['wx_info']['Openid']);

        if($this->checkUser($data['wx_info']['Openid'])){
            $Udata['UpdateTime'] = time();
            $Udata['TotalGold'] = $this->getYD($data['wx_info']['Openid']);
            $this->updateUser($Udata,array('Openid'=>$data['wx_info']['Openid']));
        }else{
            $Udata['Openid'] = $data['wx_info']['Openid'];
            $Udata['NickName'] = $data['wx_info']['NickName'];
            $Udata['HeadImg'] = $data['wx_info']['HeadImg'];
            $Udata['AddTime'] = time();
            $Udata['TotalGold'] = 1000;//$my_YD
            $Udata['UpdateTime'] = time();
            $this->addUser($Udata);
        }

        $gamekey = $this->getKey();

        //存验证码
		$this->saveGameKey($data['wx_info']['Openid'],$gamekey);

        $data['wx_info']['TotalGold'] = $Udata['TotalGold'];
		$data['wx_info']['gamekey'] = $gamekey;




		$this->load->view('poker',$data);
	}


    /**
     *  A : 黑桃
     *  B ：红心
     *  C ：梅花
     *  D ：方块
     */
    public function main(){
        if($this->checkGameKey()){  //验证用户身份
            $openid = trim($_POST['openid']);
            //验证烟豆是否够下注
            $my_YD = $this->getYD($openid);
            $sum = intval($_POST['bet_num']);
            if($my_YD < $sum){  //判断龙币是否够下注
                $result = array('Code'=>-2,'Msg'=>'龙币不足');
                $this->addErrorLog(-2,'龙币不足');//添加记录到数据库
                echo json_encode($result);
                exit();
            }else{
                //控制扑克牌的生成
                $arr_hs = array(4=>"A",3=>"B",2=>"C",1=>"D");
                $arr_p_1 = array(4,3,2,1);  //玩家花色
                $arr_p_2 = array(1,2,3,4,5,6,7,8,9,10,11,12,13);//玩家点数
                $arr_b_1 = array(4,4,3,3,2,1);//庄家花色
                $arr_b_2 = array(1,2,3,4,5,6,6,7,7,7,8,8,8,9,9,9,10,10,10,11,11,12,12,13,13);//庄家点数
                $p_1 = $this->arr_rand($arr_p_1);
                $p_2 = $this->arr_rand($arr_p_2);
                $b_1 = $this->arr_rand($arr_b_1);
                $b_2 = $this->arr_rand($arr_b_2);
                //先判断玩家和庄家随机抽取的牌是否相同，相同则重新抽取
                if($b_2==$p_2&&$b_1==$p_1){
                    if($b_2==13){
                        $p_2 = ($p_2-1)%13;
                    }else{
                        $b_2 = ($b_2+1)%13;
                    }
                    $winner = "baker";
                }else{
                    if($b_2>$p_2){
                        $winner = "baker";
                    }elseif($b_2==$p_2){
                        if($b_1>$p_1){
                            $winner = "baker";
                        }else{
                            $winner = "player";
                        }
                    }else{
                        $winner = "player";
                    }
                }
                $p_1 = $arr_hs[$p_1];
                $b_1 = $arr_hs[$b_1];
                $result = array('Code'=>0,'Msg'=>'成功','p_1'=>$p_1,'p_2'=>$p_2,'b_1'=>$b_1,'b_2'=>$b_2,'winner'=>$winner,'bets'=>$sum);
            }
        }else{
            $result = array('Code'=>-1,'Msg'=>'数据异常');
            $this->addErrorLog(-1,'Gamekey不正确');//添加记录
        }
        echo json_encode($result);
    }

	/*
	*下注
	*Code : 0 成功， -1 gamekey验证失败， -2 烟豆不足 -3 其他
	*返回结果 : json格式  {Code:错误码,Count:骰子点数,Msg:提示信息,My_YD:结算后我的总烟豆,result:每个下注输赢情况}
	*/
	public function betOn() {

		if($this->checkGameKey()){//验证gamekey

			$betdata['Bet1'] = intval($_POST['1']);
			$betdata['Bet2'] = intval($_POST['2']);
			$betdata['Bet3'] = intval($_POST['3']);
			$betdata['Bet4'] = intval($_POST['4']);
			$betdata['Bet5'] = intval($_POST['5']);
			$betdata['Bet6'] = intval($_POST['6']);
			$betdata['BetBig'] = intval($_POST['big']);
			$betdata['BetSmall'] = intval($_POST['small']);
			$betdata['BetSingle'] = intval($_POST['single']);
			$betdata['BetDouble'] = intval($_POST['double']);

			$openid = trim($_POST['openid']);

			//验证烟豆是否够下注
			$my_YD = $this->getYD($openid);
			$sum = 0;
			foreach($betdata as $v){
				$sum += $v;
			}


			if($my_YD < $sum){
				$result = array('Code'=>-2,'Msg'=>'烟豆不足');
                $this->addErrorLog(-2,'烟豆不足');//添加记录到数据库
                echo json_encode($result);
                exit();
			}

			//$count = $this->getCount();//随机生成点数

			$count = $this->getCountByProbability($betdata);//控制概率生成点数

			//结算
			$jiesuan = array();
			$betCount = $betdata['Bet'.$count];//猜点数下注结算
			foreach($betdata as $k => $b){
				if($k == 'Bet'.$count){
					$jiesuan[$k] = $b * 5;
				}else{
					$jiesuan[$k] = -$b;
				}
			}

			//猜单双下注结算
			if($count % 2 == 0){
				//双
				$jiesuan['BetDouble'] = $betdata['BetDouble'];
				$jiesuan['BetSingle'] = -$betdata['BetSingle'];
			}else {
				//单
				$jiesuan['BetSingle'] = $betdata['BetSingle'];
                $jiesuan['BetDouble'] = -$betdata['BetDouble'];
			}

			//猜大小下注结算
			if($count >= 1 && $count <=3){
				//小
				$jiesuan['BetSmall'] = $betdata['BetSmall'];
                $jiesuan['BetBig'] = -$betdata['BetBig'];
			}else{
				//大
				$jiesuan['BetBig'] = $betdata['BetBig'];
                $jiesuan['BetSmall'] = -$betdata['BetSmall'];
			}

			//var_dump($jiesuan);

			//扣除烟豆
			$YDsum = 0;
			foreach($jiesuan as $v){
				$YDsum += $v;
			}

			if($YDsum >0){
				$My_YD = $this->addYD($openid,abs($YDsum));
			}else{
				$My_YD = $this->subYD($openid,abs($YDsum));
			}

			//下注信息写入数据库
			$BetOndata = $betdata;
			$BetOndata['Openid'] = $openid;
			$BetOndata['Result'] = $YDsum;
			$BetOndata['Rmark'] = array2string($jiesuan);
			$BetOndata['AddTime'] = time();

			$this->db->insert('zy_bet_on',$BetOndata);



			$result = array('Code'=>0,'Count'=>$count,'Msg'=>'成功','My_YD'=>$My_YD,'result'=>$jiesuan);


		}else{
			$result = array('Code'=>-1,'Msg'=>'数据异常');
			$this->addErrorLog(-1,'Gamekey不正确');//添加记录
		}


		echo json_encode($result);
	}

	//随机获取骰子点数
	private function getCount(){

		$rand = mt_rand(1, 6);

		return $rand;

	}

	//根据概率获取点数,待完善
	private function getCountByProbability($betdata){

		/*$betdata['Bet1'] = 50;
		$betdata['Bet2'] = 100;
		$betdata['Bet3'] = 50;
		$betdata['Bet4'] = 30;
		$betdata['Bet5'] = 50;
		$betdata['Bet6'] = 100;
		$betdata['BetBig'] = 50;
		$betdata['BetSmall'] = 0;
		$betdata['BetSingle'] = 30;
		$betdata['BetDouble'] = 20;*/

		$temp_arr = array();//6种推算结果
		$tuisuan = array();
		for($i=1;$i<7;$i++){
			$sum = 0;
			$betCount = $betdata['Bet'.$i];//猜点数下注结算
			foreach($betdata as $k => $b){
				if($k == 'Bet'.$i){
					$temp_arr[$i][$k] = $b * 5;

				}else{
					$temp_arr[$i][$k] = -$b;
				}
				switch ($k)
                {
                case 'Bet1':
                case 'Bet2':
                case 'Bet3':
                case 'Bet4':
                case 'Bet5':
                case 'Bet6':
                	$sum += $temp_arr[$i][$k];
                }

			}

			//猜单双下注结算
			if($i % 2 == 0){
				//双
				$temp_arr[$i]['BetDouble'] = $betdata['BetDouble'];
				$temp_arr[$i]['BetSingle'] = -$betdata['BetSingle'];
			}else {
				//单
				$temp_arr[$i]['BetSingle'] = $betdata['BetSingle'];
				$temp_arr[$i]['BetDouble'] = -$betdata['BetDouble'];
			}

			$sum += $temp_arr[$i]['BetSingle'];
			$sum += $temp_arr[$i]['BetDouble'];

			//猜大小下注结算
			if($i >= 1 && $i <=3){
				//小
				$temp_arr[$i]['BetSmall'] = $betdata['BetSmall'];
				$temp_arr[$i]['BetBig'] = -$betdata['BetBig'];
			}else{
				//大
				$temp_arr[$i]['BetBig'] = $betdata['BetBig'];
				$temp_arr[$i]['BetSmall'] = -$betdata['BetSmall'];
			}

			$sum += $temp_arr[$i]['BetBig'];
			$sum += $temp_arr[$i]['BetSmall'];

			$temp_arr[$i]['sum'] = $sum;
			$tuisuan[$i] = $sum;
		}
		arsort($tuisuan);
		//var_dump($tuisuan);

		$prize_arr = array(
			'0' => array('id'=>1,'prize'=>'1','v'=>100),
			'1' => array('id'=>2,'prize'=>'2','v'=>100),
			'2' => array('id'=>3,'prize'=>'3','v'=>100),
			'3' => array('id'=>4,'prize'=>'4','v'=>100),
			'4' => array('id'=>5,'prize'=>'5','v'=>100),
			'5' => array('id'=>6,'prize'=>'6','v'=>100),
		);

		$j = 0.01;
		foreach($tuisuan as $k =>$v){
			$prize_arr[$k-1]['v'] = $prize_arr[$k-1]['v']*$j;
			$j += 0.05;
		}

		//var_dump($prize_arr);

		foreach ($prize_arr as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}

		$rid = $this->get_rand($arr); //根据概率获取奖项id

		$res['yes'] = $prize_arr[$rid-1]['prize']; //中奖项

		//echo $res['yes'];
		return $res['yes'];

	}

	//检查用户数据库是否有记录
	private function checkUser($openid) {
	    if(!$openid){
	        return false;
	    }

	    return $this->db->get_where('zy_player',array('Openid'=>$openid))->num_rows();
	}

	private function addUser($Udata){
	    if(!$Udata){
	        return false;
	    }

	    return $this->db->insert('zy_player',$Udata);
	}

	private function updateUser($Udata,$where){
        if(!$Udata){
            return false;
        }

        return $this->db->update('zy_player', $Udata, $where);
    }

    private function getYD($openid) {//获取烟豆接口
        if(!$openid){
            return false;
        }

        $Pdata = $this->db->get_where('zy_player',array('Openid'=>$openid))->row_array();

        return $Pdata['TotalGold'];
    }

	//添加烟豆
    private function addYD($openid,$num){
    	if(!is_numeric($num) || $sum < 0){
    		return false;
    	}

    	$this->db->set('TotalGold', 'TotalGold+'.$num, FALSE);
    	$this->db->update('zy_player',array('Openid'=>$openid));

    	return $this->getYD($openid);

    }

    //扣除烟豆
	private function subYD($openid,$num){
		if(!is_numeric($num) || $sum < 0){
			return false;
		}

		$this->db->set('TotalGold', 'TotalGold-'.$num, FALSE);
		$this->db->update('zy_player',array('Openid'=>$openid));

		return $this->getYD($openid);
	}

    private function getKey(){
    	$src_str = "abcdefghijklmnopqrstuvwxyz0123456789";
    	return substr(str_shuffle($src_str),1,10);
    }

	//存gamekey
    private function saveGameKey($openid,$gamekey){
    	if(!$openid){
			return false;
		}
    	$num = $this->db->get_where('zy_session',array('Openid'=>$openid))->num_rows();

    	if($num){
    		$this->db->update('zy_session', array('GameKey'=>$gamekey,'AddTime'=>time()), array('Openid'=>$openid));

    	}else{
    		$this->db->insert('zy_session',array('Openid'=>$openid,'GameKey'=>$gamekey,'AddTime'=>time()));
    	}

    	//删除过期session 24小时
    	$time = time();
    	$this->db->delete('zy_session',"AddTime < ".($time - 24*3600));
    }

    //验证gamekey
   	private function checkGameKey(){
   		$key = trim($_POST['gamekey']);
   		$openid = trim($_POST['openid']);
   		$res = false;
   		if($this->db->get_where('zy_session',array('Openid'=>$openid,'GameKey'=>$key))->num_rows()){
   			$res = true;
   		}

   		return $res;

   	}

   	private function addErrorLog($code,$msg){
		$data['Openid'] = trim($_POST['openid']);
		$data['ErrorCode'] = $code;
		$data['Msg'] = $msg;
		$data['Browser'] = getbrowser();
		$data['Ip'] = ip();
		$data['ComeFrom'] = $_SERVER['HTTP_REFERER'];
		$data['AddTime'] = time();

		$this->db->insert('zy_log',$data);


   	}

    //从数组中随机获取一个数
    private function arr_rand($arr){
        $rand_keys = array_rand($arr);
        return $arr[$rand_keys];
    }

   	function get_rand($proArr) {
        $result = '';

        //概率数组的总概率精度
        $proSum = array_sum($proArr);

        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);

        return $result;
    }





}