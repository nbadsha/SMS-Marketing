<?php

/**
 *
 */
class SmsAppModel extends CI_model
{


  public function authUser($array)
  {
      $uname = $array['uname'];
      $password = $array['pwd'];
      $r = $this->db->query("SELECT COUNT(*) AS usernumber FROM users WHERE username='$uname' AND password='$password'");
    //   print_r();
    if ($r->row()->usernumber) {
      $this->session->set_userdata('smsapp_admin_id',1);
      return True;
    }
    else {
      return False;
    }
  }

  public function update_password($new_password){
      $r = $this->db->where('id',1)
                    ->update('users',['password'=>$new_password]);
        if($r){
            $this->session->set_userdata('smsapp_admin_id',1);
            return True;
        }
        return False;
  }

  public function addSmsTemplate($array)
  {
    $data = array(
            'title'=> $array['sms_title'],
            'body'=> $array['sms_body'],
    );
    return $this->db->insert('sms_template',$data);
  }

  public function getSmsTemplate()
  {
    $q = $this->db->select()
                  ->order_by('id','asc')
                  ->get('sms_template');
    return $q->result();
  }

  public function getSmsBody($id)
  {
    $r = $this->db->select('body')
                  ->where('id',$id)
                  ->get('sms_template');
    return $r->row()->body;
  }

  public function getUnAssnTemplate()
  {
    // $q = $this->db->query("SELECT * FROM `sms_template` WHERE sms_template.id NOT IN (SELECT campaign_list.template_id FROM campaign_list)");
    $q = $this->db->query("SELECT * FROM `sms_template` ");
    return $q->result();
  }

  public function updateSMSTemplate($array)
  {
    $data = array(
                  'title'=> $array['sms_title'],
                  'body'=>$array['sms_body'],
                  );
    $q = $this->db->where(['id'=>$array['updateSMSid']])
                  ->update('sms_template',$data);
          return $q;
  }

  public function deleteSmsTemplate($id)
  {
    $this->db->where("id",$id);
    $this->db->delete("sms_template");
    return True;
  }

  public function resolveName($name)
  {
     $name = preg_replace('/[0-9]+/', '', $name);
     $name = ucwords(strtolower(preg_replace('/[^A-Za-z0-9\-]/', ' ', $name))); // Removes special chars.
     // $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
     // echo $name."<br>";
     return $this->removeMulSpaces($name);
  }

  public function removeMulSpaces($name){

      if(strlen(preg_replace('/\S/', '', $name))>3){
            return preg_replace("/\s+/", "", $name);
        }
        else{
            return $name;
        }
  }

  public function doesExist($table,$field,$value)
  {
    $r = $this->db->query("SELECT COUNT(*) AS num_rows FROM $table WHERE $field = $value");
    return $r->row()->num_rows;
  }

  public function resolveMobileNumber($str){
      $str = preg_replace("/\s+/", "", $str);
      $result = null;
     $tp1 =  preg_match("/^[6-9][0-9]{9}$/" , $str);//accept 8197603546
    $tp2 =  preg_match("/^[+][1-9][0-9][0-9]{10}$/" , $str);//accept +918197603546
    // echo "<br>$str<br>".$tp1."<br>".$tp2."<br>";
    if ($tp2) {
      $result= substr($str,1);
    }
    elseif ($tp1) {
      $result= '91'.$str;
    }
    else{
      $result= False;
    }
    if(!$result==null){
        $result = "+".$result;
    }
    // echo $result."<br>";
    return $result;
  }

  public function readCSV($data)
  {
    $allData = array();

    $row = 0;

    //reading csv file line by line
    if (($handle = fopen($data['upload_data']['full_path'], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $allData[$row] = $data;
            $row++;
        }
        fclose($handle);
    }
    //getting unique data only for inserting as
    //batch into the database
    // return $this->prepareData($allData);
    return $allData;
  }


public function prepareData($allData)
{
  $uniqueData = array();
  $uniqueRow = 0;
    if (!empty($allData)) {
      foreach ($allData as $value) {
          $resolved_mobile = null;
          $resolved_mobile = $this->resolveMobileNumber($value[1]);
          $resolved_name = $this->resolveName($value[0]);
          if(!$resolved_mobile==null && !$resolved_name==""){
              if (!$this->doesExist('main_contact_list','mobile',$resolved_mobile) ) {
              $uniqueData[$uniqueRow]=['name'=>$resolved_name,'mobile'=>$resolved_mobile];
            }
          }
        $uniqueRow++;
      }
     $uniqueData = array_map("unserialize", array_unique(array_map("serialize", $uniqueData)));
     return $uniqueData;
  }
}

public function prepareDataforGroup($allData)
{
  $uniqueData = array();
  $uniqueRow = 0;
  $allResolvedData = array();
    if (!empty($allData)) {
      foreach ($allData as $value) {
          $resolved_mobile = null;
          $resolved_mobile = $this->resolveMobileNumber($value[1]);
          $resolved_name = $this->resolveName($value[0]);

          if(!$resolved_mobile==null && !$resolved_name==""){
              $allResolvedData[] = ['name'=>$resolved_name,'mobile'=>$resolved_mobile];
              if (!$this->doesExist('main_contact_list','mobile',$resolved_mobile)) {
              $uniqueData[$uniqueRow]=['name'=>$resolved_name,'mobile'=>$resolved_mobile];
            }
          }
        $uniqueRow++;
      }
     $uniqueData = array_map("unserialize", array_unique(array_map("serialize", $uniqueData)));
     if (count($uniqueData)) {
       $this->uploadContact($uniqueData);
     }
     // echo "<pre>";
     // print_r($allResolvedData);
     return $this->getContactIDs($allResolvedData);
  }
}

public function getContactIDs($allResolvedData)
{
  // echo "<pre>";
  // print_r($allResolvedData);
  $str = "SELECT main_contact_list.id FROM main_contact_list WHERE ";
  $extra = "";
  foreach ($allResolvedData as $key=> $value) {
    // print_r($value);
    $mobile = $value["mobile"];
    if ($key==0) {
      $extra .= ' main_contact_list.mobile LIKE "'.$mobile.'" ';
    }
    else{
      $extra .= ' OR main_contact_list.mobile LIKE "'.$mobile.'" ';
    }
  }
  $query = $str.$extra." ORDER BY id ASC";
  $r = $this->db->query($query);
  return($r->result());
  // print_r($this->db->error());
}

//SELECT main_contact_list.id FROM main_contact_list WHERE main_contact_list.mobile LIKE "+916264816850" OR main_contact_list.mobile LIKE "+919502550860" OR main_contact_list.mobile LIKE "+916203423085" ORDER BY main_contact_list.id ASC
public function uploadContact($uniqueData)
{
  if ($this->db->insert_batch('main_contact_list',$uniqueData)) {
    return True;
  }
  else {
    return False;
  }

}

public function getNumContacts()
{
  $r = $this->db->select("COUNT(*) AS numRow")
                ->get("main_contact_list");
  return $r->row()->numRow;
}

public function getContacts($limit,$offset)
{
  $q = $this->db->select()
          ->limit($limit,$offset)
          ->get('main_contact_list');
  return $q->result();
}

public function getOneContact($id)
{
  $q = $this->db->select()
          ->where('id',$id)
          ->get('main_contact_list');
  return $q->row();
}

public function addOneContact($array)
{
  if (!$this->doesExist('main_contact_list','mobile',$array['mobile'])) {
    $array['name']    = $this->resolveName($array['name']);
    $array['mobile']  = '+91'.$array['mobile'];
    $data = array(
                  'name'  => $array['name'],
                  'mobile'=> $array['mobile'],
                  'dob'   => $array['date'],
    );
    return $this->db->insert('main_contact_list',$data);
  }
  return False;
}

public function updateOneContact($array)
{

  $array['name'] = $this->resolveName($array['name']);
  if (strlen($array['mobile'])==10) {
    $array['mobile'] = '+91'.$array['mobile'];
  }
  $data = array(
                'name'  => $array['name'],
                'mobile'=> $array['mobile'],
                'dob'   => $array['date'],
  );
  $q = $this->db->where('id',$array['c_id'])
                ->update('main_contact_list',$data);
  return $q;
}

public function deleteContact($id)
{
  return $this->db->where('id',$id)
                  ->delete('main_contact_list');
}

public function deleteAllContact()
{
  return $this->db->query("DELETE FROM `main_contact_list`");
}

public function launchCampaign($array)
{
  $data = array(
              'list_name'=>$array['cmp_name'],
              'template_id'=>$array['temp_id']
  );
  return $this->db->insert('campaign_list',$data);
}

public function getCampaign()
{
  $q = $this->db->query("SELECT campaign_list.id, campaign_list.list_name,sms_template.title,sms_template.id AS sms_id,sms_template.body FROM `campaign_list` INNER JOIN `sms_template` ON `campaign_list`.`template_id` = `sms_template`.`id`");
  $data  = $q->result();
  foreach($data as $value){
    $q2 = $this->db->query("SELECT COUNT(*) AS no_of_contacts FROM campaign_enrollment WHERE `c_list_id`=$value->id");
    $value->no_of_contacts= $q2->row()->no_of_contacts;
  }
  // print_r($data);
  return $data;
}

public function getUnAssnNum($c_id)
{
  $q2 = $this->db->query("SELECT COUNT(*) AS no_of_contacts FROM campaign_enrollment WHERE `c_list_id`=$c_id");
  $num_row = $q2->row()->no_of_contacts;
  return (100-$num_row);
}



public function editCampaign($id)
{
  $q = $this->db->query("SELECT campaign_list.id, campaign_list.list_name,sms_template.id AS sms_id, sms_template.title FROM `campaign_list` INNER JOIN `sms_template` ON `campaign_list`.`template_id` = `sms_template`.`id` WHERE campaign_list.id = $id");
  return($q->row());
}

public function updateCampaign($array)
{
  $data = array('list_name'=>$array['cmp_name'],'template_id'=>$array['temp_id']);
  return $this->db->where('id',$array['editCampID'])
                  ->update('campaign_list',$data);
}

public function deleteCampaign($id)
{
  return $this->db->where('id',$id)
                  ->delete('campaign_list');
}

public function getUnassignedContacts($id)
{
  $q = $this->db->query("
            SELECT * FROM `main_contact_list`
            WHERE main_contact_list.id
            NOT IN
                  (SELECT campaign_enrollment.mcl_id
                  FROM campaign_enrollment
                  WHERE
                  campaign_enrollment.c_list_id=$id)");

  return $q->result();
}

public function getAssignedContacts($id)
{
  $q = $this->db->query("
            SELECT * FROM `main_contact_list`
            WHERE main_contact_list.id
            IN
                  (SELECT campaign_enrollment.mcl_id
                  FROM campaign_enrollment
                  WHERE
                  campaign_enrollment.c_list_id=$id)");

  return $q->result();
}

public function filterassignContactsData($data)
{
  $newData = array();
  foreach ($data as $value) {
    $mcl_id = $value['mcl_id'];
    $c_list_id = $value['c_list_id'];
    $r = $this->db->query("SELECT COUNT(*) AS num_of_rows FROM campaign_enrollment WHERE mcl_id=$mcl_id AND c_list_id=$c_list_id");
    if ($r->row()->num_of_rows==0) {
      $newData[] = $value;
    }
  }
  return $newData;
}

public function assignContacts($data)
{
    $data = $this->filterassignContactsData($data);
    // echo "<pre>";
    // print_r($data);
    if (count($data)) {
      return $this->db->insert_batch('campaign_enrollment',$data);
    }
    return False;
}

public function deAssign($str)
{
//   echo $str;
  $mcl_id = explode('/',$str)[0];
  $c_list_id = explode('/',$str)[1];
  return $this->db->where(['mcl_id'=>$mcl_id, 'c_list_id'=>$c_list_id])
           ->delete('campaign_enrollment');
}

public function deAssignAll($campaign_id)
{
  return $this->db->where('c_list_id',$campaign_id)
                  ->delete('campaign_enrollment');
}

public function getContactNumbers()
{
  $q = $this->db->select('name, mobile')
                ->get('main_contact_list');
  return($q->result());
}

public function get_sms_report($match)
{
  $q = $this->db->like('timestamp',$match,'after')
                ->order_by("id","desc")
                ->get('sms_records');
  return($q->result());
}

public function getGroupNumbers($id)
{
  $q = $this->db->query("SELECT main_contact_list.mobile FROM main_contact_list LEFT JOIN campaign_enrollment ON campaign_enrollment.mcl_id=main_contact_list.id WHERE campaign_enrollment.c_list_id = $id");
  return $q->result();
}

public function getDates()
{
  $data = array();
  $q = $this->db->select('timestamp')
                ->order_by('timestamp','desc')
                ->get('sms_records');
  if($q->result()){
    // print_r($q->result());🕓
    foreach($q->result() as $value){
      $dtm = explode(" ",$value->timestamp)[0];
      $data[] = $dtm;

    }
  }
  if (!count($data)) {
    return False;
  }
  return array_unique($data);
}

public function getSchDate()
{
  $data = array();
  $dates = $this->getDates();
  if (!$dates) {
    return False;
  }
  foreach($dates as $value){
    $q = $this->db->select('id')
                  ->where('job_id','0')
                  ->like('timestamp',$value,'after')
                  ->get('sms_records');
    $data[] = ['date'=>$value,'sm'=>$q->result_id->num_rows];
  }

  return($data);
}

public function getMessageText($job_id)
{
  $q = $this->db->select('message')
           ->where('job_id',$job_id)
           ->get('sms_records');
  return($q->row());
}


}


 ?>
