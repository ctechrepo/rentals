<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

   class Content extends Admin_Controller{

       public function __construct()
       {
           parent::__construct();

           $this->assets_folder = base_url('bonfire/modules/rental_applications/assets');
           //internationalization
           $this->lang->load('rental_applications');
           //crumb trail
           Template::set('toolbar_title',"Rentals");
           //navigation
           Template::set_block('sub_nav', 'content/_sub_nav');
           //base url
           Template::set('module_baseurl','/content/rental_applications');

       }

       //----------------helper methods-----------------------------------------------------

       /**
        * Injects the Grocery CRUD assets into the template.
        *
        * @param Object $output Grocery_CRUD output object
        *
        * @return void
        */
       protected function CRUD_table($output)
       {
           if (!empty($output)){
              Template::set('CRUD_table',$output->output);
              assets::add_js($output->js_files);
              assets::add_css($output->css_files);
           } else {
               Template::set('CRUD_table','');
           }
       }

       /**
        * Generates a thumbnail.
        * @param $image
        * @param $width
        * @param $height
        * @return GdThumb
        */
       private function thumbnail($image,$width,$height)
       {
           $this->load->add_package_path(APPPATH.'third_party/phpThumb/');
           $this->load->helper('PHPThumb');

           $image_path = ROOTPATH.'/assets/uploads/files/';
           $folder_path = ROOTPATH.'/assets/cache/thumbs/';
           $save = $folder_path.$width .'_'. $height.'_'.$image;//full path of then new image.

           //create, resize, and save the thumbnail
           $thumb = PhpTHumbFactory::create($image_path.$image)
               ->adaptiveResize($width,$height)
               ->save($save);

           return $thumb;
       }

       //----------------end of helpers ------------------------------------------------

       //-----------------pages---------------------------------------------------------

       /**
        * Content index page
        */
       public function index()
       {

           //uri variables
           $uriVar1 = $this->uri->segment(5);
           $uriVar2 = $this->uri->segment(6);

           //don't use get variables if view interacts with GROCERY CRUD
           //get variables

           //post variables

           //cookies

           //session variables

           //set $output -- GROCERY_CRUD RENDER OBJECT
           switch($uriVar1)
           {


               default:
                  $this->load->model('rentalplan_application_model','applications');
                 // $output = $this->applications->output();
           }
           //set View variables
           Template::set('filter',$uriVar1);

           //inject GROCERY_CRUD assets and view
           if (!empty($output)){
               Template::set('CRUD_table',$output->output);
               assets::add_css($output->css_files);
               assets::add_js($output->js_files);
           } else {
               Template::set('CRUD_table','');
           }
           $this->cleanup();
           Template::render();
       }


       /**
        * Content/products page
        *
        * @return void
        */
       public function products()
       {
           $this->load->helper('file');
           $this->load->helper('html');
          //uri parameters starting index
          $uri_var_index = 5;

          $filter = $this->uri->segment($uri_var_index);

          //load the correct model base on the uri value
          switch($filter)
          {
            case 'categories'://load the categories table
                $this->load->model('category_model','active_model');
                break;

            default://load the default table, products
               $this->load->model('product_model','active_model');

          }

          //use the loaded model
          $this->CRUD_table($this->active_model->output());

          //display the page
          Template::set('filter',$filter);
          Template::render();
       }

       /**
        * Content/contacts page
        *
        * @return void
        */
       public function contacts()
       {
           //uri parameters starting index
           $uri_var_index = 5;

           $filter = $this->uri->segment($uri_var_index);

           switch($filter)
           {
               case 'organization_recommendations':
                   $this->load->model('organization_recommendation_model','active_model');
                   break;

               case 'contact_recommendations':
                   $this->load->model('recommendation_model','active_model');
                   break;

               case 'organizations':
                   $this->load->model('organization_model','active_model');
                   break;

               default:
                   $this->load->model('contact_model','active_model');
                   break;
           }

           //use the loaded model
           $this->CRUD_table($this->active_model->output());

           Template::set('filter',$filter);
           Template::render();
       }

       public function old_products()
       {

          //uri variables
          $uriVar1 = $this->uri->segment(5);
          $uriVar2 = $this->uri->segment(6);

          //don't use get variables if view interacts with GROCERY CRUD
          //get variables

          //post variables

          //cookies

          //session variables

          //set $output -- GROCERY_CRUD RENDER OBJECT
          switch($uriVar1)
          {
              case 'accessories': //get accessories table
                  $this->load->model('band/Accessories_model');
                  $output = $this->Accessories_model->output();
                  break;
              case 'schools': //get schools table
                  $this->load->model('band/School_model');
                  $output = $this->School_model->output();
                  break;
              case 'schoolaccessories': //get school to accessories table
                  $this->load->model('band/Bandinstruments_schoolaccessories_model','band_schoolaccessories');
                  $output = $this->band_schoolaccessories->output();
                  break;
              case 'instrumentaccessories': //get instrument to accessories table
                  $this->load->model('band/Bandinstruments_accessories_model','band_instrumentaccessories');
                  $output = $this->band_instrumentaccessories->output();
                  break;
              default:  //get the default table -- instruments table
                  $this->load->model('band/Bandinstruments_model');
                  $output = $this->Bandinstruments_model->output();
          }


          //set View variables
          Template::set('filter',$uriVar1);


          //inject GROCERY_CRUD assets and view
          if (!empty($output)){
           Template::set('CRUD_table',$output->output);
           assets::add_css($output->css_files);
           assets::add_js($output->js_files);
          } else {
            Template::set('CRUD_table','');
          }
           assets::add_module_js('rental_applications','rental_applications.js');
          //display the page
          $this->cleanup();
          Template::render();
       }

       /**
        * Content/forms page
        *
        * @return void
        */
       public function forms()
       {
           //uri parameters starting index
           $uri_var_index = 5;

           $filter = $this->uri->segment($uri_var_index);

           switch($filter)
           {
               case 'fields':
                   $this->load->model('forms/formfields_model','active_model');
                   break;

               case 'sections':
                   $this->load->model('forms/formsection_model','active_model');
                   break;

               default:
                   $this->load->model('forms/form_model','active_model');
                   break;
           }

           //use the loaded model
           $this->CRUD_table($this->active_model->output());

           Template::set('filter',$filter);
           Template::render();
       }
       /**
        * Content forms page
        */
       public function old_forms()
       {
           //uri variables
           $uriVar1 = $this->uri->segment(5);
           $uriVar2 = $this->uri->segment(6);

           //don't use get variables if view interacts with GROCERY CRUD
           //get variables

           //post variables

           //cookies

           //session variables

           //set $output -- GROCERY_CRUD RENDER OBJECT
           switch($uriVar1){
               case 'fields': //get Form Fields table
                   $this->load->model('band/Bandrental_formfields_model','rentalform_fields');
                   $output = $this->rentalform_fields->output();
                   break;
               default: //get the default table -- Form Sections
                   $this->load->model('band/Bandrental_formsections_model','rentalform_sections');
                   $output = $this->rentalform_sections->output();
           }

           //set View variables
           Template::set('filter',$uriVar1);


           //inject GROCERY_CRUD assets and view
           if (!empty($output)){
               Template::set('CRUD_table',$output->output);
               assets::add_css($output->css_files);
               assets::add_js($output->js_files);
           } else {
               Template::set('CRUD_table','');
           }
           assets::add_module_js('rental_applications','rental_applications.js');
           //display the page
           Template::render();
       }

       /**
        * Content/plans page
        *
        * @return void
        */
       public function plans()
       {
           //uri parameters starting index
           $uri_var_index = 5;

           $filter = $this->uri->segment($uri_var_index);

           switch($filter)
           {
               case "bravo_rental":
                   $this->load->model('bravo_rental_model','active_model');
                   break;
               case "instrument_rental":
                   $this->load->model('standard_rental_model','active_model');
                   break;
               case "details":
                   $this->load->model('rentalplan_details_model','active_model');
                   break;
               default:
                   $this->load->model('rentalplan_model','active_model');
                   break;
           }

           //use the loaded model
           $this->CRUD_table($this->active_model->output());

           Template::set('filter',$filter);
           Template::render();
       }

       //-------------end of pages-------------------------------------------

   }
?>