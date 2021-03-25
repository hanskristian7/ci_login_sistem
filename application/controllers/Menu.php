<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
	public function __construct()
	{
		//panggil dulu method constructor pada CI
		parent::__construct();

		is_logged_in();

		$this->load->library('form_validation');
		$this->load->model('Menu_model', 'menu');
	}

	public function index()
	{
		$data['title'] = "Menu Management";

		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['menu'] = $this->db->get('user_menu')->result_array();

		$this->form_validation->set_rules('menu', 'Menu', 'required');

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header.php', $data);
			$this->load->view('templates/sidebar.php', $data);
			$this->load->view('templates/topbar.php', $data);
			$this->load->view('menu/index', $data);
			$this->load->view('templates/footer.php');
		} else {
			$this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			New menu has successfully created!</div>');
			redirect('menu');
		}
	}

	public function edit($id)
	{
		$data['title'] = "Edit Menu";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

		$this->form_validation->set_rules('menu', 'Menu', 'required|trim');
		if ($this->form_validation->run() == false) {
			$data['menu'] = $this->db->get_where('user_menu', array('id' => $id))->result_array();
			$this->load->view('templates/header.php', $data);
			$this->load->view('templates/sidebar.php', $data);
			$this->load->view('templates/topbar.php', $data);
			$this->load->view('menu/edit', $data);
			$this->load->view('templates/footer.php');
		} else {
			$menu = [
				'id' => $this->input->post('id'),
				'menu' => $this->input->post('menu')
			];
			$this->db->replace('user_menu', $menu);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Menu has been updated!</div>');
			redirect('menu');
		}
	}

	public function delete($id)
	{
		$this->menu->delete('user_menu', $id);

		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
		Menu has been successfully deleted!</div>');
		redirect('menu');
	}

	public function submenu()
	{
		$data['title'] = "Sub Menu Management";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['menu'] = $this->db->get('user_menu')->result_array();

		$data['subMenu'] = $this->menu->getSubMenu();

		$this->form_validation->set_rules('menu_id', 'Menu', 'required');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('icon', 'Icon', 'required');

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header.php', $data);
			$this->load->view('templates/sidebar.php', $data);
			$this->load->view('templates/topbar.php', $data);
			$this->load->view('menu/submenu', $data);
			$this->load->view('templates/footer.php');
		} else {
			$data = [
				'menu_id' => $this->input->post('menu_id'),
				'title' => $this->input->post('title'),
				'url' => $this->input->post('url'),
				'icon' => $this->input->post('icon'),
				'is_active' => $this->input->post('is_active'),
			];

			$this->db->insert('user_sub_menu', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			New Submenu has successfully created!</div>');
			redirect('menu/submenu');
		}
	}

	public function editSubmenu($id)
	{
		$data['title'] = "Edit Submenu";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['menu'] = $this->db->get('user_menu')->result_array();

		$this->form_validation->set_rules('menu_id', 'Menu', 'required');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('icon', 'Icon', 'required');
		if ($this->form_validation->run() == false) {
			$data['subMenu'] = $this->db->get_where('user_sub_menu', array('id' => $id))->result_array();
			// var_dump($this->input->post());
			// die;
			$this->load->view('templates/header.php', $data);
			$this->load->view('templates/sidebar.php', $data);
			$this->load->view('templates/topbar.php', $data);
			$this->load->view('menu/editSubmenu', $data);
			$this->load->view('templates/footer.php');
		} else {
			$subMenu = [
				'id' => $this->input->post('id'),
				'menu_id' => $this->input->post('menu_id'),
				'title' => $this->input->post('title'),
				'url' => $this->input->post('url'),
				'icon' => $this->input->post('icon'),
				'is_active' => $this->input->post('is_active')
			];
			$this->db->replace('user_sub_menu', $subMenu);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Submenu has been updated!</div>');
			redirect('menu/submenu');
		}
	}

	public function deleteSubmenu($id)
	{
		$this->menu->delete('user_sub_menu', $id);

		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
		Submenu has successfully deleted!</div>');

		redirect('menu/submenu');
	}
}
