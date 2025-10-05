<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('Menu_model', 'menu');
		//PAGINATION
		$this->load->library('pagination');
	}

	//Fungsi untuk menampilkan halaman kelola menu
	public function index()
	{
		$data['title'] = 'Pengaturan Menu';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		//Mengambil data keyword pencarian
		$data['keywordMenu'] = $this->session->userdata('keywordMenu') ?? '';

		//Konfigurasi query untuk pencarian denah
		$this->db->like('menu', $data['keywordMenu']);
		$this->db->from('user_menu');

		//Konfigurasi pagination
		$config['base_url'] = 'http://localhost/indexing/menu/index';
		$config['total_rows'] = $this->db->count_all_results();
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 3;
		$config['num_links'] = 5;

		//Inisialisasi pagination
		$this->pagination->initialize($config);

		//Ambil data denah berdasarkan pagination
		$data['start'] = $this->uri->segment(3);
		$data['menu'] = $this->menu->getMenu($config['per_page'], $data['start'], $data['keywordMenu']);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('menu/index', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mencari data menu
	public function searchMenu()
	{
		$keywordMenu = $this->input->post('keywordMenu');
		$this->session->set_userdata('keywordMenu', $keywordMenu);
		redirect('menu');
	}

	//Fungsi untuk menambahkan menu baru
	public function addMenu()
	{
		//Validasi data input
		$this->form_validation->set_rules('menu', 'Menu', 'required');

		if ($this->form_validation->run() == false) {
			//Jika validasi gagal maka akan menampilkan pesan error
			$this->session->set_flashdata('error', validation_errors());
		} else {
			$this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu telah ditambahkan!!</div>');
		}
		redirect('menu');
	}

	//Fungsi untuk mengedit data menu
	public function editMenu()
	{
		$this->form_validation->set_rules('menu', 'Menu', 'required');

		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  										 Menu gagal diubah!!</div>');
			redirect('menu');
		} else {
			$this->menu->editMenu();
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  										 Menu telah diubah!!</div>');
			redirect('menu');
		}
	}

	//Fungsi untuk menghapus data menu berdasarkan id
	public function deleteMenu($menu_id)
	{
		$this->menu->deleteDataMenu($menu_id);
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
										Menu telah dihapus!!</div>');
		redirect('menu');
	}

	//Fungsi untuk menampilkan halaman kelola submenu
	public function submenu()
	{
		$data['title'] = 'Pengaturan Sub Menu';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['menu'] = $this->db->get('user_menu')->result_array();

		//Mengambil data keyword pencarian
		$data['keywordSubmenu'] = $this->session->userdata('keywordSubmenu') ?? '';

		//Konfigurasi query untuk pencarian denah
		$this->db->like('title', $data['keywordSubmenu']);
		$this->db->from('user_sub_menu');

		//Konfigurasi pagination
		$config['base_url'] = 'http://localhost/indexing/menu/submenu';
		$config['total_rows'] = $this->db->count_all_results();
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 5;
		$config['num_links'] = 5;

		//Inisialisasi pagination
		$this->pagination->initialize($config);

		//Ambil data denah berdasarkan pagination
		$data['start'] = $this->uri->segment(3);
		$data['subMenu'] = $this->menu->getSubmenu($config['per_page'], $data['start'], $data['keywordSubmenu']);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('menu/submenu', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mencari data menu
	public function searchSubmenu()
	{
		$keywordSubmenu = $this->input->post('keywordSubmenu');
		$this->session->set_userdata('keywordSubmenu', $keywordSubmenu);
		redirect('menu/submenu');
	}

	//Fungsi untuk membuat submenu baru
	public function addSubmenu()
	{
		//validasi data input
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('menu_id', 'Menu', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('icon', 'Icon', 'required');

		if ($this->form_validation->run() == false) {
			//Jika validasi gagal maka akan menampilkan pesan error
			$this->session->set_flashdata('error', validation_errors());
		} else {
			$data = [
				'title' => $this->input->post('title'),
				'menu_id' => $this->input->post('menu_id'),
				'url' => $this->input->post('url'),
				'icon' => $this->input->post('icon'),
				'is_active' => $this->input->post('is_active') ? 1 : 0
			];

			$this->db->insert('user_sub_menu', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Submenu telah ditambahkan!!</div>');
		}
		redirect('menu/submenu');
	}

	//Fungsi untuk menghapus data submenu berdasarkan id
	public function deleteSubmenu($sub_menu_id)
	{
		$this->menu->deleteDataSubmenu($sub_menu_id);
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
										Submenu telah dihapus!!</div>');
		redirect('menu/submenu');
	}

	//Fungsi untuk mengedit data submenu
	public function editSubmenu()
	{
		$data['title'] = 'Pengaturan Sub Menu';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['subMenu'] = $this->menu->getAllSubMenu();
		$data['menu'] = $this->db->get('user_menu')->result_array();

		//Validasi data input
		$this->form_validation->set_rules('menu_id', 'Menu', 'required');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('icon', 'Icon', 'required');

		$this->session->set_flashdata('message', validation_errors());

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('menu/submenu', $data);
			$this->load->view('templates/footer');
		} else {
			$this->menu->editSubmenu($data, $this->input->post('sub_menu_id'));
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Submenu telah diubah!</div>');
			redirect('menu/submenu');
		}
	}
}
