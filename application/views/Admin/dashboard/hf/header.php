<!DOCTYPE html>
<html lang="en">
<head>

<?php $active = array(
                "welcome" => ["",""],
                "viewSMStemplate" => ["",""],
                "viewSmsCampaign"=> ["",""],
                "viewSendSMS"=> ["",""],
                "viewReports"=> ["",""],
                "viewScheduleReports"=> ["",""],
);
$active_menu =  $this->uri->segment(2);
$active[$active_menu][0] = "active";
$active[$active_menu][1] = "#4287f5";
 ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SuhaAcademy|SMS-MRKTNG-APP</title>
  <!-- Bootstrap core CSS -->
  <?= link_tag('Assets/vendor/bootstrap/css/bootstrap.min.css') ?>

  <!-- Custom styles for this template -->
  <?= link_tag('Assets/css/simple-sidebar.css') ?>
  <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> -->

  <!--DateTime-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
  <?= link_tag('Assets/build/css/bootstrap-datetimepicker.min.css') ?>
  <!--DateTime-->
</head>

<body>
  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper" >
      <div class="sidebar-heading"> <a href="http://suhaacademy.com/"><img src="<?= base_url('Assets/img/logo.jpeg') ?>" alt="company-logo" width='200px'></a> </div>
      <div class="list-group list-group-flush">
        <a href="<?= base_url('admin/welcome') ?>" class="list-group-item list-group-item-action bg-light <?=$active['welcome'][0]?>" style="color:<?=$active['welcome'][1]?>">
          <svg class="bi bi-house-fill" width="1em" height="1em" viewBox="0 3 17 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
            <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
          </svg>Home</a>
        <a href="<?= base_url("admin/viewSMStemplate") ?>" class="list-group-item list-group-item-action bg-light <?=$active['viewSMStemplate'][0]?>" style="color:<?=$active['viewSMStemplate'][1]?>">
          <svg class="bi bi-chat-dots-fill" width="1em" height="1em" viewBox="0 3 17 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
          </svg>SMS Template</a>
        <a href="<?= base_url("admin/viewSmsCampaign") ?>" class="list-group-item list-group-item-action bg-light <?=$active["viewSmsCampaign"][0]?>" style="color:<?=$active['viewSmsCampaign'][1]?>">
          <svg class="bi bi-people-fill" width="1em" height="1em" viewBox="0 3 17 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
</svg>Groups</a>
        <a href="<?= base_url("admin/viewSendSMS") ?>" class="list-group-item list-group-item-action bg-light <?=$active['viewSendSMS'][0]?>" style="color:<?=$active['viewSendSMS'][1]?>">
          <svg class="bi bi-envelope" width="1em" height="1em" viewBox="0 0 17 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M14 3H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2z"/>
  <path d="M.05 3.555C.017 3.698 0 3.847 0 4v.697l5.803 3.546L0 11.801V12c0 .306.069.596.192.856l6.57-4.027L8 9.586l1.239-.757 6.57 4.027c.122-.26.191-.55.191-.856v-.2l-5.803-3.557L16 4.697V4c0-.153-.017-.302-.05-.445L8 8.414.05 3.555z"/>
</svg>Send SMS</a>
        <a href="<?= base_url("admin/viewReports")?>" class="list-group-item list-group-item-action bg-light <?=$active['viewReports'][0]?>" style="color:<?=$active['viewReports'][1]?>">
          <svg class="bi bi-file-ruled" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z"/>
  <path fill-rule="evenodd" d="M13 6H3V5h10v1zm0 3H3V8h10v1zm0 3H3v-1h10v1z"/>
  <path fill-rule="evenodd" d="M5 14V6h1v8H5z"/>
</svg>Instsant Report</a>
<a href="<?= base_url("admin/viewScheduleReports")?>" class="list-group-item list-group-item-action bg-light <?=$active['viewScheduleReports'][0]?>" style="color:<?=$active['viewScheduleReports'][1]?>">
  <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clock-history" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
    <path fill-rule="evenodd" d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
    <path fill-rule="evenodd" d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
  </svg>Detailed Report</a>
<a href="#"></a>
        <!-- <a href="#" class="list-group-item list-group-item-action bg-light">Events</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Profile</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Status</a> -->
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" style="border-left:1px solid #4287f5;">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-warning" id="menu-toggle">
          <svg class="bi bi-list-ul" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
          </svg>
        </button>
        <button type="button" class="btn btn-primary" id="get_sms_bal" style="margin-left:5px">Transactional SMS: <span style="font-weight: bold; color:yellow"><span id="sms_bal">⏳</span></span></span> </button>
        <!-- <button type="button" class="btn btn-warning" style="margin-left:5px">Promotional SMS: <span style="font-weight: bold; color:blue"><?=$dt['promo'] ?></span> </button>
        <button type="button" class="btn btn-success" style="margin-left:5px">SMS Sent: <span style="font-weight: bold; color:yellow">1200</span> </button> -->


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item">
              <a class="btn btn-danger" href="<?php echo base_url("Admin/logout"); ?>" style="color:white">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-power" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M5.578 4.437a5 5 0 1 0 4.922.044l.5-.866a6 6 0 1 1-5.908-.053l.486.875z"/>
              <path fill-rule="evenodd" d="M7.5 8V1h1v7h-1z"/>
            </svg>
              </a>
            </li>
          </ul>
        </div>
      </nav>
