<?php include("hf/header.php") ?>
<?php
$dt1 = new DateTime();
$dt1->setTimezone(new DateTimeZone('Asia/Kolkata'));
$dt = $dt1->format('Y-m-d');
 ?>
<div class="container-fluid" style="margin-top:10px">

  <div class="row">
    <div class="col-lg-3">
      <form action="#" method="post">
        <!-- <div class="custom-control custom-switch">
          <input type="checkbox" name="showScheduled" value="1" class="custom-control-input" id="customSwitch1">
          <label class="custom-control-label" for="customSwitch1">Scheduled Message only</label>
        </div> -->
        <div class="form-group">
          <label>Select Date:</label>
          <select class="form-control" name="date">
            <option class="form-control" value="">Select...</option>
            <?php
            if (count($dates)):
              foreach($dates as $value):
                $dt = date_create($value);
                $dt = $dt->format('d-M-Y');
              ?>
              <option class="form-control" value="<?=$value?>"><?=$dt?></option>
            <?php endforeach;endif ?>
          </select>

          <button class="btn btn-primary float-right" type="submit" style="margin-top:5px" name="showReport" value="1">Show</button>
        </div>
      </form>

    </div>
    <div class="col-lg-9">
      <div style="height:95%;overflow-y:auto;overflow-x:auto">
        <table class="table table-striped table-dark" >
          <thead>
            <tr>
              <th>Job ID</th>
              <th>ErrorCode</th>
              <th>ErrorMessage</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody id="showdata">
            <?php if(count($sms_report)):
              foreach($sms_report as $value):
                if($value->job_id):
                $dt = date_create($value->timestamp);
               ?>
               <tr>
                 <td><a href="javascript:;" class="btn btn-light item-show" onclick="waiting();" data="<?=$value->job_id ?>"><?=$value->job_id ?></a> </td>
                 <td><?=$value->error_code ?></td>
                 <td><?=$value->error_message ?></td>
                 <td><?=$dt->format('d-M-Y g:i:s A')?></td>
               </tr>
             <?php endif; endforeach ?>
           <?php else: ?>
             <tr>
               <td colspan="4"> <p class="lead">No data found.</p> </td>
             </tr>
           <?php endif ?>
          </tbody>
        </table>
    </div>
  </div>
</div>


<?php include("modal.php") ?>
<?php include("hf/footer.php") ?>
