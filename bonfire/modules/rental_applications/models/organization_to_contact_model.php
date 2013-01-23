<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class organization_to_contact_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'organization_to_contact';
        $this->set_key('contact_id,organization_id');


        $this->fields = array(
            'contact_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'organization_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
        );
    }

}
/**
 * Created by CTech.
 * Author: Shawn Rhoney
 * Date: 1/7/13
 * File: organization_to_contact_model.php
 * Location: models
 * Module: rental_applications
 */