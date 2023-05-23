<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Toko extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('Madmin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['toko'] = $this->Madmin->get_all_data('tbl_toko')->result();
        $this->load->view('home/layout/header');
        $this->load->view('home/toko/index', $data);
        $this->load->view('home/layout/footer');
        
    }
    public function add()
    {
        $this->load->view('home/layout/header');
        $this->load->view('home/toko/form_tambah');
        $this->load->view('home/layout/footer');
    }
    public function save()
    {
        //tambahkan form validasi disini codeigniter 3
        $this->form_validation->set_rules('namaToko', 'Nama Toko', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        if ($this->form_validation->run() == FALSE) {
            redirect('toko/add');
        }
        //end form validasi
        
        $id = $this->input->post('idKonsumen');
        $nama_toko = $this->input->post('namaToko');
        $deskripsi = $this->input->post('deskripsi');
        $config['upload_path'] = './assets/logo_toko/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('logo')) {
            $data_file = $this->upload->data();
            $data_insert = array('idKonsumen' => $id,
                                'namaToko' => $nama_toko,
                                'logo' => $data_file['file_name'],
                                'deskripsi' => $deskripsi,
                                'statusAktif' => 'Y' );
            $this->Madmin->insert('tbl_toko', $data_insert);
            redirect('toko');
        } else {
            redirect('toko/add');
        }
    }
    public function edit($idToko)
    {
        $data['toko'] = $this->Madmin->get_by_id('tbl_toko', array('idToko' => $idToko))->row();
        $this->load->view('home/layout/header');
        $this->load->view('home/toko/form_edit', $data);
        $this->load->view('home/layout/footer');
    }

    public function update()
{
    //tambahkan form validasi disini codeigniter 3
    $this->form_validation->set_rules('namaToko', 'Nama Toko', 'required');
    $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
    if ($this->form_validation->run() == FALSE) {
        redirect('toko/edit/'.$this->input->post('idToko'));
    }
    //end form validasi

    $idToko = $this->input->post('idToko');
    $nama_toko = $this->input->post('namaToko');
    $deskripsi = $this->input->post('deskripsi');

    $config['upload_path'] = './assets/logo_toko/';
    $config['allowed_types'] = 'jpg|png|jpeg';
    $this->load->library('upload', $config);

    if ($this->upload->do_upload('logo')) {
        $data_file = $this->upload->data();
        $data_update = array(
            'namaToko' => $nama_toko,
            'logo' => $data_file['file_name'],
            'deskripsi' => $deskripsi,
            'statusAktif' => 'Y'
        );
    } else {
        $data_update = array(
            'namaToko' => $nama_toko,
            'deskripsi' => $deskripsi,
            'statusAktif' => 'Y'
        );
    }

    $where = array('idToko' => $idToko);
    $this->Madmin->update('tbl_toko', $data_update, 'idToko', $idToko);
    redirect('toko');
}

public function delete($idToko)
{
    $where = array('idToko' => $idToko);
    $this->Madmin->delete('tbl_toko', 'idToko', $idToko);
    redirect('toko');
}


}


?>