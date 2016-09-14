<?php $this->load->view('admin/header');?>
<script type="text/javascript">
$(function($)
{
	// 数据列表 点击开始排序
	var sortFlag = 0;	
	$("#sortTable th").click(function()
	{		
		var tdIndex = $(this).index();		
		var temp = "";
		var trContent = new Array();
		//alert($(this).text());	
		
		// 把要排序的字符放到行的最前面，方便排序
		$("#sortTable .sortTr").each(function(i){ 
			temp = "##" + $(this).find("td").eq(tdIndex).text() + "##";			
			trContent[i] = temp + '<tr class="sortTr">' + $(this).html() + "</tr>";	
				
		});
		
		// 排序
		if(sortFlag==0) {
			trContent.sort(sortNumber);
			sortFlag = 1;
		} else {
			trContent.sort(sortNumber);
			trContent.reverse();
			sortFlag = 0;
		}
		
		// 删除原来的html 添加排序后的
		$("#sortTable .sortTr").remove();
		$("#sortTable tr").first().after( trContent.join("").replace(/##(.*?)##/, "") );		
	});
	
});

function selectTime(){
  var starttime = $('#starttime').val();
  var endtime = $('#endtime').val();
  var keywords = $('#keywords').val();
  if(starttime == '' || endtime == ''){
    alert('请选择一个时间段');
    return false;
  }
  location.href="<?=$this->baseurl?>&m=index&starttime="+starttime+'&endtime='+endtime+'&keywords='+keywords;
}


</script>
<script src="static/js/date/WdatePicker.js"></script>
<style>
.tab_btn {
	width: 100px;
	height: 30px;
	margin-right: 10px;
	border-radius: 5px;
	border-color: #eee;
	background-color: #F2F9FD;
	font-weight: bold;
}
.tab_btn_selected {
	width: 100px;
	height: 30px;
	margin-right: 10px;
	border-radius: 5px;
	border-color: #eee;
	background-color: #e2e9eD;
	font-weight: bold;
}
.datalist2 {
  margin-top: 10px;
  border-bottom: 2px solid #b5cfd9;
  border-top: 2px solid #b5cfd9;
  clear: both;
  width: 100%;
}
.datalist2 th {
  border-bottom: 1px dashed #ccc;
  color: #9ebecb;
  font-size: 12px;
  height: 25px;
  line-height: 20px;
  padding: 5px;
  text-align: left;
  word-break: break-all;
}
.datalist2 tr:hover {
	background: #F2F9FD
}

.datalist2 td {
	padding: 5px 3px;
	background: url(./static/admin_img/bg_repd.gif) repeat-x 0 bottom;
	height: 25px; word-break:break-all;
}

</style>
<div class="mainbox"> 
  <!-- <font color="#FF0000">(游戏在微信里的地址为：<?=base_url('index.php?c=raceDog')?>)</font> -->
  <input class="<?=($_GET['day']=='today' || !$_GET['day'])?'tab_btn_selected':'tab_btn'?>" type="button" onclick="location.href='<?=$this->baseurl?>&m=index&day=today'" value=" 今天 ">
  <input class="<?=($_GET['day']=='yesterday')?'tab_btn_selected':'tab_btn'?>" type="button" onclick="location.href='<?=$this->baseurl?>&m=index&day=yesterday'" value=" 昨天 ">
  <span style="float: right; display:none">
  <form action="<?=$this->baseurl?>&m=index" method="post">
    <input type="hidden" name="catid" value="<?=$catid?>">
    <input
				type="text" name="keywords" value="">
    <input type="submit"
				name="submit" value=" 搜索 " class="btn">
  </form>
  </span>
  <input type="input" class="px" id="starttime" value="<?=date('Y-m-d H:i:s',$startTime)?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" name="starttime">
  到
  <input type="input" class="px" id="endtime" value="<?=date('Y-m-d H:i:s',$endTime)?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" name="endtime">
  昵称：
  <input type="text" id="keywords" name="keywords" value="">
  <input type="button" onclick="selectTime()" value=" 查询 " class="btn">
  <p style="color:#F00; margin:10px 0; display:none;">
    <?=date('Y-m-d H:i:s',$startTime)?>
    到
    <?=date('Y-m-d H:i:s',$endTime)?>
    ,总共赢进烟豆：
    <?=$win_total?>
    , 总共支出烟豆：
    <?=$lost_total?>
    , 赢进+支出=
    <?=$win_total+$lost_total?>
  </p>
  <table width="69%" border="0" cellpadding="3" cellspacing="0" class="datalist2">
 
    <tr>
      <td width="90">日期区间： 
    </td>
    <td colspan="2"><?=date('Y-m-d H:i:s',$Stime)?>
    到
    <?=date('Y-m-d H:i:s',$Etime)?></td>
     <tr>
        <td>庄家总赢烟豆：</td><td style="color:#090;" width="120"><?=$win_total?>  </td><td style="color:#090;">今日：<?=$win_today_total?></td>  
        </tr>
         <tr>
        <td>庄家总输烟豆：</td><td style=" color:#00F"><?=$lost_total?>  </td><td style=" color:#00F">今日：<?=$lost_today_total?></td>
         </tr>
          <tr> 
            <td>庄家实际支出：</td><td style=" color: #F00"><?=$win_total+$lost_total > 0 ? '+'.($win_total+$lost_total) : $win_total+$lost_total?>  </td>
            <td style=" color: #F00">今日：<?=$win_today_total+$lost_today_total > 0 ? '+'.($win_today_total+$lost_today_total) : $win_today_total+$lost_today_total?></td>
          </tr>
  </table>
  <form action="<?=$this->baseurl?>&m=delete" method="post">
    <table width="69%" border="0" cellpadding="3" cellspacing="0" class="datalist fixwidth" id="sortTable">
      <tr>
        <th width="30"></th>
        <th >头像</th>
        <th  align="left">微信昵称</th>
        <th  align="left">总烟豆</th>
        <th  align="left">今日输赢(烟豆)</th>
        <th  align="left">游戏局数</th>
        <th >操作</th>
      </tr>
      <?php if($zuang['game_num']){?>
      <tr>
        <td><?php if(!$r['islock']){?>
          <input type="checkbox" name="delete[]" value="<?=$r['id']?>"class="checkbox" />
          <?php }?></td>
        <td><img src="<?=$zuang['head_img']?>" width="40" height="40" /></td>
        <td><?=$zuang['nickname']?></td>
        <td><?=$zuang['total_gold']?></td>
        <td><font  <?php if($zuang['total'] < 0) echo 'color="#FF0000"'; ?> >
          <?=$zuang['total']?>
          </font></td>
        <td><?=$zuang['game_num']?></td>
        
        <!-- <td title="<?=date('Y-m-d H:i:s',$r['addtime'])?>"><?=date('Y-m-d H:i:s',$r['addtime'])?></td> -->
        
        <td><!--a href="<?=$this->baseurl?>&m=getMore&openid=<?=$zuang['openid']?>&starttime=<?=$startTime?>&endtime=<?=$endTime?>">查看详细</a--> 
          -- </td>
      </tr>
      <?php }?>
      <?php foreach($list as $key=>$r) {?>
      <tr class="sortTr">
        <td><?php if(!$r['islock']){?>
          <input type="checkbox" name="delete[]" value="<?=$r['id']?>"class="checkbox" />
          <?php }?></td>
        <td><img src="<?=$r['head_img']?>" width="40" height="40" /></td>
        <td><?=$r['nickname']?></td>
        <td><?=$r['total_gold']?></td>
        <td><font  <?php if($r['total'] < 0) echo 'color="#FF0000"'; ?> >
          <?=$r['total']?>
          </font></td>
        <td><?=$r['game_num']?></td>
        
        <!-- <td title="<?=date('Y-m-d H:i:s',$r['addtime'])?>"><?=date('Y-m-d H:i:s',$r['addtime'])?></td> -->
        
        <td><a href="<?=$this->baseurl?>&m=getMore&openid=<?=$r['openid']?>&starttime=<?=$startTime?>&endtime=<?=$endTime?>">查看详细</a></td>
      </tr>
      <?php }?>
      <!-- <tr>
				<td colspan="11"><input type="checkbox" name="chkall" id="chkall"onclick="checkall('delete[]')" class="checkbox" /><label for="chkall">全选/反选</label>&nbsp; <input type="submit" value=" 删除 "class="btn" onclick="return confirm('确定要删除吗？');" /> &nbsp;</td>
			</tr> -->
    </table>
    <div class="margintop">共：
      <?=$count?>
      条&nbsp;&nbsp;
      <?=$pages?>
    </div>
  </form>
</div>
<?php $this->load->view('admin/footer');?>
