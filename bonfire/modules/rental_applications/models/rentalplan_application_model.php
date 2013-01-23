<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class rentalplan_application_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'rentalplan_application';
        $this->set_key('rentalplan_id,contact_id');

        $this->fields = array(
            'rentalplan_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'contact_id' => array(
                'type'=>'VARCHAR',
                'constraint'=>'100',
            ),
            'fields_data' => array(
                'type'=>'TEXT',
            ),
            'status' => array(
                'type'=>'ENUM',
                'constraint'=>"'new','accepted','rejected'",
                'default'=>'new',
            ),
            'postdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        );

        $this->crud = new grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
        //$this->crud->set_url_segment($this->name);
    }

    /**
     * Returns a Grocery_CRUD render object.
     *
     * @return object
     *             output - html
     *             js_files - js src
     *             css_files - css href
     *
     * Full documentation at http://www.grocerycrud.com/documentation
     */
    public function output()
    {
        $this->crud
            ->unset_add()
            ->columns(
                    'rentalplan_id',
                    'contact_id',
                    'status',
                    'postdate'
        )

            ->display_as('form_id','Application')
            ->display_as('contact_id','Name')
            ->display_as('postdate','Date')
        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }
}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: rentalplan_application_model.php
 * Module: rental_applications
 * Location: models
 **/