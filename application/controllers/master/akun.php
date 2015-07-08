<?php

class akun extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('akun_model');
	}

	function index(){
		$this->load->model('akun_model');
		$data['user'] = $this->akun_model->getAll();
		$data['content'] = 'content/Akun';
		$this->load->view('template', $data);
	}

	function edit(){

	}

	function editProcess(){

	}
	
	function hapus($id_user = NULL){
		// die("jhgjgjg");
		if (! empty($id_user))
		{
			if($this->akun_model->hapus($id_user))
			{
				$this->session->set_flashdata('pesan', 'Proses hapus data berhasil');
				redirect ('master/akun');
			}

			else
			{
				$this->session->set_flashdata('pesan', 'proses hapus data gagal');
				redirect ('master/akun');
			}
		}

		else
		{
			$this->session->set_flashdata('pesan', 'proses hapus data gagal');
			redirect ('');
		}
	}
}