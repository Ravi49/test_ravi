<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('book_model');
		$this->load->library('Pdf');
	}

	public function index()
	{

		$this->load->view('book');
	}

	public function submit()
	{
		$result = $this->book_model->saveData($_POST);
		return true;
	}
}
?>