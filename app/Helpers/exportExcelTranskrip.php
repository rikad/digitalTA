<?php

/**
 * @Author: elawliet
 * @Date:   2017-09-11 01:36:37
 * @Last Modified by:   rikad
 * @Last Modified time: 2017-09-13 16:13:00
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

	//history template column
        'Hname' => 'B4',
        'Hnim' => 'B3',
        'HfinalGrade' => 'J4',
        'HacademicAdviserName' => 'E101',
        'HacademicAdviserNip' => 'E102',
        'HprogramChairName' => 'K101',
        'HprogramChairNip' => 'K102',

        'Hsemester1' => 'A9',
        'Hsemester2' => 'A19',
        'Hsemester3' => 'A29',
        'Hsemester4' => 'A40',
        'Hsemester5' => 'A51',
        'Hsemester6' => 'A62',
        'Hsemester7' => 'A73',
        'Hsemester8' => 'A85',
    	'Hsemester1ip'  => 'I7',  
    	'Hsemester2ip'  => 'I17',
    	'Hsemester3ip'  => 'I27',  
    	'Hsemester4ip'  => 'I38',
    	'Hsemester5ip'  => 'I49',  
    	'Hsemester6ip'  => 'I60',
    	'Hsemester7ip'  => 'I71',
    	'Hsemester8ip'  => 'I83',
    	'Hsemester1ipk'  => 'L7',
    	'Hsemester2ipk'  => 'L17',
    	'Hsemester3ipk'  => 'L27',  
    	'Hsemester4ipk'  => 'L38',
    	'Hsemester5ipk'  => 'L49',  
    	'Hsemester6ipk'  => 'L60',
    	'Hsemester7ipk'  => 'L71',
    	'Hsemester8ipk'  => 'L83',
        'Hsemester1ipk_v2'  => 'O7',
        'Hsemester2ipk_v2'  => 'O17',
        'Hsemester3ipk_v2'  => 'O27',  
        'Hsemester4ipk_v2'  => 'O38',
        'Hsemester5ipk_v2'  => 'O49',  
        'Hsemester6ipk_v2'  => 'O60',
        'Hsemester7ipk_v2'  => 'O71',
        'Hsemester8ipk_v2'  => 'O83',
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
