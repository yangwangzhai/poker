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
function over(){
	if(confirm('您确定要结束本次活动？')){
		location.href='<?=$this->baseurl?>&m=over'
	}
}
function getstate(){		
		$('#submit').trigger("click");
	}	
</script>
<div class="mainbox">
 <input type="button" value=" 导出中奖名单 "  class="btn" onclick="location.href='<?=$this->baseurl?>&m=outputExcel'" />
	<span style="float: right">
       
		<form action="<?=$this->baseurl?>&m=index" method="post">
        
          领奖情况： <select  onchange="getstate()" style="width:auto"  name="isReceived">	
    						<option  value="">全部</option>					
                            <option <?php if($isReceived =='0') echo "selected"; ?> value="0">已领</option>
                            <option <?php if($isReceived =='1') echo "selected"; ?> value="1">未领</option>
                            
                  </select>     
        
        &nbsp;  &nbsp;
        
        
        领奖方式： <select  onchange="getstate()" style="width:auto"  name="received">	
    						<option  value="">全部</option>					
                            <option <?php if($received =='0') echo "selected"; ?> value="0">邮寄</option>
                            <option <?php if($received =='1') echo "selected"; ?> value="1">自提</option>
                             <option <?php if($received =='2') echo "selected"; ?> value="2">未选</option>
                  </select>     
        
        &nbsp;  &nbsp;
         <select  onchange="getstate()" style="width:auto"  name="address">	
    						<option  value="">全部</option>					
                            <option <?php if($address =='0') echo "selected"; ?> value="0">已填地址</option>
                            <option <?php if($address =='1') echo "selected"; ?> value="1">未填地址</option>
                  </select>     
        
			<input type="hidden" name="catid" value="<?=$catid?>"> <input
				type="text" name="keywords" value=""> <input type="submit" id="submit"
				name="submit" value=" 搜索 " class="btn">
		</form>
	</span>
	<?php if($isover ==0 ){ ?>
	<input type="button" value=" 结束活动 "  class="btn" onclick="over()" />
      <font color="#FF0000">(以点击结束活动按钮结束活动后的名单为准，中奖者的领奖地址为：<?=base_url('index.php?c=receive_prize')?>)</font>
    <?php }else{ ?> 
    <font color="#FF0000">(活动已结束，中奖者的领奖地址为：<?=base_url('index.php?c=receive_prize')?>)</font>
    <?php   } ?>
    
	<form action="<?=$this->baseurl?>&m=fahuo" method="post">
		<input type="hidden" name="catid" value="<?=$catid?>">
		<table width="99%" border="0" cellpadding="3" cellspacing="0"
			class="datalist fixwidth" id="sortTable">
			<tr>
				<th width="30"</th>
				<th width="30">排序</th>
				<th >头像</th>
                <th align="left">微信昵称</th>
				<th align="left">积分</th>
                <th align="left">中奖等级</th>
				<th align="left">姓名</th>
                <th align="left">手机号</th>				
				<th align="left">邮寄地址</th>
                <th align="left">鲜奶屋确认</th>
                <th align="left">确认时间</th>
                <th align="left">领奖方式</th>
				<th >操作</th>

			</tr>

    <?php $winner = array('未中奖','一等奖','二等奖','三等奖');
		  $received = array('邮寄','自提','未选');
	foreach($list as $key=>$r) {?>
    <tr class="sortTr">


				<td><?php if(!$r['islock']){?>
						<input type="checkbox" name="delete[]" value="<?=$r['id']?>"class="checkbox" />
					<?php }?>
				</td>
				<td><?=$key+1?></td>
				<td><img src="<?=$r['head_img']?>" width="50" height="50" /></td>
                <td><?=$r['nickname']?></td>
                <td><?=$r['score']?></td>
                  <td><?=$winner[$r['is_winning']] ?>	</td>
		        <td><?=$r['name']?></td>
		        <td><?=$r['tel']?></td>
		      
				<td><?=$r['prov'].$r['city'].$r['dist'].$r['address']?></td>
 				<td align=""><?=$r['truename']?></th>
                <td align="">
				  <?php if($r['admin_id']>0){ echo date('Y-m-d H:i:s',$r['admin_time']);}?>
                  
                 </th>
                       <td><?=$received[$r['received']] ?>	</td>       
				<td>
                <?php if($r['admin_id']==0){?>
                <a href="<?=$this->baseurl?>&m=fahuo&catid=<?=$catid?>&status=<?=$r['status']?>&id=<?=$r['id']?>" onclick="return confirm('确定要确认领奶吗？');">确认领奶</a>
                <?php }else{?>
					
						<font color="#0000FF">已领奶</font>
					<?php }?>
                </td>
			</tr>
    <?php }?>

			<tr>
				<td colspan="11">
                <?php if ($isover==1){ ?>
                <input type="checkbox" name="chkall" id="chkall"
										onclick="checkall('delete[]')" class="checkbox" /><label
						for="chkall">全选/反选</label>&nbsp; <input type="submit" value=" 发货 "
																class="btn" onclick="return confirm('确定发货吗？');" />
                                                            <?php }else{echo '<font color="#FF0000">活动未结束，暂无名单！</font>';}?>     &nbsp;</td>
			</tr>
		</table>

		<div class="margintop">共：<?=$count?>条&nbsp;&nbsp;<?=$pages?></div>

	</form>

</div>


<?php $this->load->view('admin/footer');?>