<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * DB model of Accessories.
 */
class Accessories_model extends CI_Model{

    protected $db_con = 'mshoppe';
    protected $table_prefix = 'instrumentrental_';
    protected $table = 'instrumentrental_accessories';
    protected $key = 'accessoryid';
    protected $soft_deletes = FALSE;
    protected $set_created = FALSE;
    protected $set_modified = FALSE;

    protected $relationships = array();

    //protected $selects = '';
    public $name = 'Accessories';


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

        $this->relationships = array(
            'instrument'=> array(
                'table'=> $this->table_prefix.'instrumentaccessories',
                'join'=>  $this->table_prefix.'instrumentaccessories.accessoryid ='.$this->table.'.'.$this->key
         ),
            'school'=> array(
                'table'=> $this->table_prefix.'schoolaccessories',
                'join'=> $this->table_prefix.'schoolaccessories.accessory_id ='.$this->table.'.'.$this->key
            )
        );

    }


    public function get_accessories($instrument=FALSE,$school=FALSE)
    {
        if (!empty($instrument))
        {
            $this->db->where('instrumentid',$instrument);
            $relationship = $this->relationships['instrument'];
            $this->db->join($relationship['table'],$relationship['join']);
        }
        if (!empty($school))
        {
            $this->db->where('schoolid',$school);
            $relationship = $this->relationships['school'];
            $this->db->join($relationship['table'],$relationship['join']);
        }
        $query = $this->db->get($this->table);

        $accessories = ($query->num_rows()  > 0)?$query->result():array();

        return $accessories;
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
            ->set_subject('Accessory')
            ->columns('accessoryid',
            'accessoryname',
            'sku',
            'photo',
            'price'
        )

            ->display_as('accessoryid','ID')
            ->display_as('accessoryname','Accessory')
        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }


}
/***
 * File:Accessories_model.php
 * Location: bonfire/modules/rental_applications/models
 */