<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class report_style {

    public function method(){
        parent::__construct();
        //Codeigniter : Write Less Do More
    }

    function get_style_title(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'font'  => array(
                'name' => 'Arial',
                'size'  => 12,
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function get_title_no_wrap(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => false
            ),
            'font'  => array(
                'name' => 'Arial',
                'size'  => 12,
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function get_row_header(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'font'  => array(
                'name' => 'Arial',
                'size'  => 11,
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function get_small_header(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'font'  => array(
                'name' => 'Arial',
                'size'  => 10,
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function get_small_row_header(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'font'  => array(
                'name' => 'Arial',
                'size'  => 10,
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function get_content_center_style(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
        );
        return $styleArray;
    }

    function get_content_right_style(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
        );
        return $styleArray;
    }

    function get_content_left_style(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
        );
        return $styleArray;
    }

    function get_content_red_style(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color' => array(
                        'rgb' => 'FF0000'
                    )
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'FF7373'
                )
            ),
        );
        return $styleArray;
    }

    function border(){
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            )
        );
        return $styleArray;
    }

    function sub_header(){
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            )
        );
        return $styleArray;
    }


    function bold_left_10px(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'font'  => array(
                'name' => 'Arial',
                'size' => 10,
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function bold_center_10px(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'font'  => array(
                'name' => 'Arial',
                'size' => 10,
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function outline_border_left(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'font' => array(
                'name' => 'Arial',
                'size' => 10,
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function tile_style_content(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' =>  array(
                    'rgb' => 'D9D9D9'
                )
            ),
            'font' => array(
                'bold' => true,
            )
        );
        return $styleArray;
    }

    function tile_style_title(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
            'font' => array(
                'bold' => true,
                'size' => 13,

            )
        );
        return $styleArray;
    }


    function times_new_roman_12(){
        $styleArray = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => false
            ),
            'font' => array(
                'name' => 'Times New Roman',
                'bold' => false,
                'size' => 12,
            )
        );
        return $styleArray;
    }

    function border_photo(){
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
                    'color' => array(
                        'rgb' => PHPExcel_Style_Color::COLOR_BLACK
                    )
                )
            ),
        );
        return $styleArray;
    }
}
