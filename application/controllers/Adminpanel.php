<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpanel extends CI_Controller
{
   function __construct()
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
        $this->load->library('form_validation');
        $this->form_validation->set_rules('userName', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
    
        if ($this->form_validation->run() == FALSE) {
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
    
}

?>
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