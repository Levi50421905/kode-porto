<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mahasiswa extends CI_Model 
{
    public function SemuaData()
    {
        return $this->db->get('mahasiswa')->result_array();
    }
}