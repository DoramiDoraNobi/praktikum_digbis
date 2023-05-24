<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpanel extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Madmin');
        $this->load->library('form_validation');
    }
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
    public function login()
    {
        $this->form_validation->set_rules('userName', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', 'Username atau Password Harap Diisi');
            $this->load->view('admin/login');
        } else {
            $userName = $this->input->post('userName');
            $password = $this->input->post('password');

            // Load model Madmin
            $this->load->model('Madmin');

            // Ambil data admin dari database
            $admin = $this->Madmin->get_by_id('tbl_admin', array('userName' => $userName));

            // Cek apakah data admin ada dalam database
            if ($admin->num_rows() > 0) {
                // Ambil data admin dari database
                $admin = $admin->row();

                // Cek apakah password yang dimasukkan sesuai dengan password admin di database
                if (password_verify($password, $admin->password)) {
                    // Buat session
                    $this->session->set_userdata('userName', $admin->userName);

                    // Redirect ke halaman dashboard
                    redirect('adminpanel/dashboard');
                } else {
                    // Password salah
                    $this->session->set_flashdata('message', 'Password salah');
                    redirect('adminpanel');
                }
            } else {
                // Username salah
                $this->session->set_flashdata('message', 'Username salah');
                redirect('adminpanel');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('adminpanel');
    }
    public function ganti_password()
    {
        $this->load->view('admin/layout/header');
        $this->load->view('admin/layout/menu');
        $this->load->view('admin/ganti_password');
        $this->load->view('admin/layout/footer');
    }
    public function do_update_password() {
        $userName = $this->session->userdata('userName');
        $password = $this->input->post('password');
    
        // Hash password baru
        $new_password_hash = password_hash($password, PASSWORD_DEFAULT);
    
        // Load model Madmin
        $this->load->model('Madmin');
    
        // Memperbarui password dalam database
        $this->Madmin->update('tbl_admin', array('password' => $new_password_hash), 'userName', $userName);
    
        redirect('adminpanel');
    }
    
    public function register()
    {
        $this->load->view('admin/register');
    }

    public function save_register()
    {
        // Load library form validation
        $this->load->library('form_validation');

        // Set rules for form validation
        $this->form_validation->set_rules('userName', 'Username', 'required');
        $this->form_validation->set_rules('password','Password','required');
        $this->form_validation->set_rules('confirm_password','Confirm Password','required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi form gagal, tampilkan kembali halaman register
            $this->load->view('admin/register');
        } else {
            // Ambil data dari form register
            $userName = $this->input->post('userName');
            $password = $this->input->post('password');

            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Load model Madmin
            $this->load->model('Madmin');

            // Cek apakah username sudah ada di database
            $admin = $this->Madmin->get_by_id('tbl_admin', array('userName' => $userName));

            // Jika username sudah ada
            if ($admin->num_rows() > 0) {
                // Set flashdata
                $this->session->set_flashdata('message', 'Username sudah ada');

                // Redirect ke halaman register
                redirect('adminpanel/register');
            } else {
                // Jika username belum ada
                // Simpan data ke database
                $data = array(
                    'userName' => $userName,
                    'password' => $password_hash
                );
                $this->Madmin->insert('tbl_admin', $data);

                // Set flashdata
                $this->session->set_flashdata('message', 'Register berhasil');

                // Redirect ke halaman login
                redirect('adminpanel');
            }
        }
    }
}
