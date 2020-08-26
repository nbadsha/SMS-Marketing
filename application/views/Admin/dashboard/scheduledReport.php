<?php include('hf/header.php')?>
<div class="container-fluid" style="margin-top:5px">
  <div class="row">
    <div class="col-lg-12">
      <form class="form-inline justify-content-center" method="post">
        <select class="form-control mb-2 mr-sm-2" name="shDate" required>
          <option value="">Select...</option>
          <?php
          if (count($sm_date)):
            foreach($sm_date as $value):
              $dt = date_create($value['date']);
              $dt = $dt->format('d-M-Y');
              if ($value['sm']) {
                $dt = $dt.' 🕓';
              }
            ?>
            <option class="form-control" value="<?=$value['date']?>"><?=$dt?></option>
          <?php endforeach;endif ?>
        </select>
        <div class="col-auto">
            <label for="" id="opt_label2">For Last:</label>
          </div>
          <div class="col-auto" id="opt_control2">
            <input type="number" list="brow" class="form-control mb-2" name="last_days" id="day" size="3" value="1" onclick='myFunction1()' placeholder="___days.">
            <datalist id="brow" size="3">
              <?php for($i=1;$i<31;$i++): ?>
                <option value="<?=$i?>"><?=$i?> Days</option>
                <?php endfor ?>
            </datalist>
          </div>
        <button type="submit" class="btn btn-primary mb-2" name="ShowSchd" value="1">Submit</button>
      </form>
      <?php if ($msg = $this->session->flashdata('msg')):?>
        <?php if ($this->session->flashdata('msg_class')){$class = $this->session->flashdata('msg_class');} ?>
        <div class="alert alert-dismissible alert-<?=$class ?> ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?php echo $msg; ?></strong>
        </div>
        <?php endif ?>
    </div>
  </div>
  <div class="row">
      <div class="col-lg-12">
          <!--<button class="btn btn-primary"></button>-->
          <div id="chartContainer" style="height: 370px; width: 100%;"></div>
      </div>
  </div>
    <div class="row">
      <div class="col-lg-12">
        <div style="height:100%;overflow-y:auto;overflow-x:auto;margin-left:5px;margin-right:5px;<?php if(!count($detailedReport)){echo 'visibility:hidden';} ?>">
          <table class="table table-striped table-dark" >
            <thead>
              <tr>
                <th>#</th>
                <th>Number</th>
                <th>SenderID</th>
                <th>Message</th>
                <th>SubmitDate</th>
                <th>Status</th>
                <th>DeliveryDate</th>
                <th>Type</th>
              </tr>
            </thead>
            <tbody>

              <?php if(count($detailedReport)):
                    $row = 1;
                    foreach($detailedReport as $value):
                      // echo "<pre>";
                      // print_r($value);
                 ?>
                 <tr>
                  <td><?=$row?></td>
                  <td><?=substr($value[0],2) ?></td>
                  <td><?=$value[1] ?></td>
                  <td><textarea class="form-control" rows="3" cols="30" disabled><?=$value[2] ?></textarea></td>
                  <td><?=$value[3] ?></td>
                  <td><?=$value[4] ?></td>
                  <td><?=$value[5] ?></td>
                  <td><?php if($value[7]==2){echo"Trans";}else{echo"Promo";}  ?></td>
                 </tr>
               <?php $row++;endforeach;endif ?>
            </tbody>
          </table>
      </div>
    </div>
  </div>
  <?php

$dataPoints = array();

?>
  <?php
  $total_messages = "";
  $chartSubTitle = "";
  if(isset($chartData) && count($chartData)):
    $total_messages = count($detailedReport);
    if ($total_messages>1) {
      $chartSubTitle = "Total Messages Sent: ".$total_messages;
    }else{
      $chartSubTitle = "Total Message Sent: ".$total_messages;
    }
  foreach($chartData as $key=> $value){
      $dataPoints[]=["label"=>$key,"y"=>$value];
    // echo $key." ".$value;
  }
  ?>


</div>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	// exportEnabled: true,
	title:{
		text: "SMS Delivery Report",
    fontSize: 30,
	},
	subtitles: [{
		text: "<?=$chartSubTitle ?>",
    fontSize: 25,
	}],
	data: [{
		type: "pie",
		showInLegend: "true",
		legendText: "{label}",
		indexLabelFontSize: 20,
		indexLabel: "{label} - #percent%",
		yValueFormatString: "#,##0",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

}
</script>
  <?php endif ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<?php include('hf/footer.php') ?>
