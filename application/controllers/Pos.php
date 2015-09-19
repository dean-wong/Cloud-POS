<?php

class Pos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

//        $this->load->library('table'); //HTML 表格类

        $this->load->model('dinningtable_model');
    }

    public function index()
    {
        $data['title'] = 'My Site';
        $this->load->view('templates/header', $data);
        $this->load->view('pos/index', $data);
        $this->load->view('templates/footer');
    }

    /*
     * filter = all, opened, closed.
     */
    public function overview($filter = 'all')
    {
        $data['title'] = 'Overview';
        $data['filter'] = $filter;
        $data['tables'] = $this->dinningtable_model->get_AllTables($filter);

        $this->load->view('templates/header', $data);
        $this->load->view('pos/overview', $data);
        $this->load->view('templates/footer');
    }

    /*
     * 查看订单详情
     * id = ticket_id
     */
    public function ticket($id)
    {
        $data['title'] = 'Ticket';
        $data['ticket'] = $this->dinningtable_model->get_Ticket($id);

        // 未开台的桌子，给转台功能使用
        $data['tables_closed'] = $this->dinningtable_model->get_AllTables('closed');

        $this->load->view('templates/header', $data);
        $this->load->view('pos/ticket', $data);
        $this->load->view('templates/footer');
    }

    public function change_table($ticket_id, $table_id)
    {
        $this->dinningtable_model->change_TicketTable($ticket_id, $table_id);
        redirect('pos/overview/all');

    }

    /** 创建订单（开台）
     * @param $table_id
     */
    public function create($table_id = 0)
    {
        $data['title'] = 'Create';
        $data['menu_categories'] = $this->dinningtable_model->get_AllMenuCategory();
        $data['cur_ticket'] = $this->dinningtable_model->create_Ticket($table_id);

        $this->form_validation->set_rules('menu_count', '点菜', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('pos/create', $data);
            $this->load->view('templates/footer');

        } else {
            $items = $this->input->post('menu_items');  // 获得选择的菜单ID列表
            $data['cur_ticket']->addItems($items);
            $data['cur_ticket']->insert();

            redirect('pos/overview/all');
        }
    }

    /** 修改订单（加菜）
     * @param $ticket_id
     */
    public function order($ticket_id)
    {
        $data['title'] = 'Order';
        $data['menu_categories'] = $this->dinningtable_model->get_AllMenuCategory();
        $data['cur_ticket'] = $this->dinningtable_model->get_Ticket($ticket_id);

        $this->form_validation->set_rules('menu_count', '点菜', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('pos/order', $data);
            $this->load->view('templates/footer');

        } else {
            $items = $this->input->post('menu_items');  // 获得选择的菜单ID列表
            $data['cur_ticket']->addItems($items);
            $data['cur_ticket']->update();

            redirect('pos/overview/all');
        }

    }

    /**
     * 结账
     * @param $ticket_id
     */
    public function cashier($ticket_id)
    {
        $data['title'] = 'Cashier';
        $data['ticket'] = $this->dinningtable_model->get_Ticket($ticket_id);
        $data['payments'] = $this->dinningtable_model->get_AllPayment();

        //
        $this->form_validation->set_rules('input-paid-amount', '付款', 'required');


        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('pos/cashier', $data);
            $this->load->view('templates/footer');
        } else {
            $final_paid = $this->input->post('input-final-amount'); //最终用户该付款
            $the_payments = $this->input->post('radio_payments'); // 不同付款方式的组合

            $this->dinningtable_model->checkout($data['ticket'], $the_payments, $final_paid);

            redirect('pos/overview/all');

        }
    }

    public function clearance($ticket_id)
    {
        $this->dinningtable_model->clearance($ticket_id);
        redirect('pos/overview/all');
    }

}
