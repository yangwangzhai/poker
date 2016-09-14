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
    show_xz: [],    //游戏底部下注数组
    show_zq: [],    //游戏底部赚取数组
    isRun: false,
    isBetAgain: false,

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
            bet_num: 10
        });

        this._bet_50 = new cc.MenuItemImage(res.s_bet50,res.s_bet50, this.betCallBack, this);
        this._bet_50.attr({
            x: this.s_bet_boxArea.width/2  + 35,
            y: PositionY,
            bet_num: 10
        });

        this._bet_100 = new cc.MenuItemImage(res.s_bet100,res.s_bet100, this.betCallBack, this);
        this._bet_100.attr({
            x: this.s_bet_boxArea.width/2  + 100,
            y: PositionY,
            bet_num: 10
        });

        this._bet_menu = new cc.Menu(this._bet_10,this._bet_20,this._bet_50,this._bet_100);
        this._bet_menu.attr({
            x: 0,
            y: 0
        });
        this.s_bet_boxArea.addChild(this._bet_menu);
    },

    betCallBack: function (sender){
        this.initChipsArea();
        this.initStartArea();
        cc.log(this.bet_on_obj.total);
    },

    //已押注的图标
    initChipsArea: function(){
        this.s_chipsArea = new cc.Sprite(res.s_chips);
        this.s_chipsArea.attr({
            x:(this.WinSize.width)/2,
            y:130
        });
        this.addChild(this.s_chipsArea);
    },

    //开始图标
    initStartArea:function(){
        this.s_chipsArea = new cc.Sprite(res.s_btn_start,this.beginCallback,this);
        this.s_chipsArea.attr({
            x:(this.WinSize.width)/2,
            y:250
        });
        this.addChild(this.s_chipsArea);
    },



    //播放音效
    playEffect : function() {
        if(AllowMusic){
            cc.audioEngine.playEffect(res.s_yao,true);
        }
    }
});


