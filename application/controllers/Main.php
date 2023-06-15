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
        $data['produk'] = $this->Madmin->get_all_data('tbl_produk')->result();
        $data['kategori'] = $this->Madmin->get_all_data('tbl_kategori')->result();
		$this->load->view('home/layout/header', $data);
		$this->load->view('home/layanan');
		$this->load->view('home/home', $data);
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
            $this->load->view('toko/register');
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

    public function home()
    {
    $data['produk'] = $this->Madmin->get_all_data('tbl_produk')->result();
    $data['kategori'] = $this->Madmin->get_all_data('tbl_kategori')->result();
	$this->load->view('home/layout/header1', $data);
    $this->load->view('home/layanan');
    $this->load->view('home/home');
    $this->load->view('home/layout/footer');
    }

    //buat fungsi untuk login dengan form validation, password_hash dan password_verify dan session
    public function do_login()
    {
        // Load library form validation
        $this->load->library('form_validation');

        // Set rules for form validation
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password','Password','required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi form gagal, tampilkan kembali halaman login
            $this->load->view('home/login');
        } else {
            // Ambil data dari form login
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Load model Madmin
            $this->load->model('Madmin');

            // Ambil data admin dari database
            $member = $this->Madmin->get_by_id('tbl_member', array('username' => $username));

            // Cek apakah data admin ada dalam database
            if ($member->num_rows() > 0) {
                // Ambil data admin dari database
                $member = $member->row();

                // Cek apakah password yang dimasukkan sesuai dengan password admin di database
                if (password_verify($password, $member->password)) {
                    // Buat session
                    $this->session->set_userdata('logged_in', TRUE);
                    $this->session->set_userdata('member_id', $member->idKonsumen);
                    $this->session->set_userdata('member_username', $member->username);

                    // Redirect ke halaman dashboard
                    redirect('main/home');
                } else {
                    // Password salah
                    $this->session->set_flashdata('error', 'Password salah');
                    redirect('main/login');
                }
            } else {
                // Username tidak ada
                $this->session->set_flashdata('error', 'Username tidak terdaftar');
                redirect('main/login');
            }
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

    public function edit_profile()
    {
        $id = $this->session->userdata('member_id');
        $data['member'] = $this->Madmin->get_by_id('tbl_member', array('idKonsumen'=>$id))->row_object();
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
        $this->Madmin->update('tbl_member', $data, 'idKonsumen', $id);
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
