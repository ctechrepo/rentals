<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class organization_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'organization';
        $fieldprefix = 'organization_';
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
            $fieldprefix.'address_line1' => array(
                'type'=>'VARCHAR',
                'constraint'=>'200',
                'NULL'=> TRUE,
            ),
            $fieldprefix.'address_line2' => array(
                'type'=>'VARCHAR',
                'constraint'=>'200',
                'NULL'=> TRUE,
            ),
            $fieldprefix.'zip'=>array(
                'type'=>'INT',
                'constraint'=>'5',
                'NULL'=> TRUE,
            ),
            $fieldprefix.'state'=>array(
                'type'=>'VARCHAR',
                'constraint'=>'2',
                'NULL'=> TRUE,
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

        $this->crud
            ->columns(
            $cols[0],
            $cols[1]
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'Name')

        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }
}
/**
 * Created by CTech.
 * Author: Shawn Rhoney
 * Date: 1/4/13
 * File: organization_model.php
 * Location: models
 * Module: rental_applications
 */