<?php defined('BASEPATH') OR exit('No direct script access allowed');

function cek_parent($id,$posisi){
  $ci = get_instance();
  $query = $ci->db->query("SELECT
                              trans_member.id_trans,
                              trans_member.id_parent,
                              trans_member.id_member,
                              tb_member.nama,
                              tb_member.posisi,
                              config_paket.paket
                            FROM
                              trans_member
                            INNER JOIN
                              tb_member ON trans_member.id_member = tb_member.id_member
                            INNER JOIN
                              config_paket ON config_paket.id_paket = tb_member.paket
                            WHERE
                              trans_member.id_parent = $id
                            AND
                              tb_member.posisi = '$posisi'

                            ");
    if ($query->num_rows() > 0) {
        $str="";
        $str.='<a href="'.base_url().'backend/pohon_jaringan/detail/'.$query->row()->id_member.'" class="detail-user show-data" id="detail-user">
                <div class="image-content" style="background-image: url('.base_url().'_template/user-'.strtolower($query->row()->paket).'.png);"></div>
              </a>';
    }else {
        $str = '<a href="'.site_url("backend/pohon_jaringan/tambah/$id/$posisi").'" class="detail-user">
                  <div class="image-content" style="background-image: url('.base_url().'_template/add-user.png);"></div>
                </a>';
    }

  return $str;
}

function cek_parent_id($id,$posisi){
  $ci = get_instance();
  $query = $ci->db->query("SELECT
                              trans_member.id_trans,
                              trans_member.id_parent,
                              trans_member.id_member,
                              tb_member.nama,
                              tb_member.posisi
                            FROM
                              trans_member
                            INNER JOIN
                              tb_member ON trans_member.id_member = tb_member.id_member
                            WHERE
                              trans_member.id_parent = $id
                            AND
                              tb_member.posisi = '$posisi'

                            ");
    if ($query->num_rows() > 0) {
        $str = $query->row()->id_member;
    }else {
      $str = false;
    }

  return $str;
}

function cek_id_cucu($id,$posisi){
  $ci = get_instance();
  $query = $ci->db->query("SELECT
                              trans_member.id_trans,
                              trans_member.id_parent,
                              trans_member.id_member,
                              tb_member.nama,
                              tb_member.posisi,
                              config_paket.paket
                            FROM
                              trans_member
                            INNER JOIN
                              tb_member ON trans_member.id_member = tb_member.id_member
                            INNER JOIN
                              config_paket ON config_paket.id_paket = tb_member.paket
                            WHERE
                              trans_member.id_parent = $id
                            AND
                              tb_member.posisi = '$posisi'

                            ");
    if ($query->num_rows() > 0) {
        $str = array( 'status'=>true,
                      'id' => $query->row()->id_member,
                      'nama' => '<h4>'.$query->row()->nama.'</h4>',
                      'username'=> '<p class="text-white">'.profile_member_where(['tb_member.id_member'=>$query->row()->id_member],'username').'</p>',
                      'paket' => $query->row()->paket,
                      "button" => site_url("backend/pohon_jaringan/tambah/$id/$posisi")
                    );
    }else {
        $str = array("status" => false,
                      "button" => site_url("backend/pohon_jaringan/tambah/$id/$posisi")
                    );
    }

  return $str;
}


function cek_anak_cucu($id)
{
  $ci = get_instance();
  $query = $ci->db->get_where("trans_member",["id_parent"=>$id]);
  if ($query->num_rows() > 0) {
    return true;
  }else {
    return false;
  }
}


function ambil_data_parent($id)
{
  $ci = get_instance();
  $query = $ci->db->get_where("trans_member",["id_member"=>$id]);
  if ($query->num_rows() > 0) {
    // $str = '<p class="text-white">Left '.$ci->btree->leftcount($id).' | '.$ci->btree->rightcount($id).' Right</p><p class="text-white">'.$ci->btree->allcount($id).'</p>';
    $str ='<a href="'.site_url("backend/pohon_jaringan/show/".$query->row()->id_parent).'" class="parent">Show Parent</a>';
    return $str;
  }
}
