<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * DB model of Forms.
 */
class Bandrental_forms_model extends CI_Model{

    protected $db_con = 'mshoppe';
    protected $table = 'instrumentrental_instrumentaccessories';
    protected $key = 'instrument_id,accessory_id';
    protected $soft_deletes = FALSE;
    protected $set_created = FALSE;
    protected $set_modified = FALSE;
    //protected $selects = '';
    public $name = 'instrumentaccessories';

    public function  __construct(){
        parent::__construct();
        //set the active database
        if (empty($this->db_con)){
            $this->load->database();
        } else {
            $this->load->database($this->db_con,FALSE,TRUE);
        }
        $this->crud = new grocery_CRUD();
        $this->crud->set_url_segment($this->name);
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
        $this->crud->set_table($this->table)
            ->set_subject("Instrument Accessory")
            ->columns('accessoryid',
            'instrumentid',
            'required',
            'default'
        )

            ->set_relation('accessoryid','instrumentrental_accessories','accessoryname')
            ->set_relation('instrumentid','instrumentrental_instruments','instrument')
            ->display_as('accessoryid','Accessory')
            ->display_as('instrumentid','Instrument')
        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }
}
/**
 * File:  Bandrental_forms_model.php
 * Bonfire Module: rental_applications
 * Location: models/band
 */