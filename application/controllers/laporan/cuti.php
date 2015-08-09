<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class cuti extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/m_cuti');
        $this->load->model('master/m_pengurussekolah');
        $this->load->model('pengaturan/m_preference');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "laporan/cuti/list.html");
        // session
        $search = $this->tsession->userdata('search_laporan_cuti');
        $this->smarty->assign('search', $search);

        // params
        $tahun_cuti = $this->m_cuti->get_min_max_tahun_cuti();
        $guru_nama = !empty($search['guru_nama']) ? "%" . $search['guru_nama'] . "%" : "%";
        $tahun = !empty($search['tahun']) ? $search['tahun'] : $tahun_cuti['max'];
        $params = array($guru_nama, $tahun);

        /* processing data */

        $list_pengurus = $this->m_pengurussekolah->get_all_pengurussekolah();
        $cuti_per_month = $this->m_cuti->get_all_cuti($params);
        $guru_cuti_id = array();
        $nilai_maks_cuti = $this->m_preference->get_preference_by_group_and_name(array('cuti', 'batas'));
        $rs_id = array();

        foreach ($cuti_per_month as $cuti) {
            $guru_cuti_id[] = $cuti['guru_id'];
        }

        // processing data from database to meets presentation requirement
        if (!empty($cuti_per_month)) {
            foreach ($list_pengurus as $key_pengurus => $pengurus) {
                if (in_array($pengurus['guru_id'], $guru_cuti_id)) {
                    $rs_id[$key_pengurus]['guru_id'] = $pengurus['guru_id'];
                    $rs_id[$key_pengurus]['guru_nama'] = $pengurus['guru_nama'];
                    $rs_id[$key_pengurus]['total_cuti'] = 0;
                    $rs_id[$key_pengurus]['sisa'] = $nilai_maks_cuti[0]['pref_value'];

                    for ($i = 1; $i <= 12; $i++) {
                        $rs_id[$key_pengurus][$i] = 0;
                    }
                    foreach ($cuti_per_month as $key_cuti => $cuti) {
                        if ($cuti['guru_id'] === $pengurus['guru_id']) {
                            $rs_id[$key_pengurus][$cuti['bulan']] += $cuti['jumlah'];
                            $rs_id[$key_pengurus]['total_cuti'] += $cuti['jumlah'];
                            $rs_id[$key_pengurus]['tahun'] = $cuti['tahun'];
                            $rs_id[$key_pengurus]['cuti_keterangan'] = $cuti['cuti_keterangan'];

                            $rs_id[$key_pengurus]['sisa'] -= $cuti['jumlah'];
                        }
                    }
                } else if ($guru_nama == '%') {
                    $rs_id[$key_pengurus]['guru_id'] = $pengurus['guru_id'];
                    $rs_id[$key_pengurus]['guru_nama'] = $pengurus['guru_nama'];
                    $rs_id[$key_pengurus]['total_cuti'] = 0;
                    $rs_id[$key_pengurus]['sisa'] = $nilai_maks_cuti[0]['pref_value'];

                    for ($i = 1; $i <= 12; $i++) {
                        $rs_id[$key_pengurus][$i] = 0;
                    }
                    foreach ($cuti_per_month as $key_cuti => $cuti) {
                        if ($cuti['guru_id'] === $pengurus['guru_id']) {
                            $rs_id[$key_pengurus][$cuti['bulan']] += $cuti['jumlah'];
                            $rs_id[$key_pengurus]['total_cuti'] += $cuti['jumlah'];
                            $rs_id[$key_pengurus]['tahun'] = $cuti['tahun'];
                            $rs_id[$key_pengurus]['sisa'] -= $cuti['jumlah'];
                            $rs_id[$key_pengurus]['cuti_keterangan'] = $cuti['cuti_keterangan'];
                        }
                    }
                }
            }
        }

        $result_tahun = array();
        $rs_tahun = $this->m_cuti->get_min_max_tahun_cuti($params);
        for ($i = 0; $i + $rs_tahun['min'] <= $rs_tahun['max']; $i++) {
            $result_tahun[] = $rs_tahun['min'] + $i;
        }

        $this->tsession->set_userdata('cuti_list', $rs_id);

        /* end of processing data */


        // load data
        $this->smarty->assign("no", 1);
        $this->smarty->assign("rs_id", $rs_id);
        $this->smarty->assign("rs_tahun", $result_tahun);
        $this->smarty->assign("cur_tahun", $tahun);

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
                "guru_nama" => $this->input->post('guru_nama'),
                "tahun" => $this->input->post('tahun')
            );
            // set
            $this->tsession->set_userdata('search_laporan_cuti', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_laporan_cuti');
        }
        //--
        redirect('laporan/cuti');
    }

    // view details cuti
    public function detail($params) {
        // set page rules
        $this->_set_page_rule("R");
        // load helper
        $this->load->helper('cookie');
        // set template content
        $this->smarty->assign("template_content", "laporan/cuti/detail.html");
        /* processing data */
        $result = array();
        $rs_id_per_bulan = $this->m_cuti->get_cuti_by_guru($params, 'bulan');
        $rs_id_per_tahun = $this->m_cuti->get_cuti_by_guru($params, 'tahun');


        foreach ($rs_id_per_tahun as $key_tahun => $tahun) {
            $result[$key_tahun]['tahun'] = $tahun['tahun'];
            $result[$key_tahun]['guru_id'] = $tahun['guru_id'];
            $result[$key_tahun]['guru_nama'] = $tahun['guru_nama'];
            $result[$key_tahun]['cuti_keterangan'] = $tahun['cuti_keterangan'];
            $result[$key_tahun]['guru_nik'] = $tahun['guru_nik'];
            for ($i = 1; $i <= 12; $i++) {
                $result[$key_tahun][$i] = 0;
            }
            foreach ($rs_id_per_bulan as $key_bulan => $bulan) {
                if ($bulan['tahun'] == $tahun['tahun']) {
                    $result[$key_tahun][$bulan['bulan']] = $bulan['jumlah'];
                    $result[$key_tahun]['total_cuti'] += $bulan['jumlah'];
                }
            }
        }
        $this->tsession->set_userdata('cuti_detail', $result);

        /* end of processing data */

        // load data		
        $this->smarty->assign("no", 1);
        $this->smarty->assign("rs_id", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // export to excel
    function export_excel_list() {
        $this->load->library('phpexcel');
        $rs_cuti = $this->tsession->userdata('cuti_list');
        $search = $this->tsession->userdata('search_laporan_cuti');

        //activate worksheet number 1
        $this->phpexcel->setActiveSheetIndex(0);
        //name the worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Rekapitulasi Cuti Tahun ');

        // set Page and Orientation
        $this->phpexcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        // end of set Page and Orientation

        $this->phpexcel->getActiveSheet()->setCellValue('A7', "No.");
        $this->phpexcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(4.43);
        $this->phpexcel->getActiveSheet()->mergeCells('A7:A8');
        $this->phpexcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('A7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->phpexcel->getActiveSheet()->setCellValue('B7', "Nama");
        $this->phpexcel->getActiveSheet()->getStyle('B7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(33.86);
        $this->phpexcel->getActiveSheet()->mergeCells('B7:B8');
        $this->phpexcel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('B7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $this->phpexcel->getActiveSheet()->setCellValue('C7', "Pengambilan Cuti per Bulan");
        $this->phpexcel->getActiveSheet()->getStyle('C7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->mergeCells('C7:N7');
        $this->phpexcel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->phpexcel->getActiveSheet()->setCellValue('O7', "Total Cuti");
        $this->phpexcel->getActiveSheet()->getStyle('O7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('O')->setWidth(12.14);
        $this->phpexcel->getActiveSheet()->mergeCells('O7:O8');
        $this->phpexcel->getActiveSheet()->getStyle('O7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('O7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->phpexcel->getActiveSheet()->setCellValue('P7', "Sisa Hak Cuti");
        $this->phpexcel->getActiveSheet()->getStyle('P7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('P')->setWidth(11.71);
        $this->phpexcel->getActiveSheet()->mergeCells('P7:P8');
        $this->phpexcel->getActiveSheet()->getStyle('P7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('P7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->phpexcel->getActiveSheet()->setCellValue('Q7', "Keterangan");
        $this->phpexcel->getActiveSheet()->getStyle('Q7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(45);
        $this->phpexcel->getActiveSheet()->mergeCells('Q7:Q8');
        $this->phpexcel->getActiveSheet()->getStyle('Q7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('Q7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        for ($i = 1; $i <= 12; $i++) {
            $this->phpexcel->getActiveSheet()->setCellValue(chr((66 + $i)) . '8', $i);
            $this->phpexcel->getActiveSheet()->getStyle(chr((66 + $i)) . '8')->getFont()->setSize(12);
            $this->phpexcel->getActiveSheet()->getColumnDimension(chr((66 + $i)))->setWidth(3);
            $this->phpexcel->getActiveSheet()->getStyle(chr((66 + $i)) . '8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle(chr((66 + $i)) . '8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        }

        $pos_cell = 8;
        foreach ($rs_cuti as $key => $cuti) {
            $pos_cell++;
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $pos_cell, ++$key);
            $this->phpexcel->getActiveSheet()->getStyle('A' . $pos_cell)->getFont()->setSize(12);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $pos_cell, $cuti['guru_nama']);
            $this->phpexcel->getActiveSheet()->getStyle('B' . $pos_cell)->getFont()->setSize(12);


            for ($i = 1; $i <= 12; $i++) {
                $this->phpexcel->getActiveSheet()->setCellValue(chr((66 + $i)) . $pos_cell, $cuti[$i]);
                $this->phpexcel->getActiveSheet()->getStyle(chr((66 + $i)) . $pos_cell)->getFont()->setSize(12);
                $this->phpexcel->getActiveSheet()->getStyle(chr((66 + $i)) . $pos_cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->phpexcel->getActiveSheet()->getStyle(chr((66 + $i)) . $pos_cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }

            $this->phpexcel->getActiveSheet()->setCellValue('O' . $pos_cell, $cuti['total_cuti']);
            $this->phpexcel->getActiveSheet()->getStyle('O' . $pos_cell)->getFont()->setSize(12);
            $this->phpexcel->getActiveSheet()->getStyle('P7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->phpexcel->getActiveSheet()->setCellValue('P' . $pos_cell, $cuti['sisa']);
            $this->phpexcel->getActiveSheet()->getStyle('P' . $pos_cell)->getFont()->setSize(12);
            $this->phpexcel->getActiveSheet()->getStyle('P7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $pos_cell, $cuti['cuti_keterangan']);
            $this->phpexcel->getActiveSheet()->getStyle('Q' . $pos_cell)->getFont()->setSize(12);
        }

        //set aligment to center for that merged cell (A1 to D1)
        $filename = 'REKAPITULASI_CUTI_TAHUN_' . $search['tahun'] . '.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        //force user to download the Excel file without writing it to server's HD
        $this->tsession->unset_userdata('search_laporan_cuti');
        $objWriter->save('php://output');
    }

    // export detail to excel
    function export_excel_detail() {
        $this->load->library('phpexcel');
        $rs_cuti = $this->tsession->userdata('cuti_detail');

        //activate worksheet number 1
        $this->phpexcel->setActiveSheetIndex(0);
        //name the worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Rekapitulasi Cuti' . $rs_cuti[0]['guru_nama']);

        // set Page and Orientation
        $this->phpexcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        // end of set Page and Orientation

        $this->phpexcel->getActiveSheet()->setCellValue('A7', "Tahun");
        $this->phpexcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
        $this->phpexcel->getActiveSheet()->mergeCells('A7:A8');
        $this->phpexcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('A7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->phpexcel->getActiveSheet()->setCellValue('B7', "Bulan Pengambilan Cuti");
        $this->phpexcel->getActiveSheet()->getStyle('B7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->mergeCells('B7:M7');
        $this->phpexcel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('B7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $this->phpexcel->getActiveSheet()->setCellValue('N7', "Total Cuti");
        $this->phpexcel->getActiveSheet()->getStyle('N7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->mergeCells('N7:N8');
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(13.57);
        $this->phpexcel->getActiveSheet()->getStyle('N7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('N7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->phpexcel->getActiveSheet()->setCellValue('O7', "Keterangan");
        $this->phpexcel->getActiveSheet()->getStyle('O7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('O')->setWidth(40);
        $this->phpexcel->getActiveSheet()->mergeCells('O7:O8');
        $this->phpexcel->getActiveSheet()->getStyle('O7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('O7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        for ($i = 1; $i <= 12; $i++) {
            $this->phpexcel->getActiveSheet()->setCellValue(chr((65 + $i)) . '8', $i);
            $this->phpexcel->getActiveSheet()->getStyle(chr((65 + $i)) . '8')->getFont()->setSize(12);
            $this->phpexcel->getActiveSheet()->getColumnDimension(chr((65 + $i)))->setWidth(4);
            $this->phpexcel->getActiveSheet()->getStyle(chr((65 + $i)) . '8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle(chr((65 + $i)) . '8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        }
        $pos_cell = 8;
        foreach ($rs_cuti as $key => $cuti) {
            $pos_cell++;
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $pos_cell, $cuti['tahun']);
            $this->phpexcel->getActiveSheet()->getStyle('A' . $pos_cell)->getFont()->setSize(12);

            $this->phpexcel->getActiveSheet()->setCellValue('N' . $pos_cell, $cuti['total_cuti']);
            $this->phpexcel->getActiveSheet()->getStyle('N' . $pos_cell)->getFont()->setSize(12);

            $this->phpexcel->getActiveSheet()->setCellValue('O' . $pos_cell, $cuti['cuti_keterangan']);
            $this->phpexcel->getActiveSheet()->getStyle('O' . $pos_cell)->getFont()->setSize(12);


            for ($i = 1; $i <= 12; $i++) {
                $this->phpexcel->getActiveSheet()->setCellValue(chr((65 + $i)) . $pos_cell, $cuti[$i]);
                $this->phpexcel->getActiveSheet()->getStyle(chr((65 + $i)) . $pos_cell)->getFont()->setSize(12);
                $this->phpexcel->getActiveSheet()->getStyle(chr((65 + $i)) . $pos_cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->phpexcel->getActiveSheet()->getStyle(chr((65 + $i)) . $pos_cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }
        }

        //set aligment to center for that merged cell (A1 to D1)
        $filename = 'REKAPITULASI_DETAIL_CUTI_' . str_replace(" ", "_", $rs_cuti[0]['guru_nama']) . '.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        //force user to download the Excel file without writing it to server's HD
        $this->tsession->unset_userdata('cuti_detail');
        //
        $objWriter->save('php://output');
    }

}
