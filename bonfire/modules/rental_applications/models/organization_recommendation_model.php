<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class organization_recommendation_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'organization_recommendation';
        $this->set_key('organization_id,product_id');


        $this->fields = array(
            'organization_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'product_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'product_type' => array(
                'type'=>'ENUM',
                'constraint'=>"'product','accessory'",
                'default'=>'accessory',
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

        $relation_tabel1 = $this->db->dbprefix('organization');
        $relation_tabel2 = $this->db->dbprefix('product');

        $this->crud
            ->set_subject('Recommendation')
            ->set_relation($cols[0],$relation_tabel1,'Organization_name')
            ->set_relation($cols[1],$relation_tabel2,'Product_name')
            ->columns(
            $cols[0],
            $cols[1],
            $cols[2]
        )

            ->display_as($cols[0],'Organization')
            ->display_as($cols[1],'Product')
            ->display_as($cols[2],'Product Type')



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
 * File: organization_recommendation_model.php
 * Module: rental_applications
 * Location: models
 **/