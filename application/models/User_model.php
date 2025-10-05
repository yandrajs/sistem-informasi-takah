<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //Fungsi untuk mengambil data pada tabel 'instansi'
    public function getInstansi()
    {
        return $this->db->get('instansi')->result_array();
    }

    //Fungsi untuk mencari takah di semua tabel instansi
    public function search_takah_all_tables($nip)
    {
        $instansi_tables = $this->db->get('instansi')->result_array();
        $results = [];

        foreach ($instansi_tables as $table) {
            $table_name = $table['nama_tabel'];
            $this->db->select($table_name . '.*, ruangan.nama_ruangan');
            $this->db->from($table_name);
            $this->db->join('ruangan', $table_name . '.ruangan_id = ruangan.ruangan_id');
            $this->db->where('NIP', $nip);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                $result['instansi'] = $table['instansi'];
                $usia = $this->hitungUsiaPegawai($result['NIP']);
                $result['usia'] = $usia;
                $result['status_pensiun'] = ($usia >= $this->getUsiaBUP()) ? 'BUP/Pensiun' : 'Aktif';
                $results[] = $result;
            }
        }

        return $results;
    }

    //Fungsi untuk mencari takah berdasarkan nama dan instansinya
    public function search_takah_by_name_and_instansi($nama, $instansi)
    {
        $instansi_info = $this->db->get_where('instansi', ['nama_tabel' => $instansi])->row_array();

        if (!$instansi_info) {
            return [];
        }

        $table_name = $instansi_info['nama_tabel'];

        $this->db->select($table_name . '.*, ruangan.nama_ruangan');
        $this->db->from($table_name);
        $this->db->join('ruangan', $table_name . '.ruangan_id = ruangan.ruangan_id');
        $this->db->like('Nama', $nama);
        $this->db->where('Instansi', $instansi_info['instansi']);

        $result = $this->db->get()->result_array();

        $usia_bup = $this->getUsiaBUP();

        foreach ($result as &$row) {
            $usia = $this->hitungUsiaPegawai($row['NIP']);
            $row['usia'] = $usia;
            $row['status_pensiun'] = ($usia >= $usia_bup) ? 'BUP/Pensiun' : 'Aktif';
        }

        return $result;
    }

    //Fungsi untuk mengambil data takah berdasarkan takah_id
    public function get_takah_by_takahid($takah_id)
    {
        $instansi_tables = $this->db->get('instansi')->result_array();

        foreach ($instansi_tables as $table) {
            $table_name = $table['nama_tabel'];
            $this->db->select($table_name . '.*, ruangan.nama_ruangan');
            $this->db->from($table_name);
            $this->db->join('ruangan', $table_name . '.ruangan_id = ruangan.ruangan_id');
            $this->db->where($table_name . '.takah_id', $takah_id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                $result['instansi'] = $table['instansi'];
                $usia = $this->hitungUsiaPegawai($result['NIP']);
                $result['usia'] = $usia;
                $result['status_pensiun'] = ($usia >= $this->getUsiaBUP()) ? 'BUP/Pensiun' : 'Aktif';
                return [$result];
            }
        }

        return [];
    }

    //Fungsi untuk mengambi seluruh data ruangan dan diurutkan berdasarkan instansi
    public function get_all_ruangan()
    {
        $this->db->select('*');
        $this->db->from('ruangan');
        $this->db->group_by('nama_ruangan');
        $query = $this->db->get();
        return $query->result_array();
    }

    //Fungsi untuk mengambil seluruh data rollopack dan diurutkan berdasarkan instansi
    public function get_all_rollopack()
    {
        $this->db->select('*');
        $this->db->from('rollopack');
        $this->db->group_by('instansi');
        $query = $this->db->get();
        return $query->result_array();
    }

    //Fungsi untung mengambil seluruh data pada tabel 'instansi'
    public function get_all_tables()
    {
        $this->db->select('*');
        $this->db->from('instansi');

        $result = $this->db->get()->result_array();
        return $result;
    }

    //Fungsi untuk mengambil nama_tabel bersadarkan instansi
    public function get_table_instansi($instansi)
    {
        $this->db->select('nama_tabel');
        $this->db->where('instansi', $instansi);
        $this->db->from('instansi');

        $result = $this->db->get()->row_array();
        return $result ? $result['nama_tabel'] : null;
    }

    //Fungsi untuk mencari posisi yang kosong
    public function findEmptyPositions($ruangan_id, $instansi, $nama_tabel)
    {
        $this->db->select('ruangan_id, no_rollopack, no_lemari, no_rak, No_Panggil');
        $this->db->from($nama_tabel);
        $this->db->where('ruangan_id', $ruangan_id);
        $this->db->where('Instansi', $instansi);
        $this->db->order_by('no_rollopack, no_lemari, no_rak, No_Panggil');
        $results = $this->db->get()->result_array();

        $emptyPositions = [];
        $expectedNO = 1;

        foreach ($results as $row) {
            if ($row['No_Panggil'] != $expectedNO) {
                $emptyPositions[] = [
                    'ruangan_id' => $ruangan_id,
                    'no_rollopack' => $row['no_rollopack'],
                    'no_lemari' => $row['no_lemari'],
                    'no_rak' => $row['no_rak'],
                    'Instansi' => $instansi,
                    'No_Panggil' => $expectedNO,
                ];
            }
            $expectedNO++;
        }

        return $emptyPositions;
    }

    //Fungsi untuk mengambil no_panggil selanjutnya
    public function getNextNumbers($kode_instansi, $ruangan_id)
    {
        $nama_tabel = $this->get_table_instansi($kode_instansi);
        //Dapatkan No_Panggil maksimal untuk Kode_instansi yang diberikan
        $this->db->select_max('No_Panggil');
        $this->db->where('Instansi', $kode_instansi);
        $this->db->where('ruangan_id', $ruangan_id);
        $query = $this->db->get($nama_tabel);
        $max_no_panggil = $query->row()->No_Panggil;
        $next_no_panggil = $max_no_panggil ? $max_no_panggil + 1 : 1;

        //Pastikan No_Panggil berikutnya unik
        while (!$this->is_no_panggil_unique($next_no_panggil, $kode_instansi, $ruangan_id, $nama_tabel)) {
            $next_no_panggil++;
        }

        return $next_no_panggil;
    }

    //Fungsi untuk mencari rollopack yang sesuai
    public function findRollopack($ruangan_id, $instansi, $no_panggil)
    {
        $query = $this->db->select('*');
        $this->db->from('rollopack');
        $this->db->where('ruangan_id', $ruangan_id);
        $this->db->where('instansi', $instansi);
        $this->db->where("$no_panggil >= no_panggil_awal AND $no_panggil <= no_panggil_akhir");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    //Fungsi untuk mencari lemari yang sesuai
    public function findLemari($ruangan_id, $no_rollopack, $no_lemari, $no_rak, $instansi)
    {
        $nama_tabel = $this->get_table_instansi($instansi);

        $this->db->where('ruangan_id', $ruangan_id);
        $this->db->where('no_rollopack', $no_rollopack);
        $this->db->where('no_lemari', $no_lemari);
        $this->db->where('no_rak', $no_rak);
        $this->db->where('Instansi', $instansi);
        return $this->db->count_all_results($nama_tabel);
    }

    //Fungsi untuk menambahkan data pegawai ke dalam tabel instansi tersebut
    public function addData($data, $instansi)
    {
        if ($this->nip_exists($data['NIP'], $instansi)) {
            return 'duplicate_nip';
        }

        $kode_instansi = htmlspecialchars($this->input->post('instansi', true));

        $instansi = $this->get_table_instansi($kode_instansi);

        if (!$this->is_no_panggil_unique($data['No_Panggil'], $kode_instansi, $data['ruangan_id'], $instansi)) {
            return false;
        }

        $result = $this->db->insert($instansi, $data);

        echo "Debug - Insert Query: " . $this->db->last_query() . "<br>";
        if (!$result) {
            echo "Debug - DB Error: " . $this->db->error()['message'] . "<br>";
        }

        return $result;
    }

    //Fungsi untuk mengambil nama ruangan berdasarkan id
    public function getRoomNameById($ruangan_id)
    {
        $query = $this->db->select('nama_ruangan')
            ->from('ruangan')
            ->where('ruangan_id', $ruangan_id)
            ->get();

        if ($query->num_rows() > 0) {
            return $query->row()->nama_ruangan;
        } else {
            return 'Unknown Room';
        }
    }

    //Fungsi untuk mencari data pegawai
    public function findDataPegawai($ruangan_id, $instansi, $no_panggil, $rollopack, $lemari, $rak)
    {
        $table_name = $this->get_table_instansi($instansi);

        if (!$table_name) {
            return null;
        }

        $this->db->where('ruangan_id', $ruangan_id);
        $this->db->where('Instansi', $instansi);
        $this->db->where('no_panggil', $no_panggil);
        $this->db->where('no_rollopack', $rollopack);
        $this->db->where('no_lemari', $lemari);
        $this->db->where('no_rak', $rak);

        $query = $this->db->get($table_name);

        return $query->row_array();
    }

    //Fungsi pembantu untuk memeriksa apakah NIP ada di database
    public function nip_exists($nip, $instansi)
    {
        // Pastikan $nip aman dari SQL injection
        $nip = $this->db->escape_str($nip);

        $nama_tabel = $this->get_table_instansi($instansi);

        // Query untuk memeriksa keberadaan NIP dalam database
        $this->db->where('nip', $nip);
        $query = $this->db->get($nama_tabel);

        // Jika ditemukan setidaknya satu baris, berarti NIP sudah ada
        return ($query->num_rows() > 0);
    }

    // Fungsi untuk memeriksa apakah No Panggil sudah ada dalam ruangan dan instansi yang sama
    public function no_panggil_exists($no_panggil, $ruangan_id, $instansi)
    {
        $nama_tabel = $this->get_table_instansi($instansi);

        $this->db->where('No_Panggil', $no_panggil);
        $this->db->where('ruangan_id', $ruangan_id);
        $this->db->where('Instansi', $instansi);
        $query = $this->db->get($nama_tabel);
        return ($query->num_rows() > 0);
    }

    //Fungsi untuk memeriksa apakah rollopack sudah ada dalam ruangan dan instansi yang sama
    public function rollopack_exists($ruangan_id, $kode_instansi)
    {
        $this->db->where('ruangan_id', $ruangan_id);
        $this->db->where('instansi', $kode_instansi);
        $query = $this->db->get('rollopack');
        return $query->num_rows() > 0;
    }

    //Fungsi untuk mencari posisi yang sesuai dengan no panggil, instansi dan ruangan
    public function find_storage_position($ruangan_id, $kode_instansi, $no_panggil)
    {
        //Dapatkan semua rollopack untuk ruangan dan institusi yang diberikan
        $query = $this->db->select('rollopack_id, nomor_rollopack, kapasitas_per_rak, jumlah_lemari_per_rollopack, jumlah_rak_per_lemari, no_panggil_awal, no_panggil_akhir')
            ->from('rollopack')
            ->where('ruangan_id', $ruangan_id)
            ->where('instansi', $kode_instansi)
            ->get();

        $rollopacks = $query->result_array();

        foreach ($rollopacks as $rollopack) {
            if ($no_panggil >= $rollopack['no_panggil_awal'] && $no_panggil <= $rollopack['no_panggil_akhir']) {
                //Hitung posisi di dalam rollopack
                $total_capacity = $rollopack['kapasitas_per_rak'] * $rollopack['jumlah_lemari_per_rollopack'] * $rollopack['jumlah_rak_per_lemari'];
                $position_in_rollopack = $no_panggil - $rollopack['no_panggil_awal'] + 1;

                $rak = ceil($position_in_rollopack / $rollopack['kapasitas_per_rak']);
                $lemari = ceil($rak / $rollopack['jumlah_rak_per_lemari']);
                $rak = $rak % $rollopack['jumlah_rak_per_lemari'] ?: $rollopack['jumlah_rak_per_lemari'];

                return [
                    'no_rollopack' => $rollopack['nomor_rollopack'],
                    'no_lemari' => $lemari,
                    'no_rak' => $rak
                ];
            }
        }

        return null; //Jika tidak ditemukan rollopack yang sesuai maka menghasilkan nilai null
    }

    //Fungsi untuk memasukkan data impor ke dalam tabel sesuai instansinya
    public function insert_batch_by_instansi($instansi, $data)
    {
        if (empty($data)) {
            return 0;
        }

        $table_name = $this->get_instansi_table_name($instansi);

        // Check if the table exists, if not, create it
        if (!$this->db->table_exists($table_name)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Instansi tidak sesuai/belum ada tabel dengan instansi tersebut</div>');
            redirect('user/import');
        }

        $this->db->insert_batch($table_name, $data);
        $affected_rows = $this->db->affected_rows();
        log_message('debug', 'Inserted ' . $affected_rows . ' rows into ' . $table_name . ' table');
        return $affected_rows;
    }

    //Fungsi untuk menampilkan jumlah data sesuai dengan keyword atau filter yang diinputkan oleh pengguna
    public function countFilteredKatalog($keyword = '', $filter_instansi = '', $filter_ruangan = '')
    {
        if (empty($filter_instansi)) {
            return 0; // Return 0 if no instansi is selected
        }

        $table_name = $this->getTableNameForInstansi($filter_instansi);
        if (!$table_name) {
            return 0; // Return 0 if table name is not found
        }

        $this->db->from($table_name);
        $this->db->join('ruangan', "$table_name.ruangan_id = ruangan.ruangan_id");

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->or_like('nip', $keyword);
            $this->db->or_like('instansi', $keyword);
            $this->db->or_like('No_Panggil', $keyword);
            $this->db->group_end();
        }

        if (!empty($filter_ruangan)) {
            $this->db->where('ruangan.nama_ruangan', $filter_ruangan);
        }

        return $this->db->count_all_results();
    }

    //Fungsi untuk mengambil data katalog berdasarkan hasil pencarian keyword atau filter
    public function getKatalog($limit, $start, $keyword = '', $sort_by = 'instansi', $sort_order = 'ASC', $filter_instansi = '', $filter_ruangan = '')
    {
        if (empty($filter_instansi)) {
            return []; // Return empty array if no instansi is selected
        }

        $table_name = $this->getTableNameForInstansi($filter_instansi);
        if (!$table_name) {
            return []; // Return empty array if table name is not found
        }

        $this->db->select("$table_name.*, ruangan.nama_ruangan");
        $this->db->from($table_name);
        $this->db->join('ruangan', "$table_name.ruangan_id = ruangan.ruangan_id");

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->or_like('nip', $keyword);
            $this->db->or_like('instansi', $keyword);
            $this->db->or_like('No_Panggil', $keyword);
            $this->db->group_end();
        }

        if (!empty($filter_ruangan)) {
            $this->db->where('ruangan.nama_ruangan', $filter_ruangan);
        }

        if ($sort_by) {
            $this->db->order_by($sort_by, $sort_order);
        }

        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();

        $usia_bup = $this->getUsiaBUP();

        foreach ($result as &$row) {
            $usia = $this->hitungUsiaPegawai($row['NIP']);
            $row['usia'] = $usia;
            $row['status_pensiun'] = ($usia >= $usia_bup) ? 'BUP/Pensiun' : 'Aktif';
        }

        return $result;
    }

    //Mengambil nama ruangan tabel 'ruangan'
    public function getUniqueRuangan()
    {
        $this->db->select('nama_ruangan');
        $this->db->from('ruangan');
        $this->db->group_by('nama_ruangan');
        $result = $this->db->get()->result_array();
        return array_column($result, 'nama_ruangan');
    }

    //Fungsi untung mengambil data takah berdasarkan id
    public function get_takah_by_id($takah_id)
    {
        $table_name = $this->getTableNameForInstansi($this->session->userdata('filter_instansi'));
        if (!$table_name) {
            return null;
        }

        $this->db->select("$table_name.*, ruangan.nama_ruangan");
        $this->db->from($table_name);
        $this->db->join('ruangan', "$table_name.ruangan_id = ruangan.ruangan_id");
        $this->db->where("$table_name.takah_id", $takah_id);

        $result = $this->db->get()->row_array();

        if ($result) {
            $result['usia'] = $this->hitungUsiaPegawai($result['NIP']);
            $result['status_pensiun'] = ($result['usia'] >= $this->getUsiaBUP()) ? 'BUP/Pensiun' : 'Aktif';
            $result['tanggal_bup'] = $this->hitungTanggalBUP($result['NIP'])->format('d-m-Y');
        }

        return $result;
    }

    public function getIdByNIP($nip, $instansi)
    {
        $nama_tabel = $this->get_table_instansi($instansi);
        return $this->db->get_where($nama_tabel, ['nip' => $nip])->row_array();
    }

    //Fungsi untuk mengedit data takah berdasarkan id
    public function editData($takah_id)
    {
        $table_name = $this->getTableNameForInstansi($this->session->userdata('filter_instansi'));
        if (!$table_name) {
            return null;
        }

        $data = [
            'NIP' => htmlspecialchars($this->input->post('nip', true)),
            'Nama' => htmlspecialchars($this->input->post('nama', true)),
            'instansi' => $this->input->post('instansi', true),
            'ruangan_id' => $this->input->post('ruangan_id', true),
            'no_panggil' => $this->input->post('no_panggil', true),
            'no_rollopack' => $this->input->post('rollopack', true),
            'no_lemari' => $this->input->post('lemari', true),
            'no_rak' => $this->input->post('rak', true),
            'D2NIP' => $this->input->post('d2nip') ? 1 : 0,
            'Ijazah' => $this->input->post('ijazah') ? 1 : 0,
            'DRH' => $this->input->post('drh') ? 1 : 0,
            'SKCPNS' => $this->input->post('skcpns') ? 1 : 0,
            'SKPNS' => $this->input->post('skpns') ? 1 : 0,
            'SK_Perubahan_Data' => $this->input->post('sk_perubahan_data') ? 1 : 0,
            'SK_Jabatan' => $this->input->post('sk_jabatan') ? 1 : 0,
            'SK_Pemberhentian' => $this->input->post('sk_pemberhentian') ? 1 : 0,
            'SK_Pensiun' => $this->input->post('sk_pensiun') ? 1 : 0,
        ];

        $this->db->where('takah_id', $takah_id);
        return $this->db->update($table_name, $data);
    }

    //Fungsi untuk mengosongkan data pegawai
    public function kosongkanDataPegawai($takah_id)
    {
        $table_name = $this->getTableNameForInstansi($this->session->userdata('filter_instansi'));
        if (!$table_name) {
            return null;
        }

        // Data yang ingin dihapus atau dikosongkan
        $data = [
            'NIP' => NULL,
            'Nama' => NULL,
            'D2NIP' => NULL,
            'Ijazah' => NULL,
            'DRH' => NULL,
            'SKCPNS' => NULL,
            'SKPNS' => NULL,
            'SK_Perubahan_Data' => NULL,
            'SK_Jabatan' => NULL,
            'SK_Pemberhentian' => NULL,
            'SK_Pensiun' => NULL,
        ];

        // Update data kecuali kolom-kolom tertentu
        $this->db->where('takah_id', $takah_id);
        $this->db->update($table_name, $data);
    }

    //Fungsi untuk memindahkan data pegawai yang sudah pensiun ke dalam tabel talah_bup
    public function transferToBUP($takah_id)
    {
        //Mulai transaksi
        $this->db->trans_start();

        $table_name = $this->getTableNameForInstansi($this->session->userdata('filter_instansi'));
        if (!$table_name) {
            return null;
        }

        //Dapatkan data dari tabel takah
        $takah_data = $this->db->get_where($table_name, ['takah_id' => $takah_id])->row_array();

        if ($takah_data) {
            //Masukkan data ke dalam tabel takah_bup
            $bup_data = [
                'ruangan_id' => $takah_data['ruangan_id'],
                'no_rollopack' => $takah_data['no_rollopack'],
                'no_lemari' => $takah_data['no_lemari'],
                'no_rak' => $takah_data['no_rak'],
                'NIP' => $takah_data['NIP'],
                'Nama' => $takah_data['Nama'],
                'Kode_Instansi' => $takah_data['Instansi'],
                'D2NIP' => $takah_data['D2NIP'],
                'Ijazah' => $takah_data['Ijazah'],
                'DRH' => $takah_data['DRH'],
                'SKCPNS' => $takah_data['SKCPNS'],
                'SKPNS' => $takah_data['SKPNS'],
                'SK_Perubahan_Data' => $takah_data['SK_Perubahan_Data'],
                'SK_Jabatan' => $takah_data['SK_Jabatan'],
                'SK_Pemberhentian' => $takah_data['SK_Pemberhentian'],
                'SK_Pensiun' => $takah_data['SK_Pensiun'],
                'No_Panggil' => $takah_data['No_Panggil']
            ];
            $this->db->insert('takah_bup', $bup_data);

            //Perbarui tabel takah untuk menghapus data
            $update_data = [
                'NIP' => '', // Kosongkan kolom NIP
                'Nama' => '', // Kosongkan kolom Nama
                'D2NIP' => null, // Kosongkan kolom D2NIP
                'Ijazah' => null, // Kosongkan kolom Ijazah
                'DRH' => null, // Kosongkan kolom DRH
                'SKCPNS' => null, // Kosongkan kolom SKCPNS
                'SKPNS' => null, // Kosongkan kolom SKPNS
                'SK_Perubahan_Data' => null, // Kosongkan kolom SK Perubahan Data
                'SK_Jabatan' => null, // Kosongkan kolom SK Jabatan
                'SK_Pemberhentian' => null, // Kosongkan kolom SK Pemberhentian
                'SK_Pensiun' => null // Kosongkan kolom SK Pensiun
            ];
            $this->db->where('takah_id', $takah_id);
            $this->db->update($table_name, $update_data);
        }

        //Selesaikan transaksi
        $this->db->trans_complete();

        //Kembalikan true jika transaksi berhasil, false jika tidak
        return $this->db->trans_status();
    }

    //Fungsi untuk menghapus data katalog berdasarkan id
    public function deleteDataKatalog($takah_id)
    {
        $table_name = $this->getTableNameForInstansi($this->session->userdata('filter_instansi'));

        $this->db->where('takah_id', $takah_id);
        $this->db->delete($table_name);
    }

    //Fungsi untuk menampilkan jumlah data pegawai yang sudah pensiun sesuai dengan keyword atau filter yang diinputkan oleh pengguna
    public function countFilteredKatalogBUP($keyword = '', $filter_instansi = '', $filter_ruangan = '')
    {
        $this->db->from('takah_bup');
        $this->db->join('ruangan', 'takah_bup.ruangan_id = ruangan.ruangan_id');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->or_like('nip', $keyword);
            $this->db->or_like('Kode_instansi', $keyword);
            $this->db->group_end();
        }

        if (!empty($filter_instansi)) {
            $this->db->where('Kode_instansi', $filter_instansi);
        }

        if (!empty($filter_ruangan)) {
            $this->db->where('ruangan.nama_ruangan', $filter_ruangan);
        }

        return $this->db->count_all_results();
    }

    //Fungsi untuk mendapatkan data katalog pegawai yang sudah pensiun dari tabel 'takah_bup' dengan pagination dan pencarian keyword
    public function getKatalogBUP($limit, $start, $keyword = '', $sort_by = 'No_Panggil', $sort_order = 'ASC', $filter_instansi = '', $filter_ruangan = '')
    {
        $this->db->select('takah_bup.*, ruangan.nama_ruangan');
        $this->db->from('takah_bup');
        $this->db->join('ruangan', 'takah_bup.ruangan_id = ruangan.ruangan_id');

        //Mengambil data sesuai dengan keyword pencarian
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->or_like('nip', $keyword);
            $this->db->or_like('no_panggil', $keyword);
            $this->db->or_like('Kode_instansi', $keyword);
            $this->db->group_end();
        }

        //Mengambil data sesuai dengan instansi yang dipilih
        if (!empty($filter_instansi)) {
            $this->db->where('Kode_instansi', $filter_instansi);
        }

        //Mengambil data yang sesuai dengan ruangan yang dipilih
        if (!empty($filter_ruangan)) {
            $this->db->where('ruangan.nama_ruangan', $filter_ruangan);
        }

        //Membatasi data yang ditampilkan per halaman
        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();

        //Mengambil angka BUP
        $usia_bup = $this->getUsiaBUP();

        //Menghitung usai dan status
        foreach ($result as &$row) {
            $usia = $this->hitungUsiaPegawai($row['NIP']);
            $row['usia'] = $usia;
            $row['status_pensiun'] = ($usia >= $usia_bup) ? 'BUP/Pensiun' : 'Aktif';
        }

        return $result;
    }

    //Fungsi untuk mengambil data takah pegawai yang sudah pensiun berdasarkan id
    public function get_takah_bup_by_id($takah_bup_id)
    {
        $this->db->select('takah_bup.*, ruangan.nama_ruangan');
        $this->db->from('takah_bup');
        $this->db->join('ruangan', 'takah_bup.ruangan_id = ruangan.ruangan_id');
        $this->db->where('takah_bup_id', $takah_bup_id);
        return $this->db->get()->row_array();
    }

    //Fungsi untuk mengambil angka BUP
    public function getUsiaBUP()
    {
        $query = $this->db->get('usia_bup');
        return $query->row()->usia_bup;
    }

    //Fungsi untuk menghitung usia pegawai berdasarkan NIP
    public function hitungUsiaPegawai($nip)
    {
        // Cek apakah NIP tidak kosong atau NULL
        if (empty($nip)) {
            return ''; // Kembalikan string kosong jika NIP kosong
        }
        // Ambil 8 digit pertama dari NIP
        $tanggalLahir = substr($nip, 0, 8);

        // Pisahkan menjadi tahun, bulan, dan tanggal
        $tahun = substr($tanggalLahir, 0, 4);
        $bulan = substr($tanggalLahir, 4, 2);
        $tanggal = substr($tanggalLahir, 6, 2);

        // Buat objek DateTime untuk tanggal lahir
        $tglLahir = DateTime::createFromFormat('Ymd', $tanggalLahir);

        // Buat objek DateTime untuk tanggal saat ini
        $sekarang = new DateTime();

        // Hitung selisih
        $selisih = $sekarang->diff($tglLahir);

        // Kembalikan usia dalam tahun
        return $selisih->y;
    }

    //Fungsi untuk menghitung tanggal pensiun pegawai berdasarkan NIP
    public function hitungTanggalBUP($nip)
    {
        if (empty($nip)) {
            return DateTime::createFromFormat('Y-m-d', '0000-00-00'); // Kembalikan "0000-00-00" jika NIP kosong
        }
        $tanggalLahir = substr($nip, 0, 8);
        $tahun = substr($tanggalLahir, 0, 4);
        $bulan = substr($tanggalLahir, 4, 2);
        $tanggal = substr($tanggalLahir, 6, 2);

        $tglLahir = DateTime::createFromFormat('Ymd', $tanggalLahir);
        $usiaBUP = $this->getUsiaBUP();

        $tanggalBUP = clone $tglLahir;
        $tanggalBUP->modify("+$usiaBUP years");

        return $tanggalBUP;
    }

    //Fungsi untuk mencari data pegawai yang sudah pensiun
    public function findDataPegawaiBUP($ruangan_id, $instansi, $no_panggil, $rollopack, $lemari, $rak)
    {
        $query = $this->db->select('*');
        $this->db->from('takah_bup');
        $this->db->where('ruangan_id', $ruangan_id);
        $this->db->where('kode_instansi', $instansi);
        $this->db->where('no_panggil', $no_panggil);
        $this->db->where('no_rollopack', $rollopack);
        $this->db->where('no_lemari', $lemari);
        $this->db->where('no_rak', $rak);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function getNIPByIdBUP($nip)
    {
        return $this->db->get_where('takah_bup', ['nip' => $nip])->row_array();
    }

    //Fungsi untuk mengedit data pegawai yang sudah pensiun berdasarkan id
    public function editDataBUP($takah_bup_id)
    {
        $data = [
            'NIP' => htmlspecialchars($this->input->post('nip', true)),
            'Nama' => htmlspecialchars($this->input->post('nama', true)),
            'Kode_instansi' => $this->input->post('instansi', true),
            'ruangan_id' => $this->input->post('ruangan_id', true),
            'no_panggil' => $this->input->post('no_panggil', true),
            'no_rollopack' => $this->input->post('rollopack', true),
            'no_lemari' => $this->input->post('lemari', true),
            'no_rak' => $this->input->post('rak', true),
            'D2NIP' => $this->input->post('d2nip') ? 1 : 0,
            'Ijazah' => $this->input->post('ijazah') ? 1 : 0,
            'DRH' => $this->input->post('drh') ? 1 : 0,
            'SKCPNS' => $this->input->post('skcpns') ? 1 : 0,
            'SKPNS' => $this->input->post('skpns') ? 1 : 0,
            'SK_Perubahan_Data' => $this->input->post('sk_perubahan_data') ? 1 : 0,
            'SK_Jabatan' => $this->input->post('sk_jabatan') ? 1 : 0,
            'SK_Pemberhentian' => $this->input->post('sk_pemberhentian') ? 1 : 0,
            'SK_Pensiun' => $this->input->post('sk_pensiun') ? 1 : 0,
        ];

        $this->db->where('takah_bup_id', $takah_bup_id);
        return $this->db->update('takah_bup', $data);
    }

    //Fungsi untuk menghapus data katalog yang sudah pensiun berdasarkan id
    public function deleteDataKatalogBUP($takah_bup_id)
    {
        $this->db->where('takah_bup_id', $takah_bup_id);
        $this->db->delete('takah_bup');
    }

    //Fungsi untuk mendapatkan data rollopack dari tabel 'rollopack' dengan pagination dan pencarian keyword
    public function getRollopack($limit, $start, $keyword = '')
    {
        $this->db->select('rollopack.*, ruangan.nama_ruangan');
        $this->db->from('rollopack');
        $this->db->join(
            'ruangan',
            'rollopack.ruangan_id = ruangan.ruangan_id'
        );

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nomor_rollopack', $keyword);
            $this->db->or_like('instansi', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    //Fungsi untuk memasukkan data impor kedalam tabel
    public function insert_batch($nama_tabel, $data)
    {
        if (empty($data)) {
            return 0;
        }

        $valid_data = [];
        foreach ($data as $item) {
            $position = $this->find_storage_position($item['ruangan_id'], $item['instansi'], $item['No_Panggil']);
            $nama_tabel = $this->get_table_instansi($item['instansi']);
            if ($position && $this->is_no_panggil_unique($item['No_Panggil'], $item['instansi'], $item['ruangan_id'], $nama_tabel)) {
                $item['no_rollopack'] = $position['no_rollopack'];
                $item['no_lemari'] = $position['no_lemari'];
                $item['no_rak'] = $position['no_rak'];
                $valid_data[] = $item;
            }
        }

        if (empty($valid_data)) {
            return 0;
        }

        $this->db->insert_batch($nama_tabel, $valid_data);
        $affected_rows = $this->db->affected_rows();
        log_message('debug', 'Inserted ' . $affected_rows . ' rows into takah table');
        return $affected_rows;
    }

    //Fungsi untuk mencari no panggil yang sama sesuai dengan instansi dan ruangan
    public function is_no_panggil_unique($no_panggil, $instansi, $ruangan_id, $nama_tabel)
    {

        $query = $this->db->select('*');
        $this->db->from($nama_tabel);
        $this->db->where('No_Panggil', $no_panggil);
        $this->db->where('Instansi', $instansi);
        $this->db->where('ruangan_id', $ruangan_id);
        $query = $this->db->get();

        // $query = $this->db->where('No_Panggil', $no_panggil)
        //     ->where('Instansi', $instansi)
        //     ->where('ruangan_id', $ruangan_id)
        //     ->get($nama_tabel);

        return $query->num_rows() === 0;
    }

    public function getTableNameForInstansi($instansi_id)
    {
        $result = $this->db->get_where('instansi', ['instansi_id' => $instansi_id])->row_array();
        return $result ? $result['nama_tabel'] : null;
    }

    private function get_instansi_table_name($instansi)
    {
        // Convert instansi name to a valid table name
        $table_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $instansi));
        return $table_name;
    }
}
