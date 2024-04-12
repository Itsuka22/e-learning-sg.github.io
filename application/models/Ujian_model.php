<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian_model extends CI_Model {
    
    public function getDataUjian($id)
    {
        $this->datatables->select('a.id_ujian, a.token, a.nama_ujian, b.nama_matkul, a.jumlah_soal, CONCAT(a.tgl_mulai, " <br/> (", a.waktu, " Menit)") as waktu, a.jenis');
        $this->datatables->from('m_ujian a');
        $this->datatables->join('matkul b', 'a.matkul_id = b.id_matkul');
        if($id!==null){
            $this->datatables->where('dosen_id', $id);
        }
        return $this->datatables->generate();
    }
    
    public function getListUjian($id, $kelas)
    {
        $this->datatables->select("a.id_ujian, e.nama_dosen, d.nama_kelas, a.nama_ujian, b.nama_matkul, a.jumlah_soal, CONCAT(a.tgl_mulai, ' <br/> (', a.waktu, ' Menit)') as waktu,  (SELECT COUNT(id) FROM h_ujian h WHERE h.mahasiswa_id = {$id} AND h.ujian_id = a.id_ujian) AS ada");
        $this->datatables->from('m_ujian a');
        $this->datatables->join('matkul b', 'a.matkul_id = b.id_matkul');
        $this->datatables->join('kelas_dosen c', "a.dosen_id = c.dosen_id");
        $this->datatables->join('kelas d', 'c.kelas_id = d.id_kelas');
        $this->datatables->join('dosen e', 'e.id_dosen = c.dosen_id');
        $this->datatables->where('d.id_kelas', $kelas);
        return $this->datatables->generate();
    }

    public function getUjianById($id)
    {
        $this->db->select('*');
        $this->db->from('m_ujian a');
        $this->db->join('dosen b', 'a.dosen_id=b.id_dosen');
        $this->db->join('matkul c', 'a.matkul_id=c.id_matkul');
        $this->db->where('id_ujian', $id);
        return $this->db->get()->row();
    }

    public function getIdDosen($nip)
    {
        $this->db->select('id_dosen, nama_dosen')->from('dosen')->where('nip', $nip);
        return $this->db->get()->row();
    }

    public function getJumlahSoal($dosen)
    {
        $this->db->select('COUNT(id_soal) as jml_soal');
        $this->db->from('tb_soal');
        $this->db->where('dosen_id', $dosen);
        return $this->db->get()->row();
    }

    public function getIdMahasiswa($nim)
    {
        $this->db->select('*');
        $this->db->from('mahasiswa a');
        $this->db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->db->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        $this->db->where('nim', $nim);
        return $this->db->get()->row();
    }

    public function HslUjian($id, $mhs)
    {
        $this->db->select('*, UNIX_TIMESTAMP(tgl_selesai) as waktu_habis');
        $this->db->from('h_ujian');
        $this->db->where('ujian_id', $id);
        $this->db->where('mahasiswa_id', $mhs);
        return $this->db->get();
    }

    public function getSoal($id)
    {
        $ujian = $this->getUjianById($id);
        $order = $ujian->jenis==="acak" ? 'rand()' : 'id_soal';
        $this->db->select('id_soal, soal, file, tipe_file, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban');
        $this->db->from('tb_soal');
        $this->db->where('dosen_id', $ujian->dosen_id);
        $this->db->where('matkul_id', $ujian->matkul_id);
        $this->db->order_by($order);
        $this->db->limit($ujian->jumlah_soal);
        return $this->db->get()->result();
    }

    public function ambilSoal($pc_urut_soal1, $pc_urut_soal_arr)
    {
        $this->db->select("*, {$pc_urut_soal1} AS jawaban");
        $this->db->from('tb_soal');
        $this->db->where('id_soal', $pc_urut_soal_arr);
        return $this->db->get()->row();
    }

    public function getJawaban($id_tes)
    {
        $this->db->select('list_jawaban');
        $this->db->from('h_ujian');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->list_jawaban;
    }

    public function getHasilUjian($nip = null)
    {
        $this->datatables->select('b.id_ujian, b.nama_ujian, b.jumlah_soal, CONCAT(b.waktu, " Menit") as waktu, b.tgl_mulai');
        $this->datatables->select('c.nama_matkul, d.nama_dosen');
        $this->datatables->from('h_ujian a');
        $this->datatables->join('m_ujian b', 'a.ujian_id = b.id_ujian');
        $this->datatables->join('matkul c', 'b.matkul_id = c.id_matkul');
        $this->datatables->join('dosen d', 'b.dosen_id = d.id_dosen');
        $this->datatables->group_by('b.id_ujian');
        if($nip !== null){
            $this->datatables->where('d.nip', $nip);
        }
        return $this->datatables->generate();
    }

    public function HslUjianById($id, $dt=false)
    {
        if($dt===false){
            $db = "db";
            $get = "get";
        }else{
            $db = "datatables";
            $get = "generate";
        }
        
        $this->$db->select('d.id, a.nama, b.nama_kelas, c.nama_jurusan, d.jml_benar, d.nilai');
        $this->$db->from('mahasiswa a');
        $this->$db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->$db->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        $this->$db->join('h_ujian d', 'a.id_mahasiswa=d.mahasiswa_id');
        $this->$db->where(['d.ujian_id' => $id]);
        return $this->$db->$get();
    }

    public function bandingNilai($id)
    {
        $this->db->select_min('nilai', 'min_nilai');
        $this->db->select_max('nilai', 'max_nilai');
        $this->db->select_avg('FORMAT(FLOOR(nilai),0)', 'avg_nilai');
        $this->db->where('ujian_id', $id);
        return $this->db->get('h_ujian')->row();
    }

    // model history jawaban

    public function getLogujian($id_soal){
        $query = "SELECT hs_h_ujian.id_soal, tb_soal.soal, hs_h_ujian.kunci_jawaban
        FROM tb_soal
        LEFT JOIN hs_h_ujian ON hs_h_ujian.id_soal = tb_soal.id_soal
        ORDER BY hs_h_ujian.id_soal";
        $result = $this->db->query($query)->result_array();
        foreach ($result as $row){
          if ($row['id_soal'] == $id_soal) {
            $id_soal = $row['id_soal'];
        }
      }
        return $result;
    }

    public function getLoghsujian(){
        return $this->db->get('h_ujian')->result_array();
    }

    public function getPertanyaan()
    {
        $query = " SELECT `p_kuesioner`.*, `m_ujian`.`nama_ujian`
                    FROM `p_kuesioner` JOIN `m_ujian`
                    ON `p_kuesioner`.`id_kegiatan` = `m_ujian`.`id_ujian`
        ";

        return $this->db->query($query)->result_array();
    }

    public function aksiKegiatan($pers_no, $date, $kuesioner, $opsi,$saran)
    {
        $this->db->trans_start();
            //INSERT TO PACKAGE
            $data = [
                "pers_no"       => $pers_no,
                "tanggal"       => $date,
                "id_kuesioner"  => $kuesioner
            ];
    
            $this->db->insert('a_kegiatan', $data);
            //GET ID PACKAGE
            $package_id = $this->db->insert_id();
            $result = array();
                foreach($opsi AS $key => $val){
                     $result[] = array(
                      'id_kuesioner'     => $kuesioner,
                      'id_aksi'          => $package_id,
                      'soal_no'          => $key,
                      'id_opsi'          => $_POST['opsi'][$key]
                     );
                }      
            //MULTIPLE INSERT TO DETAIL TABLE
            $this->db->insert_batch('k_kegiatan', $result);
            
            if(!empty($saran)){
                $value = [
                    "pers_no"       => $pers_no,
                    "tanggal"       => $date,
                    "id_kuesioner"  => $kuesioner,
                    "saran"         => $saran
                ];

                $this->db->insert('s_kegiatan', $value);
            }

        $this->db->trans_complete();
    }

    public function getSBaik($id)
    {
        $query = "SELECT
                (SELECT COUNT(id_kuesioner) FROM k_kegiatan WHERE id_kuesioner = $id) as jumlah,
                (SELECT COUNT(id_opsi) FROM k_kegiatan WHERE id_opsi = 4 AND id_kuesioner = $id) as Sbaik, 
                ROUND((SELECT Sbaik/jumlah*100),0) as persen";

        return $this->db->query($query)->row_array();
    }

    public function getBaik($id)
    {
        $query = "SELECT
                (SELECT COUNT(id_kuesioner) FROM k_kegiatan WHERE id_kuesioner = $id) as jumlah,
                (SELECT COUNT(id_opsi) FROM k_kegiatan WHERE id_opsi = 3 AND id_kuesioner = $id) as baik, 
                ROUND((SELECT baik/jumlah*100),0) as persen";

        return $this->db->query($query)->row_array();
    }

    public function getCukup($id)
    {
        $query = "SELECT
                (SELECT COUNT(id_kuesioner) FROM k_kegiatan WHERE id_kuesioner = $id) as jumlah,
                (SELECT COUNT(id_opsi) FROM k_kegiatan WHERE id_opsi = 2 AND id_kuesioner = $id) as cukup, 
                ROUND((SELECT cukup/jumlah*100),0) as persen";

        return $this->db->query($query)->row_array();
    }

    public function getKurang($id)
    {
        $query = "SELECT
                (SELECT COUNT(id_kuesioner) FROM k_kegiatan WHERE id_kuesioner = $id) as jumlah,
                (SELECT COUNT(id_opsi) FROM k_kegiatan WHERE id_opsi = 1 AND id_kuesioner = $id) as kurang, 
                ROUND((SELECT kurang/jumlah*100),0) as persen";

        return $this->db->query($query)->row_array();
    }

    public function Nkuesioner($id)
    {
        $query = "SELECT ROUND((SELECT AVG(id_opsi) FROM k_kegiatan WHERE id_kuesioner = $id),0) as nilai";

        return $this->db->query($query)->row_array();
    }
}