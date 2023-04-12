<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	
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
    // ambil data dari form register
    $data['username'] = $this->input->post('username');
    $data['password'] = md5($this->input->post('password')); // encrypt password dengan md5
    $data['namaKonsumen'] = $this->input->post('namaKonsumen');
    $data['alamat'] = $this->input->post('alamat');
    $data['email'] = $this->input->post('email');
    $data['tlpn'] = $this->input->post('tlpn');
    $data['statusAktif'] = 'Y';

    // panggil model untuk insert data ke database
    $this->load->model('Madmin');
    $this->Madmin->insert('tbl_member', $data);

    // tampilkan pesan sukses dan redirect ke halaman login
    $this->session->set_flashdata('success', 'Registrasi berhasil! Silahkan login.');
    redirect('login');
    }

    public function login()
    {
    $this->load->view('home/layout/header');
    $this->load->view('home/login');
    $this->load->view('home/layout/footer');
    }

    public function do_login()
    {
    // ambil data dari form login
    $username = $this->input->post('username');
    $password = md5($this->input->post('password')); // encrypt password dengan md5

    // panggil model untuk cek data login di database
    $this->load->model('Madmin');
    $member = $this->Madmin->get('tbl_member', ['username' => $username, 'password' => $password]);

    if ($member) { // jika data ditemukan
        if ($member->statusAktif == 'Y') { // jika statusAktif = Y, bisa login
            // set session data dan redirect ke halaman dashboard
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('member_id', $member->idMember);
            $this->session->set_userdata('member_username', $member->username);
            redirect('dashboard');
        } else { // jika statusAktif = N, tidak bisa login
            $this->session->set_flashdata('error', 'Akun Anda belum aktif. Silahkan tunggu konfirmasi dari admin.');
            redirect('login');
        }
    } else { // jika data tidak ditemukan
        $this->session->set_flashdata('error', 'Username atau password salah.');
        redirect('login');
    }
    }
    
    public function logout()
    {
    // hapus session data dan redirect ke halaman login
    $this->session->unset_userdata('logged_in');
    $this->session->unset_userdata('member_id');
    $this->session->unset_userdata('member_username');
    redirect('login');
    }




}
