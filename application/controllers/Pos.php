<?php

class Pos extends CI_Controller {

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

    /**
     * @param $table_id
     */
    public function create($table_id = 0)
    {
        $data['title'] = 'Create a ticket item';
        $data['menu_categories'] = $this->dinningtable_model->get_AllMenuCategory();
        $data['new_ticket'] = $this->dinningtable_model->create_Ticket($table_id);

        $this->form_validation->set_rules('menu_count', '点菜', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('pos/create', $data);
            $this->load->view('templates/footer');

        }
        else
        {
            $items = $this->input->post('menu_items');  // 获得选择的菜单ID列表
            $data['new_ticket']->addItems($items);
            $data['new_ticket']->save();

            redirect('pos/overview/all');

        }
    }

    public function order()
    {
        $data['title'] = 'Order';
        $this->load->view('templates/header', $data);
        $this->load->view('pos/order', $data);
        $this->load->view('templates/footer');
    }

    public function cashier()
    {
        $data['title'] = 'Cashier';
        $this->load->view('templates/header', $data);
        $this->load->view('pos/cashier', $data);
        $this->load->view('templates/footer');
    }
}
