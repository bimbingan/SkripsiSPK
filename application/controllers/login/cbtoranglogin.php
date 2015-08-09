<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/CBTOrangLoginBase.php' );

// --

class cbtoranglogin extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();

        // load global
        $this->load->library('tnotification');
    }

    // view
    public function index($status = "") {
        // set template content
        $this->smarty->assign("template_content", "login/cbtoranglogin/form.html");
        // bisnis proses
        if (!empty($this->com_user)) {
            // still login
            redirect('login/cbtoranglogin/logout_process');
        } else {
            $this->smarty->assign("login_st", $status);
        }
        // output
        parent::display();
    }

    // login process
    public function login_process() {
        // set rules
        $this->tnotification->set_rules('peserta_id', 'NOMOR PERNDAFTARAN', 'trim|required|max_length[20]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // params
            $peserta_id = trim($this->input->post('peserta_id'));
            // get user detail saat open
            $result = $this->m_cbt->get_pemohon_by_peserta_orang($peserta_id);
            // check
            if (!empty($result)) {
                // check foto
                $file_path = 'resource/doc/files/orang/' . $result['pas_photo'];
                if (!is_file($file_path)) {
                    // output
                    redirect('login/cbtoranglogin/index/foto');
                }
                // ip
                $ip_address = $this->get_client_ip();
                // update waktu masuk dan tanggal masuk
                $this->m_cbt->update_presensi_peserta_orang($ip_address, $peserta_id);
                // set session
                $this->tsession->set_userdata('session_cbt_orang', array('peserta_id' => $result['peserta_id']));
                // redirect
                redirect('cbt/orang_welcome');
            } else {
                // output
                redirect('login/cbtoranglogin/index/error');
            }
        } else {
            // default error
            redirect('login/cbtoranglogin/index/kosong');
        }
        // output
        redirect('login/cbtoranglogin');
    }

    // logout process
    public function logout_process() {
        // user id
        $user_id = $this->tsession->userdata('session_cbt_orang');
        // unset session
        $this->tsession->unset_userdata('session_cbt_orang');
        // output
        redirect('login/cbtoranglogin');
    }

    public function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

}
