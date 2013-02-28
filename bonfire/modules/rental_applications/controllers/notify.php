<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Notify extends Front_Controller {


    public function __construct(){
        parent::__construct();
        $this->load->config('notify');
        //$this->load->library('emailer/emailer');
        $this->load->library('email');

        $this->load->library('encrypt');//needed to decode excahanged data
        $this->encrypt->set_cipher(MCRYPT_BLOWFISH);

    }


    private function test_encrypt()
    {
        $to = $this->session->userdata("field_email");
        $to = $this->encrypt->decode($to);

        echo $to;
    }

    private function test_email()
    {
        $this->email->from('shawn@ctechservices.com','Testing App');
        $this->email->to('shawn@ctechservices.com');
        $this->email->subject('Email Test');
        $this->email->message("Testing CI mailing capabilities");

        $file = ROOTPATH."/assets/pdf/unsecure_0f644baa766b434a68332edd8dd4500c.pdf";

        $this->email->attach($file);

        $this->email->send();

        echo $this->email->print_debugger();
    }


    private function send_email($to,$subject,$message,$file)
    {
        $app_email = $this->config->item('from_email');
        $app_name  = $this->config->item('from_name');
        $this->email->from($app_email,$app_name);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->attach($file);

        $this->email->send();
    }

    public function send($method)
    {
        //security precaution
        if (! $this->session->userdata('contractno'))
        {
            return;
        }

        switch ($method)
        {
            case 'receipt': return $this->send_receipt();

            case 'notice': return $this->send_notice();
        }
    }


    private function send_receipt (){

        $to = $this->session->userdata("field_email");
        $to = $this->encrypt->decode($to);

        $subject = "The Music Shoppe: Rental Confirmation";

        $instrument = $this->session->userdata("field_instrumentName");
        //$instrument = $this->encrypt->decode($instrument);

         $first_name = $this->session->userdata("field_rentersFirstName");
         $middle_initial = $this->session->userdata("field_rentersMiddleInitial");
         $last_name = $this->session->userdata("field_rentersLastName");

        $renter = $this->encrypt->decode($first_name)
            ." ".$this->encrypt->decode($middle_initial)
            ." ".$this->encrypt->decode($last_name);


        $contractid = $this->session->userdata('contractno');
        //$contractid = $this->encrypt->decode($contractid);


        $message = "Instrument: ".$instrument."\r\n"
            . "Renter: ".$renter."\r\n"
            . "Contract ID: ".$contractid."\r\n";

        $path = ROOTPATH."/assets/pdf/";
        $file = $path."unsecure_".$contractid.".pdf";

        $this->send_email($to,$subject,$message,$file);

    }

    private function send_notice(){


        $to = $this->config->item('sendto');

        $subject = "Notice: A New Rental Application";

        $instrument = $this->session->userdata("field_instrumentName");
        //$instrument = $this->encrypt->decode($instrument);

        $first_name = $this->session->userdata("field_rentersFirstName");
        $middle_initial = $this->session->userdata("field_rentersMiddleInitial");
        $last_name = $this->session->userdata("field_rentersLastName");

        $renter = $this->encrypt->decode($first_name)
            ." ".$this->encrypt->decode($middle_initial)
            ." ".$this->encrypt->decode($last_name);

        $rental_program =  $this->input->post("resource");

        $contractid = $this->session->userdata('contractno');
        //$contractid = $this->encrypt->decode($contractid);


        $message = "Instrument: ".$instrument."\r\n"
                . "Renter: ".$renter."\r\n"
                . "Rental Program: ".$rental_program."\r\n"
                . "Contract ID: ".$contractid."\r\n";


        $path = ROOTPATH."/assets/pdf/";

        $file = $path."unsecure_".$contractid.".pdf";

        $this->send_email($to,$subject,$message,$file);
    }

}