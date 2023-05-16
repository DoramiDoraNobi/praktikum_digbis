<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpanel extends CI_Controller
{
    public function index()
    {
        $this->load->view('admin/login');
    }
    public function dashboard()
    {
        if (empty($this->session->userdata('userName'))) {
            redirect('adminpanel');
        }
        $this->load->view('admin/layout/header');
        $this->load->view('admin/layout/menu');
        $this->load->view('admin/dashboard');
        $this->load->view('admin/layout/footer');
    }
    //buatkan saya login dengan fitur tambahan validasi jika ada yang salah maka akan kembali ke halaman login dengan pesan error atau data tidak cocok dan enkripsi password
    public function login()
    {
        $userName = $this->input->post('userName');
        $password = $this->input->post('password');
        $data = array('userName' => $userName,
                      'password' => md5($password));
        $cek = $this->db->get_where('tbl_admin', $data);
        if ($cek->num_rows() > 0) {
            $this->session->set_userdata($data);
            redirect('adminpanel/dashboard');
        } else {
            $this->session->set_flashdata('pesan', 'Username atau Password Salah');
            redirect('adminpanel');
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('adminpanel');
    }
    //buatkan function untuk ganti password admin
    public function ganti_password()
    {
        $this->load->view('admin/layout/header');
        $this->load->view('admin/layout/menu');
        $this->load->view('admin/ganti_password');
        $this->load->view('admin/layout/footer');
    }
    //buatkan function untuk update password admin bernama do_update_password memakai md5 untuk enkripsi password dan redirect ke halaman login jika berhasil
    public function do_update_password(){
        $userName = $this->session->userdata('userName');
        $password = $this->input->post('password');
        $data = array('password' => md5($password));
        $this->db->where('userName', $userName);
        $this->db->update('tbl_admin', $data);
        redirect('adminpanel');
    }
}

?>