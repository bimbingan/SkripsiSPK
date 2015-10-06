<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class nilai extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_nilai');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");

        // set template content
        $this->smarty->assign("template_content", "master/nilai/list.html");
        // load css
        $this->smarty->load_style('editable/bootstrap-editable.css');
        // load javascript
        $this->smarty->load_javascript('resource/js/editable/bootstrap-editable.js');
        // load data
        $data_nilai = $this->m_nilai->get_all_nilai();
        $this->smarty->assign("rs_id", $data_nilai);

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    function add_nilai($params){
      // set page rules
      $this->_set_page_rule('U');

      $name = $this->input->post('name');
      $value = $this->input->post('value');
      $pk = $this->input->post('pk');

      $params = array($name => $value );
      $where = array('nis' => $pk);

      $this->m_nilai->update_nilai($params, $where);

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
      $this->smarty->assign("template_content", "master/nilai/add.html");
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

      $this->tnotification->set_rules('nis', 'No', 'trim|required|number');
      $this->tnotification->set_rules('nama', 'Nama', 'trim|required|max_length[45]');
      $this->tnotification->set_rules('mtk_un', 'MTK', 'trim');
      $this->tnotification->set_rules('ipa_un', 'IPA', 'trim');
      $this->tnotification->set_rules('bindo_un', 'B.Indo', 'trim');
      $this->tnotification->set_rules('bing_un', 'B.Ing', 'trim');
      $this->tnotification->set_rules('mtk_tes', 'MTK', 'trim');
      $this->tnotification->set_rules('ipa_tes', 'IPA', 'trim');
      $this->tnotification->set_rules('bing_tes', 'B.Ing', 'trim');
      $this->tnotification->set_rules('minat', 'Minat', 'trim');


      if($this->tnotification->run()){
          // kalau validasi benar

          $params = array(
            'nis' => $this->input->post('nis'),
            'nama' => $this->input->post('nama'),
            'mapel' => $this->input->post('mapel'),
          );


          if($this->m_nilai->insert_nilai($params)){
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

      redirect('master/nilai/add');
    }

    function delete($params){
        $this->_set_page_rule("D");

        if($this->m_nilai->delete_nilai($params)){
              // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        }else{
            $this->tnotification->sent_notification("error", "Data gagal dihapus");

        }
        redirect("master/nilai");
    }

}