// JavaScript Document
$(function() {
	var music = document.getElementById("music");	
	//$('#test').css('height',$(window).height() + 'px');
	//$('body').on('touchmove', function (event) {event.preventDefault();});
	
	$('.play').click(function(){
		time_left = 4;
		$('.yxjg').hide();
		$('.time_left_div').show();		
		setTimeout(go_start,1000);
		
		//$("#result").text("你向下滑动了" + countdown + "次");
		//$('#result-text').html('距游戏结束结束还有：');
		
	})
	
	
	//swipe 的dome
	$("body").swipe( {
		swipeUp:function(event, direction, distance, duration, fingerCount) {
			
			if(Isstart){
				if(timedown > 0){	
					countdown++;
					var ys = countdown  % 30 ;	
					if(ys == 0){
						anp(countdown);
						music.play();
					}
					if(music.paused || music.ended){
						$('#mc_play audio').get(0).play();
					}
					
					$("#result").text( countdown + "瓶");
				}else if(timedown == 0){
					Isstart = false;
					//$("#result").text("你向" + direction + "滑动了" +distance+ "像素 " );
				//	$("#result").text("游戏结束，您向上滑动了" + countdown + "次");
				//	$('#start').val('重新开始');
				//	$('#start').show();fingers:'all',
				}
			}
		},
		swipeStatus:function(event, phase, direction, distance, duration,fingerCount) {
			if(direction == 'up' && Isstart){
				if(phase == 'move' ){					
					$('.ren1').hide();
					$('.ren2').show();
				}else{
					$('.ren2').hide();
					$('.ren1').show();					
				}
			}
			//$('#result-text').text("你用"+fingerCount+"个手指以"+duration+"秒的速度向" + direction + "滑动了" +distance+ "像素 " +"你在"+phase+"中");
		},
		excludedElements: ".about_body"//排除元素
	});
	
	
	$('.close').click(function(){
		$('#fd').css('display','none');
	});
	
});
function settime(){
	if(timedown == 0){
		
	}else{
		setTimeout(function() { 			
			timedown--;
			$('#havetime').html('剩余' + timedown + '秒');
			settime() ;
		},1000) ;
	}
	
}

function anp(e){
	
	var n=e;//Math.round(Math.random()*10);
	var $i=$("<b>").text("+"+n);
	//var x=e.pageX,y=e.pageY;
	var x=200,y=375;
	$i.css({top:y-20,left:x,position:"absolute",color:"#ff0000"});
	$("body").append($i);
	$i.animate({top:y-300,opacity:0,"font-size":"10em"},1000,function(){
		$i.remove();
	});
	//e.stopPropagation();
}

function go_start(){
	if(time_left == 0){
		timedown = start_timedown; 
		countdown = 0;
		Isstart = true;
		$('.yxjg').hide();
		$('.time_left_div').hide();
		$('.time_left').attr('src','static/images/5.png');	
		endTime = new Date(zhtime(timedown)); 
		displayTime();
		$("#result").text( countdown + "瓶");
		clearTimeout(go_start);
		return false;
	}	
	if(time_left == 1){
		start.play();
	}
	$('.time_left').attr('src','static/images/'+time_left+'.png');	
	time_left--;
	
	setTimeout(go_start,1200);
}

function save_tel(){
	

	$.ajax({
		url:'index.php?c=milk_fight&m=save',
		type:'post',
		dataType:'json',
		data:{openid:openID,nickname:nickname,headimgurl:headimgurl,sex:sex,tel:'',countdown:countdown},
		success:function(res){		
				
			if(res.status == '0'){
				has_save_info = '1';
				$('#fd').css('display','none');
				
				$('.bfb').text( res.bfb + '%' );
				$('.cur_count').text( countdown );
				$('.max_count').text( res.max_count );
				$('.cur_rank').text( res.rank );
				$('.yxjg').show();	
				if(res.user_id > 0){
					$('.view a').attr('href','index.php?c=my_fight&user_id='+res.user_id);	
					$('.view').show();	
					$('.game_top').css('top','10%');		
				}
				//$("#result").text("游戏结束，您向上滑动了" + countdown + "次,目前排名第" + jsonobj.rank + "名");
				//location.reload();
			}
			if(res.status == '2'){
				alert('您已提交过！');
			}
			if(res.status == '1'){
				alert('提交失败！');
			}
		}
	})
}

//记录游戏积分
function save_log(){	
	$.ajax({
		url:'index.php?c=milk_fight&m=save_log',
		type:'post',
		dataType:'json',
		data:{user_id:user_id,countdown:countdown,openid:openID},
		success:function(res){		
			//$("#result").text("游戏结束，您向上滑动了" + countdown + "次,目前排名第" + res + "名");
			if(res.status == 0){
				$('.bfb').text( res.bfb + '%' );
				$('.cur_count').text( countdown );
				$('.max_count').text( res.max_count );
				$('.cur_rank').text( res.rank );
				$('.yxjg').show();	
			}else if(res.status == 1 ){
				alert('信息不全,游戏记录提交失败！');
			}else if( res.status == 2){
				alert('信息不全,游戏记录提交失败！');
			}else if( res.status == -1){
				alert('信息不全,游戏记录提交失败！');
			}
		},error:function (jqXHR, textStatus, errorThrown) {
            /*弹出jqXHR对象的信息*/
            alert(jqXHR.responseText);
            alert(jqXHR.status);
            alert(jqXHR.readyState);
            alert(jqXHR.statusText);
            /*弹出其他两个参数的信息*/
            alert(textStatus);
            alert(errorThrown);
		}
	})
}


function zhtime(needtime) {
        var oks = new Date();
		oks.setSeconds(oks.getSeconds()+needtime)
        var year = oks.getFullYear();
        var month = oks.getMonth() + 1;
        var date = oks.getDate();
        var hour = oks.getHours();
        var minute = oks.getMinutes();
        var second = oks.getSeconds();
        var msecond=oks.getMilliseconds()
        return year + '/' +month + '/' +  date + ' ' + hour + ':' + minute + ':' + second;
    }

	
function displayTime(){	
	//结束时间
	var now,leftTime,ms,s;
	
	now = new Date();    
	leftTime = endTime.getTime() -now.getTime();
	if(leftTime<0){
		Isstart = false;
		$('.ren2').hide();
		$('.ren1').show();	
		$('.over').show();
		over.play();
		setTimeout(function(){	
		$('.over').hide();					
		if(has_save_info == '0'){
			//$('#fd').show();
			save_tel();
		}
		if(has_save_info == '1'){
			save_log();
		}
			},2000);
		$('#fnTimeCountDown').html('00:00:000');
		
		clearTimeout(displayTime);
		return;
	}
	ms = haomiao( parseInt(leftTime%1000).toString() );
	leftTime = parseInt(leftTime/1000);
	var o = Math.floor(leftTime / 3600);
	var d = Math.floor(o/24);
	var m = Math.floor(leftTime/60%60);
	s = zero(leftTime%60);
	$('#fnTimeCountDown').html('00:'+s+':'+ms);
	
	setTimeout(displayTime,5);
      
}
function zero(n){
	var _n = parseInt(n, 10);//解析字符串,返回整数
	if(_n > 0){
		if(_n <= 9){
			_n = "0" + _n
		}
		return String(_n);
	}else{
		return "00";
	}
}
function haomiao(n){
	 if(n < 10)return "00" + n.toString();
     if(n < 100)return "0" + n.toString();
     return n.toString();
}




  function showGuideRead(){    
        $('.play_info').show();
		$('.strat').hide();
    };
    function hideGuideRead(){
        $('.yxjg').hide();
		$('.play_info').hide();
		$('.strat').show();
    };
	function showSM(){
		$('.hdsm').show();
	}
	function hideSM(){
		$('.hdsm').hide();
	}
	function showGZ(){
		$('.yxgz_div').show();
	}
	function hideGZ(){
		$('.yxgz_div').hide();
	}