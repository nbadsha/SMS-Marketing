<?php include 'hf/header.php';?>

<?php
$editTitle= '';
$editBody = '';
$editSmsID = '';
$editButtonName = 'addSmsTemplate';
$editButtonText = 'Create';
$class = 'success'
 ?>

<div class="container-fluid"  style="margin-top:10px">
  <div class="row">
    <div class="col-lg-4">
      <form class="form-horizontal" action="<?php echo base_url("admin/addSmsTemplate") ?>" method="post">
        <div class="form-group">

          <?php if ($msg = $this->session->flashdata('msg')):?>
            <?php if ($this->session->flashdata('msg_class')){$class = $this->session->flashdata('msg_class');} ?>
            <div class="alert alert-dismissible alert-<?=$class ?> ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><?php echo $msg; ?></strong>
            </div>
          <?php endif ?>
          <?php if($editSmsTemplateID = $this->session->flashdata('editSmsTemplateID')):
                  foreach ($sms as $value) {
                    if ($value->id == $editSmsTemplateID) {
                      $editTitle = $value->title;
                      $editBody = $value->body;
                      $editSmsID = $value->id;
                      $editButtonName = 'updateSMSTemplate';
                      $editButtonText = 'Update';
                    }
                  }
                endif;
              ?>
              <input type="hidden" name="updateSMSid" value="<?=$editSmsID  ?>">
          <label for="sms_title">SMS Title:</label>
          <input type="text" class="form-control" name="sms_title" value="<?php echo set_value('sms_title',$editTitle) ?>" maxlength="30" placeholder="Type Title here...">
          <?php echo form_error('sms_title','<label class="text text-danger">','</label>') ?>
        </div>
        <div class="form-group">
          <label for="sms_body">SMS Message:</label>
          <textarea name="sms_body" rows="8" cols="80" class="form-control" placeholder="Type Message here..."><?php echo set_value('sms_body',$editBody) ?></textarea>
          <p class="text text-primary">Characters: <span id='m_char' class="text text-danger">0</span> | Message: <span id='m_count' class="text text-danger">0</span></p>
          <?php echo form_error('sms_body','<label class="text text-danger">','</label>') ?>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-info btn-md float-right" name="<?=$editButtonName  ?>" value="1" style="margin-left:5px"><?= $editButtonText ?></button>
          <button type="reset" class="btn btn-secondary btn-md float-right" >Reset</button>
        </div>
      </form>
    </div>

    <div class="col-lg-8">
      <div class="card-columns">
      <div class="scrollbar">

        <?php if(count($sms)): ; foreach($sms as $value):?>
          <div class="card border-info lg" style="max-width: 25rem;">
            <div class="card-header"> <h5><?php echo $value->title; ?></h5> </div>
            <div class="card-body text-danger" style="padding-top:2px;">
              <p class="card-text" style="padding-top:2px;"><?php echo $value->body; ?></p>
              <form action="<?php echo base_url("admin/editSmsTemplate") ?>" method="post">
                <button type="submit" class="btn btn-success float-left" style="margin-bottom:2px" name="editSmsTemplateID" value="<?php echo $value->id;  ?>">
                  <svg class="bi bi-pencil-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </button>
              </form>
              <form action="<?php echo base_url("admin/deleteSmsTemplate") ?>" method="post">
                <button type="submit" class="btn btn-danger float-right" onclick="return confirm('Are you sure, you want to delete?');" style="margin-bottom:2px" name="deleteSmsTemplateID" value="<?php echo $value->id;  ?>">
                  <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </button>
              </form>
            </div>
          </div>
      <?php endforeach; ?>
      <?php endif;?>
        </div>
    </div>

  </div>
</div>
<?php include 'hf/footer.php';?>
