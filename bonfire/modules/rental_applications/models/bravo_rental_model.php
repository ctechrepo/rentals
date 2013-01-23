<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class bravo_rental_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'bravo_rental';
        $this->set_key('product_id,rentalplan_id');


        $this->fields = array(
            'product_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'rentalplan_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'rental_description'=>array(
                'type'=>'TEXT',
                'null'=>TRUE,
            ),
           'base_rental_price'=>array(
               'type'=>'DOUBLE',
               'default'=>'0',
               'constraint'=>'8,2',
           ),
           'twelve_month_adjustment'=>array(
               'type'=>'DOUBLE',
               'default'=>'0',
               'constraint'=>'8,2',
           ),
           'twentyfour_month_adjustment'=>array(
                'type'=>'DOUBLE',
                'default'=>'0',
                'constraint'=>'8,2',
           ),
           'thirtysix_month_adjustment'=>array(
               'type'=>'DOUBLE',
               'default'=>'0',
               'constraint'=>'8,2',
           ),
            'maintenance_price'=>array(
                'type'=>'DOUBLE',
                'default'=>'0',
                'constraint'=>'8,2',
            ),
            'replacement_price'=>array(
                'type'=>'DOUBLE',
                'default'=>'0',
                'constraint'=>'8,2',
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

        $plan = $this->db->dbprefix('rentalplan');
        $product = $this->db->dbprefix('product');

        $where = "rentalplan_name = 'bravo band' or rentalplan_name = 'bravo orchestra' ";

        $this->crud
            ->set_subject('Bravo Rental')
            ->set_relation($cols[0],$product,"product_name")
            ->set_relation($cols[1],$plan,"rentalplan_name",$where)

            ->columns(
            $cols[0],
            $cols[1]
        )

            ->display_as($cols[0],'Product')
            ->display_as($cols[1],'Rental Plan')
            ->display_as($cols[2],'Description')
            ->display_as($cols[3],'Base')
            ->display_as($cols[4],'12 Month')
            ->display_as($cols[5],'24 Month')
            ->display_as($cols[6],'36 Month')
        ; //end of object chain

        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }
}
/**
 * Created by CTech.
 * Author: Shawn Rhoney
 * Date: 1/8/13
 * File: standard_rental_model.php
 * Location: Models
 * Module: Rental Application
 */