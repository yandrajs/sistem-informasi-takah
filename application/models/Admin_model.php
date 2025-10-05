<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    //Fungsi untuk mendapatkan data role dari tabel 'user_role' dengan pagination dan pencarian keyword
    public function getRole($limit, $start, $keyword = '')
    {
        $this->db->select('*');
        $this->db->from('user_role');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('role', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    //Fungsi untuk mengedit data role berdasarkan id
    public function editRole()
    {
        $data = [
            'role' => $this->input->post('role')
        ];

        $this->db->where('role_id', $this->input->post('role_id'));
        $this->db->update('user_role', $data);
    }

    //Fungsi untuk menghapus data role berdasarkan id
    public function modelDeleteRole($role_id)
    {
        $this->db->where('role_id', $role_id);
        $this->db->delete('user_role');
    }

    //Fungsi untuk mendapatkan data akun dari tabel 'user' dengan pagination dan pencarian keyword
    public function getAccount($limit, $start, $keyword = '')
    {
        // Mengambil data semua user beserta rolenya, kecuali user yang sedang login
        $this->db->select('user.*, user_role.role as role_name');
        $this->db->from('user');
        $this->db->join('user_role', 'user.role_id = user_role.role_id');
        $this->db->where('user.username !=', $this->session->userdata('username'));

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    //Fungsi untuk membuat akun baru
    public function createAccount()
    {
        $data = [
            'nama' => htmlspecialchars($this->input->post('nama', true)),
            'username' => htmlspecialchars($this->input->post('username', true)),
            'password' => password_hash(
                $this->input->post('password1'),
                PASSWORD_DEFAULT
            ),
            'role_id' => $this->input->post('role_id')
        ];

        return $this->db->insert('user', $data);
    }

    //Fungsi untuk menghapus akun berdasarkan id
    public function deleteAccount($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete('user');
    }

    //Fungsi untuk mengedit akun berdasarkan id
    public function editAccount()
    {
        $user_id = $this->input->post('user_id');
        $data = [
            'nama' => htmlspecialchars($this->input->post('nama', true)),
            'username' => htmlspecialchars($this->input->post('username', true)),
            'role_id' => $this->input->post('role_id')
        ];

        // Hanya update password jika diisi
        if ($this->input->post('password')) {
            $data['password'] = password_hash(
                $this->input->post('password'),
                PASSWORD_DEFAULT
            );
        }

        $this->db->where('user_id', $user_id);
        $this->db->update('user', $data);
    }

    //Fungsi untuk menghapus data denah berdasarkan id
    public function deleteDataDenah($denah_id)
    {
        // Ambil nama file gambar sebelum menghapus data
        $denah = $this->db->get_where('denah', ['denah_id' => $denah_id])->row();

        if ($denah) {
            $file_path = FCPATH . 'assets/img/denah/' . $denah->gambar;

            // Hapus file jika ada
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Hapus data dari database
            $this->db->where('denah_id', $denah_id);
            $this->db->delete('denah');

            return true;
        }
        return false;
    }

    //Fungsi untuk mendapatkan data denah dari tabel 'denah' dengan pagination dan pencarian keyword
    public function getDenah($limit, $start, $keyword = '')
    {
        // Mengambil semua data user beserta rolenya, kecuali user yang sedang login
        $this->db->select('*');
        $this->db->from('denah');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    //fungsi untuk menghitung jumlah data pada tabel denah
    public function countAllDenah()
    {
        $this->db->select('*');
        $this->db->from('denah');
        return $this->db->get('')->num_rows();
    }

    //fungsi untuk mengedit denah berdasarkan id
    public function editDenah($denah_id, $data)
    {
        $this->db->where('denah_id', $denah_id);
        return $this->db->update('denah', $data);
    }

    //Fungsi untuk mendapatkan angka BUP
    public function getUsiaBUP()
    {
        $query = $this->db->get('usia_bup');
        return $query->row()->usia_bup;
    }

    //Fungsi untuk menghitung jumlah data pada tabel 'usia_bup'
    public function jumlahDataBUP()
    {
        $query = $this->db->get('usia_bup');

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Fungsi untuk mengubah data angka BUP
    public function setUsiaBUP($usia)
    {
        $data = ['usia_bup' => $usia];
        return $this->db->update('usia_bup', $data);
    }


    //Fungsi untuk mendapatkan data ruangan dari tabel 'ruangan' dengan pagination dan pencarian keyword
    public function getRuangan($limit, $start, $keyword = '')
    {
        $this->db->select('*');
        $this->db->from('ruangan');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_ruangan', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    //fungsi untuk menghitung data pada tabel 'ruangan'
    public function countAllRuangan()
    {
        $this->db->select('*');
        $this->db->from('ruangan');
        return $this->db->get('')->num_rows();
    }


    //Fungsi untuk mengambil data ruangan berdasarkan id_ruangan
    public function getRuanganById($ruangan_id)
    {
        return $this->db->get_where('Ruangan', array('ruangan_id' => $ruangan_id))->row_array();
    }

    //Fungsi untuk menambahkan ruangan baru
    public function insert_ruangan($data)
    {
        return $this->db->insert('Ruangan', $data);
    }

    //Fungsi untuk mengedit ruangan berdasarkan id
    public function update_ruangan($ruangan_id, $data)
    {
        $this->db->where(
            'ruangan_id',
            $ruangan_id
        );

        return $this->db->update('ruangan', $data);
    }

    //Fungsi untuk memeriksa rollopack pada ruangan
    public function check_rollopack_exists($ruangan_id)
    {
        $this->db->where('ruangan_id', $ruangan_id);
        $query = $this->db->get('rollopack');
        return $query->num_rows() > 0;
    }

    //Fungsi untuk menghapus data ruangan berdasarkan id
    public function delete_ruangan($ruangan_id)
    {
        if ($this->check_rollopack_exists($ruangan_id)) {
            return false; // Jika masih ada data terkait, return false
        }
        return $this->db->delete('Ruangan', array('ruangan_id' => $ruangan_id));
    }

    //Fungsi untuk mengambil semua data rollopack
    public function get_all_rollopacks()
    {
        $this->db->select('rollopack.*, ruangan.nama_ruangan');
        $this->db->from('rollopack');
        $this->db->join(
            'ruangan',
            'rollopack.ruangan_id = ruangan.ruangan_id'
        );
        return $this->db->get()->result_array();
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

    //Fungsi untuk menghitung jumlah data rollopack pada tabel 'rollopack'
    public function countAllRollopack()
    {
        $this->db->select('rollopack.*, ruangan.nama_ruangan');
        $this->db->from('rollopack');
        $this->db->join(
            'ruangan',
            'rollopack.ruangan_id = ruangan.ruangan_id'
        );
        return $this->db->get('')->num_rows();
    }

    //Fungsi untuk mengambil data rollopack sesuai dengan id_rollopack
    public function getRollopackById($rollopack_id)
    {
        return $this->db->get_where('Rollopack', array('rollopack_id' => $rollopack_id))->row_array();
    }

    //Fungsi untuk menambahkan rollopack baru
    public function insert_rollopack($data)
    {
        return $this->db->insert('Rollopack', $data);
    }

    //Fungsi untuk mengedit data rollopack
    public function update_rollopack($rollopack_id, $data)
    {
        $this->db->where('rollopack_id', $rollopack_id);
        return $this->db->update('Rollopack', $data);
    }

    //Fugsi untuk menghapus data rollopack
    public function delete_rollopack($rollopack_id)
    {
        return $this->db->delete('Rollopack', array('rollopack_id' => $rollopack_id));
    }

    //Fungsi untung mengambil seluruh data pada tabel 'instansi'
    public function get_all_tables()
    {
        $this->db->select('*');
        $this->db->from('instansi');

        $result = $this->db->get()->result_array();
        return $result;
    }

    //FUngsi untuk mengambil data instansi berdasarkan id 
    public function get_instansi($instansi_id)
    {
        return $this->db->get_where('instansi', ['instansi_id' => $instansi_id])->row_array();
    }

    //Fungsi untuk menghapus data berdasarkan id pada tabel 'instansi dan menghapus tabel dengan instansi tersebut dari database
    public function delete_instansi($instansi_id, $nama_tabel)
    {
        // Start a transaction
        $this->db->trans_start();

        // Delete the record from the instansi table
        $this->db->delete('instansi', ['instansi_id' => $instansi_id]);

        // Drop the associated table
        $this->dbforge->drop_table($nama_tabel, TRUE);

        // Complete the transaction
        $this->db->trans_complete();

        // Return true if the transaction was successful, false otherwise
        return $this->db->trans_status();
    }

    public function editInstansi()
    {
        $data = [
            'instansi' => $this->input->post('instansi'),
            'nama_tabel' => $this->input->post('nama_tabel')
        ];

        $this->db->where('instansi_id', $this->input->post('instansi_id'));
        $this->db->update('instansi', $data);
    }
}
