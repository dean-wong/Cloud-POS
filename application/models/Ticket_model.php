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
    private $_additional_items = array(); // 用于加菜的临时缓存数组

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
        parent::__construct();

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

    /**
     * @param $menu_id
     * @return int
     */
    private function addItem($menu_id)
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

    private function pushItem($menu_id)
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

        return array_push($this->_additional_items, $item);
    }

    /**
     * @param $menus
     */
    public function addItems($menus)
    {
        // 如果当前有数据，说明是加菜，否则就是开台
        if (count($this->_items) > 0)
        {
            // 加菜
            $this->_additional_items = array(); // 每次都会清空

            foreach ($menus as $item) {
                $this->pushItem($item);
            }
        }
        else{
            foreach ($menus as $item) {
                $this->addItem($item);
            }
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

    private function getRowdata()
    {
        $data = [
            'id' => $this->_id, // 插入的时候，这个是NULL
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

        return $data;
    }

    public function update()
    {
        $this->_modified_time = date('Y-m-d H:i:s', time());
        $data = $this->getRowdata();
        if (!$this->db->replace('ticket', $data)){
            show_error('更新订单支付数据失败!');
            return false;
        }

        foreach ($this->_additional_items as $ticket_item) {
            $data = [
                'modified_time' => $this->_modified_time,
                'menu_id' => $ticket_item['menu_id'],
                'menu_count' => $ticket_item['menu_count'],
                'discount' => $ticket_item['discount'],
                'total_price' => $ticket_item['total_price'],
                'ticket_id' => $this->_id,
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

    /**
     * 插入一个新的订单到数据库当中
     * @return bool
     */
    public function insert()
    {
        // insert ticket
        $this->_modified_time = date('Y-m-d H:i:s', time());
        $data = $this->getRowdata();

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

    /**
     * @param $payments
     * @param $paid_amount
     * @return bool
     */
    public function checkout($payments, $paid_amount)
    {
        // 由于获取的数据和数据库里面的数据顺序一样，所以ID就从1开始排列
        $payment_id = 1;
        $paid_count = 0.00;
        $the_cash = 0.00;
        foreach ($payments as $pay) {
            $data = [
                'ticket_id' => $this->_id,
                'payment_id' => $payment_id,
                'payout' => number_format($pay, 2),
            ];

            if (!$this->db->insert('ticket_payment', $data)){
                show_error('保存支付方式列表失败！');
                return false;
            }

            if ($payment_id === 1){
                $the_cash = $pay;
            }
            $paid_count += $pay;
            $payment_id++;
        }

        if ($paid_count > $paid_amount){
            // 有找零，需要修改现金的支付金额数据，因为其他方式都是不找零的
            $the_cash -= ($paid_count - $paid_amount);
            $data = [
                //'ticket_id' => $this->_id,
                //'payment_id' => 1,
                'payout' => ($the_cash > 0)?$the_cash:0, //避免保存负数
            ];

            $this->db->where('ticket_id', $this->_id);
            $this->db->where('payment_id', 1);
            if (!$this->db->update('ticket_payment', $data)){
                show_error('更新现金数据失败!');
                return false;
            }
        }

        // 更新订单的信息
        $this->_paid = true;
        $this->_paid_amount = $paid_amount;
        $this->_discount = $this->_total_price - $paid_amount; // 折扣在外部进行了重新设定
        $this->_modified_time = date('Y-m-d H:i:s', time());
        $this->_closing_time = date('Y-m-d H:i:s', time());

        $data = $this->getRowdata();
        if (!$this->db->replace('ticket', $data)){
            show_error('更新订单支付数据失败!');
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function clearance()
    {
        if (!$this->_paid){
            show_error('没有埋单就清台!');
            return false;
        }

        $this->_settled = true;
        $this->_modified_time = date('Y-m-d H:i:s', time());
        $this->_closing_time = date('Y-m-d H:i:s', time());

        $data = $this->getRowdata();
        if (!$this->db->replace('ticket', $data)){
            show_error('更新订单数据失败!');
            return false;
        }

        return true;

    }

}
