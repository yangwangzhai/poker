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

</script>
<div class="mainbox">
  <font color="#FF0000">(游戏在微信里的地址为：<?=base_url('index.php?c=raceDog')?>)</font>
	<span style="float: right">
		<form action="<?=$this->baseurl?>&m=index" method="post">
			<input type="hidden" name="catid" value="<?=$catid?>"> <input
				type="text" name="keywords" value=""> <input type="submit"
				name="submit" value=" 搜索 " class="btn">
		</form>
	</span> 


		<input type="hidden" name="catid" value="<?=$catid?>">
	<form action="<?=$this->baseurl?>&m=delete" method="post">
		<table width="69%" border="0" cellpadding="3" cellspacing="0" class="datalist fixwidth" id="sortTable">
			<tr>
				<th width="30"></th>
				<th width="100">游戏ID</th>

                <th  align="left">游戏赛场主</th>
                <th  align="left">游戏时间</th>
                <th  align="left">具体时间</th>
                 <th width="300"  align="left">排名</th>
				<th >操作</th>

			</tr>

    <?php foreach($list as $key=>$r) {?>
    <tr class="sortTr">
		<td><?php if(!$r['islock']){?>
				<input type="checkbox" name="delete[]" value="<?=$r['id']?>"class="checkbox" />
			<?php }?>
		</td>

				<td><?=$r['id']?></td>
                <td><?=getSCZNameByopenid($r['game_owner'])?></td>
                <td title="<?=date('Y-m-d H:i:s',$r['addtime'])?>"><?=timeFromNow($r['addtime'])?></td>
				<td title="<?=date('Y-m-d H:i:s',$r['addtime'])?>"><?=date('Y-m-d H:i:s',$r['addtime'])?></td>
				<td>
					<?php $rank_arr = string2array($r['ranking']);
						if(empty($rank_arr) || !is_array($rank_arr)){
							echo "<font color='red'>本局游戏未结算</font>";
						}else{
							$tem = '';
							$daxie = array('一','二','三','四','五');
							foreach ($rank_arr as $k => $v) {
								$bet_sum = getBetSum($v,$r['id']);
								$tem .= "第".($daxie[$k])."名：<font color='red'>".$v."</font> 号狗; 下注总数：".intval($bet_sum['gold'])."; 结算：".intval($bet_sum['last_gold'])."<br/>";
							}
							echo $tem;
						}


					?>
				</td>
 
                <td>
                
                <?php if(!$r['islock']){?>
                <a href="<?=$this->baseurl?>&m=delete&catid=<?=$catid?>&id=<?=$r['id']?>" onclick="return confirm('确定要删除吗？');">删除</a>
                <?php }?>
                </td>
			</tr>
    <?php }?>
			<tr>
				<td colspan="11"><input type="checkbox" name="chkall" id="chkall"onclick="checkall('delete[]')" class="checkbox" /><label for="chkall">全选/反选</label>&nbsp; <input type="submit" value=" 删除 "class="btn" onclick="return confirm('确定要删除吗？');" /> &nbsp;</td>
			</tr>
		</table>

		<div class="margintop">共：<?=$count?>条&nbsp;&nbsp;<?=$pages?></div>

	</form>

</div>


<?php $this->load->view('admin/footer');?>