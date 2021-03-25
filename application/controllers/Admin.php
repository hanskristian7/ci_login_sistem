<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
	}

	public function index()
	{
		$data['title'] = "Dashboard";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/sidebar.php', $data);
		$this->load->view('templates/topbar.php', $data);
		$this->load->view('admin/index.php', $data);
		$this->load->view('templates/footer.php');
		// echo "Halo " . $data['user']['name'];
	}

	public function role()
	{
		$data['title'] = "Role";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['role'] = $this->db->get('user_role')->result_array();

		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/sidebar.php', $data);
		$this->load->view('templates/topbar.php', $data);
		$this->load->view('admin/role.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function roleAccess($id)
	{
		$data['title'] = "Role Access";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['role'] = $this->db->get_where('user_role', ['id' => $id])->row_array();

		$this->db->where('id!=', 1);
		$data['menu'] = $this->db->get('user_menu')->result_array();

		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/sidebar.php', $data);
		$this->load->view('templates/topbar.php', $data);
		$this->load->view('admin/roleaccess.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function changeAccess()
	{
		//get data dari ajax ini
		$menu_id = $this->input->post('menuId');
		$role_id = $this->input->post('roleId');

		$data = [
			'role_id' => $role_id,
			'menu_id' => $menu_id
		];

		$result = $this->db->get_where('user_access_menu', $data);
		if ($result->num_rows() < 1) {
			$this->db->insert('user_access_menu', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Access has been granted!</div>');
		} else {
			$this->db->delete('user_access_menu', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Access has been removed!</div>');
		}
	}
}
