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
    redirect('main/login');
    }

    public function login()
    {
    $this->load->view('home/layout/header');
    $this->load->view('home/login');
    $this->load->view('home/layout/footer');
    }

    public function do_login()
{
    //form validation
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');

    // ambil data dari form login
    $username = $this->input->post('username');
    $password = md5($this->input->post('password')); // encrypt password dengan md5

    // panggil model untuk cek data login di database
    $this->load->model('Madmin');
    $cek = $this->Madmin->cek_loginMember($username, $password)->num_rows();
    $member = $this->Madmin->cek_loginMember($username, $password)->row_object();

    if ($cek == 1) { // jika data ditemukan
        if ($member->statusAktif == 'Y') { // jika statusAktif = Y, bisa login
            // set session data dan redirect ke halaman dashboard
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('member_id', $member->idMember);
            $this->session->set_userdata('member_username', $member->username);
            redirect('dashboard');
        } else { // jika statusAktif = N, tidak bisa login
            $this->session->set_flashdata('error', 'Akun Anda belum aktif. Silahkan tunggu konfirmasi dari admin.');
            redirect('main/login');
        }
    } else { // jika data tidak ditemukan
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
    redirect('login');
    }




}
