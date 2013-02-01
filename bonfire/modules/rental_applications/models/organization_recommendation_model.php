<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class organization_recommendation_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'organization_recommendation';
        $this->set_key('organization_id,product_id,accessory_id');


        $this->fields = array(
            'organization_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'product_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'accessory_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),

        );

        $this->crud = new Grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
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
        //field names
        $cols = array_keys($this->fields);

        $organization = $this->db->dbprefix('organization');
        $product = $this->db->dbprefix('product');

        $this->crud
            ->set_subject('Recommendation')
            ->set_relation($cols[0],$organization,'Organization_name')
            ->set_relation($cols[1],$product,'Product_name')
            ->set_relation($cols[2],$product,'Product_name')
            //->set_self_referencing(TRUE) //all 3 tables include product_id field -- this hack resolves the conflict in the n_n relation
            //->set_relation_n_n($cols[2],$relation_table3,$relation_table2,'accessory_id','product_id','product_name')
            ->columns(
            $cols[0],
            $cols[1],
            $cols[2]
        )
            ->display_as($cols[0],'Organization')
            ->display_as($cols[1],'Product')
            ->display_as($cols[2],'Accessory')

            //->callback_insert(array($this,'insert_callback'))
        ;

        return $this->crud->render();
    }

    public function get_list($organization_id,$product_id)
    {
        $_table = $this->db->dbprefix($this->table);
        $product_table = $this->db->dbprefix('product');

        $this->db->where($_table.'.organization_id',$organization_id);
        $this->db->where($_table.'.product_id',$product_id);
        $this->db->join($product_table,"{$_table}.accessory_id = {$product_table}.product_id ",'left');
        $query = $this->db->get($_table);

        $the_list = ($query->num_rows() > 0)?$query->result():array();

        return $the_list;
    }
}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: organization_recommendation_model.php
 * Module: rental_applications
 * Location: models
 **/