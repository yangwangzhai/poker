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

	<span style="float: right">
		<form action="<?=$this->baseurl?>&m=index" method="post">
			<input type="hidden" name="catid" value="<?=$catid?>"> <input
				type="text" name="keywords" value=""> <input type="submit"
				name="submit" value=" 搜索 " class="btn">
		</form>
	</span> 
    <?php if($_SESSION['groupid']==1){?>
    <input type="button" value=" + 添加用户 " class="btn" onclick="location.href='<?=$this->baseurl?>&m=add&catid=<?=$catid?>'" />
	<?php }?>
	<form action="<?=$this->baseurl?>&m=delete" method="post">
		<input type="hidden" name="catid" value="<?=$catid?>">
		<table width="99%" border="0" cellpadding="3" cellspacing="0"
			class="datalist fixwidth" id="sortTable">
			<tr>
				<th width="30"></th>
				<th width="30"></th>
                <th align="left">管理组</th>
				<th align="left">用户名</th>
                <th align="left">姓名</th>                
				<th >电话</th>
	      <th width="160">添加时间</th>
				<th width="100">操作</th>
			</tr>

    <?php 
	$group = array(1=>'管理组',2=>'运营组',3=>'商家组');
	 foreach($list as $key=>$r) {?>
    <tr class="sortTr">
				<td>
                <input type="checkbox" name="delete[]" value="<?=$r['id']?>"class="checkbox" />

                </td>
				<td><?=$key+1?></td>
				<td><?=$group[$r['groupid']]?></td>
                <td><?=$r['username']?></td>
                <td><?=$r['truename']?></td>
				<td><?=$r['telephone']?></td>
				<td title="<?=times($r['addtime'],1)?>"><?=timeFromNow($r['addtime'])?></td>
				<td><a href="<?=$this->baseurl?>&m=edit&id=<?=$r['id']?>">修改</a>&nbsp;&nbsp;
                <?php if(!$r['islock']){?>
                <a href="<?=$this->baseurl?>&m=delete&catid=<?=$catid?>&id=<?=$r['id']?>" onclick="return confirm('确定要删除吗？');">删除</a>
                <?php }?>
                </td>
			</tr>
    <?php }?>
		</table>

		<div class="margintop">共：<?=$count?>条&nbsp;&nbsp;<?=$pages?></div>

	</form>

</div>


<?php $this->load->view('admin/footer');?>