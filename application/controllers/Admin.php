<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->dbforge();
		is_logged_in();
		$this->load->model('Admin_model', 'admin');
	}

	//Fungsi untuk menampilkan dashboard
	public function index()
	{
		$data['title'] = 'Dashboard';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$data['akun'] =  $this->db->count_all('user'); //Hitung total akun
		$data['role'] =  $this->db->count_all('user_role'); //Hitung total role
		$data['data'] =  $this->db->count_all('instansi'); //Hitung total data takah

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menampilkan halaman kelola denah
	public function manageDenah()
	{
		$data['title'] = 'Kelola Denah';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		//Mengambil data keyword pencarian
		$data['keywordDenah'] = $this->session->userdata('keywordDenah') ?? '';

		//Konfigurasi query untuk pencarian denah
		$this->db->like('nama', $data['keywordDenah']);
		$this->db->from('denah');

		//Konfigurasi pagination
		$config['base_url'] = 'http://localhost/indexing/admin/managedenah';
		$config['total_rows'] = $this->db->count_all_results();
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 3;
		$config['num_links'] = 5;

		//Inisialisasi pagination
		$this->pagination->initialize($config);

		//Ambil data denah berdasarkan pagination
		$data['start'] = $this->uri->segment(3);
		$data['denah'] = $this->admin->getDenah($config['per_page'], $data['start'], $data['keywordDenah']);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/manage-denah', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mencari denah 
	public function searchDenah()
	{
		$keywordDenah = $this->input->post('keywordDenah');
		$this->session->set_userdata('keywordDenah', $keywordDenah);
		redirect('admin/managedenah');
	}

	//Fungsi untuk menambahkan denah
	public function addDenah()
	{
		//Validasi input
		$this->form_validation->set_rules('nama', 'Nama', 'required');

		if ($this->form_validation->run() == false) {
			//Jika validasi gagal maka tampilkan error
			$this->session->set_flashdata('error', validation_errors());
			redirect('admin/managedenah');
		} else {
			//Konfigurasi upload
			$config['upload_path']      = './assets/img/denah/';
			$config['allowed_types']    = 'gif|jpg|png|jpeg';
			$config['max_size']         = 2048;
			$config['file_name']        = uniqid();

			$this->load->library('upload', $config);

			//Jika upload gagal
			if (!$this->upload->do_upload('gambar')) {
				$error = $this->upload->display_errors();
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $error . '</div>');
				redirect('admin/managedenah');
			} else {
				//Jika upload berhasil, maka data disimpan ke dalam database
				$uploaded_data = $this->upload->data();
				$data = [
					'nama' => $this->input->post('nama'),
					'gambar' => $uploaded_data['file_name']
				];

				$this->db->insert('denah', $data);
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Denah sudah ditambahkan!</div>');
				redirect('admin/managedenah');
			}
		}
	}

	//Fungsi untuk menghapus denah
	public function deleteDenah($denah_id)
	{
		$this->admin->deleteDataDenah($denah_id); //Menghapus denah berdasarkan id
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
										Denah telah dihapus!!</div>');
		redirect('admin/managedenah');
	}

	//Fungsi untuk mengedit denah
	public function editDenah($denah_id)
	{
		$data['title'] = 'Edit Denah';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();
		$data['denah'] = $this->db->get_where('denah', ['denah_id' => $denah_id])->row_array();

		//Validasi input
		$this->form_validation->set_rules('nama', 'Nama', 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
											Denah gagal diubah!!</div>');
			redirect('admin/managedenah');
		} else {
			// Cek apakah ada gambar yang diupload, jika tidak gunakan gambar lama
			$new_image = $data['denah']['gambar'];

			if (!empty($_FILES['gambar']['name'])) {
				$config['upload_path'] = './assets/img/denah/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 2048;
				$config['file_name'] = uniqid();

				$this->load->library('upload', $config);

				//Proses upload gambar baru
				if ($this->upload->do_upload('gambar')) {
					$old_image = $data['denah']['gambar'];
					if ($old_image != 'default.jpg') {
						unlink(FCPATH . 'assets/img/denah/' . $old_image); //Hapus gambar lama
					}

					$uploaded_data = $this->upload->data();
					$new_image = $uploaded_data['file_name'];
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
					redirect('admin/managedenah');
				}
			}

			$data = [
				'nama' => $this->input->post('nama'),
				'gambar' => $new_image
			];

			$this->admin->editDenah($denah_id, $data); //Mengedit data denah sesuai dengan id
			$this->session->set_flashdata('message', '<div class="alert alert-success">Denah berhasil diubah!</div>');
			redirect('admin/managedenah');
		}
	}

	//Fungsi untuk menampilkan halaman kelola role
	public function role()
	{
		$data['title'] = 'Role';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		//Ambil data keyword
		$data['keywordRole'] = $this->session->userdata('keywordRole');

		//Konfigurasi query pencarian role
		if (!empty($data['keywordRole'])) {
			$this->db->like('role', $data['keywordRole']);
		}
		$this->db->from('user_role');

		//Konfigurasi pagination
		$config['base_url'] = 'http://localhost/indexing/admin/role';
		$config['total_rows'] = $this->db->count_all_results();
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 3;
		$config['num_links'] = 5;

		//Inisialisasi pagination
		$this->pagination->initialize($config);

		//Ambil data denah berdasarkan pagination
		$data['start'] = $this->uri->segment(3);
		$data['role'] = $this->admin->getRole($config['per_page'], $data['start'], $data['keywordRole']);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/role', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mencari role
	public function search_role()
	{
		$keywordRole = $this->input->post('keywordRole');
		$this->session->set_userdata('keywordRole', $keywordRole);
		redirect('admin/role');
	}

	//Fungsi untuk menambahkan role
	public function add_role()
	{
		//Validasi input
		$this->form_validation->set_rules('role', 'Role', 'required');

		if ($this->form_validation->run() == false) {
			//Jika validasi gagal maka muncul pesan error
			$this->session->set_flashdata('error', validation_errors());
		} else {
			$this->db->insert('user_role', ['role' => $this->input->post('role')]);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role telah ditambahkan!!</div>');
		}
		redirect('admin/role');
	}

	//Fungsi untuk mengedit data role
	public function editRole()
	{
		//Validasi input
		$this->form_validation->set_rules('role', 'Role', 'required');

		if ($this->form_validation->run() == false) {
			//Jika validasi gagal maka akan muncul pesan error
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  										 Role gagal diubah!!</div>');
			redirect('admin/role');
		} else {
			$this->admin->editRole();
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  										 Role telah diubah!!</div>');
			redirect('admin/role');
		}
	}

	//Fungsi untuk menghapus data role
	public function deleteRole($role_id)
	{
		$this->admin->modelDeleteRole($role_id); //Menghapus data role sesuai id
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
										Role telah dihapus!!</div>');
		redirect('admin/role');
	}

	//Fungsi untuk menampilkan akses setiap role
	public function roleAccess($role_id)
	{
		$data['title'] = 'Role';
		$data['subtitle'] = 'Role Akses';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$data['role'] = $this->db->get_where('user_role', ['role_id' => $role_id])->row_array();
		$data['menu'] = $this->db->get('user_menu')->result_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/role-access', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mengubah akses menu setiap role
	public function changeAccess()
	{
		$menu_id = $this->input->post('menu_id');
		$role_id = $this->input->post('role_id');
		$data = [
			'role_id' => $role_id,
			'menu_id' => $menu_id
		];

		$result = $this->db->get_where('user_access_menu', $data);

		if ($result->num_rows() < 1) {
			$this->db->insert('user_access_menu', $data);
		} else {
			$this->db->delete('user_access_menu', $data);
		}

		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  										Akses telah diubah!</div>');
	}

	//Fungsi untuk menampilkan halaman kelola akun
	public function manageAccount()
	{
		$data['title'] = 'Kelola Akun';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		//Mengambil data keyword
		if ($this->input->post('submit')) {
			$data['keywordAccount'] = $this->input->post('keywordAccount');
			$this->session->set_userdata('keywordAccount', $data['keywordAccount']);
		} else {
			$data['keywordAccount'] = $this->session->userdata('keywordAccount');
		}

		//Pastikan $data['keywordAccount'] memiliki nilai default jika null
		$data['keywordAccount'] = $data['keywordAccount'] ?? '';

		//Konfigurasi query untuk pencarian akun
		$this->db->like('nama', $data['keywordAccount']);
		$this->db->from('user');

		//Konfigurasi pagination
		$config['base_url'] = 'http://localhost/indexing/admin/manageaccount';
		$config['total_rows'] = $this->db->count_all_results();
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 3;
		$config['num_links'] = 5;

		//Inisialisasi pagination
		$this->pagination->initialize($config);

		//Mengambil data akun berdasarkan pagination
		$data['start'] = $this->uri->segment(3);
		$data['akun'] = $this->admin->getAccount($config['per_page'], $data['start'], $data['keywordAccount']);

		$data['role'] = $this->db->get('user_role')->result_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/manage-account', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk membuat akun
	public function createAccount()
	{
		$data['title'] = 'Kelola Akun';
		$data['subtitle'] = 'Buat Akun';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$data['role'] = $this->db->get('user_role')->result_array();

		//Validasi input
		$this->form_validation->set_rules('nama', 'Nama', 'required|trim', [
			'required' => 'Nama harus diisi'
		]);
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]', [
			'required' => 'Usename harus diisi'
		]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[4]|matches[password2]', [
			'required' => 'Password harus diisi',
			'matches' => 'password tidak sesuai',
			'min_length' => 'password terlalu sedikit'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
		$this->form_validation->set_rules('role_id', 'Role', 'required', [
			'required' => 'Role harus diisi'
		]);

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('admin/create-account', $data);
			$this->load->view('templates/footer');
		} else {
			$this->admin->createAccount();
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  										Akun berhasil dibuat!</div>');
			redirect('admin/createaccount');
		}
	}

	//Fungsi untuk mengedit akun
	public function editAccount()
	{
		$data['title'] = 'Kelola Akun';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		//Username yang sedang login
		$current_username = $this->session->userdata('username');

		//Query untuk mendapatkan semua akun kecuali akun yang sedang digunakan
		$this->db->where('username !=', $current_username);
		$data['akun'] = $this->db->get('user')->result_array();

		//Mendapatkan seluruh data role
		$data['role'] = $this->db->get('user_role')->result_array();

		$user_id = $this->input->post('user_id');
		$current_user = $this->db->get_where('user', ['user_id' => $user_id])->row_array();

		$this->form_validation->set_rules('nama', 'Nama', 'required|trim', [
			'required' => 'Nama harus diisi'
		]);

		// Ubah aturan validasi untuk username
		if ($this->input->post('username') != $current_user['username']) {
			$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]', [
				'required' => 'Username harus diisi',
				'is_unique' => 'Username ini sudah terdaftar'
			]);
		} else {
			$this->form_validation->set_rules('username', 'Username', 'required|trim', [
				'required' => 'Username harus diisi'
			]);
		}

		// Hanya validasi password jika diisi
		if ($this->input->post('password')) {
			$this->form_validation->set_rules('password', 'Password', 'trim|min_length[3]');
		}

		$this->form_validation->set_rules('role_id', 'Role', 'required', [
			'required' => 'Role harus diisi'
		]);

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('admin/manage-account', $data);
			$this->load->view('templates/footer');
		} else {
			$this->admin->editAccount();
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Akun telah diubah!</div>');
			redirect('admin/manageaccount');
		}
	}

	//Funsi untuk menghapus akun
	public function deleteAccount($user_id)
	{
		$this->admin->deleteAccount($user_id); //Menghapus akun sesuai id
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
										Akun telah dihapus!!</div>');
		redirect('admin/manageaccount');
	}

	//Menghapus akun saya/akun yang sedang digunakan
	public function deleteMyAccount($user_id)
	{
		$this->admin->deleteAccount($user_id);
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  										 Akun anda sudah dihapus</div>');
		redirect('auth');
	}

	//Fungsi untuk menampilkan halaman mengelola usia bup pegawai
	public function manage_bup()
	{
		$data['title'] = 'Kelola Usia BUP';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		//Ambil usia BUP saat ini
		$data['usia_bup'] = $this->admin->getUsiaBUP();
		$data['jumlah'] = $this->admin->jumlahDataBUP();

		if ($data['usia_bup'] == NULL) {
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Usia BUP telah ditambahkan!!</div>');
			redirect('admin/addusiaBUP');
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/manage_bup', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menambahkan angka BUP
	public function addUsiaBUP()
	{
		$data['title'] = 'Kelola Usia BUP';
		$data['subtitle'] = 'Input Usia BUP';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		//Validasi input
		$this->form_validation->set_rules('usia_bup', 'Usia BUP', 'required|trim');

		//Menghitung jumlah data pada tabel 'usia_bup'
		$jumlahData = $this->db->count_all('usia_bup');

		if ($jumlahData > 0) {
			//Jika pada tabel 'usia_bup' terdapat data maka akan ditampilkan halaman kelola denah
			redirect('admin/manage_bup');
		}

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('admin/input_usia_bup', $data);
			$this->load->view('templates/footer');
		} else {
			$data = [
				'usia_bup' => $this->input->post('usia_bup'),
			];

			$this->db->insert('usia_bup', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Usia BUP telah ditambahkan!!</div>');
			redirect('admin/manage_bup');
		}
	}

	//Fungsi untuk mengelola angka BUP
	public function aturUsiaPensiun()
	{
		$usia_bup = $this->input->post('usia_bup');

		if ($this->admin->setUsiaBUP($usia_bup)) {
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Usia BUP berhasil diperbarui.</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal memperbarui usia BUP.</div>');
		}

		redirect('admin/manage_bup');
	}

	//Fungsi untuk menampilkan halaman kelola data ruangan
	public function ruangan()
	{
		$data['title'] = 'Kelola Ruangan';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		//Ambil data keyword
		if ($this->input->post('submit')) {
			$data['keywordRuangan'] = $this->input->post('keywordRuangan');
			$this->session->set_userdata('keywordRuangan', $data['keywordRuangan']);
		} else {
			$data['keywordRuangan'] = $this->session->userdata('keywordRuangan');
		}

		//Pastikan $data['keywordRuangan'] memiliki nilai default jika null
		$data['keywordRuangan'] = $data['keywordRuangan'] ?? '';

		//Konfigurasi query untuk pencarian ruangan
		$this->db->like('nama_ruangan', $data['keywordRuangan']);
		$this->db->from('ruangan');

		//Konfigurasi pagination
		$config['base_url'] = 'http://localhost/indexing/admin/ruangan';
		$config['total_rows'] = $this->db->count_all_results();
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 3;
		$config['num_links'] = 5;

		//Inisialisasi pagination
		$this->pagination->initialize($config);

		//Mengambil data ruangan berdasarkan pagination
		$data['start'] = $this->uri->segment(3);
		$data['ruangan'] = $this->admin->getRuangan($config['per_page'], $data['start'], $data['keywordRuangan']);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/manage-ruangan', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menampilkan halaman menambahkan data ruangan
	public function createRuangan()
	{
		$data['title'] = 'Kelola Ruangan';
		$data['subtitle'] = 'Tambah Ruangan';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/create-ruangan', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menambahkan data ruangan
	public function storeRuangan()
	{
		$data['title'] = 'Kelola Ruangan';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		//Validasi input
		$this->form_validation->set_rules('nama_ruangan', 'Nama Ruangan', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('admin/create-ruangan', $data);
			$this->load->view('templates/footer');

			$message = "Data ruangan gagal ditambahkan";
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $message . '</div>');
		} else {
			$data = array('nama_ruangan' => $this->input->post('nama_ruangan'));
			$this->admin->insert_ruangan($data);
			$message = "Data ruangan berhasil ditambahkan";
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $message . '</div>');
			redirect('admin/ruangan');
		}
	}

	//Fungsi untuk menampilkan halaman edit ruangan
	public function editRuangan($ruangan_id)
	{
		$data['title'] = 'Kelola Ruangan';
		$data['subtitle'] = 'Edit Ruangan';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$data['ruangan'] = $this->admin->getRuanganById($ruangan_id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/edit-ruangan', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mengedit data ruangan
	public function updateRuangan($ruangan_id)
	{
		$this->form_validation->set_rules('nama_ruangan', 'Nama Ruangan', 'required');

		if ($this->form_validation->run() === FALSE) {
			$data['ruangan'] = $this->admin->getRuanganById($ruangan_id);
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('admin/edit-ruangan', $data);
			$this->load->view('templates/footer');

			$message = "Data ruangan gagal diubah";
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $message . '</div>');
		} else {
			$data = array('nama_ruangan' => $this->input->post('nama_ruangan'));
			$this->admin->update_ruangan($ruangan_id, $data);
			$message = "Data ruangan berhasil diubah";
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $message . '</div>');
			redirect('admin/ruangan');
		}
	}

	//Fungsi untuk menghapus data ruangan
	public function deleteRuangan($ruangan_id)
	{
		if ($this->admin->delete_ruangan($ruangan_id)) {
			$message = "Data ruangan berhasil dihapus";
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $message . '</div>');
		} else {
			$message = "Tidak dapat menghapus ruangan karena masih ada data terkait";
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $message . '</div>');
		}
		redirect('admin/ruangan');
	}

	//Fungsi untuk menampilkan halaman kelola data rollopack
	public function rollopack()
	{
		$data['title'] = 'Kelola Rollopack';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();



		// Ambil data keyword
		if ($this->input->post('submit')) {
			$data['keywordRollopack'] = $this->input->post('keywordRollopack');
			$this->session->set_userdata('keywordRollopack', $data['keywordRollopack']);
		} else {
			$data['keywordRollopack'] = $this->session->userdata('keywordRollopack');
		}

		// Pastikan $data['keywordRollopack'] memiliki nilai default jika null
		$data['keywordRollopack'] = $data['keywordRollopack'] ?? '';

		//Konfigurasi query untuk pencarian rollopack
		$this->db->like('nomor_rollopack', $data['keywordRollopack']);
		$this->db->or_like('instansi', $data['keywordRollopack']);
		$this->db->from('rollopack');

		//Konfigurasi pagination
		$config['base_url'] = 'http://localhost/indexing/admin/rollopack';
		$config['total_rows'] = $this->db->count_all_results();
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 3;
		$config['num_links'] = 5;

		//Inisialisasi pagination
		$this->pagination->initialize($config);

		//Mengambil data berdasarkan pagination
		$data['start'] = $this->uri->segment(3);
		$data['rollopack'] = $this->admin->getRollopack($config['per_page'], $data['start'], $data['keywordRollopack']);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/manage-rollopack', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menampilkan halaman menambahkan data rollopack baru
	public function createRollopack()
	{
		$data['title'] = 'Kelola Rollopack';
		$data['subtitle'] = 'Tambah Rollopack';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();
		$data['ruangan'] = $this->db->get('ruangan')->result_array();
		$data['instansi'] = $this->admin->get_all_tables();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/create_rollopack');
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menambahkan data rollopack
	public function storeRollopack()
	{
		$data['title'] = 'Kelola Rollopack';
		$data['subtitle'] = 'Tambah Rollopack';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$data['ruangan'] = $this->db->get('ruangan')->result_array();

		//Validasi input
		$this->form_validation->set_rules('ruangan_id', 'Nama Ruangan', 'required|numeric');
		$this->form_validation->set_rules('nomor_rollopack', 'Nomor Rollopack', 'required|numeric');
		$this->form_validation->set_rules('jumlah_lemari_per_rollopack', 'Jumlah Lemari', 'required|numeric');
		$this->form_validation->set_rules('jumlah_rak_per_lemari', 'Jumlah Rak', 'required|numeric');
		$this->form_validation->set_rules('kapasitas_per_rak', 'Kapasitas Rak', 'required|numeric');
		$this->form_validation->set_rules('instansi', 'Instansi', 'required');
		$this->form_validation->set_rules('no_panggil_awal', 'No Panggil Awal', 'required|numeric');
		$this->form_validation->set_rules('no_panggil_akhir', 'No Panggil Akhir', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			//Jika validasi gagal maka akan menampilkan pesan error
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('admin/create_rollopack');
			$this->load->view('templates/footer');

			$message = "Data rollopack gagal dibuat";
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $message . '</div>');
		} else {
			$data = array(
				'ruangan_id' => $this->input->post('ruangan_id'),
				'nomor_rollopack' => $this->input->post('nomor_rollopack'),
				'jumlah_lemari_per_rollopack' => $this->input->post('jumlah_lemari_per_rollopack'),
				'jumlah_rak_per_lemari' => $this->input->post('jumlah_rak_per_lemari'),
				'kapasitas_per_rak' => $this->input->post('kapasitas_per_rak'),
				'instansi' => $this->input->post('instansi'),
				'no_panggil_awal' => $this->input->post('no_panggil_awal'),
				'no_panggil_akhir' => $this->input->post('no_panggil_akhir')
			);

			$this->admin->insert_rollopack($data);
			$message = "Data rollopack berhasil dibuat";
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $message . '</div>');
			redirect('admin/rollopack');
		}
	}

	//Fungsi untuk menampilkan halaman edit rollopack
	public function editRollopack($rollopack_id)
	{
		$data['title'] = 'Kelola Rollopack';
		$data['subtitle'] = 'Edit Rollopack';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$data['ruangan'] = $this->db->get('ruangan')->result_array();
		$data['rollopack'] = $this->admin->getRollopackById($rollopack_id);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/edit-rollopack', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mengedit data rollopack berdasarkan id
	public function updateRollopack($rollopack_id)
	{
		$data['title'] = 'Kelola Rollopack';
		$data['subtitle'] = 'Edit Rollopack';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		//Validasi input
		$this->form_validation->set_rules('ruangan_id', 'Nama Ruangan', 'required|numeric');
		$this->form_validation->set_rules('nomor_rollopack', 'Nomor Rollopack', 'required|numeric');
		$this->form_validation->set_rules('jumlah_lemari_per_rollopack', 'Jumlah Lemari', 'required|numeric');
		$this->form_validation->set_rules('jumlah_rak_per_lemari', 'Jumlah Rak', 'required|numeric');
		$this->form_validation->set_rules('kapasitas_per_rak', 'Kapasitas Rak', 'required|numeric');
		$this->form_validation->set_rules('instansi', 'Instansi', 'required');
		$this->form_validation->set_rules('no_panggil_awal', 'No Panggil Awal', 'required|numeric');
		$this->form_validation->set_rules('no_panggil_akhir', 'No Panggil Akhir', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			//Jika validasi gagal maka akan menampilkan pesan error
			$data['rollopack'] = $this->admin->getRollopackById($rollopack_id);
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('admin/edit-rollopack', $data);
			$this->load->view('templates/footer');

			$message = "Data rollopack gagal diubah";
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $message . '</div>');
		} else {
			$data = array(
				'ruangan_id' => $this->input->post('ruangan_id'),
				'nomor_rollopack' => $this->input->post('nomor_rollopack'),
				'jumlah_lemari_per_rollopack' => $this->input->post('jumlah_lemari_per_rollopack'),
				'jumlah_rak_per_lemari' => $this->input->post('jumlah_rak_per_lemari'),
				'kapasitas_per_rak' => $this->input->post('kapasitas_per_rak'),
				'instansi' => $this->input->post('instansi'),
				'no_panggil_awal' => $this->input->post('no_panggil_awal'),
				'no_panggil_akhir' => $this->input->post('no_panggil_akhir')
			);

			$this->admin->update_rollopack($rollopack_id, $data);
			$message = "Data rollopack berhasil diubah";
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $message . '</div>');
			redirect('admin/rollopack');
		}
	}

	//Fungsi untuk menghapus data rollopack
	public function deleteRollopack($rollopack_id)
	{
		$this->admin->delete_rollopack($rollopack_id);
		$message = "Data rollopack berhasil dihapus";
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $message . '</div>');
		redirect('admin/rollopack');
	}

	//Fungsi untuk menampilkan halaman kelola instansi
	public function manageInstansi()
	{
		$data['title'] = 'Kelola Instansi';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		// Get all instansi tables
		$data['instansi'] = $this->admin->get_all_tables();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/Kelola-instansi', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menampilkan halaman membuat tabel instansi baru
	public function createInstansi()
	{
		$data['title'] = 'Kelola Instansi';
		$data['subtitle'] = 'Tambah Instansi';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/create-instansi', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menambahkan data instansi
	public function storeInstansi()
	{
		$data['title'] = 'Kelola Instansi';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$this->form_validation->set_rules('instansi', 'Instansi', 'required');

		if (
			$this->form_validation->run() === FALSE
		) {
			$data['subtitle'] = 'Tambah Instansi';
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('admin/create-instansi', $data);
			$this->load->view('templates/footer');
		} else {
			$instansi = $this->input->post('instansi');

			// Format the table name
			$nama_tabel = $this->formatTableName($instansi);

			// Check if the table already exists
			if ($this->db->table_exists($nama_tabel)) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Instansi dengan nama tabel ' . $nama_tabel . ' sudah ada!</div>');
				redirect('admin/createInstansi');
				return;
			}

			// Create the new table
			$fields = array(
				'takah_id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'ruangan_id' => array(
					'type' => 'INT',
					'constraint' => '11'
				),
				'no_rollopack' => array(
					'type' => 'INT',
					'constraint' => '11'
				),
				'no_lemari' => array(
					'type' => 'INT',
					'constraint' => '11'
				),
				'no_rak' => array(
					'type' => 'INT',
					'constraint' => '11'
				),
				'NIP' => array(
					'type' => 'varchar',
					'constraint' => '255'
				),
				'Nama' => array(
					'type' => 'varchar',
					'constraint' => '255'
				),
				'Instansi' => array(
					'type' => 'varchar',
					'constraint' => '255'
				),
				'D2NIP' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'Ijazah' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'DRH' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'SKCPNS' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'SKPNS' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'SK_Perubahan_Data' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'SK_Jabatan' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'SK_Pemberhentian' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'SK_Pensiun' => array(
					'type' => 'tinyint',
					'constraint' => '1'
				),
				'No_Panggil' => array(
					'type' => 'INT',
					'constraint' => '11'
				),
			);

			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('takah_id', TRUE);
			$this->dbforge->add_key('ruangan_id');

			try {
				if ($this->dbforge->create_table($nama_tabel)) {
					// Insert the instansi name into the 'instansi' table
					$instansi_data = array(
						'instansi' => $instansi,
						'nama_tabel' => $nama_tabel
					);
					$this->db->insert('instansi', $instansi_data);

					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Instansi berhasil ditambahkan!</div>');
					redirect('admin/manageInstansi');
				} else {
					throw new Exception('Failed to create table');
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menambahkan instansi: ' . $e->getMessage() . '</div>');
				redirect('admin/createInstansi');
			}
		}
	}

	private function formatTableName($instansi)
	{
		// Convert to lowercase
		$formatted = strtolower($instansi);
		// Replace spaces with underscores
		$formatted = str_replace(' ', '_', $formatted);
		// Remove any characters that are not alphanumeric or underscore
		$formatted = preg_replace('/[^a-z0-9_]/', '', $formatted);
		// Ensure the table name starts with a letter
		if (!ctype_alpha($formatted[0])) {
			$formatted = 'tabel_' . $formatted;
		}
		return $formatted;
	}

	//Fungsi untuk mengedit data role
	public function editInstansi()
	{
		//Validasi input
		$this->form_validation->set_rules('instansi', 'Instansi', 'required');
		$this->form_validation->set_rules('nama_tabel', 'Nama Tabel', 'required');

		if ($this->form_validation->run() == false) {
			//Jika validasi gagal maka akan muncul pesan error
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  										 Instansi gagal diubah!!</div>');
			redirect('admin/manageinstansi');
		} else {
			$this->admin->editInstansi();
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  										 Role telah diubah!!</div>');
			redirect('admin/manageinstansi');
		}
	}

	public function deleteInstansi($instansi_id)
	{
		// Check if the user has permission to delete (you may want to add this)
		// if (!$this->ion_auth->is_admin()) {
		//     $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">You do not have permission to delete institutions.</div>');
		//     redirect('admin/manageInstansi');
		// }

		// Get the institution details
		$instansi = $this->admin->get_instansi($instansi_id);

		if ($instansi) {
			// Delete the institution and its associated table
			$result = $this->admin->delete_instansi($instansi_id, $instansi['nama_tabel']);

			if ($result) {
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Instansi dan tabel berhasil dihapus!</div>');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menghapus instansi dan tabel.</div>');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Instansi tidak ditemukan.</div>');
		}

		redirect('admin/manageInstansi');
	}
}
