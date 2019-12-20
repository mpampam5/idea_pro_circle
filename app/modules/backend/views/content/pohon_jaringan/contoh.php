<script src="<?=base_url()?>_template/back/js/popover.js"></script>


<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="<?=site_url("backend/index")?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 mb-2">
    <div class="input-group mb-3">
      <input type="text" class="form-control" placeholder="Masukkan Username" id="binary_username" autocomplete="off">
      <div class="input-group-append">
        <button style="" class="input-group-text text-white bg-primary" id="binary-search"><i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
      </div>
    </div>
</div>

  <div class="col-12 grid-margin stretch-card">

    <div class="row content-binary mx-auto">


      <div class="content-users">
        <div class="content-user-1">
          <a href="<?=base_url()?>backend/pohon_jaringan/detail/<?=$root->id_member?>" class="detail-user show-data">
            <div class="image-content" style="background-image: url('<?=base_url()?>_template/user-<?=strtolower(paket($root->paket,'paket'))?>.png');"></div>
          </a>
          <?php if ($root->id_member!=$this->session->userdata('id_member')): ?>
            <?=ambil_data_parent($root->id_member)?>
          <?php endif; ?>
        </div>
      </div>

      <div class="content-users">
        <div class="content-user-2">
          <?=cek_parent($root->id_member,"kiri");?>
          <?php $id_kiri = cek_parent_id($root->id_member,"kiri");?>
        </div>

        <div class="content-user-3">
          <?=cek_parent($root->id_member,"kanan");?>
          <?php $id_kanan = cek_parent_id($root->id_member,"kanan")?>
        </div>
      </div>


      <div class="content-users">
        <div class="content-user-4">
          <?php if ($id_kiri!=false): ?>
            <?php $cucu=cek_id_cucu($id_kiri,"kiri");
            if ($cucu['status'] == true) {
              echo '<a href="'.base_url().'backend/pohon_jaringan/detail/'.$cucu['id'].'" class="detail-user show-data" id="detail-user">
                      <div class="image-content" style="background-image: url('.base_url().'_template/user-'.strtolower($cucu['paket']).'.png);"></div>
                    </a>';
              if (cek_anak_cucu($cucu['id'])==true) {
                echo '<a href="'.base_url("backend/pohon_jaringan/show").'/'.$cucu['id'].'" class="parent">Show Child</a>';
              }else {
                echo '<a href="'.base_url("backend/pohon_jaringan/show").'/'.$cucu['id'].'" class="parent">Add Child</a>';
              }
            }else {
              echo '<a href="'.$cucu['button'].'" class="detail-user">
                      <div class="image-content" style="background-image: url('.base_url().'_template/add-user.png);"></div>
                    </a>';
            }
            ?>

            <?php else: ?>
              <a class="detail-user">
                <div class="image-content" style="background-image: url('<?=base_url()?>_template/user-blank.png');"></div>
              </a>
          <?php endif; ?>
        </div>

        <div class="content-user-5">
          <?php if ($id_kiri!=false): ?>
            <?php $cucu=cek_id_cucu($id_kiri,"kanan");
            if ($cucu['status'] == true) {
              echo '<a href="'.base_url().'backend/pohon_jaringan/detail/'.$cucu['id'].'" class="detail-user show-data" id="detail-user">
                      <div class="image-content" style="background-image: url('.base_url().'_template/user-'.strtolower($cucu['paket']).'.png);"></div>
                    </a>';
              if (cek_anak_cucu($cucu['id'])==true) {
                echo '<a href="'.base_url("backend/pohon_jaringan/show").'/'.$cucu['id'].'" class="parent">Show Child</a>';
              }else {
                echo '<a href="'.base_url("backend/pohon_jaringan/show").'/'.$cucu['id'].'" class="parent">Add Child</a>';
              }
            }else {
              echo '<a href="'.$cucu['button'].'" class="detail-user">
                      <div class="image-content" style="background-image: url('.base_url().'_template/add-user.png);"></div>
                    </a>';
            }
            ?>
          <?php else: ?>
            <a class="detail-user">
              <div class="image-content" style="background-image: url('<?=base_url()?>_template/user-blank.png');"></div>
            </a>
          <?php endif; ?>
        </div>

        <div class="content-user-6">
          <?php if ($id_kanan!=false): ?>
            <?php $cucu=cek_id_cucu($id_kanan,"kiri");
            if ($cucu['status'] == true) {
              echo '<a href="'.base_url().'backend/pohon_jaringan/detail/'.$cucu['id'].'" class="detail-user show-data" id="detail-user">
                      <div class="image-content" style="background-image: url('.base_url().'_template/user-'.strtolower($cucu['paket']).'.png);"></div>
                    </a>';
              if (cek_anak_cucu($cucu['id'])==true) {
                echo '<a href="'.base_url("backend/pohon_jaringan/show").'/'.$cucu['id'].'" class="parent">Show Child</a>';
              }else {
                echo '<a href="'.base_url("backend/pohon_jaringan/show").'/'.$cucu['id'].''.$cucu['button'].'" class="parent">Add Child</a>';
              }
            }else {
              echo '<a href="'.$cucu['button'].'" class="detail-user">
                      <div class="image-content" style="background-image: url('.base_url().'_template/add-user.png);"></div>
                    </a>';
            }
            ?>

          <?php else: ?>
            <a class="detail-user">
              <div class="image-content" style="background-image: url('<?=base_url()?>_template/user-blank.png');"></div>
            </a>
          <?php endif; ?>
        </div>

        <div class="content-user-7">
          <?php if ($id_kanan!=false): ?>
            <?php $cucu=cek_id_cucu($id_kanan,"kanan");
            if ($cucu['status'] == true) {
              echo '<a href="'.base_url("backend/pohon_jaringan/show").'/'.$cucu['id'].'" class="detail-user show-data" id="detail-user">
                      <div class="image-content" style="background-image: url('.base_url().'_template/user-'.strtolower($cucu['paket']).'.png);"></div>
                    </a>';
              if (cek_anak_cucu($cucu['id'])==true) {
                echo '<a href="'.base_url().'backend/pohon_jaringan/detail/'.$cucu['id'].'" class="parent">Show Child</a>';
              }else {
                echo '<a href="'.base_url("backend/pohon_jaringan/show").'/'.$cucu['id'].''.$cucu['button'].'" class="parent">Add Child</a>';
              }
            }else {
              echo '<a href="'.$cucu['button'].'" class="detail-user">
                      <div class="image-content" style="background-image: url('.base_url().'_template/add-user.png);"></div>
                    </a>';
            }
            ?>

          <?php else: ?>
            <a class="detail-user">
              <div class="image-content" style="background-image: url('<?=base_url()?>_template/user-blank.png');"></div>
            </a>
          <?php endif; ?>
        </div>
      </div>


    </div>




  </div>
</div>

<?php if ($root->id_member!=sess('id_member')): ?>
<div class="row mb-4">
  <div class="col-12">
    <a href="<?=site_url("backend/pohon_jaringan")?>" class="btn btn-primary btn-sm btn-block text-white">Top Parent</a>
  </div>
</div>
<?php endif; ?>


<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

            <div class="binary-info">
              <div class="binary-info-content">
                <img src="<?=base_url()?>_template/user-silver.png" alt="">
                <label for="">SILVER</label>
              </div>

              <div class="binary-info-content">
                <img src="<?=base_url()?>_template/user-gold.png" alt="">
                <label for="">GOLD</label>
              </div>

              <div class="binary-info-content">
                <img src="<?=base_url()?>_template/user-platinum.png" alt="">
                <label for="">PLATINUM</label>
              </div>

              <div class="binary-info-content">
                <img src="<?=base_url()?>_template/add-user.png" alt="">
                <label for="">ADD</label>
              </div>

              <div class="binary-info-content">
                <img src="<?=base_url()?>_template/user-blank.png" alt="">
                <label for="">BLANK</label>
              </div>

            </div>
          </div>

    </div>
  </div>
</div>


<script type="text/javascript">
  $(".show-data").click(function(e){
      e.preventDefault();
      $('.modal-dialog').removeClass('modal-lg')
                        .removeClass('modal-md')
                        .addClass('modal-sm');
      $("#modalTitle").text('Detail Member');
      $('#modalContent').load($(this).attr('href'));
      $("#modalGue").modal("show");
  });

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
