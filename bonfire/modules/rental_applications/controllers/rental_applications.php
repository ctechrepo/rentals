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

        $this->load->library('grocery_crud');


        $this->load->library('encrypt');//needed to decode excahanged data
        $this->encrypt->set_cipher(MCRYPT_BLOWFISH);

        $this->load->helper('html');
        $this->load->helper('file');
        $this->load->helper('form');


        $this->load->add_package_path(APPPATH.'third_party/phpThumb/');
        $this->load->helper('phpthumb');
        Template::set('thumbnail',new Thumbnail());
        Assets::add_module_js('rental_applications','jquery.cookie.js');
        Assets::add_module_js('rental_applications','rental_applications.js');
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

        //check if they already completed an application
        if ($this->session->userdata("completed_rental_applicaton") === 'yes')
        {
            $this->session->unset_userdata("completed_rental_applicaton");
            redirect('/rental_applications/band/page/1');
            exit;
        }

        Template::set('skip_page3',FALSE);
        //input
        $curr_page = $page = $this->uri->segment(4)?$this->uri->segment(4):1; //check_uri for current_page
        $page_seen = $this->session->userdata('page_seen')?$this->session->userdata('page_seen'):array(
            7=>FALSE,
            8=>FALSE,
            9=>FALSE,
            10=>FALSE
        );

        if($curr_page == 1){
            //reset save data
            $this->session->unset_userdata('instrument_id');
            $this->session->unset_userdata('level');
            $this->session->unset_userdata('optionalMandR');
            $this->session->unset_userdata('accessories');
            $this->session->unset_userdata('page_seen');
            //form data is still save
        }


        $instrument_id = $this->input->get('instrument')?
                        $this->input->get('instrument'):$this->session->userdata('instrument_id');

        //Platinum, Gold, ... selection
        $level = $this->input->get('level')?$this->input->get('level'):$this->session->userdata('level');

        $m_r_option = $this->input->get('optionalMandR')?$this->input->get('optionalMandR'):$this->session->userdata('optionalMandR');

        //form input is handle by the ajax controller.

        //saved input for future use
        $this->session->set_userdata('instrument_id',$instrument_id);
        $this->session->set_userdata('level',$level);
        $this->session->set_userdata('optionalMandR',$m_r_option);

        //-----------------what to do with the input---------------------------------------
        $rent_only_option = false;

        //make sure an instrument has been selected before moving on.
        if (empty($instrument_id) && $curr_page != 1){
                redirect('/rental_applications/band/page/1');
                exit;
        } elseif ($curr_page != 1) { //get information related to the selected instrument.
            $instrument = $this->get_instrument($instrument_id);
            Template::set('selected_instrument', $instrument);
            $this->keep_safe("field_instrumentName",$instrument->product_name);
            $rental_details = $this->get_rental_details($instrument_id,'band');
            $rent_only_option = $rental_details->rent_only_price > 0;
            $this->standard_rental($rental_details,$level,'band');
        }
        //make sure a valid rental option has been selected before moving on.
        if (empty($level) && ! $rent_only_option && $curr_page > 3)
        {
            redirect('/rental_applications/band/page/2');
            exit;
        }



        switch ($curr_page){
            case 1:  //instrument selection
               Template::set('instruments',$this->get_rental_products('Band'));
               Template::set('instrument_url',site_url('rental_applications/band/page/2/?'));
            break;

            case 4: $this->rental_form('band');
                    break;

            case 5: $accessories = $this->get_accessories($instrument_id);
                Template::set('accessories',$accessories);

                $this->load->model('organization_model');
                $schools = $this->organization_model->find_all();
                Template::set('schools',$schools);
                break;

            case 6: $this->standard_invoice($this->session->userdata('accessories'),$rental_details,$level);
                break;

            case 7: $this->rental_form('band');
                    $page_seen[7] = TRUE;
                       break;
            case 8: $this->rental_form('band');
                    $page_seen[8]  = TRUE;
                    break;
            case 9: $this->rental_form('band');
                    $page_seen[9] = TRUE;
                    break;

            case 10: $this->rental_form('band');
                    $page_seen[10] = TRUE;
                    break;

            case 11: $this->receipt($page_seen,'band');
                break;
        }
        $this->session->set_userdata('page_seen',$page_seen);
        //----------------------------------------------------------------------------------


        //set pagination---------------------------------------------------------------------
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

        //output
        Template::set('page',$curr_page);
        Template::set('resource','band');
        Template::set('pagination',$this->pagination->create_links());

        Template::render();
    }


    /**
     * Displays the orchestra page
     *
     * @return void
     */
    public function orchestra(){

        //check if they already completed an application
        if ($this->session->userdata("completed_rental_applicaton") === 'yes')
        {
            $this->session->unset_userdata("completed_rental_applicaton");
            redirect('/rental_applications/band/page/1');
            exit;
        }

        Template::set('skip_page3',FALSE);
        //input
        $curr_page = $page = $this->uri->segment(4)?$this->uri->segment(4):1; //check_uri for current_page
        $page_seen = $this->session->userdata('page_seen')?$this->session->userdata('page_seen'):array(
            7=>FALSE,
            8=>FALSE,
            9=>FALSE,
            10=>FALSE
        );

        if($curr_page == 1){
            //reset save data
            $this->session->unset_userdata('instrument_id');
            $this->session->unset_userdata('level');
            $this->session->unset_userdata('optionalMandR');
            $this->session->unset_userdata('accessories');
            $this->session->unset_userdata('page_seen');
            //form data is still save
        }


        $instrument_id = $this->input->get('instrument')?
            $this->input->get('instrument'):$this->session->userdata('instrument_id');

        //Platinum, Gold, ... selection
        $level = $this->input->get('level')?$this->input->get('level'):$this->session->userdata('level');

        $m_r_option = $this->input->get('optionalMandR')?$this->input->get('optionalMandR'):$this->session->userdata('optionalMandR');

        //form input is handle by the ajax controller.

        //saved input for future use
        $this->session->set_userdata('instrument_id',$instrument_id);
        $this->session->set_userdata('level',$level);
        $this->session->set_userdata('optionalMandR',$m_r_option);

        //-----------------what to do with the input---------------------------------------
        $rent_only_option = false;

        //make sure an instrument has been selected before moving on.
        if (empty($instrument_id) && $curr_page != 1){
            redirect('/rental_applications/orchestra/page/1');
            exit;
        } elseif ($curr_page != 1) { //get information related to the selected instrument.
            $instrument = $this->get_instrument($instrument_id);
            Template::set('selected_instrument', $instrument);
            $this->keep_safe("field_instrumentName",$instrument->product_name);

            $rental_details = $this->get_rental_details($instrument_id,'orchestra');
            $rent_only_option = $rental_details->rent_only_price > 0;
            $this->standard_rental($rental_details,$level,'orchestra');
        };
        //make sure a valid rental option has been selected before moving on.
        if (empty($level) && ! $rent_only_option && $curr_page > 3)
        {
            redirect('/rental_applications/orchestra/page/2');
            exit;
        }



        switch ($curr_page){
            case 1:  //instrument selection
                Template::set('instruments',$this->get_rental_products('Orchestra'));
                Template::set('instrument_url',site_url('rental_applications/orchestra/page/2/?'));
                break;

            case 4: $this->rental_form('orchestra');
            break;

            case 5: $accessories = $this->get_accessories($instrument_id);
            Template::set('accessories',$accessories);

            $this->load->model('organization_model');
            $schools = $this->organization_model->find_all();
            Template::set('schools',$schools);
            break;

            case 6: $this->standard_invoice($this->session->userdata('accessories'),$rental_details,$level);
            break;

            case 7: $this->rental_form('band');
            $page_seen[7] = TRUE;
            break;
            case 8: $this->rental_form('band');
            $page_seen[8]  = TRUE;
            break;
            case 9: $this->rental_form('band');
            $page_seen[9] = TRUE;
            break;

            case 10: $this->rental_form('band');
            $page_seen[10] = TRUE;
            break;

            case 11: $this->receipt($page_seen,'band');
            break;
        }
        $this->session->set_userdata('page_seen',$page_seen);
        //----------------------------------------------------------------------------------


        //set pagination---------------------------------------------------------------------
        $this->load->library('pagination');
        $config_pagination['base_url'] = site_url('/rental_applications/orchestra/page/');
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

        //output
        Template::set('page',$curr_page);
        Template::set('resource','orchestra');
        Template::set('pagination',$this->pagination->create_links());

        Template::render();
    }


    /**
     * Displays the bravo page
     *
     * @return void
     */
    public function bravo(){

        //check if they already completed an application
        if ($this->session->userdata("completed_rental_applicaton") === 'yes')
        {
            $this->session->unset_userdata("completed_rental_applicaton");
            redirect('/rental_applications/band/page/1');
            exit;
        }

        $resource = 'bravo';

        //------------static variables that may change in the future------//
        $number_of_payments = 36;



        $seen_pages = array();
        //----------------------------Input---------------------//
        $curr_page = $this->uri->segment(4)?$this->uri->segment(4):1; //check_uri for current_page

        $group_id = $this->input->get('group')?$this->input->get('group'):$this->session->userdata('group');
        $this->session->set_userdata('group',$group_id);

        $instrument_id = $this->input->get('instrument')?$this->input->get('instrument'):$this->session->userdata('instrument');
        $this->session->set_userdata('instrument',$instrument_id);

        //-----------Redirects---------------------//
        if ( $curr_page != 1 && empty($group_id) ){
            redirect("/rental_applications/$resource/page/1");
            exit;
        }

        if ( $curr_page >= 3 && empty($instrument_id) ){
            redirect("/rental_applications/$resource/page/2");
            exit;
        }

        //-----------update seen pages------------------//
        $prev_seen = unserialize($this->session->userdata('seen_pages'));
        $seen_pages["page".$curr_page] = "yes";
        if (! empty($prev_seen))
        {
            $seen_pages = array_merge($prev_seen,$seen_pages);
        }
        $this->session->set_userdata('seen_pages',serialize($seen_pages));


        //---------Reset Session data on first page-----//
        if ( $curr_page == 1)
        {
            $this->session->unset_userdata('group');
            $this->session->unset_userdata('instrument');
            $this->session->set_userdata('seen_pages',serialize(array("page1"=>"yes")));

            //TODO clear field session data
        }


        //----------------------------What to Do---------------------//
        if (! empty($instrument_id) ){
            $instrument = $this->get_instrument($instrument_id);
            Template::set('selected_instrument', $instrument);
            $this->keep_safe("field_instrumentName",$instrument->product_name);
        }

        if ($curr_page > 3){
           $plan_details = $this->get_rental_details($instrument_id,$resource);
        }


        switch($curr_page){
            case 1:  //category selection
                $this->load->model('category_model');
                $cats =  $this->category_model->get_by_parent('bravo');
                Template::set('groups', $cats);
                Template::set('group_url',site_url("/rental_applications/$resource/page/2/?"));

                break;
            case 2: //rental description for the selected instrument
                Template::set('instruments',$this->get_rental_products('bravo',$group_id));
                Template::set('instrument_url',site_url("rental_applications/$resource/page/3/?"));

                break;
            case 4:
                $m_r_price = $plan_details->maintenance_price + $plan_details->replacement_price;
                Template::set('m_r_price',$m_r_price);

                break;
            case 5:
                $this->bravo_invoice($plan_details,$number_of_payments,$instrument);

                break;
            case 6: //fall to next case
            case 7: //fall to next case
            case 8: //fall to next case
            case 9: $this->rental_form('bravo');
                break;
        }

        //----------------------------Output---------------------//

        //set pagination-------------------------------
        $this->load->library('pagination');
        $config_pagination['base_url'] = site_url("/rental_applications/$resource/page/");
        $config_pagination['total_rows'] = 10;
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

        Template::set('pagination',$this->pagination->create_links());
        Template::set('page',$curr_page);
        Template::set('resource',$resource);
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
        $plan_id = $this->plan_id($rental_plan) or die("A rentalplan_id doesn't exist for this resource.");
        //TODO add cache control statement

        if ($rental_plan === 'band' || $rental_plan ==='orchestra'){$this->load->model('standard_rental_model','plan');}

        if ($rental_plan == 'bravo') { $this->load->model('bravo_rental_model','plan'); }

        $details = $this->plan->find(array($product_id,$plan_id));

        $details or die("This instrument: $product_id is not assigned to a rental plan.");

        return $details;
    }

    private function plan_id($resource)
    {
       $this->db->where('LOWER(rentalplan_name)',$resource);
       $this->db->select('rentalplan_id');
       $query = $this->db->get('rentalplan');

        if($query->num_rows() > 0)
        {
           $row = $query->row();
           return $row->rentalplan_id;
        }

        return false;
    }


    private function get_forms($rental_plan)
    {
        $plan_id = $this->plan_id($rental_plan) or die("A rentalplan_id doesn't exist for this resource.");

        $this->load->model('rentalplan_to_form_model','rentalform');

        //TODO add cache control statement

        //return $this->rentalform->getForms($plan_id);//TODO Fix
        return $this->rentalform->getForms(1);  //All contracts use the sameform temporary workaround
    }

    private function cost($products)
    {
        $sub_total = 0;
        $accessories_list = "";
        foreach($products as $item)
        {
            $sub_total += $item->product_price;
            $accessories_list .= $item->product_name.", ";
        }
        $this->keep_safe("field_accessoriesList",$accessories_list);
        $this->keep_safe("field_totalAccessoires",number_format($sub_total,2));
        return $sub_total;
    }

    public function ajax_test()
    {
        echo "<form id='test' method='POST'>

        </form>";
    }

    private function bravo_invoice($rental_details,$number_of_payments,$instrument)
    {
      Template::set('installments',$number_of_payments);
      $this->keep_safe("field_numberMonthlyPayments",$number_of_payments);

      $base = $rental_details->base_rental_price;

      $m_r_price = $rental_details->maintenance_price + $rental_details->replacement_price;
      Template::set('m_r_price',number_format($m_r_price,2));
      $this->keep_safe("field_mrFee",number_format($m_r_price,2));

      $adjustment = 0;
      switch($number_of_payments)
      {
          case 12: $adjustment = $rental_details->tweleve_month_adjustment;
              break;
          case 24: $adjustment = $rental_details->twentyfour_month_adjustment;
              break;
          case 36: $adjustment = $rental_details->thirtysix_month_adjustment;
              break;
      }

      $monthly_rental = $base + $adjustment;
      Template::set('monthly_rental',number_format($monthly_rental,2));

      $this->keep_safe("field_totalDue",number_format($monthly_rental+$m_r_price,2));
      $this->keep_safe("field_totalMonthly",number_format($monthly_rental+$m_r_price,2));
      $this->keep_safe("field_monthlyRentalFee",number_format($monthly_rental,2));

      $buy_price = $instrument->product_price;

      $tax_instrument = $buy_price * ($this->sales_tax/100);

      $cost_instrument = $buy_price + $tax_instrument;

      $service_charge = $cost_instrument * ($this->interest_rate/100);

      Template::set('price_instrument',number_format($buy_price,2));
      $this->keep_safe('field_price',number_format($buy_price,2));

      Template::set('tax_instrument',number_format($tax_instrument,2));
      $this->keep_safe('field_tax',number_format($tax_instrument,2));

      Template::set('cost_instrument',number_format($cost_instrument,2));
      $this->keep_safe('field_cashPrice',number_format($cost_instrument,2));

      Template::set('service_charge',number_format($service_charge,2));
      $this->keep_safe('field_serviceCharge',number_format($service_charge,2));

      Template::set('total_payment',number_format($cost_instrument+$service_charge,2));
      $this->keep_safe('field_totalPayments',number_format($cost_instrument+$service_charge,2));

      $final_payment = ($cost_instrument+$service_charge) - ($monthly_rental*$number_of_payments) - $monthly_rental + $m_r_price;
      if ($final_payment < 0){$final_payment = 0;}

      Template::set('final_payment',number_format($final_payment,2));
      $this->keep_safe("field_finalPayment",number_format($final_payment,2));

      $due_date = $this->due_date();
      Template::set('due_date',$due_date);
      $this->keep_safe("field_debitMonth",$due_date);

        //var_dump($instrument); die();
    }

    private function standard_invoice($accessories,$rental_details,$level)
    {
        $m_r_price = $rental_details->maintenance_price + $rental_details->replacement_price;
        $this->keep_safe("field_mrFee",number_format($m_r_price,2));
        $this->keep_safe("field_mr2months",number_format($m_r_price*2,2));

        $rent_to_own = array(
            'bronze'=>$rental_details->bronze_price,
            'silver'=>$rental_details->silver_price,
            'gold'=>$rental_details->gold_price,
            'platinum'=>$rental_details->platinum_price
        );

        //calculate accessories cost
        $subtotal_accessories = 0;
        $tax_accessories = 0;
        if ($accessories)
        {
            $subtotal_accessories = $this->cost($accessories);
            $tax_accessories = $subtotal_accessories * ($this->sales_tax/100);
            $this->keep_safe("field_tax2",number_format($tax_accessories,2));
        }
        Template::set('subtotal_accessories',number_format($subtotal_accessories,2));
        Template::set('tax_accessories',number_format($tax_accessories,2));

        $this->keep_safe('field_subtotal',number_format($subtotal_accessories+$tax_accessories,2));

        $monthly_rental = $rental_details->rent_only_price;
        $levels = array_keys($rent_to_own);
        if (in_array($level,$levels))
        {
            $detailsArray = (array) $rental_details;

            $monthly_rental = $rent_to_own[$level];

            $price_instrument = $detailsArray['purchase_price_'.$level];
            Template::set('price_instrument',number_format($price_instrument,2));
            $this->keep_safe('field_price',number_format($price_instrument,2));

            $tax_instrument = $price_instrument * ($this->sales_tax/100);
            Template::set('tax_instrument',number_format($tax_instrument,2));
            $this->keep_safe('field_tax',number_format($tax_instrument,2));

            $this->keep_safe('field_cashPrice',number_format($tax_instrument+$price_instrument,2));

            $service_charge = $detailsArray['service_charge_'.$level];
            Template::set('service_charge',$service_charge);
            $this->keep_safe('field_serviceCharge',number_format($service_charge,2));

            Template::set('cost_instrument',number_format($price_instrument+$tax_instrument,2));
            Template::set('total_payments',number_format($price_instrument+$tax_instrument+$service_charge,2));
            $this->keep_safe('field_totalPayments',number_format($price_instrument+$tax_instrument+$service_charge,2));

            Template::set('installments',$rental_details->installments);
            $this->keep_safe("field_numberMonthlyPayments",$rental_details->installments);

            $final_payment = ($price_instrument+$tax_instrument+$service_charge) - ($monthly_rental*$rental_details->installments) - $rental_details->two_month_price + $m_r_price;
            if ($final_payment < 0){$final_payment = 0;}
            Template::set('final_payment',number_format($final_payment,2));
            $this->keep_safe("field_finalPayment",number_format($final_payment,2));

            Template::set('r_own_selected',TRUE);
        }
        Template::set('monthly_rental',number_format($monthly_rental,2));
        $this->keep_safe("field_monthlyRentalFee",number_format($monthly_rental,2));

        $total_due = $subtotal_accessories + $tax_accessories + $rental_details->two_month_price + (2*$m_r_price);
        $this->keep_safe("field_totalDue",number_format($total_due,2));
        $this->keep_safe("field_rentalfee2months",number_format($rental_details->two_month_price,2));

        $this->keep_safe("field_totalMonthly",number_format($monthly_rental+$m_r_price,2));

        Template::set('two_months_rental',number_format($rental_details->two_month_price,2));
        Template::set('total_due',number_format($total_due,2));


        Template::set('rental_plan',$rental_details);
        //Template::set('rental_plan',$prices);

        $due_date = $this->due_date();
        Template::set('due_date',$due_date);
        $this->keep_safe("field_debitMonth",$due_date);
    }

    private function due_date(){
        // installment due dates
        // 1. if it is the 5th or the 20th of the month, simply add 2 months.
        // 2. if the day of the month is less than 20, then move to the 20th of the month and add 2 months.
        // 3. if the day of the month is greater than 20, move to the 5th of next month and add 2 months.

        $month=date("m");
        $day=date("d");
        $year=date("Y");

        if ($day=="05" or $day=="20") {
        //move ahead 2 months from today
            $due_date=date('m/d/Y', strtotime("$month/$day/$year + 2 months"));
        }

        elseif ($day>20) {
        //move to the 5th of next month and add 2 months
        $due_date=date('m/d/Y', strtotime("$month/5/$year + 1 month"));
        $due_date=date('m/d/Y', strtotime("$due_date + 2 months"));
        }

        else{ //the day is less than 20
        //move to the 20th of the month and add 2 months
        $due_date=date('m/d/Y', strtotime("$month/20/$year + 2 months"));
        }

        return $due_date;
    }


    private function standard_rental($rental_details,$level,$type)
    {
       Template::set('plan_description',$rental_details->rental_description);

       $rent_to_own = array(
              'bronze'=>$rental_details->bronze_price,
              'silver'=>$rental_details->silver_price,
              'gold'=>$rental_details->gold_price,
              'platinum'=>$rental_details->platinum_price
       );

       $plan_values = array_values($rent_to_own);
       $skip_page3 = TRUE;

       $count = 0;//if rent_to_own has any price greater than zero then the level choice page should be displayed
       while ($skip_page3 && $count < count($plan_values))
       {
                if ($plan_values[$count] != NULL && $plan_values[$count] > 0)
                    $skip_page3 = FALSE;
                $count ++;
       }

       Template::set('skip_page3',$skip_page3);
       if ($skip_page3 === FALSE)
       {
            Template::set('rent_own_url',site_url("rental_applications/$type/page/3"));
            Template::set('rent_to_own', $rent_to_own);

       }

       if ($rental_details->rent_only_price > 0)
       {
            Template::set('rent_only_url',site_url("rental_applications/$type/page/4"));
       }

      $m_r_price = $rental_details->maintenance_price + $rental_details->replacement_price;
      Template::set('m_r_price',number_format($m_r_price,2));

    }

    private function rental_form($resource)
    {
        $rental_forms = $this->get_forms($resource);

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
    }

    private function receipt($page_seen,$resource)
    {
       foreach($page_seen as $page=>$seen)
       {
           if (! $seen)
           {
              //redirect to page that was skip.
              redirect('rental_applications/band/page/'.$page);
              exit;
           }
       }
        $contractno = md5(time().$this->session->userdata('field_initials'));
        $this->session->set_userdata("contractno",$contractno);
        $this->session->set_userdata("completed_rental_applicaton","yes");

    }

    private function keep_safe($key,$value)
    {
        $encoded = $this->encrypt->encode($value);
        $this->session->set_userdata($key,$encoded);
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