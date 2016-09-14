<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    // 自定义 全局变量 by tangjian    

// 南宁 城区划分
$config['district'] = array(
        0=>'其他区域',
        1=>"西乡塘区",
        2=>"兴宁区",
        3=>"青秀区",
        4=>"江南区",
        5=>"良庆区",
        6=>"邕宁区"   
        );

// 数据表的 状态
$config['typename'] = array(0 => '默认',1 => '畅通', 2 => '缓行', 3 => '拥堵', 4 => '事故');
$config['traffic_category'] = array(1 => '路况', 2 => '爆料', 3 => '求助', 4 => '便民');

// 电台
// 电台
$config['radios'] = array(
        1 => '新闻综合广播台',
        2 => '930私家车',
        3 => '950MusicRadio',
        4 => '970女主播电台',
        5 => '广西交通台',
        6 => '北部湾之声',
		7 => '北部湾在线'
        );

// 电台
$config['radio'] = array(
        'name_910' => '新闻综合广播台',
        'name_970' => '970女主播电台',
        'name_950' => '950MusicRadio',
        'name_930' => '930私家车',
        'name_1003' => '广西交通台',
        'name_bbr' => '北部湾之声',
        );
// 电台 id
$config['radio_id'] = array(
        'id_910' => 1,
        'id_970' => 2,
        'id_950' => 3,
        'id_930' => 4,
        'id_1003' => 5,
        'id_bbr' => 6,
);

// 星期
$config['week'] = array(
        1 => '星期一',
        2 => '星期二',
        3 => '星期三',
        4 => '星期四',
        5 => '星期五',
        6 => '星期六',
        7 => '星期日',
);

// 管理员组
$config['user_category'] = array(
        1 => '超级管理',
        2 => '普通管理员'
        );

// 性别
$config['gender'] = array(
        0 => '女',
        1 => '男',
        2 => '保密'  
        );

// 数据表的 状态
$config['status'] = array(1 => '已审', 0 => '未审');
// 消息类型
$config['wxeTypes'] = array(
		//'text' => '文本消息',
		//'image' => '图片消息',
		//'voice' => '语音消息',
		//'video' => '视频消息',
		//'music' => '音乐消息',
		'news' => '图文消息'
);

// 电台
$config['group'] = array(
		'location' => '环江申遗',
		'910' => '新闻910',
		'930' => '私家车930',
		'950' => '950MusicRadio',
		'970' => '970女主播',
		'1003' => '交通1003',
		'bbr' => '北部湾之声',
		'bbrtv' => '北部湾在线',		
		);

// 举报表名 1私聊，2群聊，3路况,4路况评论，5留言,6问路
$config['report_tb'] = array(
				'1' => 'fly_chat',
				'2' => 'fly_groups_chats',
				'3' => 'fly_traffictext',
		        '4' => 'fly_comment',
				'5' => 'fly_guestbook',
				'6' => 'fly_askway',
				
);

$config['report_type'] = array(
				'1' => '私聊',
				'2' => '群聊',
				'3' => '路况',
		        '4' => '路况评论',
				'5' => '留言',
				'6' => '问路',
				
);


//信鸽推送  
$config['xinge'] = array(
		'accessId' => '2100049212',
		'secretKey' => '91ea20d635831dfe52d5e354b0990437',		
		'accessIdIOS' => '2200049236',
		'secretKeyIOS' => '682ad3753d43a4a75d4cdb8f44fbdd1a'
);

//极光推送 eb0b781441f85fe9f773e291
$config['jpush'] = array(
		//测试用账号
		//'appKey' => 'aec24e1807a19010300c6aec',
		//'masterSecret' => '05ed89c618654fd9e77baafa'
		'appKey' => 'eb0b781441f85fe9f773e291',
		'masterSecret' => 'c5a635872ebd4038cabe16e2'
		//'appKey' => 'c3942952c7494a6d4670fe26',
		//'masterSecret' => '3ffcfb344247a350241bb76a'

);

// 电台微博号 对应 的群 号
$config['weibo_ids'] = array(
		'1832083124' => array('groupid'=>1,'memberid'=>85), // 910
		'1912715061' => array('groupid'=>2,'memberid'=>86), // 930
		'1723167603' => array('groupid'=>3,'memberid'=>87), // 950		
		'1879179857' => array('groupid'=>4,'memberid'=>88), // 970
		'1838687261' => array('groupid'=>5,'memberid'=>89), // 1003	
		'2745590800' => array('groupid'=>6,'memberid'=>90), // bbr
		'2108118305' => array('groupid'=>7,'memberid'=>91), // bbrtv
		'1685878232' => array('groupid'=>8,'memberid'=>96), // chihe	
		'5037037307' => array('groupid'=>172,'memberid'=>418), // fengshangtiaopin		
		);

// 电台主播对应群号、会员号
$config['chat'] = array (		
		/*
		'1727470854' => array ('uid' => '171','groupid' => '117'),//大海
		'2017843205' => array ('uid' => '175','groupid' => '11'),//晓娟
		'1915566297' => array ('uid' => '188','groupid' => '124'),//晨冬
		'1865606165' => array ('uid' => '138','groupid' => '96'),//草莓女主播Nicole
		'1779988873' => array ('uid' => '181','groupid' => '55'),//Kevin
		'2017923657' => array ('uid' => '189','groupid' => '51'),//音华
		'1783754225' => array ('uid' => '137','groupid' => '17'),//文佳
		'2576293655' => array ('uid' => '114','groupid' => '16'),//韦波
		'1775218630' => array ('uid' => '197','groupid' => '14'),//维克
		'1919399755' => array ('uid' => '151','groupid' => '10'),//紫悦
		'1902378061' => array ('uid' => '150','groupid' => '36'),//乐乐 												
		'1937592233' => array ('uid' => '118','groupid' => '77'),//新闻910文鑫
		'2205591997' => array ('uid' => '182','groupid' => '62'),//950志翎
		'2116701604' => array ('uid' => '199','groupid' => '133'),//交通1003方毅
		'1783585693' => array ('uid' => '198','groupid' => '132'),//交通1003马小丽
		'1779685355' => array ('uid' => '169','groupid' => '59'),//950玉梓
		'1907437071' => array ('uid' => '125','groupid' => '41'),//新闻910张涛
		'2452151335' => array ('uid' => '128','groupid' => '86'),//新闻910原歌
		'1654097201' => array ('uid' => '166','groupid' => '115'),//930赛夫Sinehoo
		'2992527001' => array ('uid' => '164','groupid' => '113'),//930万鑫
	//	'1932512645' => array ('uid' => '219','groupid' => '149'),//广西风尚调频孟菲
		'2806408814' => array ('uid' => '113','groupid' => '74'),//新闻910安心
		'1427339355' => array ('uid' => '148','groupid' => '103'),//930辰晞
		'1830189965' => array ('uid' => '155','groupid' => '29'),//930马达
		'2196465113' => array ('uid' => '159','groupid' => '108'),//930大雄
		'3911926939' => array ('uid' => '200','groupid' => '134'),//交通1003张弛
		'1768525214' => array ('uid' => '201','groupid' => '135'),//交通1003霜霜
		'3901850855' => array ('uid' => '204','groupid' => '138'),//交通1003大滔
		'2308995373' => array ('uid' => '140','groupid' => '98'),//970女主播项阳
		'1339593637' => array ('uid' => '142','groupid' => '100'),//970女主播沈然
		'1750347587' => array ('uid' => '158','groupid' => '107'),//930木易
		'1863125083' => array ('uid' => '170','groupid' => '18'),//950刘炼
		'2482966944' => array ('uid' => '184','groupid' => '64'),//950小T
		'1779722897' => array ('uid' => '178','groupid' => '68'),//950叶倩
		'2025452865' => array ('uid' => '195','groupid' => '130'),//交通1003阳紫
		'1655917507' => array ('uid' => '190','groupid' => '125'),//交通1003麦琦
		'1779626341' => array ('uid' => '177','groupid' => '120'),//950覃晗
		'2526200502' => array ('uid' => '183','groupid' => '122'),//950莉香
		'2498635872' => array ('uid' => '174','groupid' => '118'),//950嘉绘
		'1817791237' => array ('uid' => '154','groupid' => '27'),//930羽菲
		'1793756527' => array ('uid' => '152','groupid' => '31'),//930安妮						
		'2485059925' => array ('uid' => '124','groupid' => '83'),//新闻910焦子
		'2794658642' => array ('uid' => '123','groupid' => '82'),//新闻910大喆
		'2017677023' => array ('uid' => '196','groupid' => '131'),//交通1003笑笑
		'1912660917' => array ('uid' => '192','groupid' => '127'),//交通1003紫涵	
		'1844897041' => array ('uid' => '156','groupid' => '105'),//930大表哥
		'2813012554' => array ('uid' => '162','groupid' => '111'),//930周妍
		'3071061747' => array ('uid' => '167','groupid' => '116'),//930阿亚
		'1920333540' => array ('uid' => '157','groupid' => '106'),//930黄经理
		'1706889797' => array ('uid' => '122','groupid' => '81'),//新闻910马晨
		'1851916004' => array ('uid' => '161','groupid' => '110')//930小薇
		*/
		
		//'2769856242' => array ('uid' => '1718','groupid' => '1178'),//wyb
		'2721483443' => array ('uid' => '111','groupid' => '12'),//910玉峰
		'1756744013' => array ('uid' => '112','groupid' => '73'),//910娅楠
		'2806408814' => array ('uid' => '113','groupid' => '74'),//910安心
		'2576293655' => array ('uid' => '114','groupid' => '16'),//910韦波
// 		'0000000' => array ('uid' => '115','groupid' => '75'),//910欣然
// 		'0000000' => array ('uid' => '116','groupid' => '76'),//910心子姐姐
		'1338240155' => array ('uid' => '117','groupid' => '88'),//910晓露
		'1937592233' => array ('uid' => '118','groupid' => '77'),//910文鑫
		'1907431213' => array ('uid' => '119','groupid' => '78'),//910程雪
		'1417205203' => array ('uid' => '120','groupid' => '79'),//910莫莉
		'1082788040' => array ('uid' => '121','groupid' => '80'),//910卫东
		'1706889797' => array ('uid' => '122','groupid' => '81'),//910马晨
		'2794658642' => array ('uid' => '123','groupid' => '82'),//910大喆
		'2485059925' => array ('uid' => '124','groupid' => '83'),//910焦子
		'1907437071' => array ('uid' => '125','groupid' => '41'),//910张涛
		'1819727761' => array ('uid' => '126','groupid' => '84'),//910刘璐
		'1231511107' => array ('uid' => '127','groupid' => '85'),//910张扬
		'2452151335' => array ('uid' => '128','groupid' => '86'),//910原歌
// 		'0000000' => array ('uid' => '129','groupid' => '87'),//910何流
		'1248501267' => array ('uid' => '130','groupid' => '89'),//910艺霖

		'1868191835' => array ('uid' => '131','groupid' => '90'),//970榴梿女主播卓然
		'1928674661' => array ('uid' => '132','groupid' => '91'),//970Orange女主播刘嘉
// 		'0000000' => array ('uid' => '133','groupid' => '92'),//970海棠果女主播王静
		'1816463105' => array ('uid' => '134','groupid' => '93'),//970芒果女主播芳菲
		'1636192937' => array ('uid' => '135','groupid' => '94'),//970荔枝女主播candy
		'1934412755' => array ('uid' => '136','groupid' => '95'),//970奇异果女主播郝心
		'1783754225' => array ('uid' => '137','groupid' => '17'),//970水蜜桃女主播文佳
		'1865606165' => array ('uid' => '138','groupid' => '96'),//970草莓女主播Nicole
		'1760686622' => array ('uid' => '139','groupid' => '97'),//970蓝莓女主播龙夜
		'2308995373' => array ('uid' => '140','groupid' => '98'),//970菠萝女主播项阳
		'1659983883' => array ('uid' => '141','groupid' => '99'),//970Apple女主播张一
		'1339593637' => array ('uid' => '142','groupid' => '100'),//970柠檬女主播沈然
		'1982122287' => array ('uid' => '143','groupid' => '101'),//970火龙果女主播欧阳璐
	
		'1920861753' => array ('uid' => '144','groupid' => '35'),//雨露
		'1465083991' => array ('uid' => '145','groupid' => '102'),//小鱼
// 		'0000000' => array ('uid' => '146','groupid' => '22'),//陈响
		'1919670687' => array ('uid' => '147','groupid' => '33'),//方原
		'1427339355' => array ('uid' => '148','groupid' => '103'),//辰晞
// 		'0000000' => array ('uid' => '149','groupid' => '104'),//凝霜
		'1902378061' => array ('uid' => '150','groupid' => '36'),//乐乐
		'1919399755' => array ('uid' => '151','groupid' => '10'),//紫悦
		'1793756527' => array ('uid' => '152','groupid' => '31'),//安妮
		'1919056500' => array ('uid' => '153','groupid' => '28'),//邱爽
		'1817791237' => array ('uid' => '154','groupid' => '27'),//羽菲
		'1830189965' => array ('uid' => '155','groupid' => '29'),//马达
		'1844897041' => array ('uid' => '156','groupid' => '105'),//大表哥
		'1920333540' => array ('uid' => '157','groupid' => '106'),//黄经理
		'1750347587' => array ('uid' => '158','groupid' => '107'),//木易
		'2196465113' => array ('uid' => '159','groupid' => '108'),//大雄
		'1759903077' => array ('uid' => '160','groupid' => '109'),//可乐
		'1851916004' => array ('uid' => '161','groupid' => '110'),//小薇
		'2813012554' => array ('uid' => '162','groupid' => '111'),//周妍
		'1882552071' => array ('uid' => '163','groupid' => '112'),//晨晨
		'2992527001' => array ('uid' => '164','groupid' => '113'),//万鑫
		'1238960682' => array ('uid' => '165','groupid' => '114'),//狗耳朵
		'1654097201' => array ('uid' => '166','groupid' => '115'),//赛夫
		'3071061747' => array ('uid' => '167','groupid' => '116'),//阿亚
		
		'1843550085' => array ('uid' => '168','groupid' => '60'),//宝琳
		'1779685355' => array ('uid' => '169','groupid' => '59'),//玉梓
		'1863125083' => array ('uid' => '170','groupid' => '18'),//刘炼
		'1727470854' => array ('uid' => '171','groupid' => '117'),//大海
		'1779962327' => array ('uid' => '172','groupid' => '57'),//黄莺
		'1779738337' => array ('uid' => '173','groupid' => '58'),//夏冰
		'2498635872' => array ('uid' => '174','groupid' => '118'),//嘉绘
		'2017843205' => array ('uid' => '175','groupid' => '11'),//晓娟
		'2018910205' => array ('uid' => '176','groupid' => '119'),//孙伟（孙嘉伟？）
		'1779626341' => array ('uid' => '177','groupid' => '120'),//覃晗
		'1779722897' => array ('uid' => '178','groupid' => '68'),//叶倩
		'1779631487' => array ('uid' => '179','groupid' => '121'),//芊语
		'1420330104' => array ('uid' => '180','groupid' => '56'),//林韬
		'1779988873' => array ('uid' => '181','groupid' => '55'),//Kevin
		'2205591997' => array ('uid' => '182','groupid' => '62'),//志翎
		'2526200502' => array ('uid' => '183','groupid' => '122'),//莉香
		'2482966944' => array ('uid' => '184','groupid' => '64'),//小T
		'3064904435' => array ('uid' => '185','groupid' => '123'),//小艾
		'1900784871' => array ('uid' => '186','groupid' => '63'),//大鹏
		
// 		'0000000' => array ('uid' => '187','groupid' => '61'),//郑刚
		'1915566297' => array ('uid' => '188','groupid' => '124'),//晨冬
		'2017923657' => array ('uid' => '189','groupid' => '51'),//音华
		'1655917507' => array ('uid' => '190','groupid' => '125'),//麦琦
		'1990609914' => array ('uid' => '191','groupid' => '126'),//少冰
		'1912660917' => array ('uid' => '192','groupid' => '127'),//紫涵
		'1871934285' => array ('uid' => '193','groupid' => '128'),//晓春
		'1662909514' => array ('uid' => '194','groupid' => '129'),//马小跳
		'2025452865' => array ('uid' => '195','groupid' => '130'),//阳紫
		'2017677023' => array ('uid' => '196','groupid' => '131'),//笑笑
		'1775218630' => array ('uid' => '197','groupid' => '14'),//维克
		'1783585693' => array ('uid' => '198','groupid' => '132'),//马小丽
		'2116701604' => array ('uid' => '199','groupid' => '133'),//方毅
		'3911926939' => array ('uid' => '200','groupid' => '134'),//张弛
		'1768525214' => array ('uid' => '201','groupid' => '135'),//霜霜
// 		'0000000' => array ('uid' => '202','groupid' => '136'),//周航
// 		'0000000' => array ('uid' => '203','groupid' => '137'),//赵川
		'3901850855' => array ('uid' => '204','groupid' => '138'),//大滔

// 		'0000000' => array ('uid' => '205','groupid' => '139'),//秦杰
// 		'0000000' => array ('uid' => '206','groupid' => '71'),//谈婕
		'1934143604' => array ('uid' => '207','groupid' => '70'),//苏岩
// 		'0000000' => array ('uid' => '208','groupid' => '140'),//茴茴
// 		'0000000' => array ('uid' => '209','groupid' => '141'),//光明
// 		'0000000' => array ('uid' => '200','groupid' => '142'),//黄山
// 		'0000000' => array ('uid' => '210','groupid' => '143'),//李江
// 		'0000000' => array ('uid' => '211','groupid' => '144'),//长城
// 		'0000000' => array ('uid' => '212','groupid' => '145'),//志强
// 		'0000000' => array ('uid' => '213','groupid' => '72'),//小丁
// 		'0000000' => array ('uid' => '214','groupid' => '146'),//永阳
// 		'0000000' => array ('uid' => '215','groupid' => '147'),//青夏
// 		'0000000' => array ('uid' => '216','groupid' => '148'),//梁烨
// 		'0000000' => array ('uid' => '217','groupid' => '39'),//黄媛
		
		'2110701223' => array ('uid' => '225','groupid' => '155'),//王琳（王小木）

/*
 *  	不能确定群的主播：4个
		'2721483443' => array ('uid' => '21','groupid' => '**'),//930石头stone
		'1835789195' => array ('uid' => '102','groupid' => '**'),//交通1003小胖
		'2464122922' => array ('uid' => '40','groupid' => '**'),//950大头
		'1779709975' => array ('uid' => '187','groupid' => '**'),//交通1003谢振纲


		不能确定群以及会员号的主播：48个
		'1921012327' => array ('uid' => '277','groupid' => '**'),//970女主播电台杰出
		'2257065181' => array ('uid' => '277','groupid' => '**'),//新闻910屯屯
		'1775283083' => array ('uid' => '277','groupid' => '**'),//新闻910彭龙
		'2385078494' => array ('uid' => '277','groupid' => '**'),//广西交通广播总监-梁海泉		
		'1811497582' => array ('uid' => '277','groupid' => '**'),//交通1003宁倩
		'1648597252' => array ('uid' => '277','groupid' => '**'),//交通1003鄢语
		'1270170800' => array ('uid' => '277','groupid' => '**'),//交通1003大光	
		'1809282007' => array ('uid' => '277','groupid' => '**'),//交通1003欣欣
		'1918803940' => array ('uid' => '277','groupid' => '**'),//930柠双
		'2018910205' => array ('uid' => '277','groupid' => '**'),//950孙嘉伟
		'1840337317' => array ('uid' => '277','groupid' => '**'),//交通1003治文
		'1822267463' => array ('uid' => '277','groupid' => '**'),//950霞红了
		'1676057657' => array ('uid' => '277','groupid' => '**'),//950麦田
		'1932978935' => array ('uid' => '277','groupid' => '**'),//950原野
		'1776544967' => array ('uid' => '277','groupid' => '**'),//970宋扬
		'1843268015' => array ('uid' => '277','groupid' => '**'),//南宁电台DJ李瑶
		'1779631487' => array ('uid' => '277','groupid' => '**'),//950刘芊语
		'2008559287' => array ('uid' => '277','groupid' => '**'),//950悦熙
		'1580253382' => array ('uid' => '277','groupid' => '**'),//950罗东
		'1456940244' => array ('uid' => '277','groupid' => '**'),//950廖婕
		'1919060025' => array ('uid' => '277','groupid' => '**'),//930周璇
		'1816463105' => array ('uid' => '277','groupid' => '**'),//970女主播方菲	
		'1245745893' => array ('uid' => '277','groupid' => '**'),//新闻910蔡小葱
		'3939435130' => array ('uid' => '277','groupid' => '**'),//新闻910小明
		'3502533581' => array ('uid' => '277','groupid' => '**'),//新闻910海风
		'2007491430' => array ('uid' => '277','groupid' => '**'),//新闻910子墁
		'3931170612' => array ('uid' => '277','groupid' => '**'),//新闻910李国团
		'3931174635' => array ('uid' => '277','groupid' => '**'),//新闻910容福钰
		'3931174151' => array ('uid' => '277','groupid' => '**'),//新闻910潘鸿武
		'3931189923' => array ('uid' => '277','groupid' => '**'),//新闻910黎森
		'1248501267' => array ('uid' => '277','groupid' => '**'),//新闻910Ciny艺霖
		'2039169001' => array ('uid' => '277','groupid' => '**'),//新闻910文婷
		'1602840007' => array ('uid' => '277','groupid' => '**'),//930嘉骅
		'2654551363' => array ('uid' => '277','groupid' => '**'),//新闻910石老师
		'2805261170' => array ('uid' => '277','groupid' => '**'),//新闻910灯火阑珊
		'2284323801' => array ('uid' => '277','groupid' => '**'),//新闻910大猫
		'2781735907' => array ('uid' => '277','groupid' => '**'),//新闻910宇小佳
		'1580526724' => array ('uid' => '277','groupid' => '**'),//新闻910阿甘
		'3444706660' => array ('uid' => '277','groupid' => '**'),//930路可
		'2032430213' => array ('uid' => '277','groupid' => '**'),//交通1003小马哥
		'1082287735' => array ('uid' => '277','groupid' => '**'),//970女主播电台李烨彤
		'1629833692' => array ('uid' => '277','groupid' => '**'),//新闻910河流
		'1438049087' => array ('uid' => '277','groupid' => '**'),//970黄海
		'2260899523' => array ('uid' => '277','groupid' => '**'),//970女主播畅鹤
		'1840763133' => array ('uid' => '277','groupid' => '**'),//930小桐
		'1937837822' => array ('uid' => '277','groupid' => '**'),//930陈男
		'1238960682' => array ('uid' => '277','groupid' => '**'),//930狗耳多
		'2349573693' => array ('uid' => '277','groupid' => '**'),//930刘个个
		'1759903077' => array ('uid' => '277','groupid' => '**')//930可乐小表妹
*/

			);
		
$config['menu_lists'] = array(	
					  'main_menu'=> array(
							  'stat' => '统计展示',
							  'traffictext' => '交通路况',
							  'news' => '内容管理',
							  'groups' => '群聊管理',
							  'weibo' => '数据聚合',
							  'weixin_lk' => '微信回复',
							  'member' => '会员管理',
							  'admin' => '系统管理',
						  ),
					  'traffictext'=> array(
									0 => array (
										'traffictext_list' => '路况管理',
										'traffictext_add' => '路况添加',
										'traffictext_check' => '路况审核',
										'traffictext_edit' => '路况编辑',
										'traffictext_del' => '路况删除',
									  ),
									1 => array (
										'trafficmap' => '路况地图',
										'trafficmap_add' => '路况地图添加',									
										'trafficmap_del' => '路况地图删除',
									),
									2 => array (
										'singleline' => '禁左/单行线',
										'singleline_add' => '禁左/单行线添加',									
										'singleline_del' => '禁左/单行线删除',
									),
								    3 => array (
										'comment' => '路况评论',
										'comment_edit' => '路况评论编辑',									
										'comment_del' => '路况评论删除',
									),
									4 => array (
										'traffictext_reports' => '路况举报',
										'traffictext_reports_check' => '路况举报编辑',									
										'traffictext_reports_del' => '路况举报删除',
									),
						  ),
						  
						  'news'=> array(
									0 => array (
										'news_list' => '新闻播报',
										'news_add' => '新闻播报添加',
										'news_template_manager' => '新闻播报模板管理',
										'news_template' => '新闻播报模板显示',
										'gather' => '采集网上新闻',
										'list910' => '采集系列台新闻',
										'news_edit' => '新闻播报编辑',
										'news_del' => '新闻播报删除',
									  ),
									1 => array (
										'notice' => '滚动通知',
										'notice_add' => '滚动通知添加',
										'notice_edit' => '滚动通知编辑',									
										'notice_del' => '滚动通知删除',
									),
									2 => array (
										'action' => '活动信息',
										'action_add' => '活动信息添加',
										'action_edit' => '活动信息编辑',									
										'action_del' => '活动信息删除',
									),
								    3 => array (
										'schedule' => '节目表',
										'schedule_add' => '节目表添加',
										'schedule_edit' => '节目表编辑',									
										'schedule_del' => '节目表删除',
									),
									4 => array (
										'guestbook' => '留言反馈',										
										'guestbook_edit' => '留言反馈编辑',									
										'guestbook_del' => '留言反馈删除',
									),
									5 => array (
										'radio' => '电台管理',
										'radio_add' => '电台添加',
										'radio_check' => '电台审核',
										'radio_edit' => '电台编辑',
										'radio_del' => '电台删除',
									  ),
									6 => array (
										'message' => '私信管理',										
										'message_check' => '私信审核',
										'message_edit' => '私信编辑',
										'message_del' => '私信删除',
									  ), 
									7 => array (
										'askway' => '问路管理',										
										'askway_check' => '问路审核',
										'askway_edit' => '问路编辑',
										'askway_del' => '问路删除',
									  ), 
									8 => array (
										'askanswer' => '问路回答',
										'askanswer_edit' => '问路回答编辑',
										'askanswer_del' => '问路回答删除',
									  ), 
									9 => array (
										'report_all' => '举报管理',
										'report_all_edit' => '举报编辑',
										'report_all_del' => '举报删除',
									  ),      
						  ),
						  
						  
					  'groups'=> array(		
									0 => array (
										'groups_list' => '群管理',	
										'groups_add' => '群添加',									
										'groups_check' => '群审核',
										'groups_edit' => '群编辑',
										'groups_del' => '群删除',
									  ),
									1 => array (
										'groups_chats' => '群聊信息',
										'groups_chats_add' => '群聊信息添加',
										'groups_chats_edit' => '群聊信息编辑',									
										'groups_chats_del' => '群聊信息删除',
									),
						  ),
						
						
						'weixin_lk'=> array(		
									0 => array (
										'weixin_lk_list' => '城区路况',	
										'weixin_lk_add' => '城区路况添加',
										'weixin_lk_edit' => '城区路况编辑',
										'weixin_lk_del' => '城区路况删除',
									  ),
									1 => array (
										'weixin_gslk_list' => '高速路况',	
										'weixin_gslk_add' => '高速路况添加',
										'weixin_gslk_edit' => '高速路况编辑',
										'weixin_gslk_del' => '高速路况删除',
									),
									2 => array (
										'weixin_sg_list' => '施工回复',	
										'weixin_sg_add' => '施工回复添加',
										'weixin_sg_edit' => '施工回复编辑',
										'weixin_sg_del' => '施工回复删除',
									),
									3 => array (
										'music_list' => '表情音乐回复',
										'music_edit' => '表情音乐回复编辑',											
									),
									4 => array (
										'weixin_menu' => '自定义菜单',	
										'weixin_menu_saveandapply' => '自定义菜单保存并同步',
										'weixin_menu_edit' => '自定义菜单保存',
										'weixin_menu_del' => '自定义菜单删除',
									),
									5 => array (
										'weixin_text_list' => '微信文本回复',	
										'weixin_text_add' => '微信文本回复添加',
										'weixin_text_edit' => '微信文本回复编辑',
										'weixin_text_del' => '微信文本回复删除',
									),
									6 => array (
										'weixin_news_list' => '微信图文回复',	
										'weixin_news_add' => '微信图文回复添加',
										'weixin_news_edit' => '微信图文回复编辑',
										'weixin_news_del' => '微信图文回复删除',
									),
									7 => array (
										'weixin_set' => '微信通用设置',
										'weixin_set_save' => '微信通用设置编辑',											
									),
									8 => array (
										'news_title' => '图片新闻',	
										'news_title_add' => '图片新闻添加',									
										'news_title_check' => '图片新闻预览',
										'news_title_edit' => '图片新闻编辑',
										'news_title_del' => '图片新闻删除',
									  ),
						  ),  
					 
		  				'member'=> array(		
									0 => array (
										'member_list' => '会员列表',	
										'member_add' => '会员添加',									
										'member_check' => '会员审核',
										'member_edit' => '会员编辑',
										'member_del' => '会员删除',
									  ),
									
						  ),
						  
						'admin'=> array(		
									0 => array (
										'admin_list' => '管理员管理',	
										'admin_add' => '管理员添加',
										'admin_edit' => '管理员编辑',
										'admin_del' => '管理员删除',
									  ),
									  
									1 => array (
										'admin_set' => '系统通用设置',
										'admin_set_save' => '系统通用设置编辑',											
									),  
									2 => array (
										'adminlog' => '操作日志',																			
									), 
									3 => array (
										'loginlog' => '访问日志',																			
									),  
									4 => array (
										'cache' => '更新缓存',																			
									), 
									5 => array (
										'role_list' => '角色管理',	
										'role_add' => '角色添加',
										'role_edit' => '角色编辑',
										'role_del' => '角色删除',
									  ), 
									6 => array (
										'permission' => '角色权限管理',
										'permission_edit' => '角色权限编辑',	
										
									  ), 
									7 => array (
											'badword_list' => '敏感词管理',	
											'badword_add' => '敏感词添加',
											'badword_edit' => '敏感词编辑',
											'badword_del' => '敏感词删除',
										  ),	  
									
						  ),
						
					);						