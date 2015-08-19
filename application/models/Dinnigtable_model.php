<?php

class Dinnigtable_model extends CI_Model {
    
    public function __construct() 
    {
        $this->load->database();
    }
    
    /*
     * filter = all, opened, closed.
     */
    public function get_AllTables($filter = 'all') 
    {
        $query = $this->db->get('shop_table');

        $items = array();
        foreach ($query->result_array() as $row) {

            $ticket_id = $this->findTicketByTable($row['id']);
            $is_opend = !is_null($ticket_id);

            if ($filter == 'opened' && $is_opend){
                $items[] = [
                    'name' => $row['name'], 
                    'capacity' => $row['capacity'],
                    'ticket' =>  $ticket_id, 
                    'total_price' => $this->findTicket($ticket_id)['total_price'],
                    'create_time' => $this->findTicket($ticket_id)['create_time'],
                ];
            }
            else if ($filter == 'closed' && (!$is_opend)){
                $items[] = [
                    'name' => $row['name'], 
                    'capacity' => $row['capacity'],
                    'ticket' =>  $ticket_id, 
                    'total_price' => 0.00,
                    'create_time' => null,
                ];

            }
            else if ($filter == 'all'){
                
                $items[] = [
                    'name' => $row['name'], 
                    'capacity' => $row['capacity'],
                    'ticket' =>  $ticket_id, 
                    'total_price' => $is_opend?$this->findTicket($ticket_id)['total_price']:0.00,
                    'create_time' => $is_opend?$this->findTicket($ticket_id)['create_time']:null,
                ];
            }
        }
        
        return $items;
    }

    public function get_Ticket($id)
    {
        
    }   

    private function findTicket($id)
    {
        $query = $this->db->get_where('ticket', array(
            'id' => $id
        ));

        return $query->row_array();
    }   


    private function findTable($id) {
        $query = $this->db->get_where('shop_table', array(
            'id' => $id
        ));

        return $query->row_array();
    }

    /*
     * param Table id
     * return Ticket id
    */
    private function findTicketByTable($table_id) {
        $query = $this->db->get_where('ticket_table', array(
            'table_id' => $table_id
        ));
        if ($query->num_rows() < 1) 
            return null; //invalid id

        $row = $query->first_row();
        
        return $row->ticket_id;
    }
}
