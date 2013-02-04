<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class rentalplan_to_form_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'rentalplan_to_form';
        $this->set_key('rentalplan_id,form_id');


        $this->fields = array(
            'form_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'rentalplan_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
        );

        $this->crud = new Grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
    }

    public function getForms($rental_plan_id,$section = NULL){
        $found = $this->find_all_by('rentalplan_id',$rental_plan_id);

        $forms = array();
        $count = 0;
        foreach ($found as $item)
        {

            $sections = array();
            $fields = array();

            $section_to = $this->db->dbprefix('form_to_formsection');
            $formsection = $this->db->dbprefix('formsection');
            $field_to = $this->db->dbprefix('formfield_to_formsection');
            $field = $this->db->dbprefix('formfield');

            $this->db->join($section_to,"{$section_to}.formsection_id = {$formsection}.formsection_id ",'left');

            if ($section != NULL)
            {
                $this->db->where('formsection_name',$section);
            }

            $this->db->where('form_id',$item->form_id);
            $this->db->order_by("formsection_order","asc");
            $query = $this->db->get($formsection);

            if ($query->num_rows()>0){
                $sections = $query->result();
            }

            foreach ($sections as $section)
            {
                $this->db->join($field_to,"{$field_to}.formfield_id = {$field}.formfield_id ",'left');
                $this->db->where('formsection_id',$section->formsection_id);
                $this->db->order_by("formfield_order","asc");

                $query = $this->db->get('formfield');

                if ($query->num_rows() > 0){
                   $fields[$section->formsection_id] = $query->result();
                }

            }

            $forms[$count] = array($sections,$fields);

            $count ++;
        }
        return $forms;

    }
}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: rentalplan_to_form_model.php
 * Module: rental_applications
 * Location: models
 **/