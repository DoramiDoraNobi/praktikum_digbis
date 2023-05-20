<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	//tambahkan fungsi construct
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Madmin');
        $this->load->library('form_validation');
    }
	public function index()
	{
		$this->load->view('home/layout/header');
		$this->load->view('home/layanan');
		$this->load->view('home/home');
		$this->load->view('home/layout/footer');
	}
   
    public function register()
    {
        $this->load->view('home/layout/header');
		$this->load->view('home/register');
		$this->load->view('home/layout/footer');
    }
    public function do_register()
    {
        // Load library form validation
        $this->load->library('form_validation');

        // Set rules for form validation
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('namaKonsumen', 'Nama Konsumen', 'required');;
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi form gagal, tampilkan kembali halaman registrasi dengan pesan error
            $this->load->view('register');
        } else {
            // Ambil data dari form register
            $data['username'] = $this->input->post('username');
            $password = $this->input->post('password');
            $data['password'] = password_hash($password, PASSWORD_DEFAULT); // Enkripsi password dengan password_hash()
            $data['namaKonsumen'] = $this->input->post('namaKonsumen');
            $data['alamat'] = $this->input->post('alamat');
            $data['email'] = $this->input->post('email');
            $data['tlpn'] = $this->input->post('tlpn');
            $data['statusAktif'] = 'Y';

            // Panggil model untuk insert data ke database
            $this->load->model('Madmin');
            $this->Madmin->insert('tbl_member', $data);

            // Tampilkan pesan sukses dan redirect ke halaman login
            $this->session->set_flashdata('success', 'Registrasi berhasil! Silahkan login.');
            redirect('main/login');
        }
    }


    public function login()
    {
    $this->load->view('home/layout/header');
    $this->load->view('home/login');
    $this->load->view('home/layout/footer');
    }

    public function do_login()
    {
        // Form validation
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        // Ambil data dari form login
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Panggil model untuk cek data login di database
        $this->load->model('Madmin');
        $member = $this->Madmin->cek_loginMember($username)->row();

        if ($member) { // Jika data ditemukan
            if (password_verify($password, $member->password)) { // Jika password cocok
                if ($member->statusAktif == 'Y') { // Jika statusAktif = Y, bisa login
                    // Set session data dan redirect ke halaman dashboard
                    $this->session->set_userdata('logged_in', true);
                    $this->session->set_userdata('member_id', $member->idMember);
                    $this->session->set_userdata('member_username', $member->username);
                    redirect('main/home');
                } else { // Jika statusAktif = N, tidak bisa login
                    $this->session->set_flashdata('error', 'Akun Anda belum aktif. Silahkan tunggu konfirmasi dari admin.');
                    redirect('main/login');
                }
            } else { // Jika password tidak cocok
                $this->session->set_flashdata('error', 'Username atau password salah.');
                redirect('main/login');
            }
        } else { // Jika data tidak ditemukan
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('main/login');
        }
    }

    
    public function logout()
    {
    // hapus session data dan redirect ke halaman login
    $this->session->unset_userdata('logged_in');
    $this->session->unset_userdata('member_id');
    $this->session->unset_userdata('member_username');
    redirect('main/login');
    }
    //tambahkan fungsi edit profile dan update profile untuk member yang login
    public function edit_profile()
    {
        $id = $this->session->userdata('member_id');
        $data['member'] = $this->Madmin->get_by_id('tbl_member', array('idMember'=>$id))->row_object();
        $this->load->view('home/layout/header');
        $this->load->view('home/edit_profile', $data);
        $this->load->view('home/layout/footer');
    }
    public function update_profile()
    {
        $id = $this->session->userdata('member_id');
        $data['username'] = $this->input->post('username');
        $data['namaKonsumen'] = $this->input->post('namaKonsumen');
        $data['alamat'] = $this->input->post('alamat');
        $data['email'] = $this->input->post('email');
        $data['tlpn'] = $this->input->post('tlpn');
        $this->Madmin->update('tbl_member', $data, 'idMember', $id);
        $this->session->set_flashdata('success', 'Data berhasil diupdate.');
        redirect('main/home');
    }
    //tambahkan fungsi untuk cek apakah sudah masuk session atau belum dengan tambahan pesan jika belum login dan sudah login
    public function cek_login()
    {
        if (empty($this->session->userdata('member_id'))) {
            $this->session->set_flashdata('error', 'Silahkan login terlebih dahulu.');
            redirect('main/login');
        }
        $this->load->view('home/layout/header');
        $this->load->view('home/home');
        $this->load->view('home/layout/footer');
    }





}
