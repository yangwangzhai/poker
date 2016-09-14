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
<style>
.maintable {
	-moz-border-bottom-colors: none;
	-moz-border-left-colors: none;
	-moz-border-right-colors: none;
	-moz-border-top-colors: none;
	border-collapse: collapse;
	border-color: #86b9d6 #d8dde5 #d8dde5;
	border-image: none;
	border-style: solid;
	border-width: 2px 1px 1px;
	width: 100%;
}
.maintable th, .maintable td {
	border: 1px solid #d8dde5;
	padding: 5px;
}
.maintable th {
	background: #f3f7ff none repeat scroll 0 0;
	color: #0d58a5;
	font-weight: normal;
	text-align: left;
	width: 210px;
}
.maintable td th, .maintable td td {
	border: medium none;
	padding: 1px;
}
.maintable th p {
	color: #909dc6;
	margin: 0;
}
.input {
	cursor: pointer;
	font-size: 16px;
	height: 25px;
	width: 200px;
}
.input2 {
	cursor: pointer;
	font-size: 14px;
	height: 22px;
	width: auto;
}
.table_list td:hover {
	background: #f2f9fd none repeat scroll 0 0;
}
</style>
<div class="mainbox nomargin" style="margin:10px 0px 0px 10px;">
  <form action="<?=$this->baseurl?>&m=save" method="post">
    <input type="hidden" name="id" value="<?=$value[id]?>">
    <table class="maintable">
    <tr>
        <td><b><font color="#FF0000">概率为0到100之间的整数；2号狗概率为20时表示2号狗赢1号狗的概率为20%，1号狗赢2号狗的概率为80%；</font> </b></td>
      </tr>
      <tr>
        <td><b>2号狗狗的规则 </b></td>
      </tr>
      <?php for($i=0 ; $i<3 ;$i++){?>
      <tr>
        <td>2号狗狗的下注烟豆<b style="color:#F00">大于</b>(不包含)
          <input name="value[2][<?=$i?>][yandou]" style="width:50px;" type="text" class="txt" value="<?=$dog2[$i][yandou] ?>"/>
          时，<b style="color:#F00">赢1号</b>藏獒的概率 <input name="value[2][<?=$i?>][gailv]" style="width:50px;" type="text" class="txt" value="<?=$dog2[$i][gailv]?>"/>%
          </td>
      </tr>
      
      <?php }?>
      <tr>
        <td>&nbsp;</td>
      </tr>
      
      
           <tr>
        <td><b>3号狗狗的规则 </b></td>
      </tr>
      <?php for($i=0 ; $i<3 ;$i++){?>
      <tr>
        <td>3号狗狗的下注烟豆<b style="color:#F00">大于</b>(不包含)
          <input name="value[3][<?=$i?>][yandou]" style="width:50px;" type="text" class="txt" value="<?=$dog3[$i][yandou]?>"/>
          时，<b style="color:#F00">赢1号</b>藏獒的概率 <input name="value[3][<?=$i?>][gailv]" style="width:50px;" type="text" class="txt" value="<?=$dog3[$i][gailv]?>"/>%
          </td>
      </tr>
      
      <?php }?>
      <tr>
        <td>&nbsp;</td>
      </tr>
      
      
           <tr>
        <td><b>4号狗狗的规则 </b></td>
      </tr>
      <?php for($i=0 ; $i<3 ;$i++){?>
      <tr>
        <td>4号狗狗的下注烟豆<b style="color:#F00">大于</b>(不包含)
          <input name="value[4][<?=$i?>][yandou]" style="width:50px;" type="text" class="txt" value="<?=$dog4[$i][yandou]?>"/>
          时，<b style="color:#F00">赢1号</b>藏獒的概率 <input name="value[4][<?=$i?>][gailv]"style="width:50px;" type="text" class="txt" value="<?=$dog4[$i][gailv]?>"/>%
          </td>
      </tr>
      
      <?php }?>
      <tr>
        <td>&nbsp;</td>
      </tr>
      
      
           <tr>
        <td><b>5号狗狗的规则 </b></td>
      </tr>
      <?php for($i=0 ; $i<3 ;$i++){?>
      <tr>
        <td>5号狗狗的下注烟豆<b style="color:#F00">大于</b>(不包含)
          <input name="value[5][<?=$i?>][yandou]" style="width:50px;" type="text" class="txt" value="<?=$dog5[$i][yandou]?>"/>
          时，<b style="color:#F00">赢1号</b>藏獒的概率 <input name="value[5][<?=$i?>][gailv]" style="width:50px;" type="text" class="txt" value="<?=$dog5[$i][gailv]?>"/>%
          </td>
      </tr>
      
      <?php }?>
      <tr>
        <td><input name="status" type="checkbox" value="1" <?php if ($status == 1){echo 'checked';} ?> /><b style="color:#F00">启用规则</b>
        (启用后才有效)
        </td>
      </tr>
      
      
      <tr>
        <td><input type="submit" name="submit" value=" 提 交 " class="btn"
					tabindex="3" /></td>
      </tr>
    </table>
  </form>
</div>
<?php $this->load->view('admin/footer');?>
