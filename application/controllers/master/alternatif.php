<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class alternatif extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_alternatif');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");

        // set template content
        $this->smarty->assign("template_content", "master/alternatif/list.html");

        // load data
        $data_alternatif = $this->m_alternatif->get_all_alternatif();
        $this->smarty->assign("rs_id", $data_alternatif);

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
      $this->smarty->assign("template_content", "master/alternatif/add.html");
      // load js
      $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
      $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
      // load css
      $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');

      // notification
      $this->tnotification->display_notification();
      $this->tnotification->display_last_field();
      // output
      parent::display();
    }

    function process_add(){
      // set page rules
      $this->_set_page_rule("C");

      $this->tnotification->set_rules('id', 'No', 'trim|required|number');
      $this->tnotification->set_rules('alternatif', 'Alternatif', 'trim|required|max_length[45]');
      $this->tnotification->set_rules('deskripsi', 'Deskripsi', 'trim');

      if($this->tnotification->run()){
          // kalau validasi benar

          $params = array(
            'id' => $this->input->post('id'),
            'alternatif' => $this->input->post('alternatif'),
            'deskripsi' => $this->input->post('deskripsi'),
          );


          if($this->m_alternatif->insert_alternatif($params)){
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

      redirect('master/alternatif/add');
    }

    function delete($params){
        $this->_set_page_rule("D");

        if($this->m_alternatif->delete_alternatif($params)){
              // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        }else{
            $this->tnotification->sent_notification("error", "Data gagal dihapus");

        }
        redirect("master/alternatif");
    }

    function edit($params){
         $this->_set_page_rule("U");
         $this->smarty->assign("template_content", "master/alternatif/edit.html");

         $alternatif = $this->m_alternatif->get_one_alternatif($params);
         $this->smarty->assign("result", $alternatif);
         // notification
         $this->tnotification->display_notification();
         $this->tnotification->display_last_field();
         // output
         parent::display();
     }

    function process_edit(){
        $this->_set_page_rule("U");
        $this->tnotification->set_rules('id', 'No', 'trim|required|number');
        $this->tnotification->set_rules('alternatif', 'Alternatif', 'trim|required|max_length[45]');
        $this->tnotification->set_rules('deskripsi', 'Deskripsi', 'trim');
        if($this->tnotification->run() !== FALSE){
            $params = array(

              'alternatif' => $this->input->post('alternatif'),
              'deskripsi' => $this->input->post('deskripsi'),
            );
            $where = array(
                'id' => $this->input->post('id'),
              );

            if($this->m_alternatif->update_alternatif($params, $where)){

                 // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            }else{
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        }else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        redirect("master/alternatif/edit/". $this->input->post('id'));
    }

}
