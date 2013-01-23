<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Admin_Controller{

    public function __construct()
    {
        parent::__construct();

        Template::set('toolbar_title',"Rentals");

        Template::set_block('sub_nav', 'reports/_sub_nav');
    }

    public function index()
    {
        Template::render();
    }

    public function band()
    {
        Template::render();
    }

    public function orchestra()
    {
        Template::render();
    }

    public function bravo()
    {
        Template::render();
    }
}
?>