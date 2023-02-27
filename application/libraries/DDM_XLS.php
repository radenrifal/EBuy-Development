<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "libraries/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;

/**
 * XLS Class
 */
class DDM_XLS
{
	var $CI;
	var $qualified;

	// simple export
	var $companyName;
	var $simpleExcel;
	var $objPHPExcel;
	var $objReader;
	var $objWriter;
	var $worksheet;
	var $tempFile;
	var $filename;
	var $title;
	var $subTitle;
	var $heading;
	var $exportDate;
	var $data;
	
	/**
	 * All styles settings
	 * @author	Yudha
	 */
	var $styleBorderThin = array(
		'borders' => array(
			'allBorders' => array(
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
		),
	);

	var $styleBorderThick = array(
		'borders' => array(
			'outline' => array(
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
			),
		),
	);

	var $styleHeading = array(
		'font'	=> array(
			'bold' => true
		),
		'alignment' => array(
	        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	    ),
		'borders' => array(
			'allBorders' => array(
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
		),
		'fill' => array(
	        'fillType' 		=> \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
	        'rotation' 		=> 90,
	        'startColor' 	=> array('argb' => 'FFEFEFEF'),
	        'endColor' 		=> array('argb' => 'FFEFEFEF'),
	    ),
	);

	// var $styleLineBreak = \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder( new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder() );

	/**
	 * Constructor - Sets up the object properties.
	 */
	function __construct()
	{
		$this->CI =& get_instance();
		$this->companyName = COMPANY_NAME;
	}

	function setHeader($content_type, $filename) {
		ob_end_clean();

		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: ' . $content_type);
		header('Content-Disposition: attachment;filename="' . $this->filename . '.xlsx"');
	}
	
	/**
	 * Set file properties
	 */
	function setProperties() {
		// Set document properties
        $this->objPHPExcel->getProperties()
            ->setCreator($this->companyName)
            ->setLastModifiedBy($this->companyName)
            ->setTitle($this->title)
            ->setSubject($this->title)
            ->setDescription($this->title)
            ->setKeywords($this->title)
            ->setCategory($this->title);
	}
	
	/**
	 * Init simple exporter
	 * 
	 */
	function simpleInit() {
		$this->objPHPExcel 	= new Spreadsheet();
		
		// setup properties
		$this->setProperties();

		$currentTime 		= time();
		$this->exportDate 	= date('d M, Y', $currentTime);
		$this->filename 	= $this->title . ' ' . date('d-m-Y_His');
		
		// set table header
		if ( is_array( $this->heading[0] ) ) {
			$_heading = array_reverse( $this->heading );
			foreach( $_heading as $heading ) {
				array_unshift( $this->data, $heading );
			}
		} else {
			array_unshift( $this->data, $this->heading );
		}
		// set export date
		array_unshift($this->data, array('Tanggal Export: ' . $this->exportDate));
		// set subtitle
		array_unshift($this->data, array($this->subTitle));
		// set main title
        array_unshift($this->data, array($this->title . ' - ' . $this->companyName));

        $set_cell 	= array();
    	$max_column = 0;
        foreach ($this->data as $row => $column) {
        	if ( $column ) {
	        	$alpha 	= 'A';
	        	foreach ($column as $col => $val) {
		        	$set_cell[($row+1)][$alpha] = $val;
		        	$alpha++;
			    	$max_column = $col;
	        	}
        	} else {
        		if ( $max_column > 0 ) {
		        	$alpha 	= 'A';
        			for ($i=0; $i <= $max_column; $i++) { 
			        	$set_cell[($row+1)][$alpha] = '';
			        	$alpha++;
        			}
        		}
        	}
        }

        if ( $set_cell ) {
        	foreach ($set_cell as $row => $column) {
	        	foreach ($column as $col => $val) {
	        		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$row, $val);
	        		if ( $row == 4 ) {
	        			$this->objPHPExcel->getActiveSheet()->getStyle($col.$row)->applyFromArray($this->styleHeading);
	        		}
	        		if ( $row >= 5 ) {
	        			$this->objPHPExcel->getActiveSheet()->getStyle($col.$row)->applyFromArray($this->styleBorderThin);
	        		}
	        	}
        	}
        }
	}

	/**
	 * Export Withdraw
	 *
	 */
	function withdraw( $export_data=array() ) {

		$this->title 		= 'Laporan Withdraw';
		$this->heading 		= array( 'No', 'Tanggal', 'Username', 'Nama', 'Bank', 'No. Rekening', 'Pemilik Rekening', 'Nominal Awal', 'AutoMt','Pajak', 'Biaya Admin', 'Nominal Transfer', 'Status', 'Tanggal Konfirmasi' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {

                $bill 			= ( $row->bill ) ? $row->bill : '-';
                $bill_name 		= ( $row->bill_name ) ? strtoupper($row->bill_name) : '-';
                $bank_name 		= '-';
                if ( $row->bank && $bank = yb_banks($row->bank) ) {
	                if ( ! empty( $bank->kode ) && ! empty( $bank->nama ) ){
	                    $bank_name 	= strtoupper($bank->kode .' - '. $bank->nama);
	                }
                }

				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->datecreated)),
					strtoupper($row->username),
					strtoupper($row->name),
					$bank_name,
					$bill,
					$bill_name,
					$row->nominal,
					$row->auto_maintenance,
					$row->tax,
					$row->admin_fund,
					$row->nominal_receipt,
					($row->status == 0 ? 'PENDING' : 'TRANSFERED'),
					( $row->status == 0 ? '' : date('Y-m-d H:i', strtotime($row->datemodified)) )
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $rowNumber, '=SUM(H5:H' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $rowNumber, '=SUM(I5:I' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $rowNumber, '=SUM(J5:J' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $rowNumber, '=SUM(K5:K' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $rowNumber, '=SUM(L5:L' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('H'.$rowNumber.':L'.$rowNumber)->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:F'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('M5:N'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('H5:L'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

    // ---------------------------------------------------------------------------
}
// END Excel Class