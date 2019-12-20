<style media="screen">
  .table-detail tr th,td{
    font-size: 14px;
    padding: 5px;
  }
</style>
<div class="row">
  <table class="table-detail">
    <tr>
      <th>Nama</th>
      <td>: <?=profile_member_where(['tb_member.id_member'=>$id],"nama")?></td>
    </tr>

    <tr>
      <th>Username</th>
      <td>: <?=profile_member_where(['tb_member.id_member'=>$id],"username")?></td>
    </tr>

    <tr>
      <th>Paket</th>
      <td>: <?=strtoupper(paket(profile_member_where(['tb_member.id_member'=>$id],"paket"),'paket'))?></td>
    </tr>

    <tr>
      <th>Left Child</th>
      <td>: <?=$this->btree->leftcount($id)?></td>
    </tr>

    <tr>
      <th>Right Child</th>
      <td>: <?=$this->btree->rightcount($id)?></td>
    </tr>

    <tr>
      <th>Total Child</th>
      <td>: <?=$this->btree->allcount($id)?></td>
    </tr>
  </table>
</div>
