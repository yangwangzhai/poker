<?php $this->load->view('admin/header');?>

<div class="main_login">
	<img src="static/admin_img/login_logo.png">   
	<div class="login">		
		<form action="index.php?d=admin&c=common&m=check_login" method="post">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="60" align="left" nowrap="nowrap">用户名:</th>
				  <td colspan="2" align="left"><input name="username" type="text" class="text"
						id="username" tabindex="1" /></td>
			  </tr>
				<tr>
					<td align="left" nowrap="nowrap">密&nbsp; 码:</th>
				  <td colspan="2" align="left"><input name="password" type="password" class="text"
						id="password" tabindex="2" /></td>
			  </tr>
				<!--<tr>
				  <td align="left">自&nbsp; 动：                
				  <td colspan="2" align="left"><select class="text" name="cookietime" id="cookietime" style="height:26px;">
				    <option value="0">不自动登录</option>
				    <option value="7">一个星期内</option>
				    <option value="30">一个月内</option>
				    <option value="90">三个月内</option>
				    <option value="365">一年内</option>
				    <option value="3650">十年内</option>
                    <option value="365000">百年内</option>
			      </select></td>
			  </tr>-->
				<tr style="display: none;">
					<th>验证码：</th>
					<td width="9%"><input name="checkcode" type="text" class="text"
						style="width: 80px" id="checkcode" tabindex="3" /></td>
					<td width="62%"><img src='cpi.php?c=common&m=checkcode'
						name="code_img" id='code_img' title="点击更换"
						onclick='this.src=this.src+"&"+Math.random()' /></td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td colspan="2" align="left"><input name="input" type="submit" value=" 登 录 "
						class="btnok" tabindex="4" /></td>
				</tr>
			</table>
		</form>
	</div>  
</div>
<p style="line-height:30px; text-align:center">建议使用IE8及以上版本的浏览器或火狐浏览器! </p>
<p style="line-height:30px;; text-align:center">Copyright ©2016 广西中烟 </p>
<?php $this->load->view('admin/footer');?>