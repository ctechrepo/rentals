<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class rentalplan_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'rentalplan';
        $fieldprefix = 'rentalplan_';
        $this->set_key($fieldprefix.'id');

        $this->fields = array(
            $fieldprefix.'id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
                'auto_increment'=>TRUE,
            ),
            $fieldprefix.'name' => array(
                'type'=>'VARCHAR',
                'constraint'=>'50',
            ),
            $fieldprefix.'description' => array(
                'type'=>'TEXT',
                'NULL'=>TRUE,
            ),

        );

        $this->crud = new Grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
    }

    public function details($product_id,$rental_plan)
    {

        $plan = $this->db->dbprefix($this->table);
        $rental = $this->db->dbprefix('rental');
        $product = $this->db->dbprefix('rental_to_plandetail');
        //$details = $this->db->dbprefix('rentalplan_details');

        $this->db->join($rental, "{$rental}.rentalplan_id={$plan}.rentalplan_id");
        $this->db->join($product,"{$product}.product_id={$rental}.product_id",'');
        //$this->db->join($details,"{$product}.rentalplan_details_id={$details}.rentalplan_details_id",'');
        $this->db->where("{$rental}.product_id",$product_id);
        $this->db->where('rentalplan_name',$rental_plan);
        $query = $this->db->get($plan);

        if ($query->num_rows() > 0){
            return $query->result();
        }


        return array();
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

        $rental_table = $this->db->dbprefix('rental');
        $product_table = $this->db->dbprefix('product');

        $relation_table1 = $this->db->dbprefix('rentalplan_to_form');
        $form_table = $this->db->dbprefix('form');


        $this->crud
            ->set_subject('Rental Plan')
            ->set_relation_n_n('Instruments',$rental_table,$product_table,$cols[0],'product_id','product_name')
            ->set_relation_n_n('Forms',$relation_table1,$form_table,$cols[0],'form_id','form_name')
            ->columns(
            $cols[0],
            $cols[1]
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'Name')

            ->fields($cols[0],$cols[1],'Instruments','Forms')

            ->callback_after_insert(array($this, 'after_update'))
            ->callback_after_update(array($this, 'after_update'))
        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }

    public function after_update($post_array,$primary_key)
    {
        var_dump($post_array);

        if (preg_match('/bravo/i',$post_array['rentalplan_name']) > 0)
        {

            $rental_table = $this->db->dbprefix('rental');
            $data = array('type'=>'bravo');

            foreach($post_array['Instruments'] as $id)
            {
                $this->db->where('product_id',$id);
            }

            $this->db->update($rental_table,$data);
        }

    }
}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: rentalplan_model.php
 * Module: rental_applications
 * Location: models
 **/