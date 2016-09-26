/**
 * Created by lkl on 2016/8/12.
 */

var G_ThouchLayer = cc.Layer.extend({
    sprite: null,
    curr_selected_OBJ: null,//当前选中的押号按钮
    curr_bet_obj: null,//当前选中的下注按钮
    bet_on_obj: null,   //存放押注对象
    my_YD: null,//我的烟豆(与数据库同步)
    UI_YD: null,//UI显示的烟豆数
    show_xz: null,    //游戏底部下注数组
    poker_value:null,//玩家背景牌精灵
    poker_value2:null,//庄家背景牌精灵
    player_num:null,//玩家、庄家的牌数（从后台异步获取）
    bgmusic_flag:null,
    MusicSet:null,
    EffectsSet:null,

    ctor: function () {
        // 1. super init first
        this._super();
        var self = this;
        this.WinSize = cc.winSize;  //获取当前游戏窗口大小
        this.my_YD = wx_info.total_gold;
        this.UI_YD = wx_info.total_gold;
        this.MusicSet = wx_info.MusicSet;
        this.EffectsSet = wx_info.EffectsSet;

        //喇叭公告
        setXlbText(this);
        this.schedule(function noting() {
                setXlbText(self);
            }, 20
        );

        this.initBgMusicBtn();//设置喇叭播放按钮位置

        this.initBgMusicStopBtn();//设置喇叭禁止播放按钮位置

        this.initBgMusic(); //播放背景音乐

        this.initBetOnObj();//初始化押注值：0

        this.initPokerBox();//设置扑克盒子位置

        this.initBetBoxArea();//设置投注框位置

        this.initBetArea();//设置投注值位置（10 20 50 100）

        this.initChipsArea(); //设置已押注的图标（点击下注前，隐藏）

        this.initXzArea();//设置下注数值（点击下注前，隐藏）

        this.initStartArea();//设置开始按钮位置（点击下注前，隐藏）

        this.initShowDownArea();//设置摊牌按钮位置（点击下注前，隐藏）

        this.initReadyArea();//设置准备按钮位置（点击下注前，隐藏）

        //this.schedule(this.updateShow, 0.5);    //定时函数，每0.5秒执行一次updateShow函数

        return true;
    },

    //播放背景音乐
    initBgMusic: function () {
        if(this.MusicSet){
            cc.audioEngine.playMusic(res.s_bg_music,true);
            this._s_horn_menu.setVisible(true);
        }else{
            cc.audioEngine.stopMusic();
            this._s_hornStop_menu.setVisible(true);
        }

    },

    initBgMusicBtn: function () {
        this.s_hornArea = new cc.MenuItemImage(res.s_horn,res.s_horn,this.BgMusicCallback,this);
        this.s_hornArea.attr({
            x:this.s_hornArea.width,
            y:450
        });
        this._s_horn_menu = new cc.Menu(this.s_hornArea);
        this._s_horn_menu.x=0;
        this._s_horn_menu.y=0;
        this.addChild(this._s_horn_menu);
        this._s_horn_menu.setVisible(false);
    },

    initBgMusicStopBtn: function () {
        this.s_hornStopArea = new cc.MenuItemImage(res.s_stop_horn,res.s_stop_horn,this.BgMusicCallback,this);
        this.s_hornStopArea.attr({
            x:this.s_hornStopArea.width,
            y:450
        });
        this._s_hornStop_menu = new cc.Menu(this.s_hornStopArea);
        this._s_hornStop_menu.x=0;
        this._s_hornStop_menu.y=0;
        this.addChild(this._s_hornStop_menu);
        this._s_hornStop_menu.setVisible(false);
    },

    BgMusicCallback: function(){
        if(this.MusicSet){
            cc.audioEngine.stopMusic();
            this._s_hornStop_menu.setVisible(true);
            this._s_horn_menu.setVisible(false);
            this.MusicSet = 0;
        }else{
            cc.audioEngine.playMusic(res.s_bg_music,true);
            this._s_hornStop_menu.setVisible(false);
            this._s_horn_menu.setVisible(true);
            this.MusicSet = 1;
        }

        var xhr = cc.loader.getXMLHttpRequest();
        xhr.open("POST", "index.php?c=poker&m=set_music");
        //set Content-type "text/plain;charset=UTF-8" to post plain text
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status >= 200 && xhr.status <= 207) {
                    var response = eval("("+xhr.responseText+")");//接收服务器返回结果
                    if(response.Code == 0){
                        //cc.log(response.MusicSet);
                    }else{
                        alert(response.Msg);
                    }
                }else {
                    alert('网络故障，请稍后再试试~');
                }
            }
        };
        xhr.send("MusicSet="+this.MusicSet+"&Openid="+wx_info.openid);//发送下注信息到服务器

    },

    //初始化押注值：0
    initBetOnObj: function () {
        this.bet_on_obj = {'total': 0};
    },

    //设置扑克盒子位置
    initPokerBox: function () {
        this.pokerboxArea = new cc.Sprite(res.s_poker_box);
        this.pokerboxArea.attr({
            x:600,
            y:350
        });
        this.addChild(this.pokerboxArea);
    },

    //设置投注框位置
    initBetBoxArea: function () {
        this.s_bet_boxArea = new cc.Sprite(res.s_bg_bet);
        this.s_bet_boxArea.attr({
            x:(this.WinSize.width)/2,
            y:30
        });
        this.addChild(this.s_bet_boxArea);
    },

    //设置投注值位置（10 20 50 100）
    initBetArea: function () {
        var PositionY = 30;
        this._bet_10 = new cc.MenuItemImage(res.s_bet10,res.s_bet10, this.betCallBack, this);
        this._bet_10.attr({
            x: this.s_bet_boxArea.width/2  - 100,
            y: PositionY,
            bet_num: 10
        });

        this._bet_20 = new cc.MenuItemImage(res.s_bet20,res.s_bet20, this.betCallBack, this);
        this._bet_20.attr({
            x: this.s_bet_boxArea.width/2  - 35,
            y: PositionY,
            bet_num: 20
        });

        this._bet_50 = new cc.MenuItemImage(res.s_bet50,res.s_bet50, this.betCallBack, this);
        this._bet_50.attr({
            x: this.s_bet_boxArea.width/2  + 35,
            y: PositionY,
            bet_num: 50
        });

        this._bet_100 = new cc.MenuItemImage(res.s_bet100,res.s_bet100, this.betCallBack, this);
        this._bet_100.attr({
            x: this.s_bet_boxArea.width/2  + 100,
            y: PositionY,
            bet_num: 100
        });

        this._bet_menu = new cc.Menu(this._bet_10,this._bet_20,this._bet_50,this._bet_100);
        this._bet_menu.attr({
            x: 0,
            y: 0
        });
        this.s_bet_boxArea.addChild(this._bet_menu);
    },
    //投注之后回调函数：依次累加每次投注的值
    betCallBack: function (sender){
        var effect_ya = cc.audioEngine.playEffect(res.s_ya,false);
        //cc.audioEngine.stopEffect(effect_ya);
        if(this.checkYD(this.bet_on_obj.total+sender.bet_num)){
            this.s_chipsArea.setVisible(true);
            this.show_xz.setVisible(true);
            this._start_menu.setVisible(true);
            this.bet_on_obj.total += sender.bet_num;    //累加每次投注的值
            this.show_xz.setString(this.bet_on_obj.total); //设置文本框中的文本
            this.UI_YD -= sender.bet_num;
            BG_Object._mybean.setString('我的龙币：'+this.UI_YD); //设置文本框中的文本
        }else{
            alert('龙币不足！');
        }
    },

    //已押注的图标
    initChipsArea: function(){
        this.s_chipsArea = new cc.Sprite(res.s_chips);
        this.s_chipsArea.attr({
            x:(this.WinSize.width)/2,
            y:150
        });
        this.addChild(this.s_chipsArea);
        this.s_chipsArea.setVisible(false);
    },

    //总的押注数值
    initXzArea:function(){
        var fontColor = new cc.Color(255, 255, 0);  //实列化颜色对象
        this.show_xz = new cc.LabelTTF('0', 'Arial', 20);
        this.show_xz.attr({
            x: (this.WinSize.width)/2,
            y: 180,
            anchorX: 0.5,
            anchorY: 0.5
        });
        this.show_xz.setColor(fontColor);
        this.addChild(this.show_xz);
        this.show_xz.setVisible(false);
    },

    //“开始发牌”按钮
    initStartArea:function(){
        this.s_btn_startArea = new cc.MenuItemImage(res.s_btn_start,res.s_btn_start,this.beginCallback,this);
        this.s_btn_startArea.attr({
            x:(this.WinSize.width)/2,
            y:250
        });
        this._start_menu = new cc.Menu(this.s_btn_startArea);
        this._start_menu.x=0;
        this._start_menu.y=0;
        this.addChild(this._start_menu);
        this._start_menu.setVisible(false);
    },

    //准备按钮
    initReadyArea:function(){
        this.s_btn_readyArea = new cc.MenuItemImage(res.s_btn_ready,res.s_btn_ready,this.readyCallback,this);
        this.s_btn_readyArea.attr({
            x:(this.WinSize.width)/2,
            y:160
        });
        this._ready_menu = new cc.Menu(this.s_btn_readyArea);
        this._ready_menu.x=0;
        this._ready_menu.y=0;
        this.addChild(this._ready_menu);
        this._ready_menu.setVisible(false);
    },
    //点击“准备”按钮后的回调函数：隐藏当前显示的扑克、准备按钮、摊牌按钮；显示投注框、投注的值
    readyCallback:function(){
        this.poker_value.setVisible(false);
        this.poker_value2.setVisible(false);
        this.s_bet_boxArea.setVisible(true);
        this._bet_menu.setVisible(true);
        this._ready_menu.setVisible(false);
        this._s_show_down_menu.setVisible(false);
        //上一盘的下注值清零
        this.bet_on_obj.total = 0;
        BG_Object._mywinbean.setString('本次赚取：' + 0);
    },

    //“摊牌”
    initShowDownArea:function(){
        this.s_show_downArea = new cc.MenuItemImage(res.s_btn_show,res.s_btn_show,this.resultCallback,this);
        this.s_show_downArea.attr({
            x:(this.WinSize.width)/2+200,
            y:50
        });
        this._s_show_down_menu = new cc.Menu(this.s_show_downArea);
        this._s_show_down_menu.x=0;
        this._s_show_down_menu.y=0;
        this.addChild(this._s_show_down_menu);
        this._s_show_down_menu.setVisible(false);
    },

    //发牌动作
    beginCallback:function(){
        var effect_send = cc.audioEngine.playEffect(res.s_send,false);
        var self=this;
        cc.spriteFrameCache.addSpriteFrames(res.s_card_plist);
        //异步
        var xhr = cc.loader.getXMLHttpRequest();
        xhr.open("POST", "index.php?c=poker&m=main");
        //set Content-type "text/plain;charset=UTF-8" to post plain text
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status >= 200 && xhr.status <= 207) {
                    var response = eval("("+xhr.responseText+")");//接收服务器返回结果
                    self.player_num =response ; //玩家、庄家的牌数赋值给全景变量
                    if(response.Code == 0){
                        //给闲家发背面牌
                        self.resultAreaHide();
                        self.poker_value  = new cc.Sprite(res.s_bg_poker);
                        self.poker_value.attr({
                            x:550,
                            y:400
                        });
                        self.addChild( self.poker_value );
                        var action1 = cc.moveTo(0.5,cc.p(self.WinSize.width/2, self.poker_value.height/2));
                        var callback = cc.callFunc(self.showCallBack,self);
                        var sequence = cc.sequence(action1,callback);
                        self.poker_value.runAction(sequence);
                        self.s_show_downArea.setVisible(true);
                        //玩家翻牌
                        self.scheduleOnce(function(){
                            var p1 = self.player_num.p_1;
                            var p2 = self.player_num.p_2;
                            var player_poker = new cc.Sprite('#'+p1+'_'+p2+'.png');
                            player_poker.attr({
                                x : self.poker_value.width/2,
                                y : self.poker_value.height/2
                            });
                            self.poker_value.addChild(player_poker);
                            self.resultAreaShow();
                        },0.5);

                    }else{
                        alert(response.Msg);
                    }
                }else {
                    alert('网络故障，请稍后再试试~');
                }
            }
        };
        xhr.send(this.postData(this.bet_on_obj.total));//发送下注信息到服务器

    },

    //放置一张扑克牌（正面）隐藏
    showCallBack:function(node){
        //this.poker_value.initWithFile("#A_8.png");
        //给庄家发背面牌
        this.poker_value2  = new cc.Sprite(res.s_bg_poker);
        this.poker_value2.attr({
            x:550,
            y:400
        });
        this.addChild( this.poker_value2 );
        var action2 = cc.moveTo(0.5,cc.p(this.WinSize.width/2, 265));
        var sequence2 = cc.sequence(action2);
        this.poker_value2.runAction(sequence2);
    },

    //点击“摊牌”按钮后的回调函数：摊开庄家的底牌。。。
    resultCallback:function(){
        var b1 = this.player_num.b_1;
        var b2 = this.player_num.b_2;
        var baker_poker = new cc.Sprite('#'+b1+'_'+b2+'.png');
        baker_poker.attr({
           x :  this.poker_value2.width/2,
           y :  this.poker_value2.height/2
        });
        this.poker_value2.addChild(baker_poker);
        //判断谁赢
        if(this.player_num.winner=='baker'){
            var effect_baker_win = cc.audioEngine.playEffect(res.s_baker_win,false);
            this.bankerWin();
        }else{
            var effect_player_win = cc.audioEngine.playEffect(res.s_player_win,false);
            this.playerWin();
        }
        this.s_show_downArea.setVisible(false);
    },

    //闲家赢的动画
    playerWin:function(){
        //押注图标从庄家移动到闲家
        this.playerChips  = new cc.Sprite(res.s_chips);
        this.playerChips.attr({
            x:this.WinSize.width/2,
            y:300
        });
        this.addChild( this.playerChips );
        var action1 = cc.moveTo(1,cc.p(this.WinSize.width/2, 150));
        var callback1 = cc.callFunc(this.playerAdd,this);
        var action2 = cc.fadeOut(0.5);
        var callback2 = cc.callFunc(this.playerFadeOut,this);
        var sequence = cc.sequence(action1,callback1,action2,callback2);
        this.playerChips.runAction(sequence);
    },

    playerAdd:function(node){
        this.show_xz.setString(this.bet_on_obj.total+this.player_num.bets); //设置文本框中的文本
        this.my_YD = this.player_num.My_YD;   //更新我的龙币值
        this.UI_YD = this.player_num.My_YD;   //更新我的龙币值
        BG_Object._mybean.setString('我的龙币：' + this.my_YD);  //显示最新的龙币值
        BG_Object._mywinbean.setString('本次赚取：' + this.bet_on_obj.total);//显示本次赚取的龙币
    },

    playerFadeOut:function(node){
        this.s_chipsArea.setVisible(false);
        this.show_xz.setVisible(false);
        this._ready_menu.setVisible(true);
    },


    //庄家赢的动画
    bankerWin:function(){
        //押注图标从闲家移动到庄家
        this.playerChipsTemp  = new cc.Sprite(res.s_chips);
        this.playerChipsTemp.attr({
            x:this.WinSize.width/2,
            y:150
        });
        this.addChild( this.playerChipsTemp );

        var callback1 = cc.callFunc(this.bakerFadeOut,this);
        var action1 = cc.moveTo(1,cc.p(this.WinSize.width/2, 300));
        var callback2 = cc.callFunc(this.bakerFadeOut2,this);
        var action2 = cc.fadeOut(1);
        var sequence = cc.sequence(callback1,action1,callback2,action2);
        this.playerChipsTemp.runAction(sequence);
    },

    bakerFadeOut:function(){
        this.s_chipsArea.setVisible(false);
        this.show_xz.setVisible(false);
        this.my_YD = this.player_num.My_YD;   //更新我的龙币值
        this.UI_YD = this.player_num.My_YD;   //更新我的龙币值
        BG_Object._mybean.setString('我的龙币：' + this.my_YD);  //显示最新的龙币值
        BG_Object._mywinbean.setString('本次赚取：-' + this.bet_on_obj.total);
    },

    bakerFadeOut2:function(){
        this._ready_menu.setVisible(true);
    },

    //显示结果
    resultAreaShow : function() {
        this._s_show_down_menu.setVisible(true);
    },

    //隐藏结果
    resultAreaHide : function() {
        //发牌前，先隐藏“开始”按钮，“下注”按钮
        this._start_menu.setVisible(false);
        this.s_bet_boxArea.setVisible(false);
        this._bet_menu.setVisible(false);
    },

    //判断是否有充足的烟豆下注
    checkYD: function (bet_num) {
        if (!bet_num) {
            return false;
        }
        //判断，若用户烟豆>=下注的总数
        if (this.my_YD >= bet_num) {
            return true;
        }else{
            return false;
        }

    },

    postData: function (data) {
        //  var data = {data:'我是你'};
        var params = "openid="+wx_info.openid+"&gamekey="+wx_info.gamekey+"&bet_num="+data;
        return params;
    },

    //播放音效
    playEffect : function() {
        if(AllowMusic){
            cc.audioEngine.playEffect(res.s_yao,true);
        }
    }
});


