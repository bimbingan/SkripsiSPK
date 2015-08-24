<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class user_manual extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('master/m_preferences');
        // load library
        $this->load->library('tnotification');
    }

    // view
    public function index() {
        // set template content
        $this->smarty->assign("template_content", "operator/user_manual/index.html");
        // data
        $rs_id = $this->m_preferences->get_list_user_manual();
        $this->smarty->assign('rs_id', $rs_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    /*
     * UTILITY
     */

    // download files
    public function download($role_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // get file
        $file_path = 'resource/doc/guides/' . $role_id . '.pdf';
        if (!is_file($file_path)) {
            // default error
            $this->tnotification->sent_notification("error", "File tidak ditemukan !");
            redirect('operator/user_manual');
        }
        // download
        $result = $this->m_preferences->get_detail_user_manual($role_id);
        $file_name = str_replace(" ", "_", $result['role_nm']);
        $file_name = "USER_MANUAL_APMS_" . strtoupper($file_name);
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '.pdf');
        readfile($file_path);
    }

}
