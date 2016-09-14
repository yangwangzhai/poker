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
	
	 //预览组图
		  $(".lookyd").click(function(){ 
		  	var openid = $(this).data('openid');
			if(openid != ''){
					$.ajax({
							type:'post',
							url:'<?=$this->baseurl?>&m=lookyd',
							dataType:'text',
							data:{openid:openid },
							success:function(res){
								
								var title = '查看烟豆';
								$.dialog({
										id: 'a15',
										max: false,    
										min: false,
										height: 50 ,
										width: 350,
										padding: '10px' ,
										title:  title,
										lock: true,
										content: res,//'<img  src="'+img[index]+'" width="100%" height="auto" />',
										cancelVal: '关闭',
										cancel: true /*为true等价于function(){}*/
								});
							}
					})
			}
		  
		  })
	
	 $(".phone").click(function(){ 
		  	var phone = $(this).data('phone');			
								
								var title = '查看浏览器信息';
								$.dialog({
										id: 'a15',
										max: false,    
										min: false,
										height: 150 ,
										width: 350,
										padding: '10px' ,
										title:  title,
										lock: true,
										content: phone,//'<img  src="'+img[index]+'" width="100%" height="auto" />',
										cancelVal: '关闭',
										cancel: true /*为true等价于function(){}*/
								});
			
		  })
	
	
	
	
});
function getstate(){		
		$('#submit').trigger("click");
	}	
	
	
	
	
</script>
<div class="mainbox">
	<span style="float: right">
   
		<form action="<?=$this->baseurl?>&m=index" method="post">
        
         
			<input type="hidden" name="catid" value="<?=$catid?>"> <input
				type="text" name="keywords" value=""> <input type="submit"
			 id="submit"	name="submit" value=" 搜索 " class="btn">
		</form>
	</span> 


		<input type="hidden" name="catid" value="<?=$catid?>">
		<table width="99%" border="0" cellpadding="3" cellspacing="0"
			class="datalist fixwidth" id="sortTable">
			<tr>
				<th width="30">排序</th>
				<th >头像</th>
                <th align="left">微信昵称</th>
				<th align="left">性别</th>
                <th align="left">openid</th>				
				<th >烟豆数</th>
                <th >是否在线</th>
                <th >首次参与时间</th>
                <th >最近一次活跃时间</th>
				<th >操作</th>

			</tr>

    <?php $sex = array('未知','男','女');foreach($list as $key=>$r) {?>
    <tr class="sortTr">


				<td><?=$key+1?></td>
		        <td><img src="<?=$r['head_img']?>" width="40" height="40" /></td>
                <td class="phone" data-phone='<?=$r['phone_os']?>' ><?=$r['nickname']?></td>
		        <td> <?=$sex[$r['sex']]?>
              </td>
		        <td><?=$r['openID']?></td>
		      
		        <td><?=$r['total_gold']?></td>
                 <td><?=$r['isonline'] == '是' ? '<font color="red">是</font>' : '否'?></td>
                  <td title="<?=times($r['addtime'],1)?>"><?=timeFromNow($r['addtime'])?></td>
 					<td title="<?=times($r['lasttime'],1)?>"><?=timeFromNow($r['lasttime'])?></td>


				<td>
                <a data-openid='<?=$r['openID']?>' class="lookyd" href="javascript:" >查询烟豆</a>&nbsp;&nbsp;
                 <?php if($_SESSION['groupid']==1){?>
               <a onclick="return confirm('确定要充值5000吗？');" href="<?=$this->baseurl?>&m=rechargeByMy&num=5000&openid=<?=$r['openID']?>" >充5000烟豆</a>&nbsp;&nbsp;
               <a onclick="return confirm('确定要充值10000吗？');" href="<?=$this->baseurl?>&m=rechargeByMy&num=10000&openid=<?=$r['openID']?>" >充10000烟豆</a>
              &nbsp;&nbsp;  <a href="<?=$this->baseurl?>&m=delete&catid=<?=$catid?>&id=<?=$r['id']?>" onclick="return confirm('确定要删除吗？');">删除</a>
                <?php }?>
               
               
                </td>
			</tr>
    <?php }?>
		</table>

		<div class="margintop">共：<?=$count?>条&nbsp;&nbsp;<?=$pages?></div>

	</form>

</div>


<?php $this->load->view('admin/footer');?>