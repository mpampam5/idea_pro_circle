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
  <div class="col-12 mb-2">
    <a href="<?=site_url("adm-backend/member/export_excel/$is_active")?>" name="button" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
  </div>

  <div class="col-12 stretch-card">

    <div class="card">
      <div class="card-body">

          <h4 class="card-title">List <?=$title?></h4>

          <div class="btn-group-header">
              <button type="button" class="btn btn-primary btn-sm btn-icon-text" id="table-reload"> <i class="fa fa-refresh btn-icon-prepend"></i></button>
          </div>

        <hr>


            <div class="table-responsive">
              <table id="table" class="table table-bordered">
                <thead >
                  <tr class="bg-warning text-white">
                      <th width="10px">No</th>
                      <th>Nama</th>
                      <th>Username</th>
                      <th>Paket</th>
                      <th>Status</th>
                      <th>Actions</th>
                  </tr>
                </thead>

              </table>
            </div>


      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

      var t = $("#table").dataTable({
          initComplete: function() {

            $(document).on('click', '#table-reload', function(){
                api.search('').draw();
                $('#table_filter input').val('');
              });

              var api = this.api();
              $('#table_filter input')
                      .off('.DT')
                      .on('keyup.DT', function(e) {
                          if (e.keyCode == 13) {
                              api.search(this.value).draw();
                  }
              });
          },
          oLanguage: {
              sProcessing: "Memuat Data..."
          },
          processing: true,
          serverSide: true,
          ajax: {"url": "<?=base_url()?>adm-backend/member/json/<?=$is_active?>", "type": "POST"},
          columns: [
              {
                "data": "id_member",
                "orderable": false,
                "visible":false
              },
              {"data":"nama"},
              {"data":"username"},
              {"data":"pakets"},
              {
                "data":"is_active",
                render:function(data, type, meta, row)
                  {
                    if (data=="1") {
                      return '<span class="text-center badge badge-success badge-pill">ON</span>';
                    }else {
                      return '<span class="text-center badge badge-danger badge-pill">OFF</span>';
                    }
                  }
              },
              {
                  "data" : "action",
                  "orderable": false,
                  "className" : "text-center"
              }
          ],
          order: [[0, 'desc']]
      });
});



$(document).on("click","#delete",function(e){
  e.preventDefault();
  $('.modal-dialog').removeClass('modal-lg')
                    .removeClass('modal-md')
                    .addClass('modal-sm');
  $("#modalTitle").text('Konfirmasi Hapus');
  $('#modalContent').html(`<p>Apakah anda yakin ingin menghapus?</p>`);
  $('#modalFooter').addClass('modal-footer').html(`<button type='button' class='btn btn-light btn-sm' data-dismiss='modal'>Batal</button>
                          <button type='button' class='btn btn-primary btn-sm' id='ya-hapus' data-id=`+$(this).attr('alt')+`  data-url=`+$(this).attr('href')+`>Ya, saya yakin</button>
                        `);
  $("#modalGue").modal('show');
});

$(document).on('click','#ya-hapus',function(e){
  $(this).prop('disabled',true)
          .text('Memproses...');
  $.ajax({
          url:$(this).data('url'),
          type:'post',
          cache:false,
          dataType:'json',
          success:function(json){
            $('#modalGue').modal('hide');
            $.toast({
              text: json.alert,
              showHideTransition: 'slide',
              icon: json.success,
              loaderBg: '#f96868',
              position: 'bottom-right',
              afterHidden: function () {
                  $('#table').DataTable().ajax.reload();
              }
            });


          }
        });
});

</script>
