<?php $this->load->view('admin/header');?>
<script type="text/javascript">
$(function(){
	$('.set').click(function(){
		var cur_val = $(this).val();
		if(cur_val == 1) $('#shuoming').show();
		if(cur_val == 0) $('#shuoming').hide();
	})
})
</script>
<div class="mainbox nomargin" style="margin:10px 0px 0px 10px;">
	<form action="<?=$this->baseurl?>&m=save" method="post">
		<input type="hidden" name="id" value="<?=$value[id]?>">
       
		<table class="opt">
          
			<tr>
				<th ></th>
				<td>游戏维护时间从<input name="value[end_time]" style="width:50px;" type="text" class="txt" value="<?=$value[end_time]?>"/>开始到第二天
                <input name="value[start_time]" style="width:50px;" type="text" class="txt" value="<?=$value[start_time]?>"/>
                </td>
			</tr>
			
			<tr>
				<th></th>
				<td>是否停止游戏 <input name="value[isStop]" type="radio" class="set" value="1" <?php if ($value[isStop] == 1){echo 'checked';} ?>/><font color="#FF0000">是</font>
                			   <input name="value[isStop]" type="radio" class="set" value="0" <?php if ($value[isStop] == 0){echo 'checked';} ?>/>否
                </td>
			</tr>
			
            <tr id="shuoming" style="display:<?php if ($value[isStop] == 0){echo 'none';} ?>">
				<th></th>
				<td>维护说明  <input name="value[repairedtext]" style="width:250px;" type="text" class="txt" value="<?=$value[repairedtext]?>"/>     </td>
			</tr>
			
		
			<tr>
				<th>&nbsp;</th>
				<td><input type="submit" name="submit" value=" 提 交 " class="btn"
					tabindex="3" /> </td>
			</tr>
		</table>
	</form>

</div>

<?php $this->load->view('admin/footer');?>