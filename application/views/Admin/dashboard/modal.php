
<style>
.loader {
  position: fixed;
  left: 50%;
  top: 50%;
  z-index: 9999;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 8px solid blue;
  border-right: 8px solid green;
  border-bottom: 8px solid red;
  border-left: 8px solid pink;
  width: 50px;
  height: 50px;
  display: none;
  -webkit-animation: spin 1s linear infinite;
  animation: spin 1s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
/* If in mobile screen with maximum width 479px. The iPhone screen resolution is 320x480 px (except iPhone4, 640x960) */    
@media only screen and (max-width: 479px){
    #container2 { width: 90%; }
}
</style>

<div class="loader" id="ldr"></div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog mw-100 w-50" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        	<table class="table table-striped table-dark">
            <thead>
              <tr>
                <th>ErrorCode: <span class="text text-primary"><span id="errorcode" class="text text-primary">NULL</span></span></th>
                <th>ErrorMessage:  <span class="text text-primary"><span id="errormessage" class="text text-primary">NULL</span></span></th>
                <th>MessageId:  <span class="text text-primary"><span id="messageid">NULL</span></span></th>
              </tr>
              <tr>
                <th colspan="3">Message Text<br> <p class="lead text text-primary" id="message">NULL</p> </th>
              </tr>
              <tr>
                <th colspan="3">DeliveryReports</th>
              </tr>
              <tr>
                <th>MessageId</th>
                <th>DeliveryStatus</th>
                <th>DeliveryDate</th>
              </tr>
            </thead>
            <tbody id=viewJobDetails class="text text-primary">

            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
