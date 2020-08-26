<?php include("hf/header.php") ?>

<div class="container-fluid" style="margin-top:10px">
    <div class="col-lg-4">
      <?php if ($msg = $this->session->flashdata('msg')):?>
        <?php if ($this->session->flashdata('msg_class')){$class = $this->session->flashdata('msg_class');} ?>
        <div class="alert alert-dismissible alert-<?=$class ?> ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?php echo $msg; ?></strong>
        </div>
        <?php endif ?>
    </div>
      <?php
      $dt1 = new DateTime();
      $dt1->setTimezone(new DateTimeZone('Asia/Kolkata'));
      ?>

  <div class="row">
    <div class="col-lg-4">
      <div class="page-header">
        <h3>Custom SMS</h3>
        <hr>
      </div>
      <form action="<?=base_url('Admin/sendCustomSMS') ?>" method="post" id="custom_sms" enctype="multipart/form-data">
        <div class="form-group">
          <label>When to send?</label>
          <select class="form-control" name="when" id='selectBox' onchange="changeFun();">
            <option value="now" selected>Instantly</option>
            <option value="schedule">Schedule</option>
          </select>
          <div id='schd'>
          </div>
        </div>
        <div class="form-group">
          <!-- <input type="tel" class="form-control" name="cs_mobile" maxlength="10" pattern="[0-9]{10}" placeholder="Type mobile number"> -->
          <div class="custom-control custom-switch">
           <input type="checkbox" class="custom-control-input" name="direct_csv" value="1" id="customSwitch2">
           <label class="custom-control-label text text-primary" for="customSwitch2" style="cursor: pointer;"><u>Either Select CSV File</u></label>
         </div>
         <div class="input-group" id="directCSV">
           <div class="custom-file">
             <input type="file" class="custom-file-input" id="customFile" accept=".csv" name="csvFile">
             <label class="custom-file-label" for="customFile">Choose file</label>
           </div>
         </div>
         <label id="mbl_num1">Mobile Number:</label>
          <input list="brow" id="mbl_num2" class="form-control" name="mobile_numbers" onclick="value=''" maxlength="13" placeholder="Select/Type Mobile Number..." required>
          <datalist id="brow">
            <?php
              if (count($contact_numbers)):
                foreach($contact_numbers as $value):?>
                  <option value="<?=$value->mobile ?>"><?=$value->name ?></option>
                <?php endforeach; endif  ?>
          </datalist>
        </div>
        <div class="form-group">
          <label>Message:</label>
          <textarea name="message" rows="7" cols="80" class="form-control" placeholder="Type Message here..." required></textarea>
          <p class="text text-primary">Characters: <span id='m_char' class="text text-danger">0</span> | Message: <span id='m_count' class="text text-danger">0</span><span><button type="submit" class="btn btn-primary float-right" style="margin-top:5px" name="sendCustomSMS" value='1'>Send</button></span> </p>
        </div>
      </form>
    </div>
    <div class="col-lg-4">
      <div class="page-header">
        <h3>Group SMS</h3>
        <hr>
      </div>
      <form action="<?=base_url('Admin/sendGroupSMS') ?>" method="post" id="groupsms">
        <div class="form-group">
          <label>When to send?</label>
          <select class="form-control" name="when" id='selectBox1' onchange="changeFun1();" required>
            <option value="now" selected>Instantly</option>
            <option value="schedule">Schedule</option>
          </select>
          <div id='schd1'>
          </div>
        </div>
        <div class="form-group">
          <label>Select Group:</label>
          <select class="form-control" name="camp_id_sms_id" onchange='updateinput(this);' required>
            <option value="" selected>Select...</option>
            <?php
            if (count($campaign)):
              foreach($campaign as $value):?>
              <?php if ($value->no_of_contacts):?>
              <option value="<?=$value->id?>.<?=$value->sms_id ?>" data-val="<?=$value->body ?>"><?=$value->list_name?> (<?=$value->no_of_contacts?> Contacts)</option>
            <?php endif ?>
            <?php endforeach;endif ?>
          </select>
        </div>
        <div class="form-group">
          <label>Selected Template:</label>
          <div class="custom-control custom-switch">
           <input type="checkbox" class="custom-control-input" name="editedSMS" value="1" id="customSwitch1">
           <label class="custom-control-label" for="customSwitch1">Enable Edit</label>
         </div>
          <textarea class="form-control" id = 'getval' name="message" rows="7" cols="80" readonly></textarea>
            <p class="text text-primary"><button type="submit" class="btn btn-primary float-right" style="margin-top:5px" name="sendGroupSMS" value="1" >Send</button></span> </p>
        </div>
      </form>
    </div>
    <div class="col-lg-4">
      <div class="page-header">
        <h3>Today's Report</h3>
        <hr>
      </div>
      <div style="height:100%;overflow-y:auto;overflow-x:auto">
        <table class="table table-striped table-dark" >
          <thead>
            <tr>
              <th>Job ID</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody id="showdata">
            <?php
                  if (count($sms_report)):
                    foreach($sms_report as $value):
                      if($value->job_id):
                      $dt = date_create($value->timestamp);
                      ?>
                    <tr>
                      <td><a href="javascript:;" class="btn btn-light item-show" onclick="waiting();" data="<?=$value->job_id ?>"><?=$value->job_id ?></a> </td>
                      <td> <p class="lead"><?=$dt->format('d-M-Y g:i:s A') ?></p> </td>
                    </tr>
             <?php endif; endforeach;else:?>

            <tr>
              <td colspan="2"> <p class="lead text text-warning">No message sent today.</p> </td>
            </tr>
            <?php endif  ?>
          </tbody>
        </table>
      </div>

    </div>



  </div>
</div>


<?php include("modal.php") ?>



<?php include("hf/footer.php") ?>
