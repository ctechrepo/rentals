<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * DB model of BandInstruments.
 */
class Bandinstruments_model extends CI_Model{

    protected $db_con = 'mshoppe';
    protected $table = 'instrumentrental_instruments';
    protected $key = 'instrumentid';
    protected $soft_deletes = FALSE;
    protected $set_created = FALSE;
    protected $set_modified = FALSE;
    //protected $selects = '';

    protected $crud;
    public $name = 'bandinstruments';

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

    public function get_instruments()
    {
        $instruments = array();
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $instruments[$row->instrumentid]=$row;
            }
        }

        return $instruments;
    }
    /**
     * Returns a Grocery_CRUD render object.
     * @return object
     *             output - html
     *             js_files - js src
     *             css_files - css href
     */
    public function output()
    {
        $this->crud->set_table($this->table)
                  ->set_subject('Instrument')
                  ->columns('instrumentid',
                            'instrument',
                             'photo',
                             'bronzeprice',
                             'silverprice',
                             'goldprice',
                             'platinumprice',
                             'renttorentprice'
                            )

                  ->display_as('instrumentid','ID')
                  ->display_as('instrument','Instrument')
                  ->display_as('bronzeprice','Bronze')
                  ->display_as('goldprice','Gold')
                  ->display_as('platinumprice','Platinum')
                  ->display_as('renttorentprice','Rent')
                  ->display_as('silverprice','Silver')
                  ;
        //$crud->fields();
        //$crud->required_fields();


        return $this->crud->render();
    }







}
/***
 * File:Bandinstruments_model.php
 * Location: bonfire/modules/rental_applications/models
 */