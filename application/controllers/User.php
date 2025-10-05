<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;


class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		is_logged_in();
		$this->load->model('User_model', 'user');
		$this->load->helper(['form', 'url']);
		$this->load->library('session');
		$this->load->library('pagination');
	}


	public function index()
	{
		$data['title'] = 'Cari Tata Letak';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['takah'] = $this->db->get('takah')->result_array();
		$data['instansi'] = $this->user->getInstansi();

		$data['search_performed'] = false;
		$data['show_table'] = false;
		$data['show_result'] = false;

		if ($this->input->post('cari')) {
			$selected_instansi = $this->input->post('instansi');
			$this->session->set_userdata('selected_instansi', $selected_instansi);
		} else {
			$selected_instansi = $this->session->userdata('selected_instansi');
		}

		$data['selected_instansi'] = $selected_instansi;

		if ($this->input->post('cari') || $this->input->get('takah_id')) {
			$nip = $this->input->post('nip');
			$nama = $this->input->post('nama');
			$instansi = $this->input->post('instansi');
			$takah_id = $this->input->get('takah_id');

			if (!empty($nip)) {
				$data['result'] = $this->user->search_takah_all_tables($nip);
				$data['search_performed'] = true;
				$data['show_result'] = true;
			} elseif (!empty($nama) && !empty($instansi)) {
				$results = $this->user->search_takah_by_name_and_instansi($nama, $instansi);
				if (count($results) > 1) {
					$data['table_data'] = $results;
					$data['search_performed'] = true;
					$data['show_table'] = true;
				} else if (count($results) == 1) {
					$data['result'] = $results;
					$data['search_performed'] = true;
					$data['show_result'] = true;
				} else {
					$this->session->set_flashdata('info', '<div class="alert alert-info" role="alert">Tidak ada hasil ditemukan</div>');
				}
			} elseif (!empty($takah_id)) {
				$data['result'] = $this->user->get_takah_by_takahid($takah_id);
				$data['search_performed'] = true;
				$data['show_result'] = true;
			} else {
				$this->session->set_flashdata('info', '<div class="alert alert-info" role="alert">Silakan masukkan NIP atau Nama dengan Instansi</div>');
			}
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/index', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk menampilkan halaman lihat denah
	public function showDenah()
	{
		$data['title'] = 'Denah';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();
		$data['denah'] = $this->db->get('denah')->result_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/show-denah', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk meng-input data pegawai
	public function addDataPegawai()
	{
		$data['title'] = 'Input Data Pegawai';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['ruangan'] = $this->user->get_all_ruangan();
		$data['rollopack'] = $this->user->get_all_rollopack();
		$data['instansi'] = $this->user->get_all_tables();

		//Validasi input
		$this->form_validation->set_rules('nip', 'NIP', 'required|trim|max_length[18]|min_length[18]', [
			'max_length' => 'NIP harus 18 digit',
			'min_length' => 'NIP harus 18 digit'
		]);
		$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
		$this->form_validation->set_rules('instansi', 'Kode Instansi', 'required|trim');
		$this->form_validation->set_rules('ruangan_id', 'Ruangan', 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('user/add-data-pegawai', $data);
			$this->load->view('templates/footer');
		} else {
			$ruangan_id = htmlspecialchars($this->input->post('ruangan_id', true));
			$instansi = htmlspecialchars($this->input->post('instansi', true));

			$nama_tabel = $this->user->get_table_instansi($instansi);

			// Periksa posisi kosong
			$emptyPositions = $this->user->findEmptyPositions($ruangan_id, $instansi, $nama_tabel);

			if (!empty($emptyPositions)) {
				$this->session->set_userdata('empty_positions', $emptyPositions);
				$this->session->set_userdata('current_ruangan_id', $ruangan_id);
				$this->session->set_userdata('current_instansi', $instansi);
				$this->session->set_flashdata('kosong', '<div class="alert alert-warning" role="alert">Terdapat posisi kosong yang harus diisi terlebih dahulu. Silakan isi posisi tersebut secara manual.' . '<br>' .
					'<b>' . 'Instansi: ' .  $emptyPositions[0]['instansi']  . '</b><br>' .
					'<b>' . 'No Panggil: ' .  $emptyPositions[0]['No_Panggil'] . '</b><br>' .
					'<b>' . 'No Rollopack: ' .   $emptyPositions[0]['no_rollopack'] . '</b><br>' .
					'<b>' . 'No Lemari: ' .   $emptyPositions[0]['no_lemari'] . '</b><br>' .
					'<b>' . 'No Rak: ' .   $emptyPositions[0]['no_rak'] . '</b>' . '</div>');
				redirect('user/addDataPegawai');
			} else {

				//Untuk mendapatkan nila No_Panggil
				$no_panggil = $this->user->getNextNumbers($instansi, $ruangan_id);

				//Cari rolloapack sesuai dengan No_Panggil yang sesuai rentannya dan sesuai instansinya dan sesuai ruangnya
				if (!$this->user->findRollopack($ruangan_id, $instansi, $no_panggil)) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Rollopack tidak ditemukan untuk no panggil ' . $no_panggil . ' dengan instansi ' . $instansi . '!</div>');
					redirect('user/adddatapegawai');
				} else {
					$data['rollopack'] = $this->user->findRollopack($ruangan_id, $instansi, $no_panggil);
					$rollopack = $data['rollopack']['nomor_rollopack'];
					$jumlahLemari = $data['rollopack']['jumlah_lemari_per_rollopack'];
					$jumlahRak = $data['rollopack']['jumlah_rak_per_lemari'];
					$kapasitasRak = $data['rollopack']['kapasitas_per_rak'];

					$positionFound = false;

					//Mencari posisi rak lemari dan rollopack yang sesuai dengan no panggil dan instansi
					for ($noAwalLemari = 1; $noAwalLemari <= $jumlahLemari; $noAwalLemari++) {
						for ($noAwalRak = 1; $noAwalRak <= $jumlahRak; $noAwalRak++) {
							$jumlahDokumen = $this->user->findLemari($ruangan_id, $rollopack, $noAwalLemari, $noAwalRak, $instansi);
							if ($jumlahDokumen < $kapasitasRak) {
								$positionFound = true;
								$input_data = [
									'ruangan_id' => $ruangan_id,
									'no_rollopack' => $rollopack,
									'no_lemari' => $noAwalLemari,
									'no_rak' => $noAwalRak,
									'NIP' => htmlspecialchars($this->input->post('nip', true)),
									'Nama' => htmlspecialchars($this->input->post('nama', true)),
									'Instansi' => $instansi,
									'D2NIP' => $this->input->post('d2nip') ? 1 : 0,
									'Ijazah' => $this->input->post('ijazah') ? 1 : 0,
									'DRH' => $this->input->post('drh') ? 1 : 0,
									'SKCPNS' => $this->input->post('skcpns') ? 1 : 0,
									'SKPNS' => $this->input->post('skpns') ? 1 : 0,
									'SK_Perubahan_Data' => $this->input->post('sk_perubahan_data') ? 1 : 0,
									'SK_Jabatan' => $this->input->post('sk_jabatan') ? 1 : 0,
									'SK_Pemberhentian' => $this->input->post('sk_pemberhentian') ? 1 : 0,
									'SK_Pensiun' => $this->input->post('sk_pensiun') ? 1 : 0,
									'No_Panggil' => $no_panggil
								];
								break 2;
							}
						}
					}
				}

				if (!$positionFound) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Semua rak penuh. Tidak dapat menambahkan data baru.</div>');
					redirect('user/adddatapegawai');
				}

				$insert_result = $this->user->addData($input_data, $instansi);

				if ($insert_result === 'duplicate_nip') {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">NIP telah terdaftar. Data tidak dapat disimpan.</div>');
				} elseif ($insert_result) {
					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diinput!</div>');
					$this->session->set_flashdata('new_data', $input_data);
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menginput data. No_Panggil sudah ada untuk instansi ini.</div>');
				}

				redirect('user/adddatapegawai');
			}
		}
	}

	//Fungsi untuk meng-input data pegawai secara manual
	public function inputManual()
	{
		$data['title'] = 'Input Data Pegawai';
		$data['subtitle'] = 'Input Data Pegawai Manual';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['ruangan'] = $this->user->get_all_ruangan();
		$data['rollopack'] = $this->user->get_all_rollopack();

		//Validasi input
		$this->form_validation->set_rules('nip', 'NIP', 'required|trim|max_length[18]|min_length[18]', [
			'max_length' => 'NIP harus 18 digit',
			'min_length' => 'NIP harus 18 digit'
		]);
		$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
		$this->form_validation->set_rules('instansi', 'Kode Instansi', 'required|trim');
		$this->form_validation->set_rules('ruangan_id', 'Ruangan', 'required|trim');
		$this->form_validation->set_rules('no_panggil', 'No Panggil', 'required|trim');
		$this->form_validation->set_rules('rollopack', 'No Rollopack', 'required|trim');
		$this->form_validation->set_rules('lemari', 'No Lemari', 'required|trim');
		$this->form_validation->set_rules('rak', 'No Rak', 'required|trim');

		$ruangan_id = $this->input->post('ruangan_id', true);
		$instansi = $this->input->post('instansi', true);
		$no_panggil = $this->input->post('no_panggil', true);
		$rollopack = $this->input->post('rollopack', true);
		$lemari = $this->input->post('lemari', true);
		$rak = $this->input->post('rak', true);

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('user/input-data-pegawai-manual', $data);
			$this->load->view('templates/footer');
		} else {
			if (!$this->user->findRollopack($ruangan_id, $instansi, $no_panggil)) {
				$nama_ruangan = $this->user->getRoomNameById($ruangan_id);
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Rollopack tidak ditemukan untuk no panggil ' . $no_panggil . ' dengan instansi ' . $instansi . ' di ruang ' . $nama_ruangan . '!</div>');
				redirect('user/inputmanual');
			} else {
				if ($this->user->findDataPegawai($ruangan_id, $instansi, $no_panggil, $rollopack, $lemari, $rak)) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data sudah ada di dalam rollopack.</div>');
					redirect('user/inputmanual');
				} else {
					$data['rollopack'] = $this->user->findRollopack($ruangan_id, $instansi, $no_panggil);
					$kapasitasRak = $data['rollopack']['kapasitas_per_rak'];
					$positionFound = false;

					$jumlahDokumen = $this->user->findLemari($ruangan_id, $rollopack, $lemari, $rak, $instansi);
					if ($jumlahDokumen <= $kapasitasRak) {
						$positionFound = true;
						$input_data = [
							'ruangan_id' => $ruangan_id,
							'no_rollopack' => $rollopack,
							'no_lemari' => $lemari,
							'no_rak' => $rak,
							'NIP' => htmlspecialchars($this->input->post('nip', true)),
							'Nama' => htmlspecialchars($this->input->post('nama', true)),
							'Instansi' => $instansi,
							'D2NIP' => $this->input->post('d2nip') ? 1 : 0,
							'Ijazah' => $this->input->post('ijazah') ? 1 : 0,
							'DRH' => $this->input->post('drh') ? 1 : 0,
							'SKCPNS' => $this->input->post('skcpns') ? 1 : 0,
							'SKPNS' => $this->input->post('skpns') ? 1 : 0,
							'SK_Perubahan_Data' => $this->input->post('sk_perubahan_data') ? 1 : 0,
							'SK_Jabatan' => $this->input->post('sk_jabatan') ? 1 : 0,
							'SK_Pemberhentian' => $this->input->post('sk_pemberhentian') ? 1 : 0,
							'SK_Pensiun' => $this->input->post('sk_pensiun') ? 1 : 0,
							'No_Panggil' => $no_panggil
						];
					}

					if (!$positionFound) {
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Semua rak penuh. Tidak dapat menambahkan data baru.</div>');
						redirect('user/inputmanual');
					}

					$insert_result = $this->user->addData($input_data, $instansi);

					if ($insert_result === 'duplicate_nip') {
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">NIP telah terdaftar. Data tidak dapat disimpan.</div>');
					} elseif ($insert_result) {
						$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diinput!</div>');
						$this->session->set_flashdata('new_data', $input_data);
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menginput data. no panggil ' . $no_panggil . ' sudah ada untuk instansi  ' .  $instansi . '</div>');
					}

					redirect('user/inputmanual');
				}
			}
		}
	}

	//Fungsi untuk mengganti password
	public function changePassword()
	{
		$data['title'] = 'Ganti Password';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		//Validasi input
		$this->form_validation->set_rules('password_lama', 'Password Lama', 'required|trim');
		$this->form_validation->set_rules('password_baru1', 'Password Baru', 'required|trim|min_length[4]|matches[password_baru2]');
		$this->form_validation->set_rules('password_baru2', 'Ulangi Password', 'required|trim|min_length[4]|matches[password_baru1]');

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('user/change-password', $data);
			$this->load->view('templates/footer');
		} else {
			$password_lama = $this->input->post('password_lama');
			$password_baru = $this->input->post('password_baru1');

			//Validasi password lama
			if (!password_verify($password_lama, $data['user']['password'])) {
				//Jika password lama tidak sesuai maka muncul pesan error
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  				Password lama salah!</div>');
				redirect('user/changepassword');
			} else {
				if ($password_lama == $password_baru) {
					//Jika password baru sama dengan password lama maka akan muncul pesan error
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  					Password baru tidak boleh sama dengan password lama!</div>');
					redirect('user/changepassword');
				} else {
					//Hash password baru
					$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

					//Mengupdate password lama menjadi password baru
					$this->db->set('password', $password_hash);
					$this->db->where('username', $this->session->userdata('username'));
					$this->db->update('user');
					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  					Password sukes diganti!</div>');
					redirect('user/changepassword');
				}
			}
		}
	}

	//Fungsi untuk menampilkan halaman impor 
	public function import()
	{
		$data['title'] = 'Import Excel';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		$data['ruangan'] = $this->user->get_all_ruangan();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/import');
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mengimpor file data pegawai
	public function do_import()
	{
		$config = [
			'upload_path' => './uploads/',
			'allowed_types' => 'xlsx|xls',
			'max_size' => 2048,
			'file_ext_tolower' => TRUE
		];

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('excel_file')) {
			$error = $this->upload->display_errors();
			$this->session->set_flashdata('error', $error);
			redirect('user/import');
		} else {
			$data = $this->upload->data();
			$file_path = './uploads/' . $data['file_name'];

			try {
				$spreadsheet = IOFactory::load($file_path);
				$worksheet = $spreadsheet->getActiveSheet();
				$highestRow = $worksheet->getHighestRow();

				$data_by_instansi = [];
				$errors = [];
				$nip_duplicates = [];
				$no_panggil_duplicates = [];
				$skipped = 0;
				$unavailable_instansi = [];

				$ruangan_id = $this->input->post('ruangan_id');

				for ($row = 2; $row <= $highestRow; ++$row) {
					$nip = $this->getCellValue($worksheet, 'B', $row);

					if (empty($nip) || !ctype_digit((string)$nip)) {
						$skipped++;
						continue;
					}

					$nama = $this->getCellValue($worksheet, 'C', $row);
					$instansi = $this->getCellValue($worksheet, 'D', $row);
					$no_panggil = $this->getCellValue($worksheet, 'O', $row);

					$d2nip = $this->getBooleanValue($this->getCellValue($worksheet, 'E', $row));
					$ijazah = $this->getBooleanValue($this->getCellValue($worksheet, 'F', $row));
					$drh = $this->getBooleanValue($this->getCellValue($worksheet, 'G', $row));
					$skcpns = $this->getBooleanValue($this->getCellValue($worksheet, 'H', $row));
					$skpns = $this->getBooleanValue($this->getCellValue($worksheet, 'I', $row));
					$sk_perubahan_data = $this->getBooleanValue($this->getCellValue($worksheet, 'J', $row));
					$sk_jabatan = $this->getBooleanValue($this->getCellValue($worksheet, 'L', $row));
					$sk_pemberhentian = $this->getBooleanValue($this->getCellValue($worksheet, 'M', $row));
					$sk_pensiun = $this->getBooleanValue($this->getCellValue($worksheet, 'N', $row));


					// Validasi data
					$row_errors = $this->validate_data($nip, $nama, $instansi);

					if ($this->user->nip_exists($nip, $instansi)) {
						$nip_duplicates[] = "NIP: $nip, Nama: $nama";
						continue;
					}

					if ($this->user->no_panggil_exists($no_panggil, $ruangan_id, $instansi)) {
						$no_panggil_duplicates[] = "No Panggil $no_panggil sudah ada dalam ruangan dan instansi yang sama";	
						continue;
					}

					if (!$this->user->rollopack_exists($ruangan_id, $instansi)) {
						if (!in_array($instansi, $unavailable_instansi)) {
							$unavailable_instansi[] = $instansi;
						}
						$row_errors[] = "Tidak ada rollopack tersedia untuk instansi: $instansi";
					} else {
						if (empty($row_errors)) {
							$nama_tabel = $this->user->get_table_instansi($instansi);
							$position = $this->user->find_storage_position($ruangan_id, $instansi, $no_panggil, $nama_tabel);

							if ($position) {
								$row_data = [
									'ruangan_id' => $ruangan_id,
									'no_rollopack' => $position['no_rollopack'],
									'no_lemari' => $position['no_lemari'],
									'no_rak' => $position['no_rak'],
									'NIP' => $nip,
									'Nama' => $nama,
									'instansi' => $instansi,
									'D2NIP' => $d2nip,
									'Ijazah' => $ijazah,
									'DRH' => $drh,
									'SKCPNS' => $skcpns,
									'SKPNS' => $skpns,
									'SK_Perubahan_Data' => $sk_perubahan_data,
									'SK_Jabatan' => $sk_jabatan,
									'SK_Pemberhentian' => $sk_pemberhentian,
									'SK_Pensiun' => $sk_pensiun,
									'No_Panggil' => $no_panggil,
								];

								if (!isset($data_by_instansi[$instansi])) {
									$data_by_instansi[$instansi] = [];
								}
								$data_by_instansi[$instansi][] = $row_data;
							}
						} else {
							$row_errors[] = "Tidak dapat menemukan posisi penyimpanan yang sesuai";
						}
					}
					if (!empty($row_errors)) {
						$errors[] = "Baris $row: " . implode(", ", $row_errors);
					}
				}

				$this->db->trans_start();
				$inserted_count = 0;
				foreach ($data_by_instansi as $instansi => $instansi_data) {
					$inserted_count += $this->user->insert_batch_by_instansi($instansi, $instansi_data);
				}
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
					throw new Exception('Gagal memasukkan data.');
				}

				$success = "";
				$message = "";
				if ($inserted_count > 0) {
					$success .= "Data berhasil diimpor.<br>";
					$this->session->set_flashdata('success', '<div class="alert alert-success" role="alert">' . $success . '</div>');
				}
				if ($skipped > 0) {
					$message .= "$skipped baris dilewati karena tidak memiliki NIP yang valid.<br>";
				}
				if (!empty($nip_duplicates)) {
					$message .= "Data berikut tidak dapat diimpor karena NIP duplikat:<br>" . implode("<br>", $nip_duplicates);
				}
				if (!empty($no_panggil_duplicates)) {
					$message .= "Data berikut tidak dapat diimpor karena No Panggil duplikat:<br>" . implode("<br>", $no_panggil_duplicates);
				}
				if (!empty($unavailable_instansi)) {
					$message .= "<div style='color: red;'><b>Instansi berikut tidak memiliki rollopack yang tersedia:<br>" . implode("<br>", $unavailable_instansi) . "</b></div>";
				}
				if (!empty($message)) {
					$this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">' . $message . '</div>');
				}
				if (!empty($message)) {
					$this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">' . $message . '</div>');
				} 

			} catch (Exception $e) {
				log_message('error', 'Import error: ' . $e->getMessage());
				$this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
			} finally {
				if (file_exists($file_path)) {
					unlink($file_path);
				}
			}

			redirect('user/import');
		}
	}

	// Fungsi untuk mencari posisi penyimpanan yang sesuai
	private function find_storage_position($ruangan_id, $instansi, $no_panggil, $nama_tabel)
	{
		// Cari rollopack yang sesuai
		$this->db->where('ruangan_id', $ruangan_id);
		$this->db->where('instansi', $instansi);
		$this->db->where("$no_panggil BETWEEN no_panggil_awal AND no_panggil_akhir");
		$rollopack = $this->db->get('rollopack')->row_array();

		if (!$rollopack) {
			return false;
		}

		$no_rollopack = $rollopack['nomor_rollopack'];
		$jumlah_lemari = $rollopack['jumlah_lemari_per_rollopack'];
		$jumlah_rak = $rollopack['jumlah_rak_per_lemari'];
		$kapasitas_rak = $rollopack['kapasitas_per_rak'];

		// Cari posisi kosong
		for ($lemari = 1; $lemari <= $jumlah_lemari; $lemari++) {
			for ($rak = 1; $rak <= $jumlah_rak; $rak++) {
				$this->db->where('ruangan_id', $ruangan_id);
				$this->db->where('no_rollopack', $no_rollopack);
				$this->db->where('no_lemari', $lemari);
				$this->db->where('no_rak', $rak);
				$count = $this->db->count_all_results($nama_tabel);

				if ($count < $kapasitas_rak) {
					return [
						'no_rollopack' => $no_rollopack,
						'no_lemari' => $lemari,
						'no_rak' => $rak
					];
				}
			}
		}

		return false; // Tidak ada posisi kosong
	}

	//Fungsi untuk mengambil data dari kolom dan baris pada tabel file data pegawai yang diimporkan
	private function getCellValue($worksheet, $column, $row)
	{
		$cell = $worksheet->getCell($column . $row);
		return $cell->getCalculatedValue();
	}

	//Fungsi untuk mengambil data boolean dari tabel file data pegawai yang diimporkan
	private function getBooleanValue($value)
	{
		if (is_bool($value)) {
			return $value ? 1 : 0;
		}

		$value = strtolower(safeTrim($value));
		return in_array($value, ['true', 'yes', '1', 'y', 'on']) ? 1 : 0;
	}

	//Fungsi untuk mengvalidasi data
	private function validate_data($nip, $nama, $kode_instansi)
	{
		$errors = [];

		if (!ctype_digit((string)$nip)) {
			$errors[] = "NIP harus berupa angka";
		}

		if (empty($nama) || !is_string($nama)) {
			$errors[] = "Nama harus berupa teks dan tidak boleh kosong";
		}

		if (empty($kode_instansi) || !is_string($kode_instansi)) {
			$errors[] = "Kode Instansi harus berupa teks dan tidak boleh kosong";
		}

		return $errors;
	}

	//Fungsi untuk menampilkan halaman katalog
	public function katalog()
	{
		$data['title'] = 'Katalog';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		if ($this->input->get('submit')) {
			$data['keywordKatalog'] = $this->input->get('keywordKatalog');
			$this->session->set_userdata('keywordKatalog', $data['keywordKatalog']);
		} else {
			$data['keywordKatalog'] = $this->session->userdata('keywordKatalog') ?? '';
		}

		$data['sort_by'] = $this->input->get('sort_by') ?? 'instansi';
		$this->session->set_userdata('sort_by', $data['sort_by']);
		$data['sort_order'] = $this->input->get('sort_order') ?? 'ASC';
		$this->session->set_userdata('sort_order', $data['sort_order']);
		$data['filter_instansi'] = $this->input->get('filter_instansi') ?? '';
		$this->session->set_userdata('filter_instansi', $data['filter_instansi']);
		$data['filter_ruangan'] = $this->input->get('filter_ruangan') ?? '';
		$this->session->set_userdata('filter_ruangan', $data['filter_ruangan']);

		$config['base_url'] = 'http://localhost/indexing/user/katalog';
		$config['total_rows'] = $this->user->countFilteredKatalog(
			$data['keywordKatalog'],
			$data['filter_instansi'],
			$data['filter_ruangan']
		);
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 5;
		$config['num_links'] = 5;

		$config['reuse_query_string'] = TRUE;
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['first_url'] = $config['base_url'] . $config['suffix'];

		$this->pagination->initialize($config);

		$data['start'] = $this->uri->segment(3);
		$data['takah'] = $this->user->getKatalog(
			$config['per_page'],
			$data['start'],
			$data['keywordKatalog'],
			$data['sort_by'],
			$data['sort_order'],
			$data['filter_instansi'],
			$data['filter_ruangan']
		);

		$data['instansi'] = $this->user->getInstansi();
		$data['ruangan'] = $this->user->getUniqueRuangan();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/katalog', $data);
		$this->load->view('templates/footer');
	}

	public function detailDataPegawai($takah_id)
	{
		$data['title'] = 'Katalog';
		$data['subtitle'] = 'Detail Data Pegawai';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['takah'] = $this->user->get_takah_by_id($takah_id);
		$data['ruangan'] = $this->user->get_all_ruangan();
		$data['filter_instansi'] = $this->session->userdata('filter_instansi');

		if (!$data['takah']) {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data tidak ditemukan.</div>');
			redirect('user/katalog');
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/detail-data-pegawai', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mengedit data ruangan berdasarkan id
	public function editDataPegawai($takah_id)
	{
		$data['title'] = 'Katalog';
		$data['subtitle'] = 'Edit Data Pegawai';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['takah'] = $this->user->get_takah_by_id($takah_id);
		$data['ruangan'] = $this->user->get_all_ruangan();
		$data['rollopack'] = $this->user->get_all_rollopack();

		//Validasi data input
		$this->form_validation->set_rules('nip', 'NIP', 'trim|min_length[18]|max_length[18]|is_unique');
		$this->form_validation->set_rules('nama', 'Nama', 'trim');
		$this->form_validation->set_rules('instansi', 'Kode Instansi', 'trim');
		$this->form_validation->set_rules('ruangan_id', 'Ruangan', 'trim');
		$this->form_validation->set_rules('no_panggil', 'No Panggil', 'trim');
		$this->form_validation->set_rules('rollopack', 'No Rollopack', 'trim');
		$this->form_validation->set_rules('lemari', 'No Lemari', 'trim');
		$this->form_validation->set_rules('rak', 'No Rak', 'trim');

		$ruangan_id = $this->input->post('ruangan_id');
		$no_panggil = $this->input->post('no_panggil');
		$instansi = $this->input->post('instansi');
		$rollopack = $this->input->post('rollopack');
		$lemari = $this->input->post('lemari');
		$rak = $this->input->post('rak');

		// Check if all required fields are provided
		if ($ruangan_id && $instansi && $no_panggil && $rollopack && $lemari && $rak) {
			$cekDataPegawai = $this->user->findDataPegawai($ruangan_id, $instansi, $no_panggil, $rollopack, $lemari, $rak);
		} else {
			$cekDataPegawai = null;
		}

		// Dapatkan nilai NIP yang 
		$nip = $this->input->post('nip');

		// Dapatkan data takah saat ini
		$current_takah = $this->user->get_takah_by_id($takah_id);

		// Periksa apakah NIP sudah ada di database
		$cekNipExist = $this->user->getIdByNIP($nip, $data['takah']['Instansi']);

		// Jika NIP berubah dan sudah ada di database
		if ($nip != $current_takah['NIP'] && $cekNipExist) {
			$this->form_validation->set_rules(
				'nip',
				'NIP',
				'trim|is_unique[' . $this->user->get_table_instansi($instansi) . '.NIP]',
				array('is_unique' => 'NIP sudah terdaftar untuk instansi ini.')
			);
		} else {
			$this->form_validation->set_rules('nip', 'NIP', 'trim|required|exact_length[18]');
		}

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('user/edit-data-pegawai', $data);
			$this->load->view('templates/footer');
		} else {
			if ($cekDataPegawai) {
				if ($cekDataPegawai['takah_id'] != $takah_id) {
					$this->session->set_flashdata('message', "<div class='alert alert-success' role='alert'>No panggil $no_panggil sudah ada dalam ruangan dan instansi yang sama</div>");
					redirect("user/editdatapegawai/$takah_id");
				} else {
					$this->user->editData($takah_id);
					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
					redirect('user/katalog');
				}
			} else {
				$this->user->editData($takah_id);
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
				redirect('user/katalog');
			}
		}
	}

	//Fungsi untuk mengosongkan posisi katalog
	public function kosongkanKatalog($takah_id)
	{
		// Kosongkan data kecuali kolom yang ditentukan
		$this->user->kosongkanDataPegawai($takah_id);

		// Set flashdata untuk notifikasi berhasil
		$this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil dikosongkan.</div>');

		// Redirect ke halaman detail atau ke halaman katalog
		redirect('user/detailDataPegawai/' . $takah_id);
	}

	//Fungsi untuk menonaktifkan katalog
	public function nonaktifKatalog($takah_id)
	{
		//Panggil metode transferToBUP dari model
		$result = $this->user->transferToBUP($takah_id);

		if ($result) {
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dipindahkan ke BUP.</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal memindahkan data ke BUP.</div>');
		}

		redirect('user/katalogBUP');
	}

	//Fungsi untuk menghapus data katalog berdasarkan id
	public function deleteDataKatalog($takah_id)
	{
		$this->user->deleteDataKatalog($takah_id);
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
										Data telah dihapus!!</div>');
		redirect('user/katalog');
	}

	//Menampilkan katalog yang sudah pensiun
	public function katalogBUP()
	{
		$data['title'] = 'Katalog BUP';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		// Ambil data keyword
		if ($this->input->post('submit')) {
			$data['keywordKatalogBUP'] = $this->input->post('keywordKatalogBUP');
			$this->session->set_userdata('keywordKatalogBUP', $data['keywordKatalogBUP']);
		} else {
			$data['keywordKatalogBUP'] = $this->session->userdata('keywordKatalogBUP') ?? '';
		}

		$data['sort_by'] = $this->input->get('sort_by') ?? 'instansi';
		$this->session->set_userdata('sort_by', $data['sort_by']);
		$data['sort_order'] = $this->input->get('sort_order') ?? 'ASC';
		$this->session->set_userdata('sort_order', $data['sort_order']);
		$data['filter_instansi'] = $this->input->get('filter_instansi') ?? '';
		$this->session->set_userdata('filter_instansi', $data['filter_instansi']);
		$data['filter_ruangan'] = $this->input->get('filter_ruangan') ?? '';
		$this->session->set_userdata('filter_ruangan', $data['filter_ruangan']);

		//Konfigurasi paginatoin
		$this->load->model('User_model');
		$config['base_url'] = 'http://localhost/indexing/user/katalogBUP';
		$config['total_rows'] = $this->user->countFilteredKatalogBUP(
			$data['keywordKatalogBUP'],
			$data['filter_instansi'],
			$data['filter_ruangan']
		);
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 5;
		$config['num_links'] = 5;

		//Sertakan parameter filter dalam tautan pagination
		$config['reuse_query_string'] = TRUE;
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['first_url'] = $config['base_url'] . $config['suffix'];

		//Inisialisai pagination
		$this->pagination->initialize($config);

		$data['start'] = $this->uri->segment(3);
		$data['takah'] = $this->user->getKatalogBUP(
			$config['per_page'],
			$data['start'],
			$data['keywordKatalogBUP'],
			$data['sort_by'],
			$data['sort_order'],
			$data['filter_instansi'],
			$data['filter_ruangan']
		);

		//Ambil data instansi dan ruangan
		$data['instansi'] = $this->user->getInstansi();
		$data['ruangan'] = $this->user->getUniqueRuangan();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/katalog-BUP', $data);
		$this->load->view('templates/footer');
	}


	//Menampilkan detail data pegawai yang sudah pensiun berdasarkan id
	public function detailDataPegawaiBUP($takah_bup_id)
	{
		$data['title'] = 'Katalog BUP';
		$data['subtitle'] = 'Detail Data Pegawai BUP';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['takah_bup'] = $this->user->get_takah_bup_by_id($takah_bup_id);
		$data['ruangan'] = $this->user->get_all_ruangan();

		$nip = $data['takah_bup']['NIP'];

		$usia_bup = $this->user->getUsiaBUP();
		$usia = $this->user->hitungUsiaPegawai($nip);
		$data['takah_bup']['usia'] = $usia;
		$data['takah_bup']['status_pensiun'] = ($usia >= $usia_bup) ? 'BUP/Pensiun' : 'Aktif';

		$tanggal_bup = $this->user->hitungTanggalBUP($nip);
		$data['takah_bup']['tanggal_bup'] = $tanggal_bup->format('d-m-Y');

		if (empty($data['takah']['NIP'])) {
			$data['takah']['NIP'] = '-';
			$data['takah']['Nama'] = '-';
			$data['takah']['NIP'] = '-';
			$data['takah']['usia'] = '-';
			$data['takah']['status_pensiun'] = '-';
			$data['takah']['tanggal_bup'] = '-';
		}
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/detail-data-pegawai-bup', $data);
		$this->load->view('templates/footer');
	}

	//Fungsi untuk mengedit data pegawai yang sudah bup berdasarkan id
	public function editDataPegawaiBUP($takah_bup_id)
	{
		$data['title'] = 'Katalog BUP';
		$data['subtitle'] = 'Edit Data Pegawai BUP';
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['takah_bup'] = $this->user->get_takah_bup_by_id($takah_bup_id);
		$data['ruangan'] = $this->user->get_all_ruangan();
		$data['rollopack'] = $this->user->get_all_rollopack();

		$this->form_validation->set_rules('nip', 'NIP', 'trim|min_length[18]|max_length[18]');
		$this->form_validation->set_rules('nama', 'Nama', 'trim');
		$this->form_validation->set_rules('instansi', 'Kode Instansi', 'trim');
		$this->form_validation->set_rules('ruangan_id', 'Ruangan', 'trim');
		$this->form_validation->set_rules('no_panggil', 'No Panggil', 'trim');
		$this->form_validation->set_rules('rollopack', 'No Rollopack', 'trim');
		$this->form_validation->set_rules('lemari', 'No Lemari', 'trim');
		$this->form_validation->set_rules('rak', 'No Rak', 'trim');

		$ruangan_id = $this->input->post('ruangan_id');
		$no_panggil = $this->input->post('no_panggil');
		$instansi = $this->input->post('instansi');
		$rollopack = $this->input->post('rollopack');
		$lemari = $this->input->post('lemari');
		$rak = $this->input->post('rak');
		$cekDataPegawai = $this->user->findDataPegawaiBUP($ruangan_id, $instansi, $no_panggil, $rollopack, $lemari, $rak);

		// Dapatkan nilai NIP yang 
		$nip = $this->input->post('nip');

		// Periksa apakah NIP sudah ada di database
		$cekNipExist = $this->user->getNIPByIdBUP($nip);

		// Jika NIP sudah ada dan takah_bup_id berbeda dari takah_bup_id saat ini
		if ($cekNipExist && $cekNipExist['takah_bup_id'] != $takah_bup_id) {
			// Tambahkan aturan is_unique untuk validasi untuk mencegah NIP duplikat
			$this->form_validation->set_rules('nip', 'NIP', 'trim|is_unique[takah.NIP]');
		} else {
			// Jika NIP tidak ada atau takah_bup_id sama, setel aturan validasi default
			$this->form_validation->set_rules('nip', 'NIP', 'trim');
		}

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('user/edit-data-pegawai-bup', $data);
			$this->load->view('templates/footer');
		} else {
			if ($cekDataPegawai) {
				if ($cekDataPegawai['takah_bup_id'] != $takah_bup_id) {
					$this->session->set_flashdata('message', "<div class='alert alert-success' role='alert'>No panggil $no_panggil sudah ada dalam ruangan dan instansi yang sama</div>");
					redirect("user/editdatapegawaiBUP/$takah_bup_id");
				} else {
					$this->user->editDataBUP($takah_bup_id);
					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
					redirect('user/katalogBUP');
				}
			} else {
				$this->user->editDataBUP($takah_bup_id);
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
				redirect('user/katalogBUP');
			}
		}
	}

	//Fungsi untuk menghapus katalog yang sudah pensiun
	public function deleteDataKatalogBUP($takah_bup_id)
	{
		$this->user->deleteDataKatalogBUP($takah_bup_id);
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
										Data telah dihapus!!</div>');
		redirect('user/katalogBUP');
	}

	//Fungsi untuk menampilkan halaman data ruangan dan rollopack
	public function ruanganRollopack()
	{
		$data['title'] = 'Ruangan/Rollopack';
		$data['user'] = $this->db->get_where('user', ['username' =>
		$this->session->userdata('username')])->row_array();

		// Ambil data keyword
		if ($this->input->post('submit')) {
			$data['keywordRR'] = $this->input->post('keywordRR');
			$this->session->set_userdata('keywordRR', $data['keywordRR']);
		} else {
			$data['keywordRR'] = $this->session->userdata('keywordRR');
		}

		// Pastikan $data['keywordRR'] memiliki nilai default jika null
		$data['keywordRR'] = $data['keywordRR'] ?? '';

		//Konfigurasi
		$this->db->like('nomor_rollopack', $data['keywordRR']);
		$this->db->or_like('instansi', $data['keywordRR']);
		$this->db->from('rollopack');

		//Konfigurasi pagination
		$config['base_url'] = 'http://localhost/indexing/user/ruanganrollopack';
		$config['total_rows'] = $this->db->count_all_results();
		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 3;
		$config['num_links'] = 5;

		//Inisialisasi pagination
		$this->pagination->initialize($config);

		$data['start'] = $this->uri->segment(3);
		$data['ruangan'] = $this->user->get_all_ruangan();
		$data['rollopack'] = $this->user->getRollopack($config['per_page'], $data['start'], $data['keywordRR']);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/ruangan-rollopack', $data);
		$this->load->view('templates/footer');
	}
}
