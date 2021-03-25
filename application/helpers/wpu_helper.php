<?php

function is_logged_in()
{
	//harus get instance dari CI dulu buat pengganti CI
	$ci = get_instance();
	if (!$ci->session->userdata('email')) {
		redirect('auth');
	} else {
		$role_id = $ci->session->userdata('role_id');
		$menu = $ci->uri->segment(1);

		$menu_id = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
		$userAccess = $ci->db->get_where('user_access_menu', [
			'role_id' => $role_id,
			'menu_id' => $menu_id['id']
		]);


		if ($userAccess->num_rows() < 1) {
			redirect('auth/blocked');
		}
	}
}
