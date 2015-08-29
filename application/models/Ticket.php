<?php

/**
 * Created by PhpStorm.
 * User: dean
 * Date: 15/8/28
 * Time: 上午11:26
 */
class Ticket extends CI_Model
{
    //
    private $_table_id; // 台号，可以不是一一对应关系，不过目前为了简化，一张台一个订单
    private $_table_name;

    private $_items = array();

    // for Database
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

    public function __construct($table_id = 0)
    {
        $this->load->database();

        $this->_create_time = date('Y-m-d H:i:s', time());
        $this->_modified_time = date('Y-m-d H:i:s', time());

        $this->_paid = false;
        $this->_discount = 0.00;
        $this->_total_price = 0.00;
        $this->_table_id = $table_id;
        $this->_table_name = $table_id ? $this->db
            ->get_where('shop_table', ['id' => $table_id,])
            ->first_row()
            ->name : '';

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

        if (!$this->db->insert('ticket', $data))
            return false;

        $id_new = $this->db->insert_id();

        // save shop_table

        // save ticket_table
        $data = [
            'ticket_id' => $id_new,
            'table_id' => $this->_table_id,
        ];
        $r = $this->db->replace('ticket_table', $data);
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
            $this->db->insert('ticket_item', $data);

        }


    }

}