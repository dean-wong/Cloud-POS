<?php

class Pos extends CI_Controller {

	public function __construct()
        {
                parent::__construct();

                $this->load->model('dinnigtable_model');
                // $this->load->helper('url_helper');
        }

	public function index()
	{
		$data['title'] = 'My Site';
		$this->load->view('templates/header', $data);
		$this->load->view('pos/index', $data);
		$this->load->view('templates/footer', $data);
	}

	public function overview()
	{
		$data['title'] = 'Overview';
        $data['tables'] = $this->dinnigtable_model->get_AllTables();

		$this->load->view('templates/header', $data);
		$this->load->view('pos/overview', $data);
		$this->load->view('templates/footer', $data);
	}

	public function ticket($id)
	{
		$data['title'] = 'Ticket';
		$date['ticket'] = $this->dinnigtable_model->get_Ticket($id);

		$this->load->view('templates/header', $data);
		$this->load->view('pos/ticket', $data);
		$this->load->view('templates/footer', $data);
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
