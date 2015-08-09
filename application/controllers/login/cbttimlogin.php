<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/CBTTimLoginBase.php' );

// --

class cbttimlogin extends ApplicationBase {

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
        $this->smarty->assign("template_content", "login/cbttimlogin/form.html");
        // bisnis proses
        if (!empty($this->com_user)) {
            // still login
            redirect('login/cbttimlogin/logout_process');
        } else {
            $this->smarty->assign("login_st", $status);
        }
        // output
        parent::display();
    }

    // login process
    public function login_process() {
        // set rules
        $this->tnotification->set_rules('peserta_id', 'ID PESERTA', 'trim|required|max_length[20]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // params
            $peserta_id = trim($this->input->post('peserta_id'));
            // get user detail
            $result = $this->m_cbt->get_pemohon_by_peserta_tim($peserta_id);
            // check
            if (!empty($result)) {
                // check foto
                $file_path = 'resource/doc/files/tim/' . $result['pas_photo'];
                if (!is_file($file_path)) {
                    // output
                    redirect('login/cbttimlogin/index/foto');
                }
                // ip
                $ip_address = $this->get_client_ip();
                // update waktu masuk dan tanggal masuk
                $this->m_cbt->update_presensi_peserta_tim($ip_address, $peserta_id);
                // set session
                $this->tsession->set_userdata('session_cbt_tim', array('peserta_id' => $result['peserta_id']));
                // redirect
                redirect('cbt/tim_welcome');
            } else {
                // output
                redirect('login/cbttimlogin/index/error');
            }
        } else {
            // default error
            redirect('login/cbttimlogin/index/error');
        }
        // output
        redirect('login/cbttimlogin');
    }

    // logout process
    public function logout_process() {
        // user id
        $user_id = $this->tsession->userdata('session_cbt_tim');
        // unset session
        $this->tsession->unset_userdata('session_cbt_tim');
        // output
        redirect('login/cbttimlogin');
    }

}
