<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class standard_rental_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'standard_rental';
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
            'bronze_price'=> array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'silver_price'=> array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'gold_price'=> array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'platinum_price'=> array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'rent_only_price'=>array(
                 'type'=>'DOUBLE',
                 'constraint'=>'8,2',
                 'default' => -1,
                 'null'=>TRUE,
            ),
            'two_month_price'=>array(
                  'type'=>'DOUBLE',
                  'constraint'=>'8,2',
                  'default' => -1,
                  'null'=>TRUE,
            ),
            'maintenance_price'=>array(
                  'type'=>'DOUBLE',
                  'constraint'=>'8,2',
                  'default' => -1,
                  'null'=>TRUE,
            ),
            'replacement_price'=>array(
                  'type'=>'DOUBLE',
                  'constraint'=>'8,2',
                  'default' => -1,
                  'null'=>TRUE,
            ),
            'service_charge'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'installments'=>array(
                'type'=>'INT',
                'constraint'=>5,
                'default'=>-1,
                'null'=>TRUE,
            ),
            'purchase_price_bronze'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'purchase_price_silver'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'purchase_price_gold'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'purchase_price_platinum'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'service_charge_bronze'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'service_charge_silver'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'service_charge_gold'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
            ),
            'service_charge_platinum'=>array(
                'type'=>'DOUBLE',
                'constraint'=>'8,2',
                'default' => -1,
                'null'=>TRUE,
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

        $where = "rentalplan_name = 'band' or rentalplan_name = 'orchestra' ";

        $this->crud
            ->set_subject('Instrument Rental')
            ->set_relation($cols[0],$product,"product_name")
            ->set_relation($cols[1],$plan,"rentalplan_name",$where)

            ->columns(
             $cols[0],
             $cols[1]
            )




            ->display_as($cols[0],'Product')
            ->display_as($cols[1],'Rental Plan')


        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }
}
/**
 * Created by CTech.
 * Author: user
 * Date: 1/8/13
 * File: standard_rental_model.php
 */