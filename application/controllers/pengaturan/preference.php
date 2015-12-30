<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --
class preference extends ApplicationBase {

    function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('pengaturan/m_preference');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    // index to profil sekolah
    public function index() {
        redirect("pengaturan/preference/kuota_ipa");
    }

        public function kuota_ipa() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/kuota_ipa/list.html");
        // kuota
        $this->smarty->assign('rs_kuota', $this->m_preference->get_preference_by_group_and_name(array('kuota', 'ipa')));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // simpan profile sekolah
    public function save_kuota_ipa() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('kuota_ipa', 'Kuota IPA', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {

            // insert or update nama

            $params = array(
                'kuota',
                'ipa',
                $this->input->post('kuota_ipa'),
                $this->com_user['user_id']
            );
            if ($this->m_preference->insert_preference($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }


        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("pengaturan/preference/profil_sekolah");
    }

}
