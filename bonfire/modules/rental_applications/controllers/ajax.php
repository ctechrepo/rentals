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

    private $response;

    public function __construct(){

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

        $this->response['school'] = $school;

        echo json_encode($this->response);
    }
}