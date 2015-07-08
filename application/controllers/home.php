<?php

class Home extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{	
		$data['main_view'] = 'welcome';
		$data['content'] = 'content/dashboard';
		$this->load->view('template', $data);
	}
}