<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		//panggil dulu method constructor pada CI
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index()
	{
		if ($this->session->userdata('email')) {
			redirect('user');
		}
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'WPU Login';
			$this->load->view("templates/auth_header", $data);
			$this->load->view('auth/login');
			$this->load->view("templates/auth_footer");
		} else {
			$this->_login();
		}
	}

	private function _login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		if ($user) {
			if ($user['is_active'] == 1) {
				//cek password kalo aktif
				if (password_verify($password, $user['password'])) {
					$data = [
						'email' => $user['email'],
						'role_id' => $user['role_id']
					];
					$this->session->set_userdata($data);

					// var_dump($user);
					// die;
					if ($user['role_id'] == 1) {
						redirect('admin');
					} else if ($user['role_id'] == 2) {
						redirect('user');
					}
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Wrong password.</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				This email has not been activated.</div>');
				redirect('auth');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
			Email is not registered Please try again.</div>');
			redirect('auth');
		}
	}

	public function registration()
	{
		if ($this->session->userdata('email')) {
			redirect('user');
		}
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
			'is_unique' => 'Oops, the email has been registered!'
		]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
			'matches' => 'password dont match!',
			'min_length' => 'Password too short!'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'WPU Registration';
			$this->load->view("templates/auth_header", $data);
			$this->load->view('auth/registration');
			$this->load->view("templates/auth_footer");
		} else {
			// role_id = 2 = member, 1 = admin
			// is_active = 1 = aktif, 0 = tidak aktif
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => htmlspecialchars($this->input->post('email', true)),
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'date_created' => time()
			];

			$this->db->insert('user', $data);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Your account has been created! Please Login.</div>');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
		You have been logged out!</div>');
		redirect('auth');
	}

	public function blocked()
	{
		$data['title'] = "My Profile";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/sidebar.php', $data);
		$this->load->view('templates/topbar.php', $data);
		$this->load->view('auth/blocked');
		$this->load->view('templates/footer.php');
	}
}
