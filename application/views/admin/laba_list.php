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

	<input type="button" value=" + 添加喇叭 " class="btn" onclick="location.href='<?=$this->baseurl?>&m=add'" />


		<input type="hidden" name="catid" value="<?=$catid?>">
		<table width="99%" border="0" cellpadding="3" cellspacing="0"
			class="datalist fixwidth" id="sortTable">
			<tr>
				<th width="30">排序</th>
                <th>内容</th>
				<th   align="left" width="80">状态</th>
				<th  align="left" width="120">添加时间</th>
				<th width="120">操作</th>

			</tr>

    <?php foreach($list as $key=>$r) {?>
    <tr class="sortTr">


				<td><?=$key+1?></td>
                <td><?=$r['content']?></td>
                <td id="row_<?=$r['id']?>"><a href="javascript:;" onclick="changeStatus(<?=$r[id]?>,<?=$r[status]?>);"><?=$r['status']?'<font color="red">已禁用</font>':'<font color="gren">已启用</font>'?></a></td>
		        <td title="<?=times($r['addtime'],1)?>"><?=timeFromNow($r['addtime'])?></td>

				<td>
					<a href="<?=$this->baseurl?>&m=edit&id=<?=$r['id']?>">编辑</a>&nbsp;&nbsp;
					<a href="<?=$this->baseurl?>&m=delete&id=<?=$r['id']?>" onclick="return confirm('确定要删除吗？');">删除</a>
				</td>
			</tr>
    <?php }?>
		</table>

		<div class="margintop">共：<?=$count?>条&nbsp;&nbsp;<?=$pages?></div>

	</form>

</div>

<script>
	function changeStatus(id,status){
		var color = status?'gren':'red';
		var text = status?"已启用":"已禁用";


		$.ajax({
			url:'index.php?d=admin&c=laba&m=changeStatus&id='+id+'&status='+status,
			type:'get',
			success:function(res){
				if(res == 1){
					$('#row_'+id).html('<a href="javascript:;" onclick="changeStatus('+id+','+(status?0:1)+')"><font color="'+color+'" >'+text+'</font></a>');
				}
			}
		})
	}
</script>


<?php $this->load->view('admin/footer');?>