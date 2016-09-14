<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

//$config['base_url'] = 'http://example.com/index.php/test/page/';
$config['total_rows'] = 20;
$config['per_page'] = 20;
$config['num_links'] = 5;
$config['first_link'] = '首页'; // 第一页显示
$config['last_link'] = '末页'; // 最后一页显示
$config['next_link'] = '下一页>'; // 下一页显示
$config['prev_link'] = '<上一页'; // 上一页显示

// $this->config->load('pagination', TRUE);
// $pagination = $this->config->item('pagination');
// $pagination['base_url'] = $this->baseurl;
// $pagination['total_rows'] = $count['num'];
// $this->load->library('pagination');
// $this->pagination->initialize($pagination);
// $data['pages'] = $this->pagination->create_links();