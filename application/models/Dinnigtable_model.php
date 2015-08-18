<?php

class Dinnigtable_model extends CI_Model {
    
    public function __construct() 
    {
        $this->load->database();
    }
    
    public function get_AllTables() 
    {
        $query = $this->db->get('shop_table');

        $items = array();
        foreach ($query->result_array() as $row) {
            $items[] = [
                'name' => $row['name'], 
                'capacity' => $row['capacity'],
                'ticket' => $this->get_TicketByTable($row['id']) , 
            ];
        }
        
        return $items;
    }

    public function get_Ticket($id)
    {
        $query = $this->db->get_where('ticket', array(
            'id' => $id
        ));

        return $query->row_array();
    }   


    private function get_Table($id) {
        $query = $this->db->get_where('shop_table', array(
            'id' => $id
        ));

        return $query->row_array();
    }

    /*
     * param Table id
     * return Ticket id
    */
    private function get_TicketByTable($table_id) {
        $query = $this->db->get_where('ticket_table', array(
            'table_id' => $table_id
        ));
        if ($query->num_rows() < 1) 
            return null; //invalid id

        $row = $query->first_row();
        
        return $row->ticket_id;
    }
}
