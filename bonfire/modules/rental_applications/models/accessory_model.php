<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class accessory_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'accessory';
        $this->set_key('product_id,accessory_id');


        $this->fields = array(
            'product_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'accessory_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
        );
    }
}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: accessory_model.php
 * Module: rental_applications
 * Location: models
 **/