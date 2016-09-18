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

    ctor: function () {
        //////////////////////////////
        // 1. super init first
        this._super();
        //this.WinSize = cc.director.getWinSize();  //获取当前游戏窗口大小
        this.WinSize = cc.winSize;  //获取当前游戏窗口大小
        this.my_YD = wx_info.total_gold;
        this.UI_YD = wx_info.total_gold;

        this.initBetOnObj();

        this.initPokerBox();//设置扑克盒子位置

        this.initBetBoxArea();//设置投注框位置

        this.initBetArea();//设置投注值位置

        this.initChipsArea(); //设置已押注的图标（点击下注前，隐藏）

        this.initXzArea();//设置下注数值（点击下注前，隐藏）

        this.initStartArea();//设置开始按钮位置（点击下注前，隐藏）

        //this.schedule(this.updateShow, 0.5);    //定时函数，每0.5秒执行一次updateShow函数

        return true;
    },

    initBetOnObj: function () {
        this.bet_on_obj = {'total': 0};
    },

    initPokerBox: function () {
        this.pokerboxArea = new cc.Sprite(res.s_poker_box);
        this.pokerboxArea.attr({
            x:600,
            y:350
        });
        this.addChild(this.pokerboxArea);
    },

    initBetBoxArea: function () {
        this.s_bet_boxArea = new cc.Sprite(res.s_bg_bet);
        this.s_bet_boxArea.attr({
            x:(this.WinSize.width)/2,
            y:30
        });
        this.addChild(this.s_bet_boxArea);
    },

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

    betCallBack: function (sender){
        this.s_chipsArea.setVisible(true);
        this.show_xz.setVisible(true);
        this._start_menu.setVisible(true);

        this.bet_on_obj.total += sender.bet_num;
        this.show_xz.setString(this.bet_on_obj.total); //设置文本框中的文本
        cc.log(sender.bet_num);
        cc.log(this.bet_on_obj.total);
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

    //押注数值
    initXzArea:function(){
        var fontColor = new cc.Color(255, 255, 0);  //实列化颜色对象
        cc.log(this.bet_on_obj.total);
        this.show_xz = new cc.LabelTTF('下注值', 'Arial', 20);
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

    //开始发牌图标
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

    //发牌动作
    beginCallback:function(){
        this.resultAreaHide();
        //this.initPokerFront();
        this.poker_value  = new cc.Sprite(res.s_bg_poker);
        this.poker_value.attr({
            x:550,
            y:400
        });
        this.addChild( this.poker_value );
      //  this.poker_value = bg_poker;
        var action1 = cc.moveTo(1,cc.p(this.WinSize.width/2, this.poker_value.height/2));
        var action2 = cc.rotateBy(3,0,-180);
        var callback = cc.callFunc(this.showCallBack,this);
        var sequence = cc.sequence(action1,action2,callback);
        this.poker_value.runAction(sequence);

    },

    //放置一张扑克牌（正面）隐藏
    initPokerFront:function(){
        this.poker_value = new cc.Sprite(res.s_poker_k);
        this.poker_value.attr({
            x:this.WinSize.width/2,
            y:this.poker_value.height/2
        });
        this.addChild(this.poker_value);
        //this.poker_value.setVisible(false);
    },

    showCallBack:function(node){
        this.poker_value.initWithFile(res.s_poker_k);
    },

    //隐藏结果
    resultAreaHide : function() {
        //发牌前，先隐藏“开始”按钮，“下注”按钮
        this._start_menu.setVisible(false);
        this.s_bet_boxArea.setVisible(false);
        this._bet_menu.setVisible(false);
    },

    //播放音效
    playEffect : function() {
        if(AllowMusic){
            cc.audioEngine.playEffect(res.s_yao,true);
        }
    }
});


