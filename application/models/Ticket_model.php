<?php

/**
 * Created by PhpStorm.
 * User: dean
 * Date: 15/8/28
 * Time: 上午11:26
 */
class Ticket_model extends CI_Model
{
    //
    private $_table_id; // 台号，可以不是一一对应关系，不过目前为了简化，一张台一个订单
    private $_table_name;

    private $_items = array();

    // for Database
    private $_id;
    private $_modified_time;
    private $_create_time;
    private $_closing_time;
    private $_delivery_time;
    private $_paid;     // 已付款
    private $_voided;   // 免单
    private $_void_reason;
    private $_void_by_user;
    private $_refunded; // 退回
    private $_settled;  // 已解决
    private $_discount; // 优惠
    private $_total_price;
    private $_paid_amount;
    private $_other_charge;
    private $_number_of_guests;
    private $_ticket_type;
    private $_owner_id;

    private function findShoptable($table_id)
    {
        return $this->db
            ->get_where('shop_table', ['id' => $table_id,])
            ->first_row();
    }

    public function __construct()
    {
        $this->load->database();

        $this->_id = null;
        $this->_modified_time = date('Y-m-d H:i:s', time());
        $this->_create_time = date('Y-m-d H:i:s', time());
        $this->_closing_time = null;
        $this->_delivery_time = null;

        $this->_paid = false;
        $this->_voided = false;
        $this->_void_reason = null;
        $this->_void_by_user = null;
        $this->_refunded = false;
        $this->_settled = false;
        $this->_discount = 0.00;
        $this->_total_price = 0.00;
        $this->_paid_amount = 0.00;
        $this->_other_charge = 0.00;
        $this->_number_of_guests = 0;
        $this->_ticket_type = 0;
        $this->_owner_id = 0;

    }

    public function attachTable($table_id)
    {
        $this->_table_id = $table_id;
        $this->_table_name = $table_id ? $this->findShoptable($table_id)->name : '';
    }

    //__get()方法用来获取私有属性
    public function __get($property_name)
    {
        if (isset($this->$property_name)) {
            return ($this->$property_name);
        }

        return parent::__get($property_name);

    }

    //__set()方法用来设置私有属性
    public function __set($property_name, $value)
    {
        $this->$property_name = $value;
    }

    public function addItem($menu_id)
    {
        // 先查找是否有这个菜，若有就+1，没有就新建一个Item加入列表
        //Todo:
        $menu_count = 1;


        // else insert new item
        $menu = $this->db
            ->get_where('menu_item', ['id' => $menu_id,])
            ->first_row();

        $item = [
            'modified_time' => date('Y-m-d H:i:s', time()),
            'menu_id' => $menu_id,
            'menu_count' => $menu_count,
            'discount' => 0.0,
            'total_price' => $menu->price * $menu_count,

            'name' => $menu->name,
        ];

        // 增加订单总价
        $this->_total_price += ($menu->price * $menu_count);

        return array_push($this->_items, $item);
    }

    public function addItems($menus)
    {
        foreach ($menus as $item) {
            $this->addItem($item);
        }

    }

    /**
     * 从数据中加载一个订单
     * @param $id
     */
    public function load($ticket_id)
    {
        $ticket = $this->db
            ->get_where('ticket', ['id' => $ticket_id,])
            ->first_row();

        $this->_id = $ticket->id;
        $this->_modified_time = $ticket->modified_time;
        $this->_create_time = $ticket->create_time;
        $this->_closing_time = $ticket->closing_time;
        $this->_delivery_time = $ticket->delivery_time;
        $this->_paid = $ticket->paid;
        $this->_voided = $ticket->voided;
        $this->_void_reason = $ticket->void_reason;
        $this->_void_by_user = $ticket->void_by_user;
        $this->_refunded = $ticket->refunded;
        $this->_settled = $ticket->settled;
        $this->_discount = $ticket->discount;
        $this->_total_price = $ticket->total_price;
        $this->_paid_amount = $ticket->paid_amount;
        $this->_other_charge = $ticket->other_charge;
        $this->_number_of_guests = $ticket->number_of_guests;
        $this->_ticket_type = $ticket->ticket_type;
        $this->_owner_id = $ticket->owner_id;

        $this->_table_id = $this->db
            ->get_where('ticket_table', ['ticket_id' => $ticket_id,])
            ->first_row()
            ->table_id;

        $this->_table_name = $this->_table_id ? $this->findShoptable($this->_table_id)->name : '';

        // load ticket_items
        $menu_items = $this->db
            ->get_where('ticket_item', ['ticket_id' => $ticket_id,])
            ->result_array();
        foreach ($menu_items as $item){
            $menu = $this->db
                ->get_where('menu_item', ['id' => $item['menu_id'],])
                ->first_row();

            $t = [
                'id' => $item['id'],
                'modified_time' => $item['modified_time'],
                'menu_id' => $item['menu_id'],
                'menu_count' => $item['menu_count'],
                'discount' => $item['discount'],
                'total_price' => $item['total_price'],
                'ticket_id' => $item['ticket_id'],
                'refunded' => $item['refunded'],
                'settled' => $item['settled'],

                'menu_name' => $menu->name,
                'unit_price' => $menu->price,
            ];

            array_push($this->_items, $t);
        }
    }

    /**
     * 保存到数据库当中
     * @return bool
     */
    public function save()
    {
        $this->_modified_time = date('Y-m-d H:i:s', time());

        // save ticket
        $data = [
            'modified_time' => $this->_modified_time,
            'create_time' => $this->_create_time,
            'closing_time' => $this->_closing_time,
            'delivery_time' => $this->_delivery_time,
            'paid' => $this->_paid,
            'voided' => $this->_voided,
            'void_reason' => $this->_void_reason,
            'void_by_user' => $this->_void_by_user,
            'refunded' => $this->_refunded,
            'settled' => $this->_settled,
            'discount' => $this->_discount,
            'total_price' => $this->_total_price,
            'paid_amount' => $this->_paid_amount,
            'other_charge' => $this->_other_charge,
            'number_of_guests' => $this->_number_of_guests,
            'ticket_type' => $this->_ticket_type,
            'owner_id' => $this->_owner_id,
        ];

        if (!$this->db->insert('ticket', $data)) {
            show_error('保存订单失败!');
            return false;
        }

        $id_new = $this->db->insert_id();

        // save shop_table

        // save ticket_table
        $data = [
            'ticket_id' => $id_new,
            'table_id' => $this->_table_id,
        ];
        if (!$this->db->replace('ticket_table', $data)){
            show_error('更新订单台号失败!');
            return false;
        }
//        show_error(var_dump($r));

        // save ticket_item
        foreach ($this->_items as $ticket_item) {
            $data = [
                'modified_time' => $this->_modified_time,
                'menu_id' => $ticket_item['menu_id'],
                'menu_count' => $ticket_item['menu_count'],
                'discount' => $ticket_item['discount'],
                'total_price' => $ticket_item['total_price'],
                'ticket_id' => $id_new,
                'refunded' => false,
                'settled' => false,
            ];
            if (!$this->db->insert('ticket_item', $data)){
                show_error('保存订单菜谱失败！');
                return false;
            }

        }
        return true;

    }

}
