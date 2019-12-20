<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Pohon_jaringan extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('Pohon_model','model');
    $this->load->library(array("btree"));
  }

  function detail($id)
  {
    $this->template->set_title("Detail Member");
    $data["id"] = $id;
    $this->template->view("content/pohon_jaringan/detail",$data,false);
  }

  function _rules()
  {
    $this->form_validation->set_rules("kode_referal","Username","trim|xss_clean|required|callback__cek_kode_ref",[
      "required" => "Silahkan masukkan kode referal mitra anda."
    ]);


    $this->form_validation->set_rules("id_parent","id_parent","trim|xss_clean|required");
    $this->form_validation->set_rules("posisi","posisi","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("nama","Nama","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("email","Email","trim|xss_clean|htmlspecialchars|required|valid_email");
    $this->form_validation->set_rules("telepon","Telepon","trim|xss_clean|numeric|required");

    $this->form_validation->set_rules("tempat_lahir","Tempat Lahir","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("tgl_lahir","Tanggal Lahir","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("jk","Jenis Kelamin","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("provinsi","Provinsi","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("kabupaten","Kabupaten/Kota","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("kecamatan","Kecamatan","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("kelurahan","Kelurahan/Desa","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("alamat","Alamat Lengkap","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("paket","Jenis Paket","trim|xss_clean|required|htmlspecialchars|callback__cek_pin");
    $this->form_validation->set_rules("bank","Jenis Bank","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("no_rek","NO.rekening","trim|xss_clean|required|numeric");
    $this->form_validation->set_rules("nama_rekening","Nama Rekening","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("kota_pembukaan_rek","Kota/Kabupaten Pembukaan Rekening","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("username","Username","trim|xss_clean|required|htmlspecialchars|alpha_numeric|is_unique[tb_auth.username]",[
      "is_unique" => "Coba Username yang lain"
    ]);
    $this->form_validation->set_rules("password","Password","trim|xss_clean|required|min_length[5]");
    $this->form_validation->set_rules("v_password","Konfirmasi Password","trim|xss_clean|required|matches[password]");
    $this->form_validation->set_error_delimiters('<label class="error mt-2 text-danger">','</label>');
  }

  function index(){
    $this->load->helper(['cek_pohon']);
    $this->template->set_title("Binary");
    $data["root"] = $this->model->get_where("tb_member",["id_member"=>$this->session->userdata("id_member")]);
    $this->template->view("content/pohon_jaringan/contoh",$data);
  }


  function show($id="")
  {
    if ($id!="") {
      if ($row = $this->model->get_where("tb_member",["id_member"=>$id])) {
        $this->load->helper(['cek_pohon']);
        $this->template->set_title("Binary");
        $data["root"] = $row;
        $this->template->view("content/pohon_jaringan/contoh",$data);
      }

    }else {
      redirect(site_url("backend/pohon_jaringan"),"refresh");
    }

  }


  function search()
  {
    $json = array('success'=>false,"alert"=>"","url"=>"");

          $username = $this->input->post("username");
            //
            $where = array("username"=> $username,'level'=>"member");
            //
            // $query= $this->db->select('id_personal,username,level')
            //                 ->from('tb_auth')
            //                 ->where('level','member')
            //                 ->like('username',"$username")
            //                 ->get();

            if ($row =  $this->model->get_where("tb_auth",$where)) {
              if ($row->id_personal==sess('id_member')) {
                $json['success'] = true;
                $json['url'] = site_url("backend/pohon_jaringan");
              }else {

                $cek_anak = $this->btree->get_all_id_children(sess('id_member'));
                if (in_array($row->id_personal,$cek_anak)) {
                  $json['success'] = true;
                  $json['url'] = site_url("backend/pohon_jaringan/show/".$row->id_personal);
                }else {
                  $json['alert'] = "Data tidak di temukan";
                }
              }
            }else {
              $json['alert'] = "Data tidak di temukan";
            }


    echo json_encode($json);
  }


  function tambah($id_parent="",$posisi="")
  {
      if ($id_parent=="" OR $posisi=="") {
        $this->_error404();
        }else {
        if ($posisi=="kiri"  OR $posisi=="kanan") {
            if ($posisi=="kiri") {
              $field = 'l_mem';
            }else {
              $field = 'r_mem';
            }
          if ($row = $this->model->get_where('trans_member',['id_member'=>$id_parent])) {
              if ($row->$field=="") {
                $this->template->set_title("Binary");
                $data["provinsi"] = $this->db->get("wil_provinsi")->result();
                $data["bank"]   = $this->db->get("ref_bank")->result();
                $data['id_parent'] = $id_parent;
                $data['posisi'] = $posisi;
                $data['serial_pin'] = "SN".date('dmyhis');
                $this->template->view("content/pohon_jaringan/form",$data);
              }else {
                redirect(site_url("backend/pohon_jaringan"),"refresh");
              }
          }else {
            $this->_error404();
          }

        }else {
            $this->_error404();
        }
      }
  }


  function tambah_action($serial_pin){
    if ($this->input->is_ajax_request()) {
        $json = array('success'=>false,'status'=>array(), 'alert'=>array());
        $this->load->library(array("form_validation"));

        $kode_referral      = $this->input->post("kode_referal",true);
        $nik                = $this->input->post("nik",true);
        $nama               = $this->input->post("nama",true);
        $email              = $this->input->post("email",true);
        $telepon            = $this->input->post("telepon",true);
        $tempat_lahir       = $this->input->post("tempat_lahir",true);
        $tgl_lahir          = $this->input->post("tgl_lahir",true);
        $jk                 = $this->input->post("jk",true);
        $provinsi           = $this->input->post("provinsi",true);
        $kabupaten          = $this->input->post("kabupaten",true);
        $kecamatan          = $this->input->post("kecamatan",true);
        $kelurahan          = $this->input->post("kelurahan",true);
        $alamat             = $this->input->post("alamat",true);
        $paket              = $this->input->post("paket",true);
        $bank               = $this->input->post("bank",true);
        $no_rek             = $this->input->post("no_rek",true);
        $nama_rekening      = $this->input->post("nama_rekening",true);
        $kota_pembukaan_rek = $this->input->post("kota_pembukaan_rek",true);
        $username           = strtolower($this->input->post("username",true));
        $password           = $this->input->post("password",true);


        $id_parent = $this->input->post("id_parent");
        $posisi = $this->input->post("posisi");

        $this->form_validation->set_rules("nik","Nik/No.KTP","trim|xss_clean|required|min_length[16]|max_length[16]|numeric|callback__cek_nik[".$tgl_lahir."]");

        $this->_rules();
        if ($this->form_validation->run()) {
            //START DB TRANS
            $this->db->trans_start();

            $insert_member = [  "kode_referral" => "ref_$username",
                                "referral_from" => "ref_".$kode_referral ,
                                "kode_register" => "MEM".date('dmYhis'),
                                "nik"           => $nik,
                                "nama"          => $nama,
                                "telepon"       => $telepon,
                                "email"         => $email,
                                "jk"            => $jk,
                                "tempat_lahir"  => $tempat_lahir,
                                "tgl_lahir"     => date("Y-m-d",strtotime($tgl_lahir)),
                                "provinsi"      => $provinsi,
                                "kabupaten"     => $kabupaten,
                                "kecamatan"     => $kecamatan,
                                "kelurahan"     => $kelurahan,
                                "alamat"        => $alamat,
                                "paket"         => $paket,
                                "is_verifikasi" => "1",
                                "posisi"        => $posisi,
                                "created"       => date("Y-m-d H:i:s"),
                            ];
          // insert member
          $this->model->get_insert("tb_member",$insert_member);

          $last_id_member = $this->db->insert_id();

          $insert_data_bank = [ "id_member"                =>  $last_id_member,
                                "id_bank"                  =>  $bank,
                                "no_rekening"              =>  $no_rek,
                                "nama_rekening"            =>  $nama_rekening,
                                "kota_pembukaan_rekening"  =>  $kota_pembukaan_rek
                              ];
          // insert data bank
          $this->model->get_insert("trans_member_rek",$insert_data_bank);

          $this->load->helper("pass_hash");

          $data_akun = [  "id_personal"  =>  $last_id_member,
                          "username"     =>  $username,
                          "password"     =>  pass_encrypt(date("dmYhis"),$password),
                          "token"        =>  date("dmYhis"),
                          "level"        =>  "member",
                          "created"      =>  date("Y-m-d H:i:s")
                        ];
          // insert data auth
          $this->model->get_insert("tb_auth",$data_akun);

          $trans_member = [ "id_parent" => $id_parent,
                            "id_member" => $last_id_member
                          ];

          $this->model->get_insert("trans_member",$trans_member);

          if ($posisi=="kiri") {
            $leave = array('l_mem' => $last_id_member);
          }else {
            $leave = array('r_mem' => $last_id_member);
          }

          $this->db->update("trans_member",$leave,["id_member" => $id_parent]);


          $pins = paket($paket,'pin');


          $query_pin = $this->model->query_cek_pin($pins);

          foreach ($query_pin as $pin) {
            $insert_trans_pin_pakai = array('serial_pin' => $serial_pin,
                                            'id_pin_trans'  => $pin->id_pin_trans,
                                            'id_member_pakai'  =>$last_id_member,
                                            'tgl_aktivasi' => date('Y-m-d H:i:s'),
                                            'status'  => "registrasi");
            $this->model->get_insert("trans_pin_pakai",$insert_trans_pin_pakai);

            $key_order_pin = str_replace('SN','KOP',$serial_pin);

            $update_trans_pin = array('key_order_pin' => $key_order_pin,
                                      'status'  => 'terpakai'
                                      );
            $this->db->update("trans_pin",$update_trans_pin,["id_pin_trans" => $pin->id_pin_trans]);
          }




          //insert bonus SPONSOR
          //cari id_member yang mempunyai kode referral hasil inputan
          $id_member_referral = profile_member_where(['kode_referral'=>"ref_".$kode_referral],"id_member");

          $inser_b_sponsor = array('id_parent' =>  $id_member_referral,
                                    'id_member' => $last_id_member,
                                    'created'   => date('Y-m-d H:i:s'),
                                    'total_bonus'=> $this->balance->get_bonus_sponsor($paket),
                                    'keterangan'=> "Penambahan member baru Paket <b class='text-danger'>".paket($paket,'paket')."</b>",
                                  );

          $this->model->get_insert("bonus_sponsor",$inser_b_sponsor);


          $is_parent = $this->btree->cek_is_parent($last_id_member);

          foreach ($is_parent as $value) {
             $this->btree->pairing($value,$last_id_member);
          }



          // Validasi DB trans
          $this->db->trans_complete();
          if ($this->db->trans_status() === FALSE)
              {
                $this->db->trans_rollback();
                $json['status'] = "error";
                $json['alert'] = "Gagal Menyimpan";
              }else{
                $this->db->trans_commit();
                // $this->_send_mail($email,$username,$nama,$telepon,$kode_referral,$nik,$paket,$password);
                  $json['alert'] = "Berhasil menambahkan member";
                $json['status'] = "success";
              }


          $json['success'] = true;
          $json['url'] = site_url("backend/pohon_jaringan/show/$last_id_member");
        }else {
          foreach ($_POST as $key => $value)
            {
              $json['alert'][$key] = form_error($key);
            }
        }

      echo json_encode($json);
    }
  }


  function _cek_kode_ref($str)
  {
    $where = array('tb_member.is_active' => '1',
                    'tb_member.is_verifikasi' => '1',
                    'tb_auth.level' => 'member',
                    'tb_auth.username' => $str);
      $query = $this->db->select("tb_member.id_member,
                                  tb_member.is_active,
                                  tb_member.is_verifikasi,
                                  tb_auth.id_auth,
                                  tb_auth.username,
                                  tb_auth.level")
                            ->from("tb_member")
                            ->join("tb_auth","tb_member.id_member = tb_auth.id_personal")
                            ->where($where)
                            ->get();
      // $qry = $this->db->get_where("tb_member",["kode_referral"=>$str, "is_verifikasi"=>"1"]);
      if ($query->num_rows() > 0) {
        return true;
      }else {
        $this->form_validation->set_message('_cek_kode_ref', 'Username yang anda masukkan tidak terdaftar');
        return false;
      }
  }

  function _cek_pin($str)
  {
    $pin = paket($str,'pin');

    if ($this->balance->stok_pin(sess('id_member')) >= $pin) {
      return true;
    }else {
      $this->form_validation->set_message('_cek_pin', 'Stok PIN tidak mencukupi, paket '.paket($str,'paket').' memerlukan '.$pin.' PIN. STOK PIN anda <b class="text-primary">'.$this->balance->stok_pin(sess('id_member')).'</b>');
      return false;
    }
  }


function _cek_nik($str,$tgl_lahir)
{
  // if ($tgl_lahir!="") {
  //   $tgl_array = explode("/",$tgl_lahir);
  //   $tgl = $tgl_array[0];
  //   $bulan = $tgl_array[1];
  //   $tahun = substr($tgl_array[2],-2);
  //
  //   $gabung = $tgl."".$bulan."".$tahun;
  //   $nik = substr($str,-10,-4);
  //   if ($gabung==$nik) {
  //       return true;
  //   }else {
  //       $this->form_validation->set_message('_cek_nik', 'Nik/No.KTP tidak valid.');
  //       return false;
  //   }
  // }else {
  //   return true;
  // }

  return true;

}




function kabupaten(){
      $propinsiID = $_GET['id'];
      $kabupaten   = $this->db->get_where('wil_kabupaten',array('province_id'=>$propinsiID));
      echo '<option value="">-- Pilih Kabupaten/Kota --</option>';
      foreach ($kabupaten->result() as $k)
      {
          echo "<option value='$k->id'>$k->name</option>";
      }
  }


  function kecamatan(){
     $kabupatenID = $_GET['id'];
     $kecamatan   = $this->db->get_where('wil_kecamatan',array('regency_id'=>$kabupatenID));
     echo '<option value="">-- Pilih Kecamatan --</option>';
     foreach ($kecamatan->result() as $k)
     {
         echo "<option value='$k->id'>$k->name</option>";
     }
 }

 function kelurahan(){
      $kecamatanID  = $_GET['id'];
      $desa         = $this->db->get_where('wil_kelurahan',array('district_id'=>$kecamatanID));
      echo '<option value="">-- Pilih Kelurahan/Desa --</option>';
      foreach ($desa->result() as $d)
      {
          echo "<option value='$d->id'>$d->name</option>";
      }
  }


  ///END REGISTER FORM MEMBER


  ///member
  function cek_verifikasi($id_member="",$paket="")
  {

    $pin = paket($paket,'pin');

    if ($this->balance->stok_pin(sess('id_member')) >= $pin) {
      echo "<p class='text-center'> APA ANDA YAKIN INGIN MEMVERIFIKASI?</p>";
      echo "<p></p>";
      echo "<hr>";
      echo "<a  class='btn btn-success btn-md'  href=".site_url("backend/pohon_jaringan/verifikasi_member/$id_member").">Ya, saya yakin</a>
            <button type='button' class='btn btn-light btn-md' data-dismiss='modal'>Batal</button>";
    }else {
      echo "<p class='text-center'>PIN Anda Tidak Mencukupi. untuk paket <b>".strtoupper(paket($paket,'paket'))."</b> memerlukan $pin PIN. Total PIN anda ".$this->balance->stok_pin(sess('id_member'))."</p>";
      echo "<p class='text-center'>Silahkan Order PIN terlebih dahulu.</p>";
      echo "<hr>";
      echo "<button type='button' class='btn btn-secondary text-white btn-md btn-block' data-dismiss='modal'>Tutup</button>";
    }

  }


  function verifikasi_member($id_member=""){
    if ($id_member!="") {
      // cek apakah id member sudah terverifikasi
      //jika sudah tidak bisa mengakses modul member verifikasi
      $qry = $this->db->get_where("tb_member",["id_member"=>$id_member,"is_verifikasi"=>"1"]);
      if ($qry->num_rows() > 0) {
          redirect(site_url("backend/pohon_jaringan/show/$id_member"),"refresh");
      }else {
        $this->load->helper(['cek_pohon_verif']);
        $this->template->set_title("Verifikasi Member");
        $data["id_member_verif"] = $id_member;
        $data["root"] = $this->model->get_where("tb_member",["id_member"=>$this->session->userdata("id_member")]);
        $this->template->view("content/pohon_jaringan/index_posisi_member",$data);
      }
    }else {
      $this->_error404();
    }
  }

  function verifikasi_member_show($id_root,$id_member=""){
    if ($id_member!="") {
      // cek apakah id member sudah terverifikasi
      //jika sudah tidak bisa mengakses modul member verifikasi
      $qry = $this->db->get_where("tb_member",["id_member"=>$id_member,"is_verifikasi"=>"1"]);
      if ($qry->num_rows() > 0) {
          redirect(site_url("backend/pohon_jaringan/show/$id_member"),"refresh");
      }else {
        $this->load->helper(['cek_pohon_verif']);
        $this->template->set_title("Verifikasi Member");
        $data["id_member_verif"] = $id_member;
        $data["root"] = $this->model->get_where("tb_member",["id_member"=>$id_root]);
        $this->template->view("content/pohon_jaringan/index_posisi_member",$data);
      }
    }else {
      $this->_error404();
    }
  }

  function verifikasi_member_action($id_parent,$posisi,$id_member_verif){
    if ($this->input->is_ajax_request()) {
      $paket = profile_member($id_member_verif,"paket");
      $pins = paket($paket,'pin');

      if ($this->balance->stok_pin(sess('id_member')) >= $pins) {
        $update_member = [ "is_verifikasi" =>"1",
                           "posisi" => $posisi,
                           "created" => date("Y-m-d H:i:s")
                          ];
        $this->model->get_update("tb_member",$update_member,["id_member"=>$id_member_verif]);

        $insert_trans_parent = [ "id_parent"=>$id_parent,
                                  "id_member"=>$id_member_verif
                              ];
        $this->model->get_insert("trans_member",$insert_trans_parent);


        if ($posisi=="kiri") {
          $leave = array('l_mem' => $id_member_verif);
        }else {
          $leave = array('r_mem' => $id_member_verif);
        }

        $this->db->update("trans_member",$leave,["id_member" => $id_parent]);

        $query_pin = $this->model->query_cek_pin($pins);

        $serial_pin = "SN".date('dmyhis');

        foreach ($query_pin as $pin) {
          $insert_trans_pin_pakai = array('serial_pin' => $serial_pin,
                                          'id_pin_trans'  => $pin->id_pin_trans,
                                          'id_member_pakai'  =>$id_member_verif,
                                          'tgl_aktivasi' => date('Y-m-d H:i:s'),
                                          'status'  => "registrasi");
          $this->model->get_insert("trans_pin_pakai",$insert_trans_pin_pakai);


          $update_trans_pin = array('key_order_pin' => "KOP".date('dmyhis'),
                                    'status'  => 'terpakai'
                                    );
          $this->db->update("trans_pin",$update_trans_pin,["id_pin_trans" => $pin->id_pin_trans]);
        }

        //insert bonus SPONSOR
        $inser_b_sponsor = array('id_parent' => sess('id_member') ,
                                  'id_member' => $id_member_verif,
                                  'created'   => date('Y-m-d H:i:s'),
                                  'total_bonus'=> $this->balance->get_bonus_sponsor($paket)
                                );

        $this->model->get_insert("bonus_sponsor",$inser_b_sponsor);


          //BONUS PAIRING
        $is_parent = $this->btree->cek_is_parent($id_member_verif);

        foreach ($is_parent as $value) {
           $this->btree->pairing($value,$id_member_verif);
        }


        $json['alert'] = "Member berhasil di verifikasi";
        $json['url']  = site_url("backend/pohon_jaringan/show/$id_parent");
      }else {
        $json['alert'] = "Gagal Memverifikasi member, PIN ANDA TIDAK MENCUKUPI";
        $json['url']  = site_url("backend/member/menunggu_verifikasi");
      }

      echo json_encode($json);
    }
  }

  function _send_mail($email,$username,$nama,$telepon,$kode_referral,$nik,$paket,$password)
  {


    error_reporting(0);

    $data = array('nama' => $nama,
                  'nik' => $nik,
                  'email' => $email,
                  'telepon' => $telepon,
                  'username' => $username,
                  'password' => $password,
                  'kode_referral' => $kode_referral,
                  'paket' => paket($paket,'paket')
                  );
    $link = site_url();
    $subject  = "Data Member";

    $template = $this->load->view('content/pohon_jaringan/email_template',$data,TRUE);

    $host = config_umum("smtp_host");
    $port = config_umum("smtp_port");
    $user = config_umum("smtp_user");
    $pass = decrypt_gue(config_umum("smtp_pass"));

    $config['charset']      = 'utf-8';
    $config['protocol']     = "smtp";
    $config['mailtype']     = "html";
    $config['smtp_host']    = "$host";//pengaturan smtp
    $config['smtp_port']    = $port;
    $config['smtp_user']    = "$user"; // isi dengan email kamu
    $config['smtp_pass']    = $pass; // isi dengan password kamu
    $config['smtp_timeout'] = 4; //4 second
    $config['crlf']         ="\r\n";
    $config['newline']      ="\r\n";

    $this->load->library('email',$config);
    //konfigurasi pengiriman

    $this->email->from($config['smtp_user'],"Binary-tree");
    $this->email->to($email);
    $this->email->subject($subject);
    $this->email->message($template);
    if ($this->email->send()) {
      return 1;
    }else {
      return 0;
    }
  }






} //end class
