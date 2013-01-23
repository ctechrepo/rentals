<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class contact_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'contact';
        $fieldprefix = 'contact_';
        $this->set_key($fieldprefix.'id');

        $this->fields = array(
            $fieldprefix.'id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
                'auto_increment'=>TRUE,
            ),
            $fieldprefix.'firstname' => array(
                'type'=>'VARCHAR',
                'constraint'=>'50',
            ),
            $fieldprefix.'lastname' => array(
                'type'=>'VARCHAR',
                'constraint'=>'50',
            ),
            $fieldprefix.'email' => array(
                'type'=>'VARCHAR',
                'constraint'=>'150',
            ),
            $fieldprefix.'phone'=>array(
                'type'=>'VARCHAR',
                'constraint'=>'30',
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

        $relation_table1 = $this->db->dbprefix('organization_to_contact');
        $selection_table1 = $this->db->dbprefix('organization');

        $this->crud
            ->set_subject('Contact')
            ->set_relation_n_n('Organizations',$relation_table1,$selection_table1,$cols[0],'organization_id','organization_name')
            ->columns(
            $cols[0],
            $cols[1],
            $cols[2],
            $cols[3],
            $cols[4],
            'Organizations'
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'First Name')
            ->display_as($cols[2],'Last Name')

            ->fields($cols[1],$cols[2],$cols[3],$cols[4],'Organizations');

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
 * File: contact_model.php
 * Module: rental_applications
 * Location: models
 **/