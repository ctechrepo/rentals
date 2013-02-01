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

       if ($resource === 'band' || $resource === 'orchestra')
       {
           $accessories = $this->input->post('accessories');

           if ($accessories !== FALSE){
               $this->session->set_userdata('accessories',$accessories);
           } elseif ($page_from == 5) {
               $this->session->unset_userdata('accessories');
           }

       }
       $this->response['accessories_post'] = $accessories;
       $this->response['accessories_save'] = $this->session->userdata('accessories');
       echo json_encode($this->response);
    }
}