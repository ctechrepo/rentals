<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


    /**
     * Module Public controller
     *
     * The base controller which handles rental applications ajax request/response on
     * the public side.
     *
     * @package    Bonfire
     * @subpackage Controllers
     * @category   Controllers
     * @author     Bonfire Dev Team
     * @link       http://guides.cibonfire.com/helpers/file_helpers.html
     *
     */
class Ajax extends Front_Controller
{

    private $response = array();

    public function __construct(){
       /* if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }*/
        $this->load->helper(array('form', 'url'));

        $this->load->library('pdfform');

        $this->load->library('encrypt');
        $this->encrypt->set_cipher(MCRYPT_BLOWFISH);

        $this->load->library('form_validation');
        $this->form_validation->CI =& $this; //HMVC HACK for form callbacks to function right.

    }

    /**
     * Ajax request from the accessories page.
     *
     * @POST
     * @GET
     * @RESPONSE - JSON
     */
    public function accessories(){

        $school = $this->input->get('school');
        $instrument = $this->input->get('instrument');

        if($instrument > 0)
        {

            if ($school > 0)
            {
                $this->load->model('organization_recommendation_model','org_recommends');
                $the_list = $this->org_recommends->get_list($school,$instrument);
            } else {
                $the_list = array();
            }
        }

        $this->response['list'] = json_encode($the_list);
        $this->response['school']=$school;
        $this->response['instrument']=$instrument;

        echo json_encode($this->response);
        exit;
    }

    /**
     * Ajax receipt request.
     *
     * @POST - data needs to be updated
     * @RESPONSE - json
     */
    public function receipt(){
        $this->response['error'] = 'none';
        $security = $this->input->post('security');
        $resource = $this->input->post('resource');
        $contractno = $this->session->userdata("contractno");

        if ($this->input->post('getReceipt') === 'yes' &&
                $security && $resource)
        {

            $this->prepare_receipt($resource,$contractno,$security);
        }

        echo json_encode($this->response);
        exit;
    }

    public function notify(){
        $this->response['error'] = 'none';

        $method = $this->input->post('method');

        require "notify.php";

        $notify = new Notify();

        $notify->send($method);

        echo json_encode($this->response);
        exit;
    }

    /**
     * Ajax pagination request.
     *
     * @POST - data needs to be updated
     * @RESPONSE - json
     */
    public function page(){
       $this->response['error'] = 'none';
       $this->response['formErrors'] = array();
       $this->response['formValues'] = array();

       $resource = $this->input->post('resource');
       $page_from = $this->input->post('pageFrom');
       $page_to = $this->input->post('pageTo');
       $form_section = $this->input->post('formSection');

       if ($resource === 'band' || $resource === 'orchestra')
       {
           $accessories = $this->input->post('accessories');

           if ($accessories !== FALSE){
               $this->load->model('product_model');
               $list_accessories = $this->product_model->getList($accessories);

               $this->session->set_userdata('accessories',$list_accessories);
           } elseif ($page_from == 5) {
               $this->session->unset_userdata('accessories');
           }

       }

      if ($form_section){
        $this->validate_section($resource,$form_section);
      }


      $this->response['form_section'] = $form_section;
       echo json_encode($this->response);
       exit;
    }

    //--------------------helper methods-------------------------------------
    private function validate_section($resource,$form_section)
    {
        $sec_names = explode(",", $form_section);

        foreach ($sec_names as $name){

        $section = $section = $this->get_forms($resource,$name);
        $fields = $section['1']['0']['fields'];
            if(empty($fields))
            {
                //check to see if the user is bypassing validation by modifying the section name in the html
                $this->response['ERROR'] = 'Failed Validation';
                exit;
            }

        $this->set_rules($fields);

        $this->set_message($fields);
        }
    }


    private function set_rules(& $fields)
    {
        foreach($fields  as $field)
        {

            $name  = $field->formfield_name;
            $label = $field->formfield_label;
            $rules = $field->formfield_validation_rules;

            $this->form_validation->set_rules($name,$label,$rules);
        }
    }

    private function set_message(& $fields)
    {
       if ($this->form_validation->run($this) == FALSE)
        {
            $this->response['error'] = 'Failed Validation';
            $form_errors = array();
            $form_values = array();

            foreach($fields  as $field)
            {
               $name = $field->formfield_name;
               if (strlen(form_error($name)) > 0){
                    $form_errors[$name] = form_error($name);
                }
               $form_values[$name] = set_value($name);
            }
            $this->response['formErrors'] = array_merge($this->response['formErrors'],$form_errors);
            $this->response['formValues'] = array_merge($this->response['formValues'],$form_values);
        }
       else
       {
           //save the data
           foreach($fields  as $field)
           {
               $name = $field->formfield_name;
               $val = set_value($name);
               $val = $this->encrypt->encode($val);//all data is treated sensitive.
               $this->session->set_userdata('field_'.$name,$val);
           }
       }
    }

    /**
     * @param $resource
     * @param $section
     * @return mixed -- array FormIds=>array Sections => array('fields'=>Object 'section'=>Object) //where fields is fields meta data
     *  and section is section meta data.
     */
    private function get_forms($resource,$section)
    {
        //TODO fix plan id

        $this->load->model('rentalplan_to_form_model','rentalform');

        //TODO add cache control statement

        return $this->rentalform->get_Forms(1,$section);
    }

    private function prepare_receipt($resource,$contractno,$level = 'unsecure')
    {
        switch($resource)
        {
            case 'band':
                $data = $this->band_data($level);
                $form = 'assets/pdf/contract.pdf';
                break;
            case 'orchestra':
                $data = $this->orchestra_data($level);
                $form = 'assets/pdf/contract.pdf';
                break;
            case 'bravo':
                $data = $this->bravo_data($level);
                $form = 'assets/pdf/contract.pdf';
                break;
        }

        $data = array_merge($data,array("contractno"=>$contractno));

        $this->response['data'] = $data;

        $this->pdfform->__init($form,$data);
        $this->pdfform->create($level.'_'.$contractno);

    }



    /*
    * Contructs key-field pair for FDF data, shared data between multiple contracts
    *
    * @param $level - security level used to censore sensetive data when needed.
    */
    private function common_data($level)
    {
        $common_data = array(
            "contractdate" => date('m/d/Y'),
            "debitmonth" => $this->session->userdata("field_debitMonth"),

            "instrumentname" => $this->session->userdata("field_instrumentName"),
            "price" => $this->session->userdata('field_price'),
            "tax" => $this->session->userdata('field_tax'),
            "servicecharge"=> $this->session->userdata("field_serviceCharge"),
            "totalofpayments"=> $this->session->userdata("field_totalPayments"),
            "tax2"=> $this->session->userdata("field_tax2"),
            "subtotal"=> $this->session->userdata("field_subtotal"),
            "totaldue"=> $this->session->userdata("field_totalDue"),
            "monthlymaintenancefee" => $this->session->userdata("field_mrFee"),
            "monthlyrentalfee"=> $this->session->userdata("field_monthlyRentalFee"),
            "totalmonthly" => $this->session->userdata("field_totalMonthly"),
            "school" => $this->session->userdata("field_school"),
            "deliverto" => $this->session->userdata("field_delivery"),
            "signature" => $this->session->userdata("field_initials"),
            "cardtype" => $this->session->userdata("field_ccType"),
            "cardholder" => $this->session->userdata("field_ccName"),
            "expmonth" => $this->session->userdata("field_ccExpMonth"),
            "expyear" => $this->session->userdata("field_ccExpYear"),

            "renterfirstname" => $this->session->userdata("field_rentersFirstName")." ".
                                 $this->session->userdata("field_rentersMiddleInitial"),
            "renterlastname" => $this->session->userdata("field_rentersLastName"),
            "employer" => $this->session->userdata("field_employerName"),

            "spousename" => $this->session->userdata("field_spouseName"),
            "spouseemployer" => $this->session->userdata("field_spouseEmployer"),

            "referencename" => $this->session->userdata("field_referenceName"),
            "studentname" => $this->session->userdata("field_student"),
            "signature" => $this->session->userdata("field_initials")
        );

        if ($level === 'secure')
        {
            $common_data['homephone'] = $this->session->userdata("field_homePhone");
            $common_data['workphone'] = $this->session->userdata("field_workPhone");
            $common_data['renteremail'] = $this->session->userdata("field_email");
            $common_data['ssn'] = $this->session->userdata("field_ssn");
            $common_data['spousessn'] = $this->session->userdata("field_spouseSSN");
            $common_data['spouseemail'] = $this->session->userdata("field_spouseEmail");
            $common_data['spouseemployeraddress'] = $this->session->userdata("field_spouseEmployerAddressLine1"). " ".
                $this->session->userdata("field_spouseEmployerAddressLine2"). " ".
                $this->session->userdata("field_spouseEmployerCity"). ", ".$this->session->userdata("field_spouseEmployerState"). " ".
                $this->session->userdata("field_spouseEmployerZip");


            $common_data['spouseworkphone'] = $this->session->userdata("field_spouseWorkPhone");
            //$common_data['spouseaddress'] = "";
            $common_data['spousedriverslicense'] = $this->session->userdata("field_spouseDriversLicense");

            $common_data['referenceaddress'] = $this->session->userdata("field_referenceAddressLine1"). " ".
                $this->session->userdata("field_referenceAddressLine2"). " ".
                $this->session->userdata("field_referenceCity"). ", ".$this->session->userdata("field_referenceState"). " ".
                $this->session->userdata("field_referenceZip");

            $common_data['referencerelationship'] = $this->session->userdata("field_referenceRelationship");
            $common_data['referencephone'] = $this->session->userdata("field_referencePhone");
            $common_data['cardnumber'] = $this->session->userdata("field_ccNumber");
            $common_data['cvc'] = $this->session->userdata("field_ccCode");
            $common_data['driverslicense'] = $this->session->userdata("field_driversLicense");
            $common_data['homeaddress'] = $this->session->userdata("field_homeAddressLine1")." ".
                                          $this->session->userdata("field_homeAddressLine2");
            $common_data['homecity'] =    $this->session->userdata("field_city");
            $common_data['homestate'] =   $this->session->userdata("field_state");
            $common_data['homezip'] =     $this->session->userdata("field_zip");
            $common_data['empoyeraddress'] = $this->session->userdata("field_employerAddressLine1"). " ".
                                             $this->session->userdata("field_employerAddressLine2"). " ".
                $this->session->userdata("field_employerCity"). ", ".$this->session->userdata("field_employerState"). " ".
                $this->session->userdata("field_employerZip");
        }

        return $common_data;
    }

    /*
    * Contructs key-field pair for FDF data, related to a band contract
    *
    * @param $level - security level used to censore sensetive data when needed.
    */
    private function band_data($level)
    {
        $band_data = array(
            "plan" => $this->session->userdata('level'),
            "totalaccessories" => $this->session->userdata("field_totalAccessoires"),
            "rentalfee2months" => $this->session->userdata("field_rentalfee2months"),
            "maintenance2months" => $this->session->userdata("field_mr2months"),
            "numbermonthlypayments" => $this->session->userdata("field_numberMonthlyPayments"),
            "finalpayment" => $this->session->userdata("field_finalPayment"),
            "cashprice" => $this->session->userdata("field_cashPrice"),

            "accessories" => $this->session->userdata("field_accessoriesList")
        );

        return array_merge($this->common_data($level),$band_data);
    }

    /*
    * Contructs key-field pair for FDF data, related to a orchestra contract
    *
    * @param $level - security level used to censore sensetive data when needed.
    */
    private function orchestra_data($level)
    {
        $band_data = array(
            "plan" => $this->session->userdata('level'),
            "totalaccessories" => $this->session->userdata("field_totalAccessoires"),
            "rentalfee2months" => $this->session->userdata("field_rentalfee2months"),
            "maintenance2months" => $this->session->userdata("field_mr2months"),
            "numbermonthlypayments" => $this->session->userdata("field_numberMonthlyPayments"),
            "finalpayment" => $this->session->userdata("field_finalPayment"),
            "cashprice" => $this->session->userdata("field_cashPrice"),

            "accessories" => $this->session->userdata("field_accessoriesList")
        );

        return array_merge($this->common_data($level),$band_data);
    }

    /*
    * Contructs key-field pair for FDF data, related to a bravo contract
    *
    * @param $level - security level used to censore sensetive data when needed.
    */
    private function bravo_data($level)
    {
        $band_data = array(
            "plan" => $this->session->userdata('level'),
            "totalaccessories" => $this->session->userdata("field_totalAccessoires"),
            "rentalfee2months" => $this->session->userdata("field_rentalfee2months"),
            "maintenance2months" => $this->session->userdata("field_mr2months"),
            "numbermonthlypayments" => $this->session->userdata("field_numberMonthlyPayments"),
            "finalpayment" => $this->session->userdata("field_finalPayment"),
            "cashprice" => $this->session->userdata("field_cashPrice"),

            "accessories" => $this->session->userdata("field_accessoriesList")
        );

        return array_merge($this->common_data($level),$band_data);
    }
    //-----------------------------------------------------------------------
}