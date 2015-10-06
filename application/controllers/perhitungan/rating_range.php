<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class rating_range extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('perhitungan/m_rating_range');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");

        // set template content
        $this->smarty->assign("template_content", "perhitungan/rating_range/list.html");

        // load data
        $data_rating_range = $this->m_rating_range->get_all_rating_range();
        $this->smarty->assign("rs_id", $data_rating_range);

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
      $this->smarty->assign("template_content", "perhitungan/rating_range/add.html");
      // load js
      $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
      $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
      // load css
      $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');

      // data
      $this->smarty->assign("rs_rating", $this->m_rating_range->get_all_rating());
      $this->smarty->assign("rs_range_nilai", $this->m_rating_range->get_all_range_nilai());

      // notification
      $this->tnotification->display_notification();
      $this->tnotification->display_last_field();
      // output
      parent::display();
    }

    function process_add(){
      // set page rules
      $this->_set_page_rule("C");

      $this->tnotification->set_rules('id_rating', 'Nama Rating', 'trim|required|max_length[45]');
      $this->tnotification->set_rules('id_range', 'Batas Range Nilai', 'trim');
      $this->tnotification->set_rules('nilai_range', 'Nilai Range', 'trim');

        if($this->tnotification->run()){
          // kalau validasi benar

          $params = array(
            'id_rating' => $this->input->post('id_rating'),
            'id_range' => $this->input->post('id_range'),
            'nilai_range' => $this->input->post('nilai_range'),

          );

          if($this->m_rating_range->insert_rating_range($params)){
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

      redirect('perhitungan/rating_range/add');
    }

    function delete($params){
        $this->_set_page_rule("D");

        if($this->m_rating_range->delete_rating_range($params)){
              // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        }else{
            $this->tnotification->sent_notification("error", "Data gagal dihapus");

        }
        redirect("perhitungan/rating_range");
    }

    function edit($params){
         $this->_set_page_rule("U");
         $this->smarty->assign("template_content", "perhitungan/rating_range/edit.html");

         // load js
         $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
         $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
         // load css
         $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');

         $rating_range = $this->m_rating_range->get_one_rating_range($params);
         
         $this->smarty->assign("result", $rating_range);
         $this->smarty->assign("rs_rating", $this->m_rating_range->get_all_rating());
         $this->smarty->assign("rs_range_nilai", $this->m_rating_range->get_all_range_nilai());

         // notification
         $this->tnotification->display_notification();
         $this->tnotification->display_last_field();
         // output
         parent::display();
     }

    function process_edit(){
        $this->_set_page_rule("U");

        $this->tnotification->set_rules('id_rating', 'Nama Rating', 'trim|required|max_length[45]');
        $this->tnotification->set_rules('id_range', 'Batas Range Nilai', 'trim');
        $this->tnotification->set_rules('nilai_range', 'Nilai Range', 'trim');

        if($this->tnotification->run() !== FALSE){
            $params = array(

                'id_rating' => $this->input->post('id_rating'),
                'id_range' => $this->input->post('id_range'),
                'nilai_range' => $this->input->post('nilai_range'),

            );
            $where = array(
                'id_range' => $this->input->post('id_range'),
            );

            if($this->m_rating_range->update_rating_range($params, $where)){

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
        redirect("perhitungan/rating_range/edit/". $this->input->post('id_range'));
    }

}
