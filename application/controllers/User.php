<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
	}

	public function index()
	{
		$data['title'] = "My Profile";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/sidebar.php', $data);
		$this->load->view('templates/topbar.php', $data);
		$this->load->view('user/index', $data);
		$this->load->view('templates/footer.php');
		// echo "Halo " . $data['user']['name'];
	}

	public function editProfile()
	{
		$data['title'] = "Edit Profile";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

		$this->form_validation->set_rules('name', 'Full Name', 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header.php', $data);
			$this->load->view('templates/sidebar.php', $data);
			$this->load->view('templates/topbar.php', $data);
			$this->load->view('user/edit', $data);
			$this->load->view('templates/footer.php');
		} else {
			$email = $this->input->post('email');
			$name = $this->input->post('name');

			$upload_image = $_FILES['image']['name'];
			// var_dump($upload_image);
			// die;

			if ($upload_image) {
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = '2048';
				$config['upload_path'] = './assets/img/profile';
				$this->load->library('upload', $config);

				if ($this->upload->do_upload('image')) {
					$old_image = $data['user']['image'];
					if ($old_image != "noimage.png") {
						unlink(FCPATH . 'assets/img/profile/' . $old_image);
					}

					$new_image = $this->upload->data('file_name');
					$this->db->set('image', $new_image);
				} else {
					echo $this->upload->display_errors();
				}
			}

			$this->db->set('name', $name);
			$this->db->where('email', $email);
			$this->db->update('user');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Profile has been updated!</div>');

			redirect('user');
		}
	}
}
