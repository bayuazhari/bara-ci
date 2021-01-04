<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\UserModel;

class Register extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new UserModel();
		
		$this->email = \Config\Services::email();
	}

	public function index()
	{
		$validation = $this->validate([
			'first_name' => ['label' => 'First Name', 'rules' => 'required'],
			'last_name' => ['label' => 'Last Name', 'rules' => 'required'],
			'user_email' => ['label' => 'Email', 'rules' => 'required|valid_email|is_unique[user.user_email]'],
			'country_calling_code' => ['label' => 'Calling Code', 'rules' => 'required|numeric'],
			'user_phone' => ['label' => 'Phone', 'rules' => 'required|numeric|is_unique[user.user_phone]'],
			'user_password' => ['label' => 'Password', 'rules' => 'required|min_length[6]|matches[user_repassword]'],
			'user_repassword' => ['label' => 'Confirm Password', 'rules' => 'required|min_length[6]'],
			'user_address' => ['label' => 'Address', 'rules' => 'required'],
			'country' => ['label' => 'Country', 'rules' => 'required'],
			'state' => ['label' => 'State', 'rules' => 'required'],
			'city' => ['label' => 'City', 'rules' => 'required'],
			'district' => ['label' => 'District', 'rules' => 'required'],
			'sub_district' => ['label' => 'Sub District', 'rules' => 'required'],
			'user_photo' => ['label' => 'Photo', 'rules' => 'permit_empty|ext_in[user_photo,png,jpg,gif]|max_size[user_photo,2048]'],
			'g-recaptcha-response' => ['label' => 'Google reCAPTCHA', 'rules' => 'required']
		]);

		if(@$this->request->getPost('country')){
			$state = $this->model->getState($this->request->getPost('country'));
		}else{
			$state = NULL;
		}
		if(@$this->request->getPost('state')){
			$city = $this->model->getCity($this->request->getPost('state'));
		}else{
			$city = NULL;
		}
		if(@$this->request->getPost('city')){
			$district = $this->model->getDistrict($this->request->getPost('city'));
		}else{
			$district = NULL;
		}
		if(@$this->request->getPost('district')){
			$sub_district = $this->model->getSubDistrict($this->request->getPost('district'));
		}else{
			$sub_district = NULL;
		}
		$data = array(
			'setting' => $this->setting,
			'web_title' => @$this->setting->getSettingById(1)->setting_value,
			'title' =>  'Register',
			'meta_desc' => @$this->setting->getSettingById(2)->setting_value,
			'nav_brand' => @$this->setting->getSettingById(5)->setting_value,
			'request' => $this->request,
			'validation' => $this->validator,
			'country_calling_code' => $this->model->getIddCode(),
			'country' => $this->model->getCountry(),
			'state' => $state,
			'city' => $city,
			'district' => $district,
			'sub_district' => $sub_district
		);
		if(!$validation){
			echo view('frontend/header', $data);
			echo view('frontend/form_register', $data);
			echo view('frontend/footer');
		}else{
			$userIp = $this->request->getIPAddress();
			$credential = array(
				'secret' => @$this->setting->getSettingById(19)->setting_value,
				'response' => $this->request->getPost('g-recaptcha-response'),
				'remoteip' => $userIp
			);
			$verify = curl_init();
			curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($verify, CURLOPT_POST, true);
			curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
			curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($verify);

			$status= json_decode($response, true);
			if($status['success'] == false){
				session()->setFlashdata('warning', 'Sorry Google reCAPTCHA unsuccessful!');
				return redirect()->to(base_url('register'));
			}else{
				$user_id = $this->model->getUserId();
				$user_photo = $this->request->getFile('user_photo');
				if($user_photo != ''){
					if(is_dir('../public/assets/img/user/'.$user_id) == false){
						mkdir('../public/assets/img/user/'.$user_id);
					}
					$user_photo_name = $user_photo->getRandomName();
					$user_photo->move('../public/assets/img/user/'.$user_id, $user_photo_name);
					$user_photo_ext = $user_photo->getClientExtension();
					$user_photo_size = $user_photo->getSize();
				}else{
					$user_photo_name = NULL;
					$user_photo_ext = NULL;
					$user_photo_size = NULL;
				}
				$userData = array(
					'user_id' => $user_id,
					'level_id' => 'L12100001',
					'first_name' => $this->request->getPost('first_name'),
					'last_name' => $this->request->getPost('last_name'),
					'user_email' => $this->request->getPost('user_email'),
					'email_verification' => 0,
					'country_calling_code' => $this->request->getPost('country_calling_code'),
					'user_phone' => $this->request->getPost('user_phone'),
					'phone_verification' => 0,
					'user_password' => hash('sha256', $this->request->getPost('user_password')),
					'user_address' => $this->request->getPost('user_address'),
					'sdistrict_id' => $this->request->getPost('sub_district'),
					'user_photo_name' => $user_photo_name,
					'user_photo_ext' => $user_photo_ext,
					'user_photo_size' => $user_photo_size,
					'req_reset_pass' => 0,
					'user_status' => 1
				);
				$this->model->insertUser($userData);

				$userHistoryData = array(
					'uhistory_id' => $this->model->getUserHistoryId(),
					'user_id' => $userData['user_id'],
					'uhistory_action' => 'Register',
					'uhistory_time' => date('Y-m-d H:i:s')
				);
				$this->model->insertUserHistory($userHistoryData);

				$notifData = array(
					'notif_id' => $this->setting->getNotifId(),
					'sender_id' => session('user_id'),
					'recipient_id' => 'U120091600001',
					'notif_class' => 'fa fa-plus media-object bg-silver-darker',
					'notif_title' => 'New user registered',
					'notif_desc' => 'User data with ID:'.$userData['user_id'].' has been successfully registered.',
					'notif_url' => 'user?id='.$userData['user_id'],
					'notif_date' => date('Y-m-d H:i:s'),
					'notif_data' => json_encode($userData),
					'is_read' => 0
				);
				$this->setting->insertNotif($notifData);

				$email_message = $this->send_email_verification($user_id);

				session()->setFlashdata($email_message['session_item'], 'User has been successfully registered.'.$email_message['email_msg']);
				return redirect()->to(base_url('login'));
			}
		}
	}

	protected function send_email_verification($id)
	{
		$user = $this->model->getUserById($id);
		if(@$user->email_verification == 0 AND @$user->user_status == 1){
			$config = array(
				'protocol' => @$this->setting->getSettingById(12)->setting_value,
				'SMTPHost' => @$this->setting->getSettingById(13)->setting_value,
				'SMTPUser' => @$this->setting->getSettingById(14)->setting_value,
				'SMTPPass' => @$this->setting->getSettingById(15)->setting_value,
				'SMTPPort' => @$this->setting->getSettingById(16)->setting_value,
				'SMTPCrypto' => @$this->setting->getSettingById(17)->setting_value,
				'mailType' => 'html'
			);

			$this->email->initialize($config);

			$this->email->setFrom($config['SMTPUser'], @$this->setting->getSettingById(4)->setting_value);
			$this->email->setTo(@$user->user_email);

			$web_title = @$this->setting->getSettingById(1)->setting_value;

			$this->email->setSubject('['.$web_title.'] Please verify your email address');
			$this->email->setMessage('Hi '.@$user->first_name.' '.@$user->last_name.',<br><br>To complete your sign up, please verify your email:<br><br><a href="'.base_url('login/verify_email/'.$id).'">Click here</a><br><br>Thank you,<br>'.$web_title.' Team');
			if($this->email->send()){
				$data = array(
					'session_item' => 'success',
					'email_msg' => ''
				);
			}else{
				$data = array(
					'session_item' => 'warning',
					'email_msg' => '<br><br>'.$this->email->printDebugger(['headers'])
				);
			}
		}else{
			$data = array(
				'session_item' => 'success',
				'email_msg' => ''
			);
		}
		return $data;
	}
}
