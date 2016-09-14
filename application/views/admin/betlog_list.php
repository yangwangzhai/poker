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
function getstate(){		
		$('#submit').trigger("click");
	}	
</script>
<div class="mainbox">
  
	<span style="float: right;margin-bottom: 10px;">
		<form action="<?=$this->baseurl?>&m=index" method="post">
        	
            <select  onchange="getstate()" style="width:auto"  name="type">							
                            <option <?php if($type ==0) echo "selected"; ?> value="0">全部</option>
                            <option <?php if($type ==1) echo "selected"; ?> value="1">隐藏系统玩家</option>
                  </select>   
        
			<input type="hidden" name="catid" value="<?=$catid?>"> <input
				type="text" name="keywords" value=""> <input type="submit" id="submit"
				name="submit" value=" 搜索 " class="btn">
		</form>
	</span> 


		<input type="hidden" name="catid" value="<?=$catid?>">
	<form action="<?=$this->baseurl?>&m=delete" method="post">
		<table width="69%" border="0" cellpadding="3" cellspacing="0" class="datalist fixwidth" id="sortTable">
			<tr>
				<th width="30"></th>
				<th width="100">游戏ID</th>
				<th >头像</th>
                <th  align="left">微信昵称</th>
                <th  align="left">狗狗编号</th>
                <th  align="left">下注烟豆</th>
                <th  align="left">结算（倍数X烟豆=结果）</th>
                <th  align="left">下注时间</th>
				<th >操作</th>

			</tr>

    <?php foreach($list as $key=>$r) {?>
    <tr class="sortTr">
		<td><?php if(!$r['islock']){?>
				<input type="checkbox" name="delete[]" value="<?=$r['id']?>"class="checkbox" />
			<?php }?>
		</td>

				<td><?=$r['gameid']?></td>
                  <td><img src="<?=$r['head_img']?>" width="40" height="40" /></td>
                <td><?=getSCZNameByopenid($r['openid'])?></td>
                <td><?=$r['dog']?>号狗</td>
                <td><?=$r['gold']?></td>
                <td> <font color="<?=$r['bs'] < 0 ? '#FF0000' : 'blue' ?>"> <?=$r['bs'] . ' X '. $r['gold'] . ' = '.$r['last_gold'] ?> </font></td>
                <td title="<?=date('Y-m-d H:i:s',$r['addtime'])?>"><?=date('Y-m-d H:i:s',$r['addtime'])?></td>
				<!-- <td title="<?=date('Y-m-d H:i:s',$r['addtime'])?>"><?=date('Y-m-d H:i:s',$r['addtime'])?></td> -->
				
 
                
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