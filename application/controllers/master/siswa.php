<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class siswa extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_siswa');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");

        // set template content
        $this->smarty->assign("template_content", "master/siswa/list.html");

        // load data
        $data_siswa = $this->m_siswa->get_all_siswa();
        $this->smarty->assign("rs_id", $data_siswa);

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // // pencarian
    // public function search_process() {
    //     // set page rules
    //     $this->_set_page_rule("R");
    //     //--
    //     if ($this->input->post('save') == 'Cari') {
    //         $params = array(
    //             "siswa_nis" => $this->input->post('siswa_nis'),
    //             "siswa_nama" => $this->input->post('siswa_nama'),
    //         );
    //
    //         // set
    //         $this->tsession->set_userdata('search_siswa', $params);
    //     } else {
    //         // unset
    //         $this->tsession->unset_userdata('search_siswa');
    //     }
    //     //--
    //     redirect('master/siswa');
    // }


    function add(){
      // set page rules
      $this->_set_page_rule("C");

      // set template content
      $this->smarty->assign("template_content", "master/siswa/add.html");

      // notification
      $this->tnotification->display_notification();
      $this->tnotification->display_last_field();
      // output
      parent::display();
    }

    function process_add(){
      // set page rules
      $this->_set_page_rule("C");

      $this->tnotification->set_rules('nis', 'NIS', 'trim|required|number');
      $this->tnotification->set_rules('nama', 'Nama', 'trim|required|max_length[45]');
      if($this->tnotification->run()){
          // kalau validasi benar

          $params = array(
            'nis' => $this->input->post('nis'),
            'nama' => $this->input->post('nama'),
          );

          if($this->m_siswa->insert_siswa($params)){
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
          }else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
          }

      }else{
        // kalau validasi salah
        $this->tnotification->sent_notification("error", "Data gagal disimpan");

      }

      redirect('master/siswa/add');
    }

}
