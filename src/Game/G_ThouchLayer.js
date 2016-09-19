/**
 * Created by lkl on 2016/8/12.
 */

var G_ThouchLayer = cc.Layer.extend({
    sprite: null,
    curr_selected_OBJ: null,//当前选中的押号按钮
    curr_bet_obj: null,//当前选中的下注按钮
    bet_on_obj: null,   //存放押注对象
    my_YD: null,//我的烟豆
    UI_YD: null,//UI显示的烟豆数
    ZQ_YD: 0,
    show_xz: null,    //游戏底部下注数组
    show_zq: [],    //游戏底部赚取数组
    isRun: false,
    isBetAgain: false,
    poker_value:null,
    poker_value2:null,

    ctor: function () {
        // 1. super init first
        this._super();
        this.WinSize = cc.winSize;  //获取当前游戏窗口大小
        this.my_YD = wx_info.total_gold;
        this.UI_YD = wx_info.total_gold;

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
        if(this.checkYD(this.bet_on_obj.total+sender.bet_num)){
            this.s_chipsArea.setVisible(true);
            this.show_xz.setVisible(true);
            this._start_menu.setVisible(true);
            this.bet_on_obj.total += sender.bet_num;    //累加每次投注的值
            this.show_xz.setString(this.bet_on_obj.total); //设置文本框中的文本
        }else{
            alert('龙币不足！');
        }
    },

    //已押注的图标
    initChipsArea: function(){
        this.s_chipsArea = new cc.Sprite(res.s_chips);
        this.s_chipsArea.attr({
            x:(this.WinSize.width)/2,
            y:135
        });
        this.addChild(this.s_chipsArea);
        this.s_chipsArea.setVisible(false);
    },

    //总的押注数值
    initXzArea:function(){
        var fontColor = new cc.Color(255, 255, 0);  //实列化颜色对象
        cc.log(this.bet_on_obj.total);
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
        this.resultAreaHide();
        this.resultAreaShow();
        //给闲家发牌
        this.poker_value  = new cc.Sprite(res.s_bg_poker);
        this.poker_value.attr({
            x:550,
            y:400
        });
        this.addChild( this.poker_value );
        var action1 = cc.moveTo(0.5,cc.p(this.WinSize.width/2, this.poker_value.height/2));
        var callback = cc.callFunc(this.showCallBack,this);
        var sequence = cc.sequence(action1,callback);
        this.poker_value.runAction(sequence);
        this.s_show_downArea.setVisible(true);
    },

    //放置一张扑克牌（正面）隐藏
    showCallBack:function(node){
        this.poker_value.initWithFile(res.s_poker_k);
        //给庄家发牌
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
        this.poker_value2.initWithFile(res.s_poker_a);
        //闲家赢
        //this.playerWin();
        this.bankerWin();
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
        var action2 = cc.fadeOut(1);
        var callback1 = cc.callFunc(this.showCallBackAdd,this);
        var callback2 = cc.callFunc(this.showCallBackFadeOut,this);
        var sequence = cc.sequence(action1,callback1,action2,callback2);
        this.playerChips.runAction(sequence);
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
        var action1 = cc.moveTo(1,cc.p(this.WinSize.width/2, 300));
        var action2 = cc.fadeOut(1);
        var callback1 = cc.callFunc(this.showCallBackFade,this);
        var callback2 = cc.callFunc(this.showCallBackFadeOut,this);
        var sequence = cc.sequence(callback1,action1,callback2,action2);
        this.playerChipsTemp.runAction(sequence);
    },

    showCallBackAdd:function(node){
        this.show_xz.setString(this.bet_on_obj.total+100); //设置文本框中的文本
    },

    showCallBackFadeOut:function(node){
        this._ready_menu.setVisible(true);
    },

    showCallBackFade:function(node){
        this.s_chipsArea.setVisible(false);
        this.show_xz.setVisible(false);
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

    //播放音效
    playEffect : function() {
        if(AllowMusic){
            cc.audioEngine.playEffect(res.s_yao,true);
        }
    }
});


