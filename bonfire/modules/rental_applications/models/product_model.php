<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class product_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'product';
        $fieldprefix = 'product_';
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
            $fieldprefix.'cart_url' => array(
                'type'=>'VARCHAR',
                'constraint'=>'100',
                'NULL'=>TRUE,
            ),
            $fieldprefix.'photo_url' => array(
                'type'=>'VARCHAR',
                'constraint'=>'100',
                'NULL'=>TRUE,
            ),
            $fieldprefix.'model' => array(
                'type'=>'VARCHAR',
                'constraint'=>'100',
                'NULL'=>TRUE,
            ),
            $fieldprefix.'sku' => array(
                'type'=>'VARCHAR',
                'constraint'=>'100',
                'NULL'=>TRUE,
            ),
            $fieldprefix.'price' => array(
                'type'=>'DOUBLE',
                'constraint'=>'10,2',
                'Default'=>0.00,
            ),

        );

        $this->crud = new grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
    }


   /*
    public function bravo_rental_products($group)
    {

    }
   */

    public function rental_products($rental_plan,$group = null)
    {


        $p_table = $this->db->dbprefix($this->table);
        $r_table = $this->db->dbprefix('rental');
        $rp_table = $this->db->dbprefix('rentalplan');

          if (preg_match('/bravo/i',$rental_plan) > 0 && $group != null)
        {
            $c_table = $this->db->dbprefix('category_to_product');
            $this->db->join($c_table,"{$c_table}.product_id = {$p_table}.product_id");
            $this->db->where('category_id',$group);
        }

        $this->db->join($r_table,"{$r_table}.product_id = {$p_table}.product_id");
        $this->db->join($rp_table,"{$r_table}.rentalplan_id = {$rp_table}.rentalplan_id");
        $this->db->where('rentalplan_name',$rental_plan);
        $query = $this->db->get($p_table);

        if ($query->num_rows() > 0)
        {
            return $query->result();
        }

        return array();
    }


    public function accessories($product_id)
    {
        $p_table = $this->db->dbprefix($this->table);
        $a_table = $this->db->dbprefix('accessory');

        $this->db->join($a_table,"{$a_table}.product_id = {$p_table}.product_id");
        $this->db->join("{$p_table} AS aces","aces.product_id = {$a_table}.accessory_id");
        $this->db->where("{$p_table}.product_id",$product_id);
        $query = $this->db->get("{$p_table}");

        if ($query->num_rows() > 0)
        {
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
        $relation_table1 = $this->db->dbprefix('accessory');
        $selection_table1 = $this->db->dbprefix('product');

        $table = $this->db->dbprefix($this->table);


        $this->crud
            ->set_subject('Product')
            ->set_self_referencing(TRUE)
            ->set_relation_n_n('Accessories',$relation_table1,$selection_table1,$cols[0],'accessory_id','product_name')
            ->columns(
            $cols[0],
            $cols[1],
            $cols[4],
            $cols[5],
            $cols[6]
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'Name')
            ->display_as($cols[4],'Photo')
            ->display_as($cols[5],'Model')
            ->display_as($cols[6],'SKU')

            ->fields($cols[1],$cols[2],$cols[3],$cols[4],$cols[5],$cols[6],'Accessories')
            ->set_field_upload($cols[4],'assets/uploads/files')
        ;

        $this->crud->unset_jquery();

        return $this->crud->render();
    }





}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: product_model.php
 * Module: rental_applications
 * Location: models
 **/