<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 后台公共页控制器 登陆页 by tangjian 
class Common extends CI_Controller
{
    
    // 框架首页
    public function index ()
    {     
        $uid = $this->session->userdata('id');
        if (! $uid) {
           redirect('d=admin&c=common&m=login');
        }
        
        $list = get_cache('category');
        
        $this->load->library('tree');
        $this->tree->init($list);
        $string = '<li><a href=\"index.php?d=admin&c=news&m=index&catid=$id\" target=\"main\" >$spacer  ▪ $name </li>';
        
        $data['category'] = $this->tree->get_tree(0, $string);
        
        $data['mainurl'] = '';
        $this->load->view('admin/frame_index', $data);
    }
    
    // 默认搜索页
    public function main ()
    {
        $uid = $this->session->userdata('id');
        if (! $uid) {
            redirect('d=admin&c=common&m=login');
        }
        $this->load->view('admin/main');
    }
    
    // 登陆页
    public function login ()
    {
        $uid = $this->session->userdata('id');
        if ($uid) {
            redirect('d=admin&c=common');
        }
        
        $this->load->view('admin/login', $data);
    }
    
    // 验证登陆
    public function check_login()
    {
        
        $username   = trim($_POST['username']);
        $password   = get_password($_POST['password']);
        //$checkcode  = trim($_POST['checkcode']); 
        $cookietime = intval($_POST['cookietime'])*3600*24;        
        
//         if ($checkcode != $_SESSION['checkcode']) {
//             show_msg('验证码不正确，请重新输入');
//         }
        
        $query = $this->db->get_where('zy_admin',
                array(
                        'username' => $username,
                        'password' => $password
                ), 1);
        $user = $query->row_array();
        if ($user) {
            // $this->db->query('update fly_user set
            // logins=logins+1,lastlogtime='.time().' where id='.$user['id']);
            unset($user['password']);
           $this->session->sess_expiration = $cookietime;
            $this->session->set_userdata($user);  		
            redirect('d=admin&c=common&m=index');
        } else {
            show_msg('用户名或密码错误，请重新登录！', 'admin.php');
        }
    }
    
    // 退出
    public function login_out ()
    {
        $this->session->sess_destroy();
        redirect('d=admin&c=common&m=login');
    }
    
    // 基础设置
    public function website ()
    {
        $data[web] = get_cache('website');
        
        $this->load->view('admin/website', $data);
    }
    
    // 基础设置
    public function website_save ()
    {
        $data = array(
                'title' => trim($_POST['title']),
                'keywords' => trim($_POST['keywords']),
                'description' => trim($_POST['description']),
                'aboutus' => trim($_POST['aboutus'])
        );
        set_cache('website', $data);
        
        show_msg('设置成功！', 'index.php?d=admin&c=common&m=website');
    }
}

/* End of file welcome.php */




