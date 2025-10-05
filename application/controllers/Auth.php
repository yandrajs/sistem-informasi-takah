<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	//Fungsi untuk menampilkan halaman login
	public function index()
	{
		if ($this->session->userdata('username')) {
			redirect('user');
		}

		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Login Page | INDEXING';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
		} else {
			// validasinya sukses
			$this->_login();
		}
	}

	//Fungsi untuk melakukan proses login
	private function _login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$user = $this->db->get_where('user', ['username' => $username])->row_array();

		if ($user) {
			//Jika user telah terdaftar maka validasi password
			if (password_verify($password, $user['password'])) {
				$data = [
					'username' => $user['username'],
					'role_id' => $user['role_id'],
				];
				$this->session->set_userdata($data);

				if ($user['role_id'] == 1) {
					redirect('admin');
				} else {
					redirect('user');
				}
			} else {
				//Jika password salah maka akan menampilkan pesan error/gagal
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  										 Password salah!</div>');
				redirect('auth');
			}
		} else {
			//Jika passwors benar maka akan menampilkan pesan berhasil
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  										 Username tidak terdaftar!</div>');
			redirect('auth');
		}
	}

	//Fungsi untuk menjalankan proses logout logout
	public function logout()
	{
		//Menghapus session
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  										 Anda sudah logout</div>');
		redirect('auth');
	}

	//Fungsi untuk memblokir akses pengguna
	public function blocked()
	{
		$this->load->view('auth/blocked');
	}
}
