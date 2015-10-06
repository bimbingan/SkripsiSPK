<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class range_nilai extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('perhitungan/m_range_nilai');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");

        // set template content
        $this->smarty->assign("template_content", "perhitungan/range_nilai/list.html");

        // load data
        $data_range_nilai = $this->m_range_nilai->get_all_range_nilai();
        $this->smarty->assign("rs_id", $data_range_nilai);

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
      $this->smarty->assign("template_content", "perhitungan/range_nilai/add.html");
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

      $this->tnotification->set_rules('batas_atas', 'Batas Atas Range Nilai', 'trim|required|max_length[45]');
      $this->tnotification->set_rules('batas_bawah', 'Batas Bawah Range Nilai', 'trim');

        if($this->tnotification->run()){
          // kalau validasi benar

          $params = array(
            'batas_atas' => $this->input->post('batas_atas'),
            'batas_bawah' => $this->input->post('batas_bawah'),

          );

          if($this->m_range_nilai->insert_range_nilai($params)){
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

      redirect('perhitungan/range_nilai/add');
    }

    function delete($params){
        $this->_set_page_rule("D");

        if($this->m_range_nilai->delete_range_nilai($params)){
              // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        }else{
            $this->tnotification->sent_notification("error", "Data gagal dihapus");

        }
        redirect("perhitungan/range_nilai");
    }

    function edit($params){
         $this->_set_page_rule("U");
         $this->smarty->assign("template_content", "perhitungan/range_nilai/edit.html");

         // load js
         $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
         $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
         // load css
         $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');

         $range_nilai = $this->m_range_nilai->get_one_range_nilai($params);
         $this->smarty->assign("result", $range_nilai);
         // notification
         $this->tnotification->display_notification();
         $this->tnotification->display_last_field();
         // output
         parent::display();
     }

    function process_edit(){
        $this->_set_page_rule("U");

        $this->tnotification->set_rules('batas_atas', 'Batas Atas Range Nilai', 'trim|required|max_length[45]');
        $this->tnotification->set_rules('batas_bawah', 'Batas Bawah Range Nilai', 'trim');

        if($this->tnotification->run() !== FALSE){
            $params = array(

              'batas_atas' => $this->input->post('batas_atas'),
              'batas_bawah' => $this->input->post('batas_bawah'),

            );
            $where = array(
                'id_range' => $this->input->post('id_range'),
            );

            if($this->m_range_nilai->update_range_nilai($params, $where)){

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
        redirect("perhitungan/range_nilai/edit/". $this->input->post('id_range'));
    }

}
