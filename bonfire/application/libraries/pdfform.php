<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pdfform {

    private $pdf_form;
    private $filled_data;
    private $ci;

    public function __init($pdf_form,$filled_data,$cipher = MCRYPT_BLOWFISH)
    {
        $this->pdf_form = $pdf_form;
        $this->filled_data = $filled_data;

        $ci = &get_instance();

        $this->ci = $ci;

        $ci->load->helper('createXFDF');
        $ci->load->library('encrypt');
        $ci->encrypt->set_cipher($cipher);
    }

    public function view($pdf)
    {

    }

    public function email($pdf)
    {
        //$email
    }

    public function trash($filename)
    {

    }

    public function decode($filename,$ignore = array())
    {

        $fdf_path = realpath(ROOTPATH."/assets/fdf/".$filename.'.fdf');

        $doc = new DOMDocument();
        if (! $doc->load( $fdf_path ) ){
            echo "failed";
            return false;}

        $new_data = array();

        $fields = $doc->getElementsByTagName("field");

        foreach ($fields as $field)
        {
            $name = $field->getAttribute('name');

            if (! in_array($name,$ignore) ){

                $values = $field->getElementsByTagName("value");
                $value =  $values->item(0)->nodeValue;
                $value = $this->ci->encrypt->decode($value);

                if ($value){
                    $new_data[$name] = $value;
                }
            }
        }

        $this->filled_data = $new_data;
        $this->create($filename);
    }

    public function create_fdf($filename)
    {

    }

    public function create_pdf($filename)
    {

    }



    public function create($filename)
    {
        //TODO add config file and call variables from config
        //TODO add updates from live app

        //data to be used
        $filled_data = $this->filled_data;

        //location of the pdf form
        //$form_pdf = ROOTPATH."/".$this->pdf_form;
        $form_pdf = $this->pdf_form;

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
            exit;
        }

        passthru($pass_thru);

    }


}