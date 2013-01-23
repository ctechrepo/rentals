<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class category_to_product_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'category_to_product';
        $this->set_key('product_id,category_id');


        $this->fields = array(
            'product_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'category_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
        );

        $this->crud = new Grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
    }
}
/**
 * Created by CTech.
 * Author: user
 * Date: 1/4/13
 * File: category_to_product_model.php
 * Location: models
 * Module: rental_applications
 */