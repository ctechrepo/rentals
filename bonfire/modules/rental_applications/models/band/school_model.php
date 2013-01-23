<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * DB model for a School.
 */
class School_model extends CI_Model{

    protected $db_con = 'mshoppe';
    protected $table = 'school';
    protected $key = 'school_id';
    protected $soft_deletes = FALSE;
    protected $set_created = FALSE;
    protected $set_modified = FALSE;
    //protected $selects = '';
    public $name = 'school';

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

    public function get_schools()
    {
        $query = $this->db->get($this->table);

        $schools = ($query->num_rows()  > 0)?$query->result():array();

        return $schools;
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
            ->set_subject('School')
            ->columns('school_id',
            'school',
            'phone',
            'email',
            'contact'
        )

            ->display_as('school_id','ID')
        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }


}
/***
 * File:School_model.php
 * Location: bonfire/modules/rental_applications/models
 */