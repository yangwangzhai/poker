<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>打鸡针游戏</title>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="full-screen" content="yes"/>
    <meta name="screen-orientation" content="portrait"/>
    <meta name="x5-fullscreen" content="true"/>
    <meta name="360-fullscreen" content="true"/>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <style>
        body, canvas, div {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            -khtml-user-select: none;
            -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
        }
    </style>
</head>
<body style="padding:0; margin: 0; background: #000;">
<script>
    var wx_info = {openid:'<?=$wx_info["Openid"]?>',nickname:'<?=$wx_info["NickName"]?>',imgUrl:'<?=$wx_info["HeadImg"]?>',total_gold:<?=$wx_info["TotalGold"]?>,gamekey:'<?=$wx_info["gamekey"]?>',MusicSet:<?=$wx_info["MusicSet"]?>,EffectsSet:<?=$wx_info["EffectsSet"]?>}
</script>
<!--<script src="http://192.168.1.217:3000/socket.io/socket.io.js"></script>-->
<script src="res/loading.js"></script>
<canvas id="gameCanvas" width="640" height="480"></canvas>
<script src="frameworks/cocos2d-html5/CCBoot.js"></script>
<script cocos src="main.js"></script>
</body>
</html>
