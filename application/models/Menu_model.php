<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{

    //Fungsi untuk mendapatkan data menu dari tabel 'user_menu' dengan pagination dan pencarian keyword
    public function getMenu($limit, $start, $keyword = '')
    {
        $this->db->select('*');
        $this->db->from('user_menu');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('menu', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    //Fungsi untuk menghitung jumkah data menu pada tabel 'user_menu'
    public function countAllMenu()
    {
        $this->db->select('*');
        $this->db->from('user_menu');
        return $this->db->get('')->num_rows();
    }

    //Fungsi untuk mengambil semua data submenu
    public function getAllSubMenu()
    {
        $this->db->select('user_sub_menu.*, user_menu.menu');
        $this->db->from('user_sub_menu');
        $this->db->join('user_menu', 'user_sub_menu.menu_id = user_menu.menu_id');
        return $this->db->get()->result_array();
    }

    //Fungsi untuk mendapatkan data submenu dari tabel 'user_sub_menu' dengan pagination dan pencarian keyword
    public function getSubmenu($limit, $start, $keyword = '')
    {
        $this->db->select('user_sub_menu.*, user_menu.menu');
        $this->db->from('user_sub_menu');
        $this->db->join('user_menu', 'user_sub_menu.menu_id = user_menu.menu_id');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('title', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    //Fungsi untuk menghitung jumlah data submenu pada tabel 'user_sub_menu'
    public function countAllSubmenu()
    {
        $this->db->select('user_sub_menu.*, user_menu.menu');
        $this->db->from('user_sub_menu');
        $this->db->join('user_menu', 'user_sub_menu.menu_id = user_menu.menu_id');
        return $this->db->get('')->num_rows();
    }

    //Fungsi untuk menghapus data menu berdasarkan id
    public function deleteDataMenu($menu_id)
    {
        $this->db->where('menu_id', $menu_id);
        $this->db->delete('user_menu');
    }

    //Fungsi untuk menghapus data submenu
    public function deleteDataSubmenu($sub_menu_id)
    {
        $this->db->where('sub_menu_id', $sub_menu_id);
        $this->db->delete('user_sub_menu');
    }

    //Fungsi untuk mengedit data menu
    public function editMenu()
    {
        $data = [
            'menu' => $this->input->post('menu')
        ];

        $this->db->where('menu_id', $this->input->post('menu_id'));
        $this->db->update('user_menu', $data);
    }

    //Fungsi untuk mengedit data submenu
    public function editSubmenu()
    {
        $data = [
            'title' => $this->input->post('title'),
            'menu_id' => $this->input->post('menu_id'),
            'url' => $this->input->post('url'),
            'icon' => $this->input->post('icon'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        $this->db->where('sub_menu_id', $this->input->post('sub_menu_id'));
        $this->db->update('user_sub_menu', $data);
    }
}
