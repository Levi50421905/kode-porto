<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('image_model');
        $this->load->helper(['form', 'url', 'file']);
        $this->load->library(['form_validation', 'session']);
        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function index() {
        $data['title'] = 'Galeri Foto Pribadi';
        $data['images'] = $this->image_model->get_all_images();
        
        $this->load->view('image/list', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Gambar';
        
        if (!is_dir(FCPATH . 'uploads')) {
            mkdir(FCPATH . 'uploads', 0777, true);
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Judul', 'required');
            $this->form_validation->set_rules('description', 'Deskripsi', 'required');
            
            if (empty($_FILES['image']['name'])) {
                $this->form_validation->set_rules('image', 'Gambar', 'required');
            }
            
            if ($this->form_validation->run() === FALSE) {
                $this->load->view('image/add', $data);
            } else {
                $config['upload_path'] = realpath(FCPATH . 'uploads');
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;
                
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload('image')) {
                    $data['error'] = $this->upload->display_errors();
                    $this->load->view('image/add', $data);
                } else {
                    $upload_data = $this->upload->data();
                    $image_path = 'uploads/' . $upload_data['file_name'];
                    
                    $image_data = [
                        'title' => $this->input->post('title'),
                        'description' => $this->input->post('description'),
                        'image_path' => $image_path,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $this->image_model->insert_image($image_data);
                    $this->session->set_flashdata('success', 'Gambar berhasil ditambahkan!');
                    redirect('image');
                }
            }
        } else {
            $this->load->view('image/add', $data);
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Gambar';
        $data['image'] = $this->image_model->get_image_by_id($id);
        
        if (empty($data['image'])) {
            show_404();
        }
        
        if (!is_dir(FCPATH . 'uploads')) {
            mkdir(FCPATH . 'uploads', 0777, true);
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Judul', 'required');
            $this->form_validation->set_rules('description', 'Deskripsi', 'required');
            
            if ($this->form_validation->run() === FALSE) {
                $this->load->view('image/edit', $data);
            } else {
                $image_data = [
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if (!empty($_FILES['image']['name'])) {
                    $config['upload_path'] = realpath(FCPATH . 'uploads');
                    $config['allowed_types'] = 'gif|jpg|jpeg|png';
                    $config['max_size'] = 2048; 
                    $config['encrypt_name'] = TRUE;
                    
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload('image')) {
                        $data['error'] = $this->upload->display_errors();
                        $this->load->view('image/edit', $data);
                        return;
                    } else {
                        $upload_data = $this->upload->data();
                        $new_image_path = 'uploads/' . $upload_data['file_name'];
                        
                        $old_image = $data['image']->image_path;
                        if (file_exists(FCPATH . $old_image)) {
                            unlink(FCPATH . $old_image);
                        }
                        
                        $image_data['image_path'] = $new_image_path;
                    }
                }
                
                $this->image_model->update_image($id, $image_data);
                $this->session->set_flashdata('success', 'Gambar berhasil diperbarui!');
                redirect('image');
            }
        } else {
            $this->load->view('image/edit', $data);
        }
    }

    public function delete($id) {
        $image = $this->image_model->get_image_by_id($id);
        
        if (empty($image)) {
            show_404();
        }
        
        if (file_exists(FCPATH . $image->image_path)) {
            unlink(FCPATH . $image->image_path);
        }
        
        $this->image_model->delete_image($id);
        $this->session->set_flashdata('success', 'Gambar berhasil dihapus!');
        redirect('image');
    }
}