/**
 * Created by lkl on 2016/8/12.
 * 加载背景图片
 * 加载微信头像
 * 加载微信昵称
 * 加载我的烟豆
 * 加载赚取烟豆
 *
 */

var BG_Object = null;
var G_BackGroundLayer = cc.Layer.extend({
    sprite:null,
    _Avatar : null,
    ctor:function () {
        //////////////////////////////
        // 1. super init first
        this._super();
        BG_Object = this;
        this.WinSize = cc.winSize;
        this.my_win_bean = 0;

        //加载背景图片
        this.initBackGround();
        //加载微信头像、微信昵称、我的烟豆、赚取烟豆
        this.initHeader();

        return true;
    },

    //加载背景图片
    initBackGround : function() {
        this._bg = new cc.Sprite(res.S_bg);
        this._bg.attr({
            x:this.WinSize.width/2,
            y:this.WinSize.height/2
        });
        this.addChild(this._bg);

    },

    //加载微信头像、微信昵称、我的烟豆、赚取烟豆
    initHeader : function() {
        /*this._Header_bg = new cc.DrawNode();
        var ltp = cc.p(0, this.WinSize.height);
        var rbp = cc.p(this.WinSize.height, this.WinSize.height - 80);
        this._Header_bg.drawRect(ltp, rbp, cc.color(233,158,57));
        this.addChild(this._Header_bg);*/

        var self = this;
        //加载微信头像到背景图的某个位置
        cc.loader.loadImg(wx_info.imgUrl, {isCrossOrigin : false }, function(err, img)
        {
            self._Avatar = new cc.Sprite(img);
            //设置精灵（图片）的锚点
            self._Avatar.attr({
                anchorX : 0.5,
                anchorY : 0.5
            });
            self._Avatar.setPosition(this._Avatar.width/2,this._Avatar.height/2);  //设置微信头像的位置
            self.addChild(self._Avatar);

        }.bind(this));


        //微信昵称
        this._NickName_label = new cc.LabelTTF('微信昵称：'+wx_info.nickname,'Arial',14);
        this._NickName_label.attr({
            anchorX : 0,
            anchorY : 0.5
        });
        this._NickName_label.setPosition(70,53);
        this.addChild(this._NickName_label);

        //我的烟豆
        this._mybean = new cc.LabelTTF('我的龙币：'+wx_info.total_gold,'Arial',14);
        this._mybean.attr({
            anchorX : 0,
            anchorY : 0.5
        });
        this._mybean.setPosition(70,31);
        this.addChild(this._mybean);
        //总共赚取烟豆
        this._mywinbean = new cc.LabelTTF('本次赚取：'+this.my_win_bean,'Arial',14);
        this._mywinbean.attr({
            anchorX : 0,
            anchorY : 0.5
        });
        this._mywinbean.setPosition(70,10);
        this.addChild(this._mywinbean);

    }
});

