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
        $this->load->model('perhitungan/m_rating');
        $this->load->model('master/m_periode');
        $this->load->model('master/m_siswa');
        $this->load->model('perhitungan/m_rating_range');
        $this->load->model('pengaturan/m_preference');
        // load library
        $this->load->library('tnotification');
        $this->load->library('enum');
        $this->load->library('report_style');

        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // session
        $search = $this->tsession->userdata('search_nilai');
        $this->smarty->assign("search", $search);
        $siswa_nama = !empty($search['siswa_nama']) ? '%'.$search['siswa_nama'].'%' : '%';
        $periode_aktif = $this->m_periode->get_active_periode();
        $periode = !empty($search['periode']) ? $search['periode'] : $periode_aktif['id'];
        // set template content
        $this->smarty->assign("template_content", "master/nilai/list.html");
        // load css
        $this->smarty->load_style('editable/bootstrap-editable.css');
        // load javascript
        $this->smarty->load_javascript('resource/js/editable/bootstrap-editable.js');
        // load data
        $params = array($siswa_nama, $periode);
        $data_nilai = $this->m_nilai->get_list_nilai($params);

        usort($data_nilai, function($a, $b) {
            if($b['nilai_akhir'] == $a['nilai_akhir']){
                return intval($b['nis']) < intval($a['nis']);
            }
            return $b['nilai_akhir'] > $a['nilai_akhir'];
        });

        $this->smarty->assign("rs_id", $data_nilai);
        $rs_import = $this->m_periode->get_all_periode_order_status();
        $this->smarty->assign("rs_import", $rs_import);

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

        if($this->m_nilai->update_nilai($params, $where)){
            echo json_encode($where);
        }else{
            return false;
        }
    }

    function import($params){

        $this->smarty->assign("template_content", "master/nilai/import.html");
        $rs_import = $this->m_periode->get_all_periode();
        $this->smarty->assign("rs_import", $rs_import);

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();

        parent::display();
    }

    function hitung(){
        // set page rules
        $this->_set_page_rule("R");
        $search = $this->tsession->userdata('search_nilai');
        $this->smarty->assign("search", $search);
        $siswa_nama = !empty($search['siswa_nama']) ? '%'.$search['siswa_nama'].'%' : '%';
        $periode_aktif = $this->m_periode->get_active_periode();
        $periode = !empty($search['periode']) ? $search['periode'] : $periode_aktif['id'];
        $params =  array($periode);
        // set template content
        $this->smarty->assign("template_content", "master/nilai/list.html");
        // load css
        $this->smarty->load_style('editable/bootstrap-editable.css');
        // load javascript
        $this->smarty->load_javascript('resource/js/editable/bootstrap-editable.js');
        // load data
        $data_nilai = $this->m_nilai->get_all_nilai_by_periode($params);
        $this->smarty->assign("rs_id", $data_nilai);

        $rs_rating = $this->m_rating->get_all_rating(array('%',$periode));


        $kuota_ipa = $this->m_periode->get_kuota_ipa($periode);
        $rs_nilai = $this->m_nilai->get_all_nilai_by_periode($params);
        $matrix = array();
        $max = array();

        foreach ($rs_nilai as $key1 => $nilai) {
            foreach ($rs_rating as $key2 => $rating) {
                $params = array($nilai[$rating['nama_rating']], $rating['id_rating']);
                $matrix[$nilai['nis']][$rating['nama_rating']] = $this->m_rating_range->get_rating_by_nilai($params);

                $max[$rating['nama_rating']] = ($max[$rating['nama_rating']] > $matrix[$nilai['nis']][$rating['nama_rating']]) ? $max[$rating['nama_rating']] : $matrix[$nilai['nis']][$rating['nama_rating']];
                if($nilai['minat'] == 2){
                    $matrix[$nilai['nis']]['ipa_minat'] = 0;
                    $matrix[$nilai['nis']]['ips_minat'] = 5;
                }elseif($nilai['minat'] == 1){
                    $matrix[$nilai['nis']]['ips_minat'] = 0;
                    $matrix[$nilai['nis']]['ipa_minat'] = 5;
                }else{
                    $matrix[$nilai['nis']]['ipa_minat'] = 0;
                    $matrix[$nilai['nis']]['ips_minat'] = 0;
                }
            }
        }

        $max['ips_minat'] = 5;
        $max['ipa_minat'] = 5;

        foreach ($matrix as $key => $mat) {
            foreach ($rs_rating as $key_rating => $rating) {
                // echo  "(".$mat[$rating['nama_rating']] ."/". $max[$rating['nama_rating']].")"."*". $rating['nilai_rating']."<br>" ;
                $rating_result[$key][$rating['nama_rating']] = ($mat[$rating['nama_rating']] / $max[$rating['nama_rating']]) * $rating['nilai_rating'] ;
                $rating_result[$key]['jumlah'] += round($rating_result[$key][$rating['nama_rating']], 1);
                $rating_result[$key]['nis'] = $key;
                $rating_result[$key]['nama'] = $this->m_siswa->get_nama_by_nis($key);
            }
        }

        foreach ($rating_result as $key_result => $res) {
            $params = array("nilai_akhir" => $res['jumlah']);
            $where = array("nis" => $res['nis']);
            $this->m_nilai->update_nilai($params, $where);
        }

        foreach ($rating_result as $key => $result) {

        }
        usort($rating_result, function($a, $b) {
            if($b['jumlah'] == $a['jumlah']){
                return intval($b['nis']) < intval($a['nis']);
            }
            return $b['jumlah'] > $a['jumlah'];
        });

        $rs_ipa = array_slice($rating_result, 0, $kuota_ipa);
        $rs_ips = array_slice($rating_result, count($rs_ipa));

        foreach($rs_ipa as $ipa){
            $params = array('hasil' => 'IPA');
            $where = array('nis' => $ipa['nis']);
            $this->m_nilai->update_nilai($params, $where);
        }
        foreach($rs_ips as $ips){
            $params = array('hasil' => 'IPS');
            $where = array('nis' => $ips['nis']);
            $this->m_nilai->update_nilai($params, $where);
        }

        redirect('master/nilai');

    }

    // pencarian
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "siswa_nis" => $this->input->post('siswa_nis'),
                "siswa_nama" => $this->input->post('siswa_nama'),
                "periode" => $this->input->post('periode'),
            );

            // set
            $this->tsession->set_userdata('search_nilai', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_nilai');
        }
        //--
        redirect('master/nilai');
    }


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
        $this->tnotification->set_rules('ips_tes', 'IPS', 'trim');
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

    function download(){
        $this->_set_page_rule("C");
        $this->load->library('phpexcel');
        $this->tnotification->set_rules('periode_id', 'Periode', 'trim');

        if($this->tnotification->run() !== FALSE){

            $inputfilename ='./resource/doc/formatSPK.xlsx';

            try{
                $inputfileType= PHPExcel_IOFactory::identify($inputfilename);
                $objReader= PHPExcel_IOFactory::createReader($inputfileType);
                $objPHPExcel = $objReader->load($inputfilename);
            } catch(Exception $e){
                die('Error loading file "'.pathinfo($inputfileName, PATHINFO_BASENAME).'"; '.$e->getMessage());
            }

            $objPHPExcel->getActiveSheet(0)->setCellValue('BS1', $this->input->post('periode_id'));

            $filename= 'BERKAS_IMPORT_DATA.xlsx';
            header('content-type: application/vnd.ms-excel');
            header('content-disposition: attachment;filename="'. $filename .'"');
            header('cache-control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');

            $objWriter->save('php://output');
        }else{
            $this->tnotification->sent_notification("error", "Lengkapi terlebih dahulu pilihan dibawah");
            redirect("master/nilai/import/");
        }
    }

    function import_process(){
        $this->_set_page_rule("C");

        $this->load->library('phpexcel');
        $this->load->library('tupload');

        if (!empty($_FILES['file_siswa']['tmp_name'])){
            $this->load->library('tupload');

            $config['upload_path']= 'resource/doc/';
            $config['allowed_types']= 'xlsx';
            $config['file_name'] = 'import_siswa';
            $this->tupload->initialize($config);

            if ($this->tupload->do_upload('file_siswa')){
                $data= $this->tupload->data();

                $inputfilename= $data['full_path'];

                try{
                    $inputfileType= PHPExcel_IOFactory::identify($inputfilename);
                    $objReader= PHPExcel_IOFactory::createReader($inputfileType);
                    $objPHPExcel = $objReader->load($inputfilename);
                }catch(Exception $e){
                    die('Error loading file "'.pathinfo($inputfileName, PATHINFO_BASENAME).'"; '.$e->getMessage());
                }

                $sheet =$objPHPExcel->getSheet(0);
                $highestRow= $sheet->getHighestRow();
                $highestColoumn= $sheet->getHighestColumn();
                $periode_id='';

                $this->db->trans_begin();
                for($row=1;$row <= $highestRow; $row++){
                    $rowData= $sheet->rangeToArray('A' . $row. ':' . $highestColoumn . $row, NULL,TRUE,FALSE);

                    if($rowData[0][0] == 'Nis'){
                        $periode_id=$rowData[0][70];
                        continue;
                    }else if($rowData[0][0] == ''){
                        continue;
                    }else{

                        $params = array(
                            'nis'=> $rowData[0]['0'],
                            'nama'=> $rowData[0]['1'],
                            'jenis_kelamin'=>$rowData[0]['2'],
                            'tempat_lahir'=>$rowData[0]['3'],
                            'tgl_lahir' =>substr($rowData[0]['4'], 0,4)."-".substr($rowData[0]['4'],4,2)."-".substr($rowData[0]['4'], 6,2),
                            'asal_sekolah' =>$rowData[0]['5'],
                            'periode' => $periode_id,
                        );

                        if(!$this->m_siswa->is_nis_exist($params['nis'])){
                            if($this->m_siswa->insert_siswa($params)){
                                $nis= $params['nis'];
                                $params = array(
                                    'mtk_un' =>$rowData[0]['6'],
                                    'ipa_un' =>$rowData[0]['7'],
                                    'bindo_un' => $rowData[0]['8'],
                                    'bing_un' => $rowData[0]['9'],
                                    'mtk_tes' => $rowData[0]['10'],
                                    'ipa_tes' => $rowData[0]['11'],
                                    'ips_tes' => $rowData[0]['12'],
                                    'bing_tes'=> $rowData[0]['13'],
                                    'minat' => ($rowData[0]['14'] == 'IPA') ? '1' : '2',
                                    'nis' => $rowData[0]['0'],
                                );
                                $where = array(
                                    'nis' => $nis
                                );
                                $this->m_nilai->update_nilai($params, $where);
                            }
                        }else{
                            $where = array('nis' => $params['nis']);
                            unset($params['nis']);
                            $this->m_siswa->update_siswa($params, $where);
                            $params = array(
                                'mtk_un' =>$rowData[0]['6'],
                                'ipa_un' =>$rowData[0]['7'],
                                'bindo_un' => $rowData[0]['8'],
                                'bing_un' => $rowData[0]['9'],
                                'mtk_tes' => $rowData[0]['10'],
                                'ipa_tes' => $rowData[0]['11'],
                                'ips_tes' => $rowData[0]['12'],
                                'bing_tes'=> $rowData[0]['13'],
                                'minat' => ($rowData[0]['14'] == 'IPA') ? '1' : '2',
                            );
                            $this->m_nilai->update_nilai($params, $where);
                        }
                    }
                }

                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $this->tnotification->sent_notification("error", "Import Gagal");
                }else{
                    $this->db->trans_commit();
                    $this->tnotification->sent_notification("succes", "Import Data Berhasil");
                }
            }else{
                $this->tnotification->sent_error_message($this->tupload->display_errors());
            }
        }else{
            $this->tnotification->sent_notification("error", "Import Gagal");
        }

        redirect("master/nilai/import/");
    }

    function eksport_nilai(){
        $this->_set_page_rule("R");

        $this->load->library('phpexcel');

        $search = $this->tsession->userdata('search_nilai');
        $this->smarty->assign('search', $search);

        $siswa_nama = !empty($search['siswa_nama']) ? '%'.$search['siswa_nama'].'%' : '%';
        $periode = !empty($search['periode']) ? $search['periode'] : '%';

        $params = array($siswa_nama, $periode);
        $rs_id = $this->m_nilai->get_list_nilai($params);

        $this->phpexcel->setActiveSheetIndex('0');

        $this->phpexcel->getActiveSheet()->setTitle('sheet1');
        $this->phpexcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setfITtoPage(true);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToWidth(0);

        $cursor= array(
            'huruf' => 'A',
            'angka' => 1
        );

        $this->phpexcel->getActiveSheet()->setCellValue($cursor['huruf'].$cursor['angka'], 'Laporan Peminatan Siswa');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_style_title());

        $this->phpexcel->getActiveSheet()->setCellValue($cursor['huruf'].++$cursor['angka'], strtoupper('SMA NEGERI 1 BOJONG'));
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_style_title());

        $periode= $this->m_periode->get_one_periode($search['periode']);


        if (!empty($periode)) {

            $this->phpexcel->getActiveSheet()->setCellValue($cursor['huruf'].++$cursor['angka'], 'Tahun Ajaran '.$periode['tahun']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_style_title());

        }

        $cursor['angka'] +=2;

        $this->phpexcel->getActiveSheet()->setCellValue($cursor['huruf'].$cursor['angka'], 'Nis');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(5);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Nama');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(30);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Jenis Kelamin');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(20);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'TTL');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(30);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Asal Sekolah');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(30);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'MTK UN');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'IPA UN');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Bindo UN');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Bing UN');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'MTK Tes');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'IPA Tes');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'IPS Tes');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Bing Tes');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Minat 1');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Minat 2');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], 'Hasil Peminatan');
        $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_row_header());
        $this->phpexcel->getActiveSheet()->getColumnDimension($cursor['huruf'])->setWidth(15);

        $this->phpexcel->getActiveSheet()->mergeCells("A1:".$cursor['huruf']."1");
        $this->phpexcel->getActiveSheet()->mergeCells("A2:".$cursor['huruf']."2");
        $this->phpexcel->getActiveSheet()->mergeCells("A3:".$cursor['huruf']."3");


        foreach ($rs_id as $key_siswa => $result) {
            $cursor['angka']++;
            $cursor['huruf'] = 'A';

            $this->phpexcel->getActiveSheet()->setCellValue($cursor['huruf'].$cursor['angka'], $result['nis']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['nama']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['jenis_kelamin']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['tempat_lahir'].", ".$this->datetimemanipulation->get_full_date($result['tgl_lahir']));
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['asal_sekolah']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['mtk_un']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['ipa_un']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['bindo_un']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['bing_un']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['mtk_tes']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['ipa_tes']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['ips_tes']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['bing_tes']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], (($result['minat'] == '1') ? 'IPA' : 'IPS') );
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], (($result['minat'] == '1') ? 'IPS' : 'IPA') );
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());

            $this->phpexcel->getActiveSheet()->setCellValue(++$cursor['huruf'].$cursor['angka'], $result['hasil']);
            $this->phpexcel->getActiveSheet()->getStyle($cursor['huruf'].$cursor['angka'])->applyFromArray($this->report_style->get_content_center_style());
        }

        $filename = 'Laporan_Peminatan_Siswa';
        $filename .='.xlsx';

        header('content-type: application/vnd.ms-excel');
        header('content-disposition: attachment;filename="'.$filename.'"');
        header('cache-control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');
    }



}
