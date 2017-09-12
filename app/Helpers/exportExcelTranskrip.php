<?php

/**
 * @Author: elawliet
 * @Date:   2017-09-11 01:36:37
 * @Last Modified by:   rikad
 * @Last Modified time: 2017-09-12 12:09:39
 * Helper import transkrip
 * Build Object dengan parameter $data, $template, $ouput_file
 * **$data array multi dengan key sesuai var $cellLocation
 		$data['semester'] array dengan urutan sesuai template
 		$data['finalExamination'] array dengan urutan sesuai template
 		$data['GpaChecklist'] array dengan urutan sesuai template
 		$data['SchChecklist'] array dengan urutan sesuai template
 */
namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
* 
*/
class exportExcelTranskrip
{
    private $spreadsheet;
    private $date;
    private $template;
    private $output;

    private $cellLocation = [
        'name' => 'J3',
        'nim' => 'J2',
        'yudisiumDate' => 'U2',
        'graduationDate' => 'U3',
        'academicAdviserName' => 'S39',
        'academicAdviserNip' => 'S38',
        'programChairNip' => 'S47',
        'programChairName' => 'S48',
        'semester1' => 'A6',
        'semester2' => 'J6',
        'semester3' => 'A17',
        'semester4' => 'J17',
        'semester5' => 'A29',
        'semester6' => 'J29',
        'semester7' => 'A41',
        'semester8' => 'J41',
        'finalExamination' => 'S7',
        'finalExaminationCheck' => 'W7',
    	'semester1ip'  => 'E14',  
    	'semester2ip'  => 'N14',
    	'semester3ip'  => 'E26',  
    	'semester4ip'  => 'N26',
    	'semester5ip'  => 'E38',  
    	'semester6ip'  => 'N38',
    	'semester7ip'  => 'E52',  
    	'semester8ip'  => 'N52',

    	'semester1ipk'  => 'G14',  
    	'semester2ipk'  => 'P14',
    	'semester3ipk'  => 'G26',  
    	'semester4ipk'  => 'P26',
    	'semester5ipk'  => 'G38',  
    	'semester6ipk'  => 'P38',
    	'semester7ipk'  => 'G52',  
    	'semester8ipk'  => 'P52',

    ];


    
    function __construct($data,$template,$output)
    {
        $reader = IOFactory::createReader('Xlsx');
        $this->spreadsheet = $reader->load($template);
        $this->data = $data;
        $this->output = $output;
    }

    function getCell($key) {
        return isset($this->cellLocation[$key]) ? $this->cellLocation[$key] : false;
    }

    function splitCell($cell) {
        preg_match_all('~[A-Z]+|\d+~', $cell, $newCell);
        $cell = $newCell[0];
        
        $row = $cell[1];
        $row = (int)$row;
        $column = $cell[0];

        return [ 'row' => $row, 'column' => $column ];
    }

    function generateRows($cell,$data) {
        foreach ($data as $r => $valueColumns) {
            $rowIndex = $cell['row'] + $r;
            $columnIndex = $cell['column'];

            foreach ($valueColumns as $value) {
                $newCell = $columnIndex.$rowIndex;

                $this->spreadsheet->getActiveSheet()->setCellValue($newCell, $value);
                $columnIndex++;
            }
        }
    }

    function generate() {

        foreach ($this->data as $key => $value) {

            $cell = $this->getCell($key);

            if(!$cell) continue;

            if (is_array($value)) {
                $this->generateRows($this->splitCell($cell),$value);
            } else {
                $this->spreadsheet->getActiveSheet()->setCellValue($cell,$value);
            }
        }

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($this->output);
    }
}
