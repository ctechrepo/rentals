<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class form_to_formsection_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'form_to_formsection';
        $this->set_key('form_id,formsection_id');


        $this->fields = array(
            'form_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'formsection_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
        );

        $this->crud = new Grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
    }
}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: form_to_formsection_model.php
 * Module: rental_applications
 * Location: models
 **/