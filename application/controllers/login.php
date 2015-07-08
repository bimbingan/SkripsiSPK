<?php if (!defined('BASEPATH')) 
	exit ('No direct script acces allowed');

class Login extends CI_Controller
{
	public $data = array('pesan' => '' );

	public function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->model('Login_model', 'login', TRUE);
	}

	public function index()
	{
		//status user login= BENAR, pindah ke halaman absen
		if ($this->session->userdata('login') == TRUE)
		{
			redirect('home');
		}

		//status login salah, tampilkan form login

		else
		{
			//validasi sukses
			if ($this->login->validasi())
			{
				//cek dii database sukses
				if($this->login->cek_user())
				{
					redirect ('home');
				}

				//cek dtaabase gagal
				else
				{
	$this->data['pesan']='Username atau Password salah.';
	$this->load->view('login/login_form', $this->data);
				}
			}
			//validasi gagal
			else
			{
				$this->load->view('login/login_form', $this->data);
			}
		}
	}

	public function logout()
	{
		$this->login->logout();
		redirect('login');
	}
} 

?>