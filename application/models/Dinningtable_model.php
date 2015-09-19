<?php

require_once('Ticket_model.php');

class Dinningtable_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

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

            $item = [
                'id' => $row['id'],
                'name' => $row['name'],
                'capacity' => $row['capacity'],
                'ticket' => $ticket_id,
                'total_price' => $is_opened ? $this->findTicket($ticket_id)['total_price'] : 0.00,
                'create_time' => $is_opened ? $this->findTicket($ticket_id)['create_time'] : null,
                'paid' => $is_opened ? $this->findTicket($ticket_id)['paid']:false,
            ];

            if ($filter == 'opened' && $is_opened) {
                $items[] = $item;
            } else if ($filter == 'closed' && (!$is_opened)) {
                $items[] = $item;
            } else if ($filter == 'all') {
                $items[] = $item;
            }
        }

        return $items;
    }

    public function get_AllMenuCategory()
    {
        $query = $this->db->get('menu_category');
        $items = array();

        foreach ($query->result_array() as $row) {

            if ($row['visible']){
                $menu_groups = $this->findMenuGroups($row['id']);
                $item_menu = array();

                foreach ($menu_groups as $group){

                    if ($group['visible']){
                        $item_menu[] = [
                            'id' => $group['id'],
                            'name' => $group['name'],
                            'menus' => $this->findMenuItems($group['id']),
                        ];
                    }
                }

                $items[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'menu_groups' => $item_menu,
                ];
            }

        }

        return $items;
    }

    public function get_AllPayment()
    {
        return $this->db->get('payment')
            ->result();
    }

    /**
     * @param $id
     * @return array for display
     */
    public function get_Ticket($ticket_id)
    {
        $ticket = new Ticket_model();
        $ticket->load($ticket_id);

        return $ticket;
    }

    /**
     * @param $ticket_id
     * @param $table_id
     */
    public function change_TicketTable($ticket_id, $table_id)
    {
        $data = [
            'ticket_id' => $ticket_id,
            'table_id' => $table_id,
        ];

        $this->db
            ->where('ticket_id', $ticket_id)
            ->update('ticket_table', $data);
    }

    /**
     * @param $ticket_id
     * 清台
     */
    public function clearance($ticket_id)
    {
        $ticket = $this->get_Ticket($ticket_id);

        return $ticket->clearance();
    }

    public function create_Ticket($table_id)
    {
        $ticket = new Ticket_model();
        $ticket->attachTable($table_id);

        return $ticket;
    }

    public function checkout ($ticket /*Ticket_model*/, $payments /*array*/, $paid_amount /*number*/)
    {
       return $ticket->checkout ($payments, $paid_amount);
    }


    /**
     * @param $category_id
     * @return Menu group array
     */
    private function findMenuGroups($category_id)
    {
        $query = $this->db->get_where('menu_group', array(
            'category_id' => $category_id,
        ));
        return $query->result_array();
    }

    /**
     * @param $group_id
     * @return Menu item array
     */
    private function findMenuItems($group_id)
    {
        $query = $this->db->get_where('menu_item', array(
            'group_id' => $group_id,
        ));

        return $query->result_array();
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


    /**
     * @param $table_id
     * @return Ticket id.
     * 订单ID有多个，只返回没有清台的订单ID
     */
    private function findTicketIDByTable($table_id)
    {
        $query = $this->db->get_where('ticket_table', array(
            'table_id' => $table_id
        ));
        if ($query->num_rows() < 1)
            return null; //invalid id

        foreach ($query->result() as $row)
        {
            $t = $this->findTicket ($row->ticket_id);
            
            if (!is_null($t) && empty($t['settled'])){
                return $row->ticket_id;
            }
        }

        return null;

    }

}
