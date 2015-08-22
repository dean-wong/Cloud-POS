<?php

class Pos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dinningtable_model');
        // $this->load->helper('url_helper');
    }

    public function index()
    {
        $data['title'] = 'My Site';
        $this->load->view('templates/header', $data);
        $this->load->view('pos/index', $data);
        $this->load->view('templates/footer', $data);
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
        $this->load->view('templates/footer', $data);
    }

    /*
     * 查看订单详情
     * id = ticket_id
     */
    public function ticket($id)
    {
        $data['title'] = 'Ticket';
        $data['ticket'] = $this->dinningtable_model->get_Ticket($id);

        $this->load->view('templates/header', $data);
        $this->load->view('pos/ticket', $data);
        $this->load->view('templates/footer', $data);
    }

    public function create()
    {
        $data['title'] = 'Create a ticket item';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'text', 'required');

        if ($this->form_validation->run() === FALSE)
        {
//            $this->load->view('templates/header', $data);
//            $this->load->view('pos/create');
//            $this->load->view('templates/footer');

        }
        else
        {
            // $this->dinningtable_model->set_news();
            // $this->load->view('news/success');
        }
    }

    public function order()
    {
        $data['title'] = 'Order';
        $this->load->view('templates/header', $data);
        $this->load->view('pos/order', $data);
        $this->load->view('templates/footer', $data);
    }

    public function cashier()
    {
        $data['title'] = 'Cashier';
        $this->load->view('templates/header', $data);
        $this->load->view('pos/cashier', $data);
        $this->load->view('templates/footer', $data);
    }
}
