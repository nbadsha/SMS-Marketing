<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class SmsAPIModel extends CI_model
{
  private $user = "suhaacademy";
  private $password = "817764";
  private $apikey = "uJozbeyu5Eyp7LwsuMLeyA";
//   private $apikey = "6UrpiHHRl0aCvJp1g5kFVQ";
  private $senderId = "SKYUSF";
  private $route = "11";

  public function connectAPI($url,$params)
  {
    $ch = curl_init();
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Follow redirects
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Set maximum redirects
    curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    // Allow a max of 5 seconds.
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // set url
    if( count($params) > 0 ) {
      $query = http_build_query($params);
      curl_setopt($ch, CURLOPT_URL, "$url?$query");
    } else {
      curl_setopt($ch, CURLOPT_URL, $url);
    }
    // echo "$url?$query".'<br>';

    // $output contains the output string
    $output = curl_exec($ch);

    // Check for errors and such.
    $info = curl_getinfo($ch);
    $errno = curl_errno($ch);
    if( $output === false || $errno != 0 ) {
      // Do error checking
      // echo 'Error No='. $errno;
      return False;
    } else if($info['http_code'] != 200) {
      // Got a non-200 error code.
      // Do more error checking
      return False;
      echo "<pre>";
      print_r($info);
    }
    else{
      // close curl resource to free up system resources
      if($output[0]=="{"){
        $main = json_decode($output);
        $array2 = json_decode(json_encode($main), True);
        curl_close($ch);
        return $array2;
      }
      else {
        return $output;
      }
    }
  }

  public function resolveDT($str)
  {
    //schedtime=2015/12/31 22:35:00 PM  (format)
    $dt = new DateTime($str);
    return  $dt->format('Y/m/d h:i:s A');
  }

  public function createOTP(){
      $otp = substr(mt_rand(),0,6);
      $this->session->set_userdata('otp',$otp);
      return $otp;
  }

  public function sendOTP(){
      $otp = $this->createOTP();
      $msg = "$otp is the OTP for resetting the password. SuhaAcademy-SMSAPP";
      $url = "https://www.smsgatewayhub.com/api/mt/SendSMS";
        $params = array(
          "apikey"=>$this->apikey,
          "senderid"=>$this->senderId,
          "channel"=>"trans",
          "DCS"=>"0",
          "flashsms"=>"0",
          "number"=>'917980102938',
          // "number"=>'917679262095',
          "text"=>$msg,
          "route" => $this->route,
        );
        $data = $this->connectAPI($url,$params);
         if ($data['ErrorCode']==000) {
             return True;
         }
         return False;
  }

  public function resolveNumber($str)
  {
    $tp1 =  preg_match("/^[6-9][0-9]{9}$/" , $str);//accept 8197603546
    $tp2 =  preg_match("/^[+][1-9][0-9][0-9]{10}$/" , $str);//accept +918197603546
    // echo "<br>$str<br>".$tp1."<br>".$tp2."<br>";
    if ($tp2) {
      return substr($str,1);
    }
    elseif ($tp1) {
      return '91'.$str;
    }
    else{
      return False;
    }
  }

  public function checkBalance()
  {
    $url = "https://www.smsgatewayhub.com/api/mt/GetBalance";
    $params = ['APIKey'=>$this->apikey];
    $data = $this->connectAPI($url,$params);
    $balance = $data['Balance'];
    if ($balance) {
      $promo = (explode(":",explode("|",$balance)[0])[1]);
      $trans = (explode(":",explode("|",$balance)[1])[1]);
      $new_data = array('promo'=>$promo, 'trans'=>$trans);
      return $new_data;
    }
    else{
      return array('promo'=>'ERROR','trans'=>'ERROR');
    }
    //response
    //     Array
    // (
    //     [ErrorCode] => 0
    //     [ErrorMessage] => Done
    //     [Balance] => Promo:0|Trans:23
    // )

  }

  public function sendSMS($array)
  {
    $url = "https://www.smsgatewayhub.com/api/mt/SendSMS";
    $params = array(
      "apikey"=>$this->apikey,
      "senderid"=>$this->senderId,
      "channel"=>"trans",
      "DCS"=>"0",
      "flashsms"=>"0",
      "number"=>$array['mobile_numbers'],
      "text"=>$array['message'],
      "route" => $this->route,
    );
    $data = $this->connectAPI($url,$params);
    if ($data['ErrorCode']==000) {
      $dt1 = new DateTime();
      $dt1->setTimezone(new DateTimeZone('Asia/Kolkata'));
      $dt = $dt1->format('Y-m-d G:i:s');
      $messageData = $data;
      $messageData['timestamp'] = $dt;
      $messageData['message'] = $array['message'];
      $messageData['schedule_time'] = "";
      $this->putJobRecord($messageData);
    }
    // echo "<pre>";
    // print_r($data);
    return $data;
    // print_r($data);
    // Array
    // (
    //     [ErrorCode] => 000
    //     [ErrorMessage] => Done
    //     [JobId] => 4818638
    //     [MessageData] => Array
    //         (
    //             [0] => Array
    //                 (
    //                     [Number] => 917679262095
    //                     [MessageId] => WCdCioaS3UyAfEsIdL7iIA
    //                 )
    //
    //         )
    //
    // )
  }

  public function putJobRecord($messageData)
  {
    $data = array(
      'error_code'    => $messageData['ErrorCode'],
      'error_message' => $messageData['ErrorMessage'],
      'job_id'        => $messageData['JobId'],
      'message'       => $messageData['message'],
      'timestamp'     => $messageData['timestamp'],
      'schedule_time' => $messageData['schedule_time']
    );
    $this->db->insert('sms_records',$data);
  }

  public function scheduleSMS($array)
  {
    $url = "https://www.smsgatewayhub.com/api/mt/SendSMS";
    $params = array(
      "apikey"    =>  $this->apikey,
      "senderid"  =>  $this->senderId,
      "channel"   =>  "trans",
      "DCS"       =>  "0",
      "flashsms"  =>  "0",
      "number"    =>  $array['mobile_numbers'],
      "text"      =>  $array['message'],
      "schedtime" =>  $array['cs_date'],
      "route" => $this->route,
    );
    $data = $this->connectAPI($url,$params);
    // echo "<pre>";
    // print_r($data);
    if ($data['ErrorCode']==000) {
      $dt1 = new DateTime();
      $dt1->setTimezone(new DateTimeZone('Asia/Kolkata'));
      $dt = $dt1->format('Y-m-d G:i:s');
      $messageData = $data;
      $messageData['timestamp'] = $dt;
      $messageData['message'] = $array['message'];
      $messageData['schedule_time'] = $array['cs_date'];
      $this->putJobRecord($messageData);
    }
    return $data;
  }

  public function checkDelivery($job_id)
  {
    $url = "https://www.smsgatewayhub.com/api/mt/GetDelivery";
    $params = ['APIKey'=>$this->apikey,'jobid'=>$job_id];
    $data = $this->connectAPI($url,$params);
    return($data);
  }

  public function getDateWiseReport($from_date,$to_date)
  {
    $url = "https://www.smsgatewayhub.com/smsapi/mis.aspx";
    $params = [
      'user'=>$this->user,
      'password'=>$this->password,
      'todate'=>$to_date,
      'fromdate'=>$from_date
    ];
    $output = $this->connectAPI($url,$params);
    $delimiter = ",";
  $enclosure = '"';
  $escape = "\n" ;
  $rows = array_filter(explode("\r\n", $output));
  $header = NULL;
  $data = [];

  foreach($rows as $row)
  {
    $row = str_getcsv ($row, $delimiter, $enclosure , $escape);

    if(!$header) {
      $header = $row;
    } else {
      $data[] =  $row;
    }
  }
  // echo "<pre>";
  //   print_r($data);
    if(count($data)){
        foreach ($data as $key => $val) {
      if(sizeof($val)==8){
          $time[$key] = $val[5];
      }

    }
    array_multisort($time, SORT_DESC, $data);
    // print_r($data);
    return($data);
   }
  return([]);
  }

  public function getexp()
  {
    $url = "https://www.smsgatewayhub.com/smsapi/mis.aspx";
    $params = [
      'user'=>$this->user,
      'password'=>$this->password,
      'todate'=>"08/08/2020",
      'fromdate'=>"08/08/2020"
    ];
    $data = $this->connectAPI($url,$params);
    // $data = trim(preg_replace('/\s+/', ' ', $data));
    return $data;
  }

  public function getChartData($array){
      $newarr = array();
      foreach($array as $value){
          $newarr[] = $value[4];
      }
      $newarr = (array_count_values($newarr));
      return($newarr);
  }


}


?>
