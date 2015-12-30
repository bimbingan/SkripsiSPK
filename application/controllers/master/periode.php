<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class periode extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_periode');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        $search = $this->tsession->userdata('search_periode');
        $this->smarty->assign("search", $search);
        $tahun = !empty($search['tahun']) ? '%'.$search['tahun'].'%' : '%';
        // session

        // set template content
        $this->smarty->assign("template_content", "master/periode/list.html");

        // load data
        $params = array($tahun);
        $data_periode = $this->m_periode->get_list_periode($params);
        $this->smarty->assign("rs_id", $data_periode);

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pencarian
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "id" => $this->input->post('id'),
                "tahun" => $this->input->post('tahun'),
            );

            // set
            $this->tsession->set_userdata('search_periode', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_periode');
        }
        //--
        redirect('master/periode');
    }


    function add(){
      // set page rules
      $this->_set_page_rule("C");

      // set template content
      $this->smarty->assign("template_content", "master/periode/add.html");
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

      $this->tnotification->set_rules('id', 'ID', 'trim|required|number');
      $this->tnotification->set_rules('tahun', 'Tahun', 'trim');
      $this->tnotification->set_rules('status', 'Status', 'trim');
      $this->tnotification->set_rules('kuota_ipa', 'Kuota IPS','trim');
      if($this->tnotification->run()){
          // kalau validasi benar

          $params = array(
            'id' => $this->input->post('id'),
            'tahun' => $this->input->post('tahun'),
            'status' => $this->input->post('status'),
            'kuota_ipa' => $this->input->post('kuota_ipa'),
          );


          if($this->m_periode->insert_periode($params)){
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

      redirect('master/periode/add');
    }

    function delete($params){
        $this->_set_page_rule("D");

        if($this->m_periode->delete_periode($params)){
              // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        }else{
            $this->tnotification->sent_notification("error", "Data gagal dihapus");

        }
        redirect("master/periode");
    }

    function edit($params){
         $this->_set_page_rule("U");
         $this->smarty->assign("template_content", "master/periode/edit.html");

         // load js
         $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
         $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
         // load css
         $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');

         $periode = $this->m_periode->get_one_periode($params);
         $this->smarty->assign("result", $periode);
         // notification
         $this->tnotification->display_notification();
         $this->tnotification->display_last_field();
         // outputF
         parent::display();
     }

    function process_edit(){
        $this->_set_page_rule("U");
        $this->tnotification->set_rules('id', 'ID', 'trim|required|number');
        $this->tnotification->set_rules('tahun', 'Tahun', 'trim|required');
        $this->tnotification->set_rules('status', 'Status', 'trim');
        $this->tnotification->set_rules('kuota_ipa', 'Kuota IPA', 'trim');
        if($this->tnotification->run() !== FALSE){
            $params = array(

              'tahun' => $this->input->post('tahun'),
              'status' => $this->input->post('status'),
              'kuota_ipa' => $this->input->post('kuota_ipa'),
            );
            $where = array(
                'id' => $this->input->post('id'),
            );

            if($this->m_periode->update_periode($params, $where)){

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
        redirect("master/periode/edit/". $this->input->post('id'));
    }

}
