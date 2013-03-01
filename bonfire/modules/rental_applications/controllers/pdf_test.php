<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pdf_test extends Front_Controller {

    private $pdf_form;
    private $filled_data;



    public function __construct(){
        parent::__construct();
        $this->load->helper('createXFDF');
        $this->load->library('Pdfform');
    }

    public function __init($pdf_form,$filled_data)
    {
        $this->pdf_form = $pdf_form;
        $this->filled_data = $filled_data;
    }

    public function index()
    {
        echo ROOTPATH;
        //Template::render();
    }

    public function view($pdf_id)
    {
        echo $pdf_id;
    }

    public function email($pdf_id)
    {
        //$email
    }

    public function trash($pdf_id)
    {

    }

    public function decode($filename)
    {
        //$form_pdf = ROOTPATH."/".$this->pdf_form;
        $form_pdf = realpath("assets/pdf/contract.pdf");

        $this->pdfform->__init($form_pdf,array() );

        $this->pdfform->decode($filename,array("contractno"));
    }



    public function create($filename)
    {

        //data to be used
        $filled_data = $this->filled_data;

        //location of the pdf form
        $form_pdf = ROOTPATH."/".$this->pdf_form;

        //url of the pdf form
        $form_url = base_url($this->pdf_form);

        //third party program for conversion
        //Publisher: http://www.pdflabs.com
        //Product: PDFtk -- the PDF tookkit
        $program = 'C:/pdftk-1.41/pdftk';

        //where to save the fdf after its created
        $fdf_path = ROOTPATH."/assets/fdf/".$filename.'.fdf';
        //where to save the merge flatten pdf
        $pdf_path = ROOTPATH."/assets/pdf/".$filename.'.pdf';

        //create the FDF in XML format
        $data_fdf = createXFDF($form_url,$filled_data);
        //command line execution for the tool kit
        $pass_thru = "$program \"$form_pdf\" fill_form \"$fdf_path\" output \"$pdf_path\" flatten";

        try{
        //save the fdf
        $bytes = file_put_contents($fdf_path,$data_fdf);
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }

        //save the pdf and send as an attachment.
        //header("Content-type: application/pdf");
        //header('Content-Disposition: attachment; filename="'.$filename.'.pdf" ');
        passthru($pass_thru);
        //exit;


    }


}
/**
 * Created by CTech.
 * Author: Shawn Rhoney
 * Date: 2/6/13
 * File: fdf_pdf.php
 */