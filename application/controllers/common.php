<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 通用页 默认控制器

class Common extends CI_Controller 
{	
	
	// 登录
	function index()
	{
		header('location: index.php?d=admin&c=common&m=login');		
	}
	
	
	
}