<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class category_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'category';
        $fieldprefix = 'category_';
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
            $fieldprefix.'parent_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
                'NULL'=>TRUE
            ),
            $fieldprefix.'description' => array(
                'type'=>'TEXT',
                'NULL'=>TRUE
            ),

        );

        $this->crud = new Grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
    }


    public function get_by_parent($parent){

        $returnArray = array();
        $table = $this->db->dbprefix($this->table);

        $this->db->where("category_name LIKE '%{$parent}%'");
        $query = $this->db->get($table);

        $result = ($query->num_rows() > 0)? $query->result(): array();

        foreach ($result as $parent)
        {
               $this->db->where('category_parent_id',$parent->category_id);
               $sub_query = $this->db->get($table);

               $sub_result = ($sub_query->num_rows() > 0)? $sub_query->result(): array();

               $group = new Category_node();
               $group->parent = $parent;
               $group->children = $sub_result;

               array_push($returnArray,$group);
        }

        return $returnArray;
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

        $relation_table1 = $this->db->dbprefix('category_to_product');
        $selection_table1 = $this->db->dbprefix('product');

        $relation_table2 = $this->db->dbprefix($this->table);

        $this->crud
            ->set_subject('Category')
            ->set_relation_n_n('Products',$relation_table1,$selection_table1,$cols[0],'product_id','product_name')
            ->set_relation($cols[2],'category', 'category_name')
            ->columns(
            $cols[0],
            $cols[1]
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'Name')
            ->display_as($cols[2],'Parent')
            ->display_as($cols[3],'Description')

            ->fields($cols[1],$cols[3],$cols[2],'Products');
        ;

        //$crud->required_fields();

        return $this->crud->render();
    }
}

class Category_node{

    public $parent;

    public $children;
}
/**
 * Created by CTech.
 * Author: Shawn Rhoney
 * Date: 1/4/13
 * File: category_model.php
 * Location:models
 * Module: Rental_Applications
 */