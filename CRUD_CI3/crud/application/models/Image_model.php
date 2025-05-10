<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all_images() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('images')->result();
    }
    
    public function get_image_by_id($id) {
        return $this->db->get_where('images', ['id' => $id])->row();
    }
    
    public function insert_image($data) {
        return $this->db->insert('images', $data);
    }
    
    public function update_image($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('images', $data);
    }
    
    public function delete_image($id) {
        $this->db->where('id', $id);
        return $this->db->delete('images');
    }
}