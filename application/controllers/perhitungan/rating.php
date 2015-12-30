<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class rating extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('perhitungan/m_rating');
        $this->load->model('master/m_periode');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");

        $search = $this->tsession->userdata('search_rating');
        $this->smarty->assign("search", $search);
        $nama_rating = !empty($search['nama_rating']) ? '%'.$search['nama_rating'].'%' : '%';
        $periode = !empty($search['periode']) ? $search['periode'] : '%';

        // set template content
        $this->smarty->assign("template_content", "perhitungan/rating/list.html");

        // load data
        $params = array($nama_rating, $periode);
        $data_rating = $this->m_rating->get_all_rating($params);
        $this->smarty->assign("rs_id", $data_rating);

        $rs_import = $this->m_periode->get_all_periode();
        $this->smarty->assign("rs_import", $rs_import);


        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }


    function add(){
      // set page rules
      $this->_set_page_rule("C");

      // set template content
      $this->smarty->assign("template_content", "perhitungan/rating/add.html");
      // load js
      $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
      $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
      // load css
      $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');
      $periode = $this->m_periode->get_all_periode();
      $this->smarty->assign("rs_periode", $periode);
      // notification
      $this->tnotification->display_notification();
      $this->tnotification->display_last_field();
      // output
      parent::display();
    }

    function process_add(){
      // set page rules
      $this->_set_page_rule("C");


      $this->tnotification->set_rules('nama_rating', 'Nama Rating', 'trim|required|max_length[45]');
      $this->tnotification->set_rules('group_rating', 'Group Rating', 'trim');
      $this->tnotification->set_rules('nilai_rating', 'Nilai Rating', 'trim');
      $this->tnotification->set_rules('periode', 'Periode', 'trim');
        if($this->tnotification->run()){
          // kalau validasi benar

          $params = array(

            'nama_rating' => $this->input->post('nama_rating'),
            'group_rating' => $this->input->post('group_rating'),
            'nilai_rating' => $this->input->post('nilai_rating'),
            'periode' => $this->input->post('periode'),
          );

          if($this->m_rating->insert_rating($params)){
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

      redirect('perhitungan/rating/add');
    }

    function delete($params){
        $this->_set_page_rule("D");

        if($this->m_rating->delete_rating($params)){
              // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        }else{
            $this->tnotification->sent_notification("error", "Data gagal dihapus");

        }
        redirect("perhitungan/rating");
    }

    function edit($params){
         $this->_set_page_rule("U");
         $this->smarty->assign("template_content", "perhitungan/rating/edit.html");

         // load js
         $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
         $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
         // load css
         $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');

         $rating = $this->m_rating->get_one_rating($params);
         $this->smarty->assign("result", $rating);
         $periode = $this->m_periode->get_all_periode();
         $this->smarty->assign("rs_periode", $periode);
         // notification
         $this->tnotification->display_notification();
         $this->tnotification->display_last_field();
         // output
         parent::display();
     }

    function process_edit(){
        $this->_set_page_rule("U");

        $this->tnotification->set_rules('nama_rating', 'Nama Rating', 'trim|required|max_length[45]');
        $this->tnotification->set_rules('group_rating', 'Group Rating', 'trim');
        $this->tnotification->set_rules('nilai_rating', 'Nilai Rating', 'trim');
        $this->tnotification->set_rules('periode', 'Periode', 'trim');
        if($this->tnotification->run() !== FALSE){
            $params = array(

              'nama_rating' => $this->input->post('nama_rating'),
              'group_rating' => $this->input->post('group_rating'),
              'nilai_rating' => $this->input->post('nilai_rating'),
              'periode' => $this->input->post('periode'),
            );
            $where = array(
                'id_rating' => $this->input->post('id_rating'),
            );

            if($this->m_rating->update_rating($params, $where)){

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
        redirect("perhitungan/rating/edit/". $this->input->post('id_rating'));
    }

    // pencarian
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "id_rating" => $this->input->post('id_rating'),
                "nama_rating" => $this->input->post('nama_rating'),
                "periode" => $this->input->post('periode'),
            );

            // set
            $this->tsession->set_userdata('search_rating', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_rating');
        }
        //--
        redirect('perhitungan/rating');
    }

}
