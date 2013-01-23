<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class formsection_to_formfield_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'formfield_to_formsection';
        $this->set_key('formfield_id,formsection_id');


        $this->fields = array(
            'formfield_id' => array(
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
 * File: formsection_to_formfield_model.php
 * Module: rental_applications
 * Location: models
 **/