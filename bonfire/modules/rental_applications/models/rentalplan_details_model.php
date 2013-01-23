<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class rentalplan_details_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'rentalplan_details';
        $fieldprefix = 'rentalplan_details_';
        $this->set_key($fieldprefix.'id');

        $this->fields = array(
            $fieldprefix.'id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
                'auto_increment'=>TRUE,
            ),
            'rentalplan_id' => array(
                'type'=>'INT',
                'constraint'=>'50',
            ),
            $fieldprefix.'dataname'=> array(
                'type'=>'VARCHAR',
                'constraint'=>'50',
            ),
            $fieldprefix.'data' => array(
                'type'=>'TEXT',
                'NULL'=>TRUE,
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
        $cols = array_keys($this->fields);

        $table1 = $this->db->dbprefix('rentalplan');

        $this->crud
            ->set_relation($cols[1],$table1,'rentalplan_name')
            ->set_subject('Plan Detail')
            ->columns(
            $cols[0],
            $cols[1],
            $cols[2]
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'Rental Plan')
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
 * File: rentalplan_details_model.php
 * Module: rental_applications
 * Location: models
 **/
