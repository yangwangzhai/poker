<!DOCTYPE html>
<html>
<head>
<title>【<?=TITLE?>】后台管理中心</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="static/admin_img/admincp.css?1"
	type="text/css" media="all" />
<script type="text/javascript" src="static/js/jquery-1.7.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('.nav li').click(function(){
    	$('.nav li').removeClass();
    	$(this).addClass("tabon");
    	$(".frame_left > ul").hide().eq($('.nav li').index(this)).show();
    });
    
    $('.frame_left a').click(function(){
    	$('.frame_left a').removeClass();			
    	$(this).addClass("on");		
        });

/*$("#leftdaaa").animate({ 
    width: "10px",
    height: "100%", 
    fontSize: "10em", 
    borderWidth: 10
}, 1000 );
*/
});
</script>
<style>
html,body {
	width: 100%;
	height: 100%;
	overflow: hidden;
}
</style>

</head>
<body scroll="no">
	<div class="mainhd">
		<div class="logo">
			<img src="./static/admin_img/logo.png">
		</div>
		<div class="nav">
			<ul>
				<li class="tabon"><a href="#">赛狗游戏</a></li>
             
                <?php if($_SESSION['groupid']==1){?>
					<li><a href="#">系统管理</a></li>
                <?php }?>
                
			</ul>
		</div>
		<div class="uinfo">
			<p>
				欢迎您, <em><?php echo $_SESSION['truename']?$_SESSION['truename']:$_SESSION['username'];?></em> 
               <a href="index.php?d=admin&c=admin&m=index&m=edit&id=<?=$this->session->userdata('id')?>" target="main">个人资料</a>
           
                <a href="index.php?d=admin&c=common&m=login_out" target="_top">退出</a>
			</p>

		</div>
	</div>
    
	<table cellpadding="0" cellspacing="0" width="100%" height="100%">
		<tr>
			<td valign="top" class="sider" width="160"
				style="">
				<div class="frame_left">
					<ul>
                      <?php if($_SESSION['groupid']==1){?>
                      
                       <li><a href="index.php?d=admin&c=sets" class="on" target="main">▪游戏记录</a></li>
                        <li><a href="index.php?d=admin&c=betlog" target="main">▪下注记录</a></li>
                        <li><a href="index.php?d=admin&c=giftlog" target="main">▪送礼记录</a></li>
                        <li><a href="index.php?d=admin&c=recharge_log" target="main">▪结算记录</a></li>
						<li><a href="index.php?d=admin&c=notice" target="main" >▪烟豆排行</a></li>
                        <li><a href="index.php?d=admin&c=play_title" target="main">▪参与用户</a></li>  
                        <li><a href="index.php?d=admin&c=laba" target="main">▪喇叭公告</a></li>  
                        <li><a href="index.php?d=admin&c=game_set" target="main">▪游戏设置</a></li>  
                        <li><a href="index.php?d=admin&c=game_TJ" target="main">▪游戏统计</a></li>  
                        <li><a href="index.php?d=admin&c=game_rule" target="main">▪概率规则</a></li>  
					
                <?php }else{?>
                      <li><a href="index.php?c=play_title" target="main">▪参与用户</a></li>   
                        <li><a href="index.php?c=game_TJ" target="main">▪游戏统计</a></li> 
                         <li><a href="index.php?c=game_TJ&m=tongji_tb" target="main">▪统计图表</a></li>  
                          <li><a href="index.php?d=admin&c=game_rule" target="main">▪概率规则</a></li> 
                      <?php }?>    
                       	 <li><a href="http://new.cnzz.com/v1/login.php?siteid=1259386050" target="main">▪站长统计</a></li>                
					</ul>
					
 					<ul style="display: none;">				
						<li><a href="index.php?d=admin&c=admin&m=index" target="main"> ▪用户管理</a></li>                       
                        <!--<li><a href="./index.php?d=admin&c=sets&m=index" target="main"> ▪系统设置</a></li>-->	
					</ul>                   
				</div>
			</td>
<?php $src = $_SESSION['groupid']!=1 ? 'index.php?c=play_title' : './index.php?d=admin&c=sets';?>
			<td valign="top" height="100%"><iframe
					src="<?=$src?>" name="main" width="100%"
					height="96%" frameborder="0" scrolling="yes"
					style="overflow: auto;"></iframe></td>
		</tr>
	</table>
</body>
</html>