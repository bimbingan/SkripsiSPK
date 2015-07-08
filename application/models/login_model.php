<?php if (!defined('BASEPATH')) 
	exit ('No direct script acces allowed');

class Login_model extends CI_Model {
	public $db_tabel ='user';

	public function load_form_rules()
	{
		$form_rules= array(array('field' =>'username' ,
								'label'=> 'username', 
								'rules' => 'required'), 
							array('field'=>'password',
								'label'=> 'password',
								'rules'=> 'required'),
		);
		
		return $form_rules;
	}

	public function validasi ()
	{
		$form = $this->load_form_rules();
		$this->form_validation->set_rules($form);

		if ($this->form_validation->run())
		{

			return TRUE;

		}
		else
		{
			return FALSE;
		}
	}


	//cek status user, login atau tidak?
	public function cek_user()
	{
		$username =$this->input->post('username');
		$password =md5 ($this->input->post('password'));

		$query = $this->db->where('username', $username)->where('password', $password)->limit(1)->get($this->db_tabel);
		// echo "<pre>";
		// echo $username;
		// print_r($query);
		// die();
		if ($query->num_rows() == 1)
		{
			$data= array('username' =>$username ,'login' => TRUE);
						// buat data session jika login benar
						$this->session->set_userdata ($data);
						return TRUE;
		}

						else
						{
							return FALSE;
						}
	}

	public function logout ()
	{ 
		$this->session->unset_userdata(
		array('username' => '', 'login'=> FALSE));
					$this->session->sess_destroy();			
	}

}