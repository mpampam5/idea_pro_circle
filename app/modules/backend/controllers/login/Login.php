<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require APPPATH."/modules/backend/core/MY_Controller.php";

class Login extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->load->helper(array("backend/telegram"));
  }

  function index(){
    // $this->load->set_title("Login");
    if ($this->session->userdata('member_login')==true) {
        redirect(site_url("backend/home"),"refresh");
    }else {
      $this->load->view("login/index");
    }
  }


  function action(){
    if ($this->input->is_ajax_request()) {
        $json = array('success' => false,
                      "valid"=>false,
                      'url'=>"",
                      'alert'=>array()
                    );
        $this->load->library("form_validation");

        $this->form_validation->set_rules("username","Username","trim|xss_clean|required");
        $this->form_validation->set_rules("password","Password","trim|required");
        $this->form_validation->set_error_delimiters('<label class="error mt-2 text-danger">','</label>');


        if ($this->form_validation->run()) {
            $json["success"] = true;
            $username = $this->input->post("username",true);
            $password = $this->input->post("password");

            // $str_raplace_username = str_replace('\'','mpampamdotcom',$username);
            $where = array( "username" => $username,
                            "level" => "member",
                            "is_active" => '1',
                            "is_verifikasi" => '1');
            $qry = $this->db->select("tb_auth.id_personal,
                                      tb_auth.username,
                                      tb_auth.`password`,
                                      tb_auth.`level`,
                                      tb_auth.token,
                                      tb_member.email,
                                      tb_member.kode_referral,
                                      tb_member.is_active,
                                      tb_member.is_verifikasi")
                            ->from("tb_auth")
                            ->join("tb_member","tb_member.id_member = tb_auth.id_personal")
                            // ->where("(username = '$str_raplace_username' OR email='$str_raplace_username')")
                            ->where($where)
                            ->get();
            if ($qry->num_rows() > 0) {

                $this->load->helper("pass_hash");
                if (pass_decrypt($qry->row()->token,$password,$qry->row()->password)==true) {
                  $this->load->library('user_agent');


                  $insert_log_login = array('id_member' =>$qry->row()->id_personal ,
                                            'level' => "member",
                                            'time_login' => date('Y-m-d H:i:s'),
                                            'ip_address' => $this->input->ip_address(),
                                            'user_agent' => "Browser ".$this->agent->browser()." v.".$this->agent->version()." (".$this->agent->platform().")"
                                            );
                  $this->db->insert('log_login',$insert_log_login);

                  $session = array(
                                    'id_member'     => $qry->row()->id_personal,
                                    'kode_referral' => $qry->row()->kode_referral,
                                    'member_login'  => true
                                  );
                  $this->session->set_userdata($session);
                  $json['valid'] = true;
                  $json['url'] = site_url("backend/home");
                }else {
                  $json["alert"] = "Data yang anda masukkan tidak valid. Coba lagi!";
                }
            }else {
                $json["alert"] = "Data yang anda masukkan tidak valid. Coba lagi!";
            }

        }else {
          foreach ($_POST as $key => $value) {
                    $json['alert'][$key] = form_error($key);
                  }
        }
        echo json_encode($json);
    }
  }


  function logout()
  {
    $this->session->sess_destroy();
    redirect(site_url("member-panel"));
  }



} //end class