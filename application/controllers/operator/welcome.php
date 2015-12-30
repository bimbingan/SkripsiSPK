<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class welcome extends ApplicationBase {

    // constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_periode');
        $this->load->model('master/m_siswa');
        $this->load->model('master/m_nilai');
        // load library
        $this->load->library('tnotification');
        $this->load->library('datetimemanipulation');
    }

    // welcome administrator
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "operator/welcome/index.html");
        $this->smarty->load_javascript("resource/js/adminlte/Chart.min.js");

        $this->smarty->assign("rs_periode", $this->m_periode->get_all_periode());
        $this->smarty->assign("rs_jumlah_ipa", $this->m_siswa->get_jumlah_siswa_by_jurusan_per_year('IPA'));
        $this->smarty->assign("rs_jumlah_ips", $this->m_siswa->get_jumlah_siswa_by_jurusan_per_year('IPS'));

        $this->smarty->assign("rs_max_nilai", $this->m_nilai->get_max_nilai());
        $this->smarty->assign("rs_min_nilai", $this->m_nilai->get_min_nilai());
        // output
        parent::display();
    }

}
