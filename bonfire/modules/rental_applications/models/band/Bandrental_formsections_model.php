<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * DB model of rental form sections
 */
class Bandrental_formsections_model extends CI_Model{

    protected $db_con = 'mshoppe';
    protected $table = 'instrumentrental_formsections';
    protected $key = 'sectionid';
    protected $soft_deletes = FALSE;
    protected $set_created = FALSE;
    protected $set_modified = FALSE;
    //protected $selects = '';
    public $name = 'formsections';

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

    public function get_sections($formid,$page)
    {
        $sections = array();

        $this->db->where('formid',$formid);
        $this->db->where('page',$page);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0){
           foreach ($query->result() as $section)
           {
               array_push($sections,$section);

           }
        }

        return $sections;
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
            ->set_subject("Section")
            ->columns('sectionid',
            'sectionname',
            'sectionorder',
            'formid',
            'page'
        )

            ->set_relation('formid','instrumentrental_forms','formname')
            ->display_as('sectionid','ID')
            ->display_as('sectionname','Name')
            ->display_as('sectionorder','Order')
            ->display_as('sectionpage','Page')
            ->display_as('formid','Form')

        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }
}
/**
 * File:  Bandrental_formsections_model.php
 * Bonfire Module: rental_applications
 * Location: models/band
 */