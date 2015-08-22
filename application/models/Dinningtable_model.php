<?php

class Dinningtable_model extends CI_Model {
    
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

            $ticket_id = $this->findTicketIDByTable($row['id']);
            $is_opened = !is_null($ticket_id);

            if ($filter == 'opened' && $is_opened){
                $items[] = [
                    'name' => $row['name'], 
                    'capacity' => $row['capacity'],
                    'ticket' =>  $ticket_id, 
                    'total_price' => $this->findTicket($ticket_id)['total_price'],
                    'create_time' => $this->findTicket($ticket_id)['create_time'],
                ];
            }
            else if ($filter == 'closed' && (!$is_opened)){
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
                    'total_price' => $is_opened?$this->findTicket($ticket_id)['total_price']:0.00,
                    'create_time' => $is_opened?$this->findTicket($ticket_id)['create_time']:null,
                ];
            }
        }
        
        return $items;
    }

    /**
     * @param $id
     * @return array for display
     */
    public function get_Ticket($id)
    {
        $result = array();
        $ticket = $this->findTicket($id);

        if (!empty($ticket)){
            // 订单价格
            $result['total_price'] = $ticket['total_price'];

            //桌台名称
            $table_id = $this->findTableIDByTicket($ticket['id']);
            $result['table_name'] = $this->findTable($table_id)['name'];

            //开台时间
            $result['create_time'] = $ticket['create_time'];

            // 顾客人数
            $result['number_of_guests'] = $ticket['number_of_guests'];

        }


        return $result;
    }

    /**
     * @param $ticket_id
     * @return Ticket object
     */
    private function findTicket($ticket_id)
    {
        $query = $this->db->get_where('ticket', array(
            'id' => $ticket_id
        ));

        return $query->row_array();
    }   


    /*
     * Param table_id
     */
    private function findTable($table_id) {
        $query = $this->db->get_where('shop_table', array(
            'id' => $table_id
        ));

        return $query->row_array();
    }

    /**
     * @param $table_id
     * @return Ticket id.
     */
    private function findTicketIDByTable($table_id) {
        $query = $this->db->get_where('ticket_table', array(
            'table_id' => $table_id
        ));
        if ($query->num_rows() < 1) 
            return null; //invalid id

        $row = $query->first_row();
        
        return $row->ticket_id;
    }

    /*
     * param Ticket id
     * return Table id
    */
    private function findTableIDByTicket($ticket_id) {
        $query = $this->db->get_where('ticket_table', array(
            'ticket_id' => $ticket_id
        ));
        if ($query->num_rows() < 1) 
            return null; //invalid id

        $row = $query->first_row();
        
        return $row->table_id;
    }

}
