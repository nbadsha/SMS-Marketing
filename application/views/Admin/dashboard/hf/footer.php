</div>
<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Bootstrap core JavaScript -->

<script src="<?php echo base_url('Assets/vendor/jquery/jquery.min.js') ?>" type="text/javascript">

</script>

<script src="<?php echo base_url("Assets/vendor/bootstrap/js/bootstrap.bundle.min.js") ?>" type="text/javascript">

</script>

<!--DateTime-->
<!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>-->
<!--<script src="<?php echo base_url('Assets/build/js/bootstrap-datetimepicker.min.js')?>"></script>-->
<!--<script type="text/javascript">-->
<!--            $(function () {-->
<!--                $('#datetimepicker1').datetimepicker();-->

<!--            });-->
<!--        </script>-->
<!--DateTime-->

<!-- Menu Toggle Script -->
<script>
$("#menu-toggle").click(function(e) {
  e.preventDefault();
  $("#wrapper").toggleClass("toggled");
});
</script>

<script type="text/javascript">
$('#groupsms :checkbox').change(function() {
  // this will contain a reference to the checkbox
  if (this.checked) {
    $('#getval').attr('readonly', false);
  } else {
    $('#getval').attr('readonly', true);
  }
});
</script>

<script type="text/javascript">
$('#custom_sms :checkbox').change(function() {
  // this will contain a reference to the checkbox
  if (this.checked) {
    $("#mbl_num1").hide();
    $("#mbl_num2").hide();
    $("#directCSV").show();
    $("#customFile").attr("required",true);
    $("#mbl_num2").attr("required",false);
  } else {
    $("#mbl_num1").show();
    $("#mbl_num2").show();
    $("#directCSV").hide();
  }
});
</script>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<script type="text/javascript">
$(document).ready(function(){
  $("#directCSV").hide();
  $.ajax({
        type: 'ajax',
        method: 'get',
        url: '<?php echo base_url() ?>admin/getSmsBal',
        async: true,
    success: function(result){

       $("#sms_bal").html(result);
    },

    });
})(jQuery);

</script>


<script>
function toggle(source) {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i] != source)
          checkboxes[i].checked = source.checked;
  }
}
</script>
<script type="text/javascript">
$('textarea').on("input", function(){
  var maxlength = 160;
  var currentLength = $(this).val().length;
  var message = 0;
  console.log(currentLength);
  console.log(Math.ceil(currentLength/maxlength));
  document.getElementById("m_char").innerHTML = currentLength;
  document.getElementById("m_count").innerHTML = Math.ceil(currentLength/maxlength);
});
</script>

<script type="text/javascript">
  function changeFun() {
    var addInput = document.getElementById("schd");
    var selectBox = document.getElementById("selectBox");
   var selectedValue = selectBox.options[selectBox.selectedIndex].value;
   if (selectedValue == "schedule") {
     addInput.innerHTML += '<div class="form-group"><label>Select DateTime:</label><input type="datetime-local" class="form-control" min="<?=$dt1->format('Y-m-d\TH:i') ?>" name="cs_date" required></div>';
   }
   else{
     addInput.innerHTML = "";
   }
  }
  function changeFun1() {
    var addInput = document.getElementById("schd1");
    var selectBox = document.getElementById("selectBox1");
   var selectedValue = selectBox.options[selectBox.selectedIndex].value;
   if (selectedValue == "schedule") {
     addInput.innerHTML += '<div class="form-group"><label>Select DateTime:</label><input type="datetime-local" class="form-control" min="<?=$dt1->format('Y-m-d\TH:i') ?>" name="cs_date" required/></div>';
   }
   else{
     addInput.innerHTML = "";
   }
  }
</script>

<script type="text/javascript">
  function updateinput(e) {
  var selectedOption = e.options[e.selectedIndex];
  var v = selectedOption.getAttribute('data-val');
  document.getElementById('getval').value = v;
}
</script>

<script type="text/javascript">

$('#showdata').on('click', '.item-show', function(){
var id = $(this).attr('data');
var txt = "";
$.ajax({
  type: 'ajax',
  method: 'get',
  url: '<?php echo base_url() ?>admin/showJob',
  data: {id},
  async: false,
  dataType: 'json',
  beforeSend: function() {
              $("#ldr").show();
           },
  success: function(data){
    $('#errorcode').html(data['ErrorCode']);
    $('#errormessage').html(data['ErrorMessage']);
    $('#message').html(data['message']);
    if (data['MessageId']=='') {
      $('#messageid').html(data['MessageId']);
    }
    else{
      $('#messageid').html('NULL');
    }
    for(x in data['DeliveryReports']){
      txt += "<tr>"
      for(y in data['DeliveryReports'][x]){
        txt +="<td>"+data['DeliveryReports'][x][y]+"</td>"
      }
      txt +="</tr>";
    }
    $("#viewJobDetails").html(txt);
    // $("#ldr").hide();
  },
  complete:function(data){
    // Hide image container
    // $("#ldr").hide();
   }

});
setTimeout(function(){
$("#ldr").hide();
}, 500);
setTimeout(function(){
  $('#myModal').modal('show');
  $('#myModal').find('.modal-title').text('Job Details');
}, 500);
});
</script>
</body>
</html>
