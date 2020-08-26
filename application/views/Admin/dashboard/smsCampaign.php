<?php include('hf/header.php') ?>

<?php
$campName = "";
$campID = "";
$button = "Launch";
$buttonAction = "launchCampaign";
$option = "";
if($campData = $this->session->flashdata('editCampaignData')){
  $campName = $campData->list_name;
  $campID = $campData->id;
  $sms_id = $campData->sms_id;
  $sms_title = $campData->title;
  $option = "<option value='$sms_id' selected>$sms_title</option>";
  $button = "Update Group";
  $buttonAction = "editCamp";
}
?>
<div class="container-fluid">
    <div class="row">
      <div class="col-lg-4">
        <?php if ($msg = $this->session->flashdata('msg')):?>
          <?php if ($this->session->flashdata('msg_class')){$class = $this->session->flashdata('msg_class');} ?>
          <div class="alert alert-dismissible alert-<?=$class ?> ">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong><?php echo $msg; ?></strong>
          </div>
          <?php endif ?>
          <form action="<?=base_url('admin/viewSmsCampaign') ?>" method="post">
            <div class="form-group">
                <label>Group Name:</label>
                <input type="text" class="form-control" name="cmp_name" value="<?= $campName?>" required>
            </div>
            <input type="hidden" name="editCampID" value="<?=$campID ?>">
            <div class="form-group">
                <label>Assign Template</label>
                <select class="form-control" name="temp_id" required>
                  <option value="">Select...</option>
                  <?php if (count($template)):
                    foreach($template as $value):?>
                      <option value="<?=$value->id ?>"><?=$value->title?></option>
                  <?php endforeach ?>
                <?php endif ?>
                <?=$option ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary float-right" name="<?=$buttonAction ?>" value="1"><?=$button ?></button>
            </div>
          </form>
      </div>
      <div class="col-lg-8" style="height:500px;overflow-y:auto;overflow-x:auto">
          <table class='table table-striped' >
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Template</th>
                <th>Conacts</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                <?php if (count($campaign)):
                      $count=1;
                      foreach($campaign as $value):?>
                      <tr class="text text-primary">
                        <td><?=$count ?></td>
                        <td><?=$value->list_name ?></td>
                        <td><?=$value->title?></td>
                        <td><?=$value->no_of_contacts?></td>
                        <td>
                          <form style="display: inline-block;"  action="<?=base_url('admin/contactEnrollment') ?>" method="post">
                            <button type="submit" class="btn btn-primary" value="<?=$value->id.'/'.$value->list_name ?>" name="cntEnroll">
                              <svg class="bi bi-eye" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
                              <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                              </svg>
                            </button>
                          </form>
                          <form style="display: inline-block;" action="<?=base_url('admin/editCampaign') ?>" method="post">
                            <button type="submit" class="btn btn-secondary" name="editCampaignID" value="<?=$value->id ?>">
                              <svg class="bi bi-pencil-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                              </svg>
                            </button>
                          </form>
                          <form style="display: inline-block;" action="<?=base_url('admin/deleteCampaign') ?>" method="post">
                            <button type="submit" class="btn btn-danger" name="deleteCampaignID" value="<?=$value->id?>" onclick="return confirm('Are you sure, you want to delete?')">
                              <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                              </svg>
                            </button>
                          </form>
                        </td>
                        </tr>
                  <?php $count++; endforeach ?>
                <?php else: ?>
                <tr>
                  <td colspan="5" class="lead text text-danger">No group launched.</td>
                </tr>
                <?php endif ?>

            </tbody>
          </table>
      </div>

    </div>
</div>


<?php include('hf/footer.php') ?>
