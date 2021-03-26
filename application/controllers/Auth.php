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

			$token = base64_encode(random_bytes(32));
			$user_token = [
				'email' => $data['email'],
				'token' => $token,
				'date_created' => time()
			];

			$this->db->insert('user_token', $user_token);
			$this->db->insert('user', $data);

			$this->_sendEmail($token, 'verify', $data['email']);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Your account has been created! Please check your email to activate the account.</div>');
			redirect('auth');
		}
	}

	private function _sendEmail($token, $type, $recipient)
	{
		//app pass akun 1 lagi
		// 'smtp_password' => "vgrqrgtmznivkbuc",
		// $config = [
		// 	'protocol' => "smtp",
		// 	'smtp_host' => "smtp.googlemail.com",
		// 	'smtp_user' => "hans.testing07@gmail.com",
		// 	'smtp_password' => 'vdagiuxxrtckmsns',
		// 	'smtp_port' => 465,
		// 	'mailtype' => "html",
		// 	'charset' => "utf-8",
		// 	'newline' => "\r\n",
		// 	'SMTPCrypto' => '',
		// ];

		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'hans.testing07@gmail.com',
			'smtp_pass' => 'vdagiuxxrtckmsns',
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'newline' => "\r\n"
		);

		$this->load->library('email', $config);
		$this->email->initialize($config);

		$this->email->from('hans.testing07@gmail.com', 'Hans Kristian');
		$this->email->to($recipient);

		if ($type == 'verify') {
			$this->email->subject('Account Verification');
			$this->email->message('Click this link to verify your account: <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . $token . '">Activate</a>');
		}

		if ($this->email->send()) {
			echo "EMAIL SENT";
		} else {
			echo $this->email->print_debugger();
			die;
		}
	}

	public function verify()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$result = $this->db->get_where('user', ['email' => $email])->num_rows();
		if ($result > 0) {
			//kalo cek pake token error jadi email aja ya
			$user_token = $this->db->get_where('user_token', ['email' => $email])->row_array();
			if ($user_token) {
				if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
					$this->db->set('is_active', "1");
					$this->db->where('email', $email);
					$this->db->update('user');

					$this->db->delete('user_token', ['email' => $email]);

					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
					Your account has been activated! You can login now!</div>');
					redirect('auth');
				} else {
					$this->db->delete('user_token', ['email' => $email]);
					$this->db->delete('user', ['email' => $email]);
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Account activation failed! Token has expired!</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				Account activation failed! Token invalid!</div>');
				redirect('auth');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
			Account activation failed! Wrong email!</div>');
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
