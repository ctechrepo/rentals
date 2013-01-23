<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rental_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'rental';
        $this->set_key('product_id,rentalplan_id');


        $this->fields = array(
            'product_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'rentalplan_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'type'=> array(
                'type'=>'ENUM',
                'constraint'=>" 'standard','bravo' ",
                'default'=>'standard',
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
 * File: Rental_model.php
 * Module: rental_applications
 * Location: models
 **/