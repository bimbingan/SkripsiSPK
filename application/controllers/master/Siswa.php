<?php

class Siswa extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_siswa');

	}

	function index(){
		$this->load->model('m_siswa');
		$data['siswa'] = $this->m_siswa->getAll();
		$data['content'] = 'content/siswa';
		$this->load->view('template', $data);
	}

	function edit($nis =NULL){

		$this->data['breadcumb'] = 'Siswa > Edit';
		$this->data['main_view'] = 'siswa/siswa_form';
		$this->data['form_action'] = 'siswa/edit/' . $nis;

	}

	function editProcess(){

	}
	
	function hapus($nis = NULL){
		// die("jhgjgjg");
		if (! empty($nis))
		{
			if($this->m_siswa->hapus($nis))
			{
				$this->session->set_flashdata('pesan', 'Proses hapus data berhasil');
				redirect ('master/siswa');
			}

			else
			{
				$this->session->set_flashdata('pesan', 'proses hapus data gagal');
				redirect ('master/siswa');
			}
		}

		else
		{
			$this->session->set_flashdata('pesan', 'proses hapus data gagal');
			redirect ('');
		}
	}
}