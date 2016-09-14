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

<div class="mainbox"> <span style="float: right">
  <form action="<?=$this->baseurl?>&m=index" method="post">
    <input type="hidden" name="catid" value="<?=$catid?>">
    <input
				type="text" name="keywords" value="">
    <input type="submit"
				name="submit" value=" 搜索 " class="btn">
  </form>
  </span>
  <input type="hidden" name="catid" value="<?=$catid?>">
  <form action="<?=$this->baseurl?>&m=delete" method="post">
    <table  border="0" cellpadding="3" cellspacing="0" class="datalist " id="sortTable">
      <tr>
        <th width="30"></th>
        <th width="80">游戏ID</th>
        <th width="60">头像</th>
        <th  align="left" width="200">微信昵称</th>
        <th  align="left" width="300">发送的参数</th>
        <th  align="left" width="300">返回的参数</th>
        <th  align="left" width="300">来源地址</th>
        <th  align="left" width="300">浏览器</th>
        <th  align="left" width="100">IP地址</th>
        <th  align="left" width="130">时间</th>
        <th >操作</th>
      </tr>
      <?php foreach($list as $key=>$r) {?>
      <tr class="sortTr">
        <td><?php if(!$r['islock']){?>
          <input type="checkbox" name="delete[]" value="<?=$r['id']?>"class="checkbox" />
          <?php }?></td>
        <td><?=$r['gameid']?></td>
        <td><img src="<?=$r['head_img']?>" width="40" height="40" /></td>
        <td><?=getSCZNameByopenid($r['openid'])?></td>
        <td><p><?=$r['desc']?><p></td>
        <td><?=$r['return']?></td>
        <td><?=$r['comefrom']?></td>
        <td><?=$r['browser']?></td>
        <td><?=$r['ip']?></td>
        <td title="<?=date('Y-m-d H:i:s',$r['addtime'])?>"><?=date('Y-m-d H:i:s',$r['addtime'])?></td>
        <!-- <td title="<?=date('Y-m-d H:i:s',$r['addtime'])?>"><?=date('Y-m-d H:i:s',$r['addtime'])?></td> -->
        
        <td><?php if(!$r['islock']){?>
          <a href="<?=$this->baseurl?>&m=delete&catid=<?=$catid?>&id=<?=$r['id']?>" onclick="return confirm('确定要删除吗？');">删除</a>
          <?php }?></td>
      </tr>
      <?php }?>
      <tr>
        <td colspan="11"><input type="checkbox" name="chkall" id="chkall"onclick="checkall('delete[]')" class="checkbox" />
          <label for="chkall">全选/反选</label>
          &nbsp;
          <input type="submit" value=" 删除 "class="btn" onclick="return confirm('确定要删除吗？');" />
          &nbsp;</td>
      </tr>
    </table>
    <div class="margintop">共：
      <?=$count?>
      条&nbsp;&nbsp;
      <?=$pages?>
    </div>
  </form>
</div>
<?php $this->load->view('admin/footer');?>
