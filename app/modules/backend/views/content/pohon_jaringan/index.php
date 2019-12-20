<link rel="stylesheet" href="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
<script src="<?=base_url()?>_template/back/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="<?=site_url("backend/index")?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
  </ol>
</nav>


<div class="row">



  <div class="col-md-12 mb-4">
    <div class="input-group mb-3">
      <input type="text" class="form-control" placeholder="Masukkan Username" id="binary_username" autocomplete="off">
      <div class="input-group-append">
        <button style="" class="input-group-text text-white bg-primary" id="binary-search"><i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
      </div>
    </div>
    <?php if ($root->id_member!=sess('id_member')): ?>
      <a href="<?=site_url("backend/pohon_jaringan")?>" class="btn btn-info btn-sm text-white">Back To Top Parent</a>
    <?php endif; ?>
</div>



          <div class="col-sm-12 content-root mt-2 mb-5">
            <table id="table-content-pohon">
              <!-- level1 -->
              <tr>
                <td colspan="4">
                  <div id="root" class="root1">
                    <h4><?=$root->nama?></h4>
                    <?php if ($root->id_member!=$this->session->userdata('id_member')): ?>
                      <?=ambil_data_parent($root->id_member)?>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <!-- end level 1 -->

              <!-- level 2 -->                                
              <tr>
                <!-- kiri -->
                <td colspan="2">
                  <div id="root" class="root2">
                    <?=cek_parent($root->id_member,"kiri");?>
                    <?php $id_kiri = cek_parent_id($root->id_member,"kiri");?>
                  </div>
                </td>

                <!-- kanan -->
                <td colspan="2">
                  <div id="root" class="root3">
                    <?=cek_parent($root->id_member,"kanan");?>
                    <?php $id_kanan = cek_parent_id($root->id_member,"kanan")?>
                  </div>
                </td>

              </tr>
              <!-- end level 2 -->



              <!-- level 3 -->
              <tr>
                <!-- kiri -->
                <td>
                  <div id="root" class="root4">
                    <?php if ($id_kiri!=false): ?>
                      <?php $cucu=cek_id_cucu($id_kiri,"kiri");
                      if ($cucu['status'] == true) {
                        echo $cucu['nama'];
                      }else {
                        echo $cucu["button"];
                      }
                      ?>
                    <?php endif; ?>
                  </div>
                </td>

                <!-- kanan -->
                <td>
                  <div id="root" class="root5">
                    <?php if ($id_kiri!=false): ?>
                      <?php $cucu=cek_id_cucu($id_kiri,"kanan");
                        if ($cucu['status'] == true) {
                          echo $cucu['nama'];
                        }else {
                          echo $cucu["button"];
                        }
                      ?>

                    <?php endif; ?>
                  </div>
                </td>


                <!-- kiri -->
                <td>
                  <div id="root" class="root6">
                    <?php if ($id_kanan!=false): ?>
                      <?php
                          $cucu = cek_id_cucu($id_kanan,"kiri");
                          if ($cucu['status'] == true) {
                            echo $cucu['nama'];
                          }else {
                            echo $cucu["button"];
                          }
                      ?>
                    <?php endif; ?>
                  </div>
                </td>


                <!-- kanan -->
                <td>
                  <div id="root" class="root7">
                    <?php if ($id_kanan!=false): ?>
                      <?php
                          $cucu = cek_id_cucu($id_kanan,"kanan");
                          if ($cucu['status'] == true) {
                            echo $cucu['nama'];
                          }else {
                            echo $cucu["button"];
                          }
                      ?>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <!-- end level 3 -->

            </table>
          </div>

      </div>


      <script type="text/javascript">
      $(document).on("click","#binary-search",function(e){
        e.preventDefault();
        var val_username = $("#binary_username").val();

        if (val_username=="") {
          $.toast({
            text: "form input tidak boleh kosong",
            showHideTransition: 'slide',
            icon: "error",
            loaderBg: '#f96868',
            position: 'bottom-right',
          });
        }else {
          $.ajax({
              url: "<?=base_url("backend/pohon_jaringan/search")?>",
              type: "post",
              data: {username: $("#binary_username").val()}, // Set data yang akan dikirim
              dataType: "json",
            }).done(function(json){
              if (json.success!=true) {
                $("#binary_username").val("");
                $.toast({
                  text: json.alert,
                  showHideTransition: 'slide',
                  icon: "error",
                  loaderBg: '#f96868',
                  position: 'bottom-right',
                });

              }else {
                window.location.href = json.url;
              }
            });
        }






      });
      </script>
