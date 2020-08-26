<?php include('hf/header.php') ?>
<?php
// print_r($this->db->error());
$deleteButton = "";
if(!count($AssContacts)){
  $deleteButton = "disabled";
}
$disabled = "";
if (!$remain_contacts) {
  $disabled = "disabled";
}
 ?>
<div class="container-fluid">
  <div class="page-header">
    <p class="lead">
      <a href="<?= base_url("admin/viewSmsCampaign") ?>" >
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-caret-left-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path d="M3.86 8.753l5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
</svg></a>
      Assign Contacts For Group: <span class="text text-primary font-weight-bold"><?=$campaign_name ?></span>.</p>
  </div>
  <hr>
  <div class="row">
    <div class="col-lg-5" style="height:600px;overflow-y:auto;overflow-x:auto">
      <?php if ($msg = $this->session->flashdata('msg')):?>

        <?php if ($this->session->flashdata('msg_class')){$class = $this->session->flashdata('msg_class');} ?>

        <div class="alert alert-dismissible alert-<?=$class ?> ">

        <button type="button" class="close" data-dismiss="alert">&times;</button>

        <strong><?php echo $msg; ?></strong>

        </div>

        <?php endif ?>

      <form  action="<?=base_url('admin/uploadCSVtoGroup') ?>" enctype="multipart/form-data" method="post">
        <div class="input-group mb-3">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="customFile" accept=".csv" name="csvFile" required>

            <label class="custom-file-label" for="customFile">Choose file</label>
          </div>
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary" name="uploadCSV" value="<?=$campaign_id ?>">Upload & Assign</button>
          </div>
        </div>
      </form>
      <table class="table table-striped" >
        <thead>
          <tr>
            <th colspan="3" class="text text-primary">Available contacts to assign.</th>
          </tr>
          <tr style="">
            <th scope="col">#</th>
            <th scope="col">Name & Mobile</th>

            <th scope="col">CheckAll<input type="checkbox" name="assign_id"   style="margin-left:2px" onclick="toggle(this);"  <?=$disabled?>/></th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($unAssContacts)):
            $count1 = 1;
            foreach ($unAssContacts as $value):?>
          <tr style="">
            <td scope="row"><?=$count1 ?></td>
            <td scope="col"><?=$value->name ?><br><?=$value->mobile ?></td>

            <form  action="<?=base_url('admin/assignContacts') ?>" method="post">
              <td><input type="checkbox" class="form-control" name="assign_id[]" value="<?=$value->id ?>" <?=$disabled?>></td>
          </tr>
        <?php $count1++; endforeach ?>
      <?php else: ?>
        <tr>
          <td colspan="3"> <span class="text text-danger">No contacts to add.</span> </td>
        </tr>
      <?php endif ?>
        </tbody>
      </table>
    </div>
    <div class="col-lg-2">
      <button type="submit" class="btn btn-success" name="assignContact" value="<?=$campaign_id ?>" <?=$disabled?>>
        <svg class="bi bi-person-plus-fill" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7.5-3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
        <path fill-rule="evenodd" d="M13 7.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0v-2z"/>
      </svg>
      </button>
      </form>
    </div>
    <div class="col-lg-5" style="height:600px;overflow-y:auto;overflow-x:auto">
      <table class="table table-striped" >
        <thead>
          <tr>
            <th colspan="3" class="text text-primary">Assigned contacts.</th>
          </tr>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name & Mobile</th>
            <th scope="col"><form method="post" action="<?=base_url('admin/deAssignAll') ?>"><button type="submit" class="btn btn-danger float-right" style="padding:1px" name="deRoll" value="<?=$campaign_id ?>" onclick="return confirm('All contacts will be deassigned. Are you sure, you want to delete?')" <?=$deleteButton ?>>
              Action
              <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
            </button></form></th>
          </tr>
        </thead>
        <tbody>
          <?php if(count($AssContacts)):
            $count2=1;
            foreach ($AssContacts as $value1): ?>
          <tr >
            <td scope="row"><?=$count2 ?></td>
            <td scope="col"><?=$value1->name ?><br><?=$value1->mobile ?></td>
            <td scope="col" align="right">
              <form style="display: inline-block;"  action="<?=base_url('admin/deAssign') ?>" method="post">
                <button type="submit" class="btn btn-danger" value="<?=$value1->id.'/'.$campaign_id ?>" name="deRoll">
                  <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                  </svg>
                </button>
              </form>
            </td>
          </tr>
        <?php $count2++; endforeach ?>
      <?php else: ?>
        <tr>
          <td colspan="3"> <span class="text text-danger">No contacts assigned.</span> </td>
        </tr>
      <?php endif ?>

        </tbody>
      </table>
    </div>



  </div>

</div>
<?php include('hf/footer.php') ?>
