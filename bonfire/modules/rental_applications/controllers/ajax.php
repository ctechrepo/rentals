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
    }

    /**
     * Ajax pagination request.
     *
     * @POST - data needs to be updated
     * @RESPONSE - json
     */
    public function page(){
       $this->response['error'] = 'none';

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

       //Proccess the Form on a given Page
      switch ($form_section){
         case "renter":
           $this->general_information($resource);
           break;

         case "references":
             $this->references($resource);
              break;

         case "payment":
             $this->payment($resource);
             break;
       }
       $this->response['form_section'] = $form_section;
       echo json_encode($this->response);
    }

    public function test(){
        $this->general_information('band');
        echo "<form action='#' method='POST'>
              <input type='hidden' name='ci_csrf_token' value='{$_COOKIE['ci_csrf_token']}'/>
              <input type='text' name='test' />
              <input type='submit' value='submit'/>
        </from>";
        var_dump($this->response);
        die();
    }

    //--------------------helper methods-------------------------------------
    private function general_information($resource)
    {
        $section = $this->get_forms($resource,'General Information');
        $fields = $section[0][1][1];

        $this->set_rules($fields);

        $this->set_message($fields);
    }

    private function references($resource)
    {
        $section = $this->get_forms($resource,'Employer Information');
        $fields = $section[0][1][1];

        $this->set_rules($fields);

        $this->set_message();

        $fields_two = get_forms($resource,"Spouse's Information");

        $fields_three = get_forms($resource,"Reference's Information");
    }

    private function payment($resource)
    {
        $section = $this->get_forms($resource,'Payment');
        $fields = $section[0][1][1];

        $this->set_rules($fields);

        $this->set_message($fields);
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
            //todo more detail error message
            foreach($fields  as $field)
            {
               $name = $field->formfield_name;
               if (strlen(form_error($name)) > 0){
                    $form_errors[$name] = form_error($name);
                }
               $form_values[$name] = set_value($name);
            }
            $this->response['formErrors'] = $form_errors;
            $this->response['formValues'] = $form_values;
        }
    }

    private function get_forms($resource,$section)
    {
        //TODO fix plan id

        $this->load->model('rentalplan_to_form_model','rentalform');

        //TODO add cache control statement

        return $this->rentalform->getForms(1,$section);
    }
    //-----------------------------------------------------------------------
}