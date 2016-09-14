<?php $this->load->view('admin/header');?>
<script type="text/javascript">

</script>
<div class="mainbox nomargin" style="margin:10px 0px 0px 10px;">
	<form action="<?=$this->baseurl?>&m=save" method="post">
		<input type="hidden" name="id" value="<?=$value[id]?>">
       
		<table class="opt">
          
			<tr>
				<th >喇叭内容</th>
				<td><textarea name="value[content]" style="width:500px;" type="text" class="txt"><?=$value[content]?></textarea></td>
			</tr>
			
			<tr>
				<th>启用状态</th>
				<td>
					<select name="value[status]" id="status">
						<option value='0' <?=$value['status']?'':'selected=true'?>>启用</option>
						<option value='1' <?=$value['status']?'selected=true':''?>>禁用</option>
					</select>
				</td>
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