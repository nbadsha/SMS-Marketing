<?php include 'hf/header.php';?>

<div class="container-fluid " style="margin-top:10px">

  <div class="row">
    <div class="col-lg-4">

      <div class="form-group">
        <label for="csvFile">Import CSV File To Load Contacts:</label>

        <?php if ($msg = $this->session->flashdata('msg')):?>
          <?php if ($this->session->flashdata('msg_class')){$class = $this->session->flashdata('msg_class');} ?>
          <div class="alert alert-dismissible alert-<?=$class ?> ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><?php echo $msg; ?></strong>
          </div>
        <?php endif ?>

        <form  action="<?=base_url('admin/uploadCSV') ?>" enctype="multipart/form-data" method="post">
          <div class="input-group mb-3">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile" accept=".csv" name="csvFile" required>
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary" name="uploadCSV" value="1">Upload</button>
            </div>
          </div>
        </form>
      </div>
      <?php

        $name = '';
        $mobile = '';
        $dob = '';
        $addButton = 'Add';
        $id = '';
        $addButtonName = 'addOneContact';
       ?>
<?php if ($data = $this->session->flashdata('editContactData')) {
  $name = $data->name;
  $mobile = substr($data->mobile,3);
  $dob = $data->dob;
  $id = $data->id;
  $addButton = 'Update';
  $addButtonName = 'updateOneContact';
} ?>
      <hr style="border-top: 2px dashed black">
      <form class="form-horizontal" action="<?=base_url('Admin/modifyOneContact') ?>" method="post">
        <div class="form-group">
          <label >Either enter manually:</label><br>
          <label>Name:</label>
          <input type="text" class="form-control" name="name" value="<?=$name ?>" placeholder="Enter name..." required>

        </div>
        <div class="form-group">
          <label>Mobile:</label>
          <input type="tel" class="form-control" name="mobile" value="<?=$mobile ?>" maxlength="10" placeholder="Enter 10 digits only..." required>

        </div>
        <div class="form-group">
          <label>D.O.B:</label>
          <input type="date" class="form-control" name="date" value="<?=$dob ?>" required>
          <input type="hidden" name="c_id" value="<?=$id ?>">
        </div>
        <button type="submit" class="btn btn-primary float-right" style="margin-right:5px" name="<?=$addButtonName ?>" value="1"><?=$addButton ?></button>
        <button type="reset" class="btn btn-secondary float-right" style="margin-right:5px">Reset</button>
      </form>
    </div>


    <div class="col-lg-8" style="height:100%;overflow-y:auto;overflow-x:auto">
      <?=$this->pagination->create_links() ?>
      <!-- <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item active">
      <a class="page-link" href="#">4 <span class="sr-only">(current)</span></a>
    </li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
      </ul>
    </nav> -->
      <table class="table table-striped" >
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Mobile</th>
            <th scope="col">DOB</th>
            <th scope="col">
              <form method="post" action="<?=base_url('admin/deleteAllContact') ?>"><button type="submit" class="btn btn-danger float-right" style="padding:1px" name="deleteAllContact" value="1" onclick="return confirm('All contacts will be deleted. Are you sure, you want to delete?')">
                Action
                <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                  </svg>
              </button></form>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($contacts)):
            $count = $this->uri->segment(3)+1;
            foreach ($contacts as $value):;
            if ($value->dob) {
              $dt = date_create($value->dob);
              $db = $dt->format('d-M-Y');
            }
            else{
              $db = '';
            }
            ?>
            <tr style="color:#7c01ff  ">
              <th ><?=$count ?></th>
              <td scope='col'><?=$value->name ?></td>
              <td scope='col'> <a tel:="<?=$value->mobile?>"><?=$value->mobile?></a> </td>
              <td scope='col'><?=$db ?></td>
              <td scope='col' align='left'>
              <form method="post" action="<?=base_url('admin/viewEditContact') ?>">  <button type="submit" class="btn btn-info float-left" name="editContact" style="padding:1px" value="<?=$value->id ?>">
                  <svg class="bi bi-pencil-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </button></form>
                <form method="post" action="<?=base_url('admin/deleteContact') ?>"><button type="submit" class="btn btn-danger float-right" style="padding:1px" name="deleteContactID" value="<?=$value->id ?>" onclick="return confirm('Are you sure, you want to delete?')">
                  <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </button></form>
              </td>
            </tr>
            <?php $count++; endforeach ?>
          <?php else:?>
            <tr>
              <td scope='col' colspan="5" class="text text-danger lead" align="center">No contacts added.</td>
            </tr>
          <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php include 'hf/footer.php';?>
