<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	* Index Page for this controller.
	*
	* Maps to the following URL
	* 		http://example.com/index.php/welcome
	*	- or -
	* 		http://example.com/index.php/welcome/index
	*	- or -
	* Since this controller is set as the default controller in
	* config/routes.php, it's displayed at http://example.com/
	*
	* So any other public methods not prefixed with an underscore will
	* map to /index.php/welcome/<method_name>
	* @see https://codeigniter.com/user_guide/general/urls.html
	*/


	public function getSmsBal(){
	        $sms_bal = $this->SmsAPIModel->checkBalance();
	        echo (explode('.',$sms_bal['trans'])[0]);
	}

	public function isLoggedIn()
	{
		if ($this->session->userdata('smsapp_admin_id')) {
			return True;
		}
		else{
			return False;
		}
	}

	public function index()
	{
		if (!$this->isLoggedIn()) {
			$this->load->view('Admin/Login');
		}
		else{
			return redirect('Admin/welcome');
		}
	}

	public function doLogin()
	{
		if ($this->input->post('adminLogin')) {
			if ($this->form_validation->run('login_user')) {
				if ($this->smsAppModel->authUser($this->input->post())) {
					return redirect("admin/welcome");
				}
				else {
					$this->session->set_flashdata('Login_failed','Invalid Username/Password');
					$this->load->view('Admin/Login');
				}
			}
			else{
				$this->load->view('Admin/Login');
			}
		}
		elseif ($this->isLoggedIn()) {
			return redirect('admin/welcome');
		}
		else {
			return redirect('admin/index');
		}
	}

	public function forgot_password(){
	        if ($this->isLoggedIn()) {
			return redirect('admin/welcome');}
	        $otpcount = 1;
	        $otpcount = $this->session->userdata("otp_count");
	        $this->session->set_userdata("otp_count",$otpcount+1);
	        if($this->input->post("resetPassword")){
	            $array = $this->input->post();
	            $sent_otp = $this->session->userdata("otp");
	            $entered_otp = $array["otp"];
	            $new_password = $array["new_password"];
	            if($sent_otp==$entered_otp){
	                if($this->smsAppModel->update_password($new_password)){
	                    $this->session->set_flashdata('msg','Password Reset Successful.');
				        			$this->session->set_flashdata('msg_class','success');
	                    return redirect('Admin/index');
	                }
	            }
	            else{
	              $this->session->set_flashdata('msg','Incorrect OTP');
				        $this->session->set_flashdata('msg_class','danger');
	            }
	           // print_r($array);
	        }
	        else{
	            if($otpcount<3){
	            if($this->SmsAPIModel->sendOTP()){
	            $this->session->set_flashdata('msg','OTP sent to your phone.📲');
				$this->session->set_flashdata('msg_class','success');
	        }
	        else{
	            $this->session->set_flashdata('msg','OTP could not be sent');
				$this->session->set_flashdata('msg_class','danger');
	        }
	        }
	        else{
	            $this->session->set_flashdata('msg','OTP limit exceeded.');
				$this->session->set_flashdata('msg_class','danger');
	        }
	        }

		    $this->load->view('Admin/update_password');
	}

	public function welcome()
	{
		if (!$this->isLoggedIn()) {
			return redirect('admin/index');
		}
		else {
			$num_of_contacts = $this->smsAppModel->getNumContacts();
			$this->load->library('pagination');
			$config = array(
								'base_url' 			=> base_url('admin/welcome'),
								'per_page' 			=> 10,
								'total_rows' 		=> $num_of_contacts,
								'full_tag_open'	=>'<nav><ul class="pagination">',
								'full_tag_close'=>'</ul></nav>',
								'attributes'  => array('class' => 'page-link'),
								'first_link'  => 'First',
								'last_link'  => 'Last',
								
								'num_tag_open'	=>'<li class="page-item">',
								'num_tag_close'	=>'</li>',
								'cur_tag_open' =>'<li class="page-item active"><a class="page-link" href="#">',
								'cur_tag_close' => '<span class="sr-only">(current)</span></a></li>'
			);
			$this->pagination->initialize($config);
			$data['contacts'] = $this->smsAppModel->getContacts($config['per_page'],$this->uri->segment(3));
			$this->load->view('Admin/dashboard/index',$data);
		}

	}
	//-------------------------------
//sms template CRUD functions start
	public function viewSMStemplate()
	{
		if ($this->isLoggedIn()) {

			$data['sms']=$this->smsAppModel->getSmsTemplate();
			$this->load->view('Admin/dashboard/sms_template',$data);
		}
		else{
			return redirect('admin/index');
		}
	}

	public function addSmsTemplate()
	{
		if ($this->input->post('addSmsTemplate')) {
			if ($this->form_validation->run('sms_template')) {
					if ($this->smsAppModel->addSmsTemplate($this->input->post())) {
						 $this->session->set_flashdata('msg','Templated Added');
						 return redirect('admin/viewSMStemplate');
					}
			}
			else{
					$this->load->view('Admin/dashboard/sms_template');
			}
		}
		elseif ($this->input->post('updateSMSTemplate')) {
			if ($this->smsAppModel->updateSMSTemplate($this->input->post())) {
				$this->session->set_flashdata('msg','Templated Updated');
				return redirect('admin/viewSMStemplate');
			}
		}
		else {
			return redirect('admin/viewSMStemplate');
		}
	}

	public function editSmsTemplate()
	{
		if ($this->input->post('editSmsTemplateID')) {
			$this->session->set_flashdata('editSmsTemplateID',$this->input->post('editSmsTemplateID'));

		}

			return redirect('admin/viewSMStemplate');

	}

	public function deleteSmsTemplate()
	{
		if ($id = $this->input->post('deleteSmsTemplateID')) {
				if ($this->smsAppModel->deleteSmsTemplate($id)) {
					$this->session->set_flashdata('msg','Templated Deleted');
					$this->session->set_flashdata('msg_class','danger');
				}
		}
			return redirect('admin/viewSMStemplate');

	}

	//sms template CRUD functions end
//-------------------------------
	//contact CRUD functions start

	public function uploadCSV()
	{
		if ($this->input->post('uploadCSV')) {
							 $config['upload_path']          = './Assets/CSV/';
							 $config['allowed_types']        = 'csv';
							 $this->load->library('upload', $config);
							 if ( ! $this->upload->do_upload('csvFile'))
							 {
											 	$error = array('error' => $this->upload->display_errors());
												$this->session->set_flashdata('msg',$error['error']);
												$this->session->set_flashdata('msg_class','danger');
							 }
							 else
							 {
											 $data = array('upload_data' => $this->upload->data());
											 $allData = $this->smsAppModel->readCSV($data);
											 $uniqueData = $this->smsAppModel->prepareData($allData);
											 if (!empty($uniqueData)) {
											 		if ($this->smsAppModel->uploadContact($uniqueData)) {
											 			$this->session->set_flashdata('msg','Unique Contacts Added.');
											 			$this->session->set_flashdata('msg_class','success');
											 		}
													else {
														$this->session->set_flashdata('msg','Contacts could not be added.');
											 			$this->session->set_flashdata('msg_class','danger');
													}
											 }
											 else{
												 $this->session->set_flashdata('msg','These contacts are already added.');
												 $this->session->set_flashdata('msg_class','danger');
											 }
							 }
		}
		return redirect('admin/welcome');
	}

	public function uploadCSVtoGroup()
	{
		if ($this->input->post('uploadCSV')) {
							 $config['upload_path']          = './Assets/CSV/';
							 $config['allowed_types']        = 'csv';
							 $this->load->library('upload', $config);
							 if ( ! $this->upload->do_upload('csvFile'))
							 {
											 	$error = array('error' => $this->upload->display_errors());
												$this->session->set_flashdata('msg',$error['error']);
												$this->session->set_flashdata('msg_class','danger');
							 }
							 else
							 {
											 $data = array('upload_data' => $this->upload->data());
											 $allData = $this->smsAppModel->readCSV($data);
											 $contactIDs = $this->smsAppModel->prepareDataforGroup($allData);
											 $campaign_id = $this->input->post('uploadCSV');
											 // echo $campaign_id;
											 // echo "<pre>";
											 // print_r($contactIDs);
											 $id = $campaign_id;
									 		$assn_id = $contactIDs;
									 		// echo "<pre>";
									 		// print_r($this->input->post());
									 		$data = array();
									 		foreach($assn_id as $value){
									 			$data[]=array('mcl_id'=>$value->id,'c_list_id'=>$id);
									 		}
									 		// print_r($data);
									 		if (count($data)) {
													if ($this->smsAppModel->assignContacts($data)) {
														$this->session->set_flashdata('msg','Unique contacts uploaded and assigned.');
														$this->session->set_flashdata('msg_class','success');
													}
													else{
														$this->session->set_flashdata('msg','These contacts are already been assigned.');
														$this->session->set_flashdata('msg_class','danger');
													}

									 		}

							 }
		}
		return redirect('admin/contactEnrollment');
	}

	public function viewEditContact()
	{
		if ($id = $this->input->post('editContact')) {
				$data = $this->smsAppModel->getOneContact($id);
				$this->session->set_flashdata('editContactData',$data);
		}
		return redirect('admin/welcome');
	}
	public function deleteAllContact()
	{
		if ($this->input->post('deleteAllContact')) {
			$this->smsAppModel->deleteAllContact();
			$this->session->set_flashdata('msg','All contacts Deleted.');
			$this->session->set_flashdata('msg_class','warning');
			return redirect('admin/welcome');
		}
	}

	public function modifyOneContact()
	{
		if ($this->input->post('addOneContact')) {
			if ($this->form_validation->run('contact')) {
				if ($this->smsAppModel->addOneContact($this->input->post())) {
					$this->session->set_flashdata('msg','Contact Added.');
					$this->session->set_flashdata('msg_class','success');
				}
				else {
					$this->session->set_flashdata('msg','Contact Could Not be Added.');
					$this->session->set_flashdata('msg_class','danger');
				}
			}
			else{
				$this->session->set_flashdata('msg','Enter correct input.');
				$this->session->set_flashdata('msg_class','danger');
			}
		}
		elseif ($this->input->post('updateOneContact')) {
				if ($this->form_validation->run('contact')) {
					if ($this->smsAppModel->updateOneContact($this->input->post())) {
						$this->session->set_flashdata('msg','Contact Updated Successfully.');
						$this->session->set_flashdata('msg_class','success');
					}
				}
				else{
					$this->session->set_flashdata('msg','Enter correct input.');
					$this->session->set_flashdata('msg_class','danger');
				}

		}
		return redirect('Admin/welcome');
	}

	public function deleteContact()
	{
		if ($this->smsAppModel->deleteContact($this->input->post('deleteContactID'))) {
			$this->session->set_flashdata('msg','Contact deleted.');
			$this->session->set_flashdata('msg_class','danger');
			return redirect('Admin/welcome');
		}
	}
	//contact CRUD functions end
//-------------------------------
	//sms campaign functions start

	public function viewSmsCampaign()
	{
		if ($this->isLoggedIn()) {
			// echo "<pre>";
			// print_r($data);
			if ($this->input->post('launchCampaign')) {
				if ($this->smsAppModel->launchCampaign($this->input->post())) {
					$this->session->set_flashdata('msg','Campaign Launched.');
					$this->session->set_flashdata('msg_class','success');
				}
			}
			elseif ($this->input->post('editCamp')) {
				if ($this->smsAppModel->updateCampaign($this->input->post())) {
					$this->session->set_flashdata('msg','Campaign updated.');
					$this->session->set_flashdata('msg_class','success');
				}
			}

		}
		else{
				return redirect('admin/index');
		}
		$data['template'] = $this->smsAppModel->getUnAssnTemplate();
		$data['campaign'] = $this->smsAppModel->getCampaign();
		$this->load->view('Admin/dashboard/smsCampaign',$data);
	}

	public function editCampaign()
	{
		if ($id = $this->input->post('editCampaignID')) {
			$this->session->set_flashdata('editCampaignData',$this->smsAppModel->editCampaign($id));
			$this->session->set_flashdata('msg','Editing group data...');
			$this->session->set_flashdata('msg_class','warning');
		}
		return redirect('admin/viewSmsCampaign');
	}

	public function deleteCampaign()
	{
		if ($this->smsAppModel->deleteCampaign($this->input->post('deleteCampaignID'))) {
			$this->session->set_flashdata('msg','Campaign deleted');
			$this->session->set_flashdata('msg_class','danger');
		}
		return redirect('admin/viewSmsCampaign');
	}

	//sms campaign functions end

//contactEnrollment functions
public function contactEnrollment()
{
	if ($this->input->post('cntEnroll')) {
		$this->session->set_userdata('cntEnroll',$this->input->post('cntEnroll'));
	}
	if ($this->isLoggedIn() && $this->session->userdata('cntEnroll')) {
		echo $this->db->last_query();
			$data['campaign_name'] = explode("/",$this->session->userdata('cntEnroll'))[1];
			$c_id = explode("/",$this->session->userdata('cntEnroll'))[0];
			$data['campaign_id'] = $c_id;
			$data['remain_contacts'] = $this->smsAppModel->getUnAssnNum($c_id );
			$data['unAssContacts'] = $this->smsAppModel->getUnassignedContacts($c_id );
			$data['AssContacts'] = $this->smsAppModel->getAssignedContacts($c_id );
			// echo "<pre>";
			// print_r($data);
			$this->load->view('Admin/dashboard/contactEnrollent',$data);
	}
	else{
		return redirect('admin/viewSmsCampaign');
	}
}
public function assignContacts()
{
	if ($this->input->post('assign_id')) {
		$id = $this->input->post('assignContact');
		$assn_id = $this->input->post('assign_id');
		// echo "<pre>";
		// print_r($this->input->post());
		$data = array();
		foreach($assn_id as $value){
			$data[]=array('mcl_id'=>$value,'c_list_id'=>$id);
		}
		// print_r($data);
		if ($this->smsAppModel->assignContacts($data)) {
			 return redirect('admin/contactEnrollment');
		}

	}
	else {
		return redirect('admin/contactEnrollment');
	}
}

public function deAssign()
{
	if ($this->input->post('deRoll')) {
		if ($this->smsAppModel->deAssign($this->input->post('deRoll'))) {
			return redirect('admin/contactEnrollment');
		}
	}
	else {
		return redirect('admin/contactEnrollment');
	}
}

public function deAssignAll()
{
	if ($this->input->post('deRoll')) {
		if ($this->smsAppModel->deAssignAll($this->input->post('deRoll'))) {
			return redirect('admin/contactEnrollment');
		}
	}
	else {
		return redirect('admin/contactEnrollment');
	}
}

public function viewSendSMS()
{
	if ($this->isLoggedIn()) {
		$dt1 = new DateTime();
	  $dt1->setTimezone(new DateTimeZone('Asia/Kolkata'));
	  $match = $dt1->format('Y-m-d');
		$data['campaign'] = $this->smsAppModel->getCampaign();
		$data['sms_report'] = $this->smsAppModel->get_sms_report($match);//getting sms records for the same day
		$data['contact_numbers'] = $this->smsAppModel->getContactNumbers();
		// echo "<pre>";
		// print_r($data);
		$this->load->view('Admin/dashboard/sendSMS',$data);
	}
	else{
		return redirect("Admin/index");
	}
}

public function sendCustomSMS()
{
	// echo "<pre>";
	if ($this->isLoggedIn()) {
		if ($this->input->post('sendCustomSMS')) {
				$array = $this->input->post();
				if (!isset($array["direct_csv"])) {
					$mobile = $this->SmsAPIModel->resolveNumber($array['mobile_numbers']);
					if (!$mobile) {
						$this->session->set_flashdata('msg','Wrong mobile number.');
						$this->session->set_flashdata('msg_class','danger');
					}
					$array["mobile_numbers"] = $mobile;
				}
				else{
					$config['upload_path']          = './Assets/CSV/';
					$config['allowed_types']        = 'csv';
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload('csvFile'))
					{
									 $error = array('error' => $this->upload->display_errors());
									 $this->session->set_flashdata('msg',$error['error']);
									 $this->session->set_flashdata('msg_class','danger');
									 // print_r($error);
					}
					else{
						$data = array('upload_data' => $this->upload->data());
						$data = $this->smsAppModel->readCSV($data);
						$build_numbers = "";
						foreach($data as $number){
							$build_numbers .= $this->SmsAPIModel->resolveNumber($number[1]).',';
						}
						$build_numbers = rtrim($build_numbers,", ");
						// echo $build_numbers;
						$array["mobile_numbers"] = $build_numbers;
					}
				}


				// print_r($array);

				if ($this->input->post('when') == 'schedule') {
					$datime = $this->SmsAPIModel->resolveDT($this->input->post('cs_date'));
					$array['cs_date'] = $datime;
					$respones = $this->SmsAPIModel->scheduleSMS($array);
					if ($respones['ErrorCode']==000) {
						$this->session->set_flashdata('msg','Message scheduled successfully.');
						$this->session->set_flashdata('msg_class','success');
					}
					else {
						$this->session->set_flashdata('msg',$respones['ErrorMessage']);
						$this->session->set_flashdata('msg_class','danger');
					}
				}
				else{
					$respones = $this->SmsAPIModel->sendSMS($array);
					if($respones['ErrorCode']==000){
						$this->session->set_flashdata('msg','Message sent.');
						$this->session->set_flashdata('msg_class','success');
					}
					else{
						$this->session->set_flashdata('msg',$respones['ErrorMessage']);
						$this->session->set_flashdata('msg_class','danger');
					}
				}

		}
	}
	return redirect('Admin/viewSendSMS');
}

public function sendGroupSMS()
{
	if ($this->isLoggedIn()) {
		if ($this->input->post('sendGroupSMS')) {
				$array = $this->input->post();
				// echo "<pre>";
				// print_r($array);
				$msg = "";
				$camp_id =  explode(".",$array['camp_id_sms_id'])[0];
				$sms_id = explode(".",$array['camp_id_sms_id'])[1];
				$numbers = $this->smsAppModel->getGroupNumbers($camp_id);
				if (isset($array["editedSMS"])) {
					$msg =$array['message'];
				}
				else{
					$msg =$this->smsAppModel->getSmsBody($sms_id);
				}
				$build_numbers = "";
				foreach($numbers as $number){
					$build_numbers .= $this->SmsAPIModel->resolveNumber($number->mobile).',';
				}
				$build_numbers = rtrim($build_numbers,", ");
				$new_array = array('message'=>$msg,'mobile_numbers' => $build_numbers);
				if ($this->input->post('when') == 'schedule') {
					$datime = $this->SmsAPIModel->resolveDT($this->input->post('cs_date'));
					$new_array['cs_date'] = $datime;
					$respones = $this->SmsAPIModel->scheduleSMS($new_array);
					if ($respones['ErrorCode']==000) {
						$this->session->set_flashdata('msg','Message scheduled successfully.');
						$this->session->set_flashdata('msg_class','success');
					}
					else {
						$this->session->set_flashdata('msg',$respones['ErrorMessage']);
						$this->session->set_flashdata('msg_class','danger');
					}
				}
				else{
					$respones = $this->SmsAPIModel->sendSMS($new_array);
					if($respones['ErrorCode']==000){
						$this->session->set_flashdata('msg','Message sent.');
						$this->session->set_flashdata('msg_class','success');
					}
					else{
						$this->session->set_flashdata('msg',$respones['ErrorMessage']);
						$this->session->set_flashdata('msg_class','danger');
					}
				}
		}
	}
		return redirect('Admin/viewSendSMS');
}

public function viewReports()
{
	if ($this->isLoggedIn()) {
		$match = ""		;
		if ($this->input->post('showReport')) {
				$match = $this->input->post('date');
				// print_r($this->input->post());
		}
		$data['sms_report'] = $this->smsAppModel->get_sms_report($match);//getting sms records for the same day
		$data['dates'] = $this->smsAppModel->getDates();
		$this->load->view('Admin/dashboard/sms_report',$data);
	}
	else{
		return redirect("Admin/index");
	}
}

public function showJob()
{
	$job_id = $this->input->get('id');
	// echo "<pre>";
	// echo json_encode($this->SmsAPIModel->checkDelivery($job_id));
	$msgData = $this->SmsAPIModel->checkDelivery($job_id);
	$msgText = $this->smsAppModel->getMessageText($job_id);
	$msgData['message'] = $msgText->message;
	echo json_encode($msgData);
}

public function viewScheduleReports()
{
	$detailedReport = array();
	if ($this->isLoggedIn()) {
		if ($this->input->post('ShowSchd')) {
			$array = $this->input->post();
			// print_r($array);
			$date = $array['shDate'];
			$date = date_create($date);
			$date = $date->format('m/d/Y');
			$from_date = "";
			$to_date = "";
			$days = $array["last_days"];
			if ($days==1) {
				$from_date = $date;
				$to_date = $date;
			}
			else{
				// echo $date."<br>";
				$date1 = strtotime($date);
				$date1 = strtotime("-".$days." day", $date1);
				$d =  date('m/d/Y', $date1);
				$from_date = $d;
				$to_date = $date;
			}
			// echo "$from_date|$to_date";
			$detailedReport = $this->SmsAPIModel->getDateWiseReport($from_date,$to_date);//accepts to_date and from_date
			$from_date = date_create($from_date);
			$to_date = date_create($to_date);
			$this->session->set_flashdata("msg","Showing Reports from ".$from_date->format("d-M-Y")." to ".$to_date->format("d-M-Y").".");
			$this->session->set_flashdata("msg_class","success");
		}
		$data['detailedReport'] = $detailedReport;
// 		echo "<pre>";
// 		print_r($detailedReport);
		if(isset($detailedReport)){
		    $data['chartData'] = $this->SmsAPIModel->getChartData($detailedReport);
		  //  print_r($data['chartData']);
		}

		$data['sm_date'] = $this->smsAppModel->getSchDate();

		$this->load->view('Admin/dashboard/scheduledReport',$data);
	}
}

public function expfun()
{
	echo $this->SmsAPIModel->getexp();
}
	public function logout()
	{
		// $this->session->unset_userdata('smsapp_admin_id');
		session_destroy();
		return redirect ('admin/index');
	}

}
