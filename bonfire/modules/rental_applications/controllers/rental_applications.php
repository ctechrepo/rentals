<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Module Public controller
 *
 * The base controller which displays the homepage of the Bonfire site.
 *
 * @package    Bonfire
 * @subpackage Controllers
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Rental_applications extends Front_Controller
{
    private $sales_tax;
    private $interest_rate;

    public function __construct(){
        parent::__construct();
        $this->output->enable_profiler(TRUE);//debugger (profiler's appearence is control by bonfire)

        $this->config->load('rental_applications');
        $this->sales_tax = $this->config->item('sales_tax');
        $this->interest_rate = $this->config->item('interest_rate');

        $this->load->helper('html');
        $this->load->helper('file');


        $this->load->add_package_path(APPPATH.'third_party/phpThumb/');
        $this->load->helper('PHPThumb');
        Template::set('thumbnail',new Thumbnail());
        Assets::add_module_js('rental_applications','jquery.cookie.js');
        Assets::add_module_js('rental_applications','rental_applications.js');

        //$csrf_token = $this->config->item('')
    }

    /**
     * Displays the homepage of the Bonfire app
     *
     * @return void
     */
    public function index()
    {
        Template::render();

    }//end index()

    //--------------------------------------------------------------------


    /**
     * Displays the band page
     *
     * @return void
     */
    public function band(){

        $page = $this->uri->segment(4)?$this->uri->segment(4):1; //check_uri for current_page
        $skip_page3 = TRUE;

        Template::set('page',$page);
        Template::set('resource','band');

        $input = $this->input->get('instrument'); //get variable instrument
        $instrument_id = empty($input)?$this->get_userdata('instrument_id'):$input;

        $rental_details = ' ';

        //set session variables
        $this->set_userdata('instrument_id',$instrument_id);

        if (!empty($instrument_id)){
            $instrument = $this->get_instrument($instrument_id);
            Template::set('selected_instrument', $instrument);

            $rental_details = $this->get_rental_details($instrument_id,'band');

            Template::set('plan_description',$rental_details->rental_description);

            $rent_to_own = array(
                'bronze'=>$rental_details->bronze_price,
                'silver'=>$rental_details->silver_price,
                'gold'=>$rental_details->gold_price,
                'platinum'=>$rental_details->platinum_price
            );

            $prices = array_map(function($val){
                 if ($val >= 0){
                     global $skip_page3;
                     $skip_page3= FALSE;
                     return $val;}
                 return NULL;
            },$rent_to_own);

            Template::set('m_r_price',number_format($rental_details->maintenance_price + $rental_details->replacement_price,2));


            $monthly_rental = $skip_page3?$rental_details->rent_only_price:'';
            Template::set('monthly_rental',number_format($monthly_rental,2));

            $accessories = $this->get_accessories($instrument_id);
            Template::set('accessories',$accessories);

            $this->load->model('organization_model');
            $schools = $this->organization_model->find_all();
            Template::set('schools',$schools);



            Template::set('two_months_rental',number_format($rental_details->two_month_price,2));
        }

        Template::set('rental_plan',$rental_details);
        //Template::set('rental_plan',$prices);

        $due_date = date('m/d/Y',mktime(0,0,0,date("n")+2,date("j")+10));
        Template::set('due_date',$due_date);


        //handle any page skips
        if ($skip_page3 === TRUE && $page==3)(Template::redirect('/rental_applications/band/page/4'));



        $rental_forms = $this->get_forms('band');

        //data containers for various sections
        $general_information = array();
        $employer_information = array();
        $spouse_information = array();
        $reference_information = array();
        $payment_information = array();
        $terms_information = array();
        $m_r_information = array();//maintenance and replacement

        $add_to = null;//reference pointer

        foreach ($rental_forms as $form)
        {
            $sections = $form[0];
            $fields = $form[1];

            foreach ($sections as $item)
            {
                //assign the correct array to the pointer
                //echo $item->formsection_name;
                switch($item->formsection_name)
                {
                    case "General Information": $add_to =& $general_information;
                            break;
                    case "Employer Information": $add_to =& $employer_information;
                            break;
                    case "Spouse's Information": $add_to =& $spouse_information;
                            break;
                    case "Payment Information": $add_to =& $payment_information;
                            break;
                    case "Reference's Information": $add_to =& $reference_information;
                            break;
                    case "terms": $add_to =& $terms_information;
                          break;
                    case "Maintenance and Replacement": $add_to =& $m_r_information;
                    break;
                }
                //populate the array -- pass by the pointer
                array_push($add_to,$item);
                if (isset($fields[$item->formsection_id])){
                array_push($add_to,$fields[$item->formsection_id]);
                }
            }
        }
        //die();
        Template::set('general_information',$general_information);
        Template::set('employer_information',$employer_information);
        Template::set('spouse_information',$spouse_information);
        Template::set('reference_information',$reference_information);
        Template::set('payment_information',$payment_information);
        Template::set('terms_information',$terms_information);
        Template::set('m_r_information',$m_r_information);


        switch($page)
        {
            case 1:  //instrument selection
                Template::set('instruments',$this->get_rental_products('Band'));
                Template::set('instrument_url',site_url('rental_applications/band/page/2/?'));
            break;

            case 2: //rental description for the selected instrument
                //Template::set('rent_own_url',site_url('rental_applications/band/page/3'));
                 // Template::set('rent_only_url',site_url('rental_applications/band/page/4'));

            break;

            case 3: //plan selection if multiple plans exist
            break;

            case 4: //Maintenance and Replacement Policy

            break;

            case 5: //Add Accessories to your rental

            break;

            case 6: //Rental Invoice

            break;

            case 7:

            break;

            default://404

        }


        //set pagination
        $this->load->library('pagination');
        $config_pagination['base_url'] = site_url('/rental_applications/band/page/');
        $config_pagination['total_rows'] = 11;
        $config_pagination['per_page'] = 1;
        $config_pagination['use_page_numbers'] = TRUE;
        $config_pagination['uri_segment'] = 4;
        $config_pagination['num_links'] = 11;
        $config_pagination['first_link'] = FALSE;
        $config_pagination['last_link'] = FALSE;
        $config_pagination['next_link'] = FALSE;
        $config_pagination['prev_link'] = FALSE;
        $this->pagination->initialize($config_pagination);
        //--------------------------------------------

        //view variables
        Template::set('pagination',$this->pagination->create_links());
        Template::set('page',$page);

        Template::render();
    }


    /**
     * Displays the band page
     *
     * @return void
     */
    public function old_band(){

        //local variables
        $page = $this->uri->segment(4)?$this->uri->segment(4):1; //check_uri for current_page

        $input = $this->input->get('instrument'); //get variable instrument
        $instrument_id = empty($input)?$this->get_userdata('instrument_id'):$input;

        //------------------------------

        //set session variables
        $this->set_userdata('instrument_id',$instrument_id);


        //load models
        $this->load->model('band/Bandinstruments_model','instruments');

      switch($page){
            case 1: //instrument selection

            Template::set('instruments', $this->instruments->get_instruments());
            Template::set('instrument_url',site_url('rental_applications/band/page/2/?'));
            break;

            case 2: //rental description for the selected instrument
               $temp = $this->instruments->get_instruments();
               Template::set('selected_instrument',$temp[$instrument_id]);
               Template::set('instruments',array());
               Template::set('rent_own_url',site_url('rental_applications/band/page/3'));
               Template::set('rent_only_url',site_url('rental_applications/band/page/4'));
            break;

            case 3: //plan selection if multiple plans exist

            break;

            case 4: //Maintenance and Replacement Policy
               $temp = $this->instruments->get_instruments();
               Template::set('selected_instrument',$temp[$instrument_id]);
            break;

            case 5: //Add Accessories to your rental
               $this->load->model('band/Accessories_model');
               $this->load->model('band/School_model');
               Template::set('accessories',$this->Accessories_model->get_accessories($instrument_id));
               Template::set('schools',$this->School_model->get_schools());
            break;

            case 6: //Rental Invoice
            //models
            $this->load->model('band/Bandrental_formfields_model','formfields');
            $this->load->model('band/Bandrental_formresponses_model','formresponses');
            $this->load->model('band/Bandrental_formsections_model','formsections');
            Template::set('formsections',$this->formsections->get_sections(1,1));
            Template::set('formfields',$this->formfields);
            break;

            case 7: //applicant's data
        default: //404 Error
        }


        //set pagination
        $this->load->library('pagination');
        $config_pagination['base_url'] = site_url('/rental_applications/band/page/');
        $config_pagination['total_rows'] = 9;
        $config_pagination['per_page'] = 1;
        $config_pagination['use_page_numbers'] = TRUE;
        $config_pagination['uri_segment'] = 4;
        $this->pagination->initialize($config_pagination);
        //--------------------------------------------

        //view variables
        Template::set('pagination',$this->pagination->create_links());
        Template::set('page',$page);

        //perform cleanup and load view
        $this->cleanup();
        Template::render();

    }

    /**
     * Displays the orchestra page
     *
     * @return void
     */
    public function orchestra(){
        $page = $this->uri->segment(4)?$this->uri->segment(4):1; //check_uri for current_page
        $skip_page3 = TRUE;

        $input = $this->input->get('instrument'); //get variable instrument
        $instrument_id = empty($input)?$this->get_userdata('instrument_id'):$input;

        $rental_details = ' ';

        //set session variables
        $this->set_userdata('instrument_id',$instrument_id);

        if (!empty($instrument_id)){
            $instrument = $this->get_instrument($instrument_id);
            Template::set('selected_instrument', $instrument);

            $rental_details = $this->get_rental_details($instrument_id,'band');

            Template::set('plan_description',$rental_details->rental_description);

            $rent_to_own = array(
                'bronze'=>$rental_details->bronze_price,
                'silver'=>$rental_details->silver_price,
                'gold'=>$rental_details->gold_price,
                'platinum'=>$rental_details->platinum_price
            );

            $prices = array_map(function($val){
                if ($val >= 0){
                    global $skip_page3;
                    $skip_page3= FALSE;
                    return $val;}
                return NULL;
            },$rent_to_own);

            Template::set('m_r_price',number_format($rental_details->maintenance_price + $rental_details->replacement_price,2));


            $monthly_rental = $skip_page3?$rental_details->rent_only_price:'';
            Template::set('monthly_rental',number_format($monthly_rental,2));

            $accessories = $this->get_accessories($instrument_id);
            Template::set('accessories',$accessories);

            Template::set('two_months_rental',number_format($rental_details->two_month_price,2));
        }

        Template::set('rental_plan',$rental_details);
        //Template::set('rental_plan',$prices);

        $due_date = date('m/d/Y',mktime(0,0,0,date("n")+2,date("j")+10));
        Template::set('due_date',$due_date);


        //handle any page skips
        if ($skip_page3 === TRUE && $page==3)(Template::redirect('/rental_applications/band/page/4'));



        $rental_forms = $this->get_forms('band');

        //data containers for various sections
        $general_information = array();
        $employer_information = array();
        $spouse_information = array();
        $reference_information = array();
        $payment_information = array();
        $terms_information = array();

        $add_to = null;//reference pointer

        foreach ($rental_forms as $form)
        {
            $sections = $form[0];
            $fields = $form[1];

            foreach ($sections as $item)
            {
                //assign the correct array to the pointer
                switch($item->formsection_name)
                {
                    case "General Information": $add_to =& $general_information;
                    break;
                    case "Employer Information": $add_to =& $employer_information;
                    break;
                    case "Spouse's Information": $add_to =& $spouse_information;
                    break;
                    case "Payment Information": $add_to =& $payment_information;
                    break;
                    case "Reference's Information": $add_to =& $reference_information;
                    break;
                    case "terms": $add_to =& $terms_information;
                    break;
                }
                //populate the array -- pass by the pointer
                array_push($add_to,$item);
                if (isset($fields[$item->formsection_id])){
                    array_push($add_to,$fields[$item->formsection_id]);
                }
            }
        }
        Template::set('general_information',$general_information);
        Template::set('employer_information',$employer_information);
        Template::set('spouse_information',$spouse_information);
        Template::set('reference_information',$reference_information);
        Template::set('payment_information',$payment_information);
        Template::set('terms_information',$terms_information);


        switch($page)
        {
            case 1:  //instrument selection
                Template::set('instruments',$this->get_rental_products('Band'));
                Template::set('instrument_url',site_url('rental_applications/band/page/2/?'));
                break;

            case 2: //rental description for the selected instrument
                //Template::set('rent_own_url',site_url('rental_applications/band/page/3'));
                // Template::set('rent_only_url',site_url('rental_applications/band/page/4'));

                break;

            case 3: //plan selection if multiple plans exist
                break;

            case 4: //Maintenance and Replacement Policy

                break;

            case 5: //Add Accessories to your rental

                break;

            case 6: //Rental Invoice

                break;

            case 7:

                break;

            default://404

        }


        //set pagination
        $this->load->library('pagination');
        $config_pagination['base_url'] = site_url('/rental_applications/band/page/');
        $config_pagination['total_rows'] = 11;
        $config_pagination['per_page'] = 1;
        $config_pagination['use_page_numbers'] = TRUE;
        $config_pagination['uri_segment'] = 4;
        $config_pagination['num_links'] = 11;
        $config_pagination['first_link'] = FALSE;
        $config_pagination['last_link'] = FALSE;
        $config_pagination['next_link'] = FALSE;
        $config_pagination['prev_link'] = FALSE;
        $this->pagination->initialize($config_pagination);
        //--------------------------------------------

        //view variables
        Template::set('pagination',$this->pagination->create_links());
        Template::set('page',$page);

        Template::render();
    }

    /**
     * Displays the bravo page
     *
     * @return void
     */
    public function bravo(){
        $page = $this->uri->segment(4)?$this->uri->segment(4):1; //check_uri for current_page

        $input = $this->input->get('instrument'); //http get variable instrument
        $instrument_id = empty($input)?$this->get_userdata('instrument_id'):$input;

        $input2 = $this->input->get('group'); //http get variable group
        $group_id = empty($input2)?$this->get_userdata('group_id'):$input2;

        //set session variables
        $this->set_userdata('instrument_id',$instrument_id);
        $this->set_userdata('group_id',$group_id);

        if (!empty($instrument_id)){
            $instrument = $this->get_instrument($instrument_id);
            Template::set('selected_instrument', $instrument);

            $rental_details = $this->get_rental_details($instrument_id,'bravo');

            Template::set('plan_description',$rental_details->rental_description);

            Template::set('m_r_price',number_format($rental_details->maintenance_price+ $rental_details->replacement_price,2));

            Template::set('monthly_rental',number_format($rental_details->base_rental_price,2));
            //var_dump($rental_details);
            //die();
            $due_date = date('m/d/Y',mktime(0,0,0,date("n")+1,date("j")+10));
            Template::set('due_date',$due_date);
        }

        $rental_forms = $this->get_forms('bravo');

        //data containers for various sections
        $general_information = array();
        $employer_information = array();
        $spouse_information = array();
        $reference_information = array();
        $payment_information = array();
        $terms_information = array();

        $add_to = null;//reference pointer

        foreach ($rental_forms as $form)
        {
            $sections = $form[0];
            $fields = $form[1];

            foreach ($sections as $item)
            {
                //assign the correct array to the pointer
                switch($item->formsection_name)
                {
                    case "General Information": $add_to =& $general_information;
                    break;
                    case "Employer Information": $add_to =& $employer_information;
                    break;
                    case "Spouse's Information": $add_to =& $spouse_information;
                    break;
                    case "Payment Information": $add_to =& $payment_information;
                    break;
                    case "Reference's Information": $add_to =& $reference_information;
                    break;
                    case "terms": $add_to =& $terms_information;
                    break;
                }
                //populate the array -- pass by the pointer
                array_push($add_to,$item);
                if (isset($fields[$item->formsection_id])){
                    array_push($add_to,$fields[$item->formsection_id]);
                }
            }
        }
        Template::set('general_information',$general_information);
        Template::set('employer_information',$employer_information);
        Template::set('spouse_information',$spouse_information);
        Template::set('reference_information',$reference_information);
        Template::set('payment_information',$payment_information);
        Template::set('terms_information',$terms_information);


        switch($page)
        {
            case 1:  //category selection
                $this->load->model('category_model');
                $cats =  $this->category_model->get_by_parent('bravo');
                Template::set('categories', $cats);
                break;

            case 2: //rental description for the selected instrument
            Template::set('instruments',$this->get_rental_products('bravo band',$group_id));
            Template::set('instrument_url',site_url('rental_applications/bravo/page/3/?'));
            break;

                break;

            case 3: //plan selection if multiple plans exist
                break;

            case 4: //Maintenance and Replacement Policy

                break;

            case 5: //Add Accessories to your rental

                break;

            case 6: //Rental Invoice

                break;

            case 7:

                break;

            default://404

        }


        //set pagination
        $this->load->library('pagination');
        $config_pagination['base_url'] = site_url('/rental_applications/bravo/page/');
        $config_pagination['total_rows'] = 10;
        $config_pagination['per_page'] = 1;
        $config_pagination['use_page_numbers'] = TRUE;
        $config_pagination['uri_segment'] = 4;
        $config_pagination['num_links'] = 10;
        $config_pagination['first_link'] = FALSE;
        $config_pagination['last_link'] = FALSE;
        $config_pagination['next_link'] = FALSE;
        $config_pagination['prev_link'] = FALSE;
        $this->pagination->initialize($config_pagination);
        //--------------------------------------------

        //view variables
        Template::set('pagination',$this->pagination->create_links());
        Template::set('page',$page);

        Template::render();
    }

    //------------------------------------------------------
    //helper functions

    private function get_rental_products($rental_plan,$group=null){
        $this->load->model('product_model','products');

        //TODO add cache control statement

        return $this->products->rental_products($rental_plan,$group);
    }

    private function get_instrument($id){
        $this->load->model('product_model','products');

        //TODO add cache control statement

        return $this->products->find(array($id));
    }

    private function get_accessories($product_id){
        $this->load->model('product_model','products');

        //TODO add cache control statement

        return $this->products->accessories($product_id);
    }

    private function get_rental_details($product_id,$rental_plan)
    {

        //TODO fix plan id

        if ($rental_plan === 'band' || $rental_plan ==='orchestra')
        {
            $this->load->model('standard_rental_model','plan');
            //TODO add cache control statement

            return $this->plan->find(array($product_id,1));
        }

        if ($rental_plan == 'bravo')
        {
            $this->load->model('bravo_rental_model','plan');

            return $this->plan->find(array($product_id,3));
        }

        return array();

    }

    private function get_forms($rental_plan)
    {
        //TODO fix plan id

        $this->load->model('rentalplan_to_form_model','rentalform');

        //TODO add cache control statement

        return $this->rentalform->getForms(1);
    }


}


class Thumbnail
{
    public function  __construct()
    {

    }

    /**
     * Generates a thumbnail.
     * @param $image
     * @param $width
     * @param $height
     * @return GdThumb
     */
    public function make($image,$width=150,$height=150,$color=array(255,255,255))
    {
        $image_path = ROOTPATH.'/assets/uploads/files/';
        $folder_path = ROOTPATH.'/assets/cache/thumbs/';
        $save = $folder_path.$width .'_'. $height.'_'.$image;//full path of then new image.

        //create, resize, and save the thumbnail
        $thumb = PhpTHumbFactory::create($image_path.$image)
            ->resize($width,$height)
            ->pad($width,$height,$color)
            ->save($save);

        return $thumb;
    }

}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 */