<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\UserModel;

class Profile extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new UserModel();
		
		$this->email = \Config\Services::email();
	}

	public function index()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' =>  @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'checkLevel' => $checkLevel,
				'request' => $this->request,
				'user' => $this->model->getUserDetail(session('user_id'))
			);

			$validation = $this->validate([
				'current_password' => ['label' => 'Current Password', 'rules' => 'required|min_length[6]'],
				'new_password' => ['label' => 'New Password', 'rules' => 'required|min_length[6]|matches[confirm_password]'],
				'confirm_password' => ['label' => 'Confirm Password', 'rules' => 'required|min_length[6]']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('profile/view_profile', $data);
				echo view('layout/footer');
			}else{
				$user_id = session('user_id');
				$current_password = hash('sha256', $this->request->getPost('current_password'));			
				$check_password = $this->model->checkPassword($user_id, $current_password);
				if(!$check_password){
					session()->setFlashdata('warning', 'Invalid current password.');
					return redirect()->to(base_url('profile'));
				}elseif($this->request->getPost('current_password') == $this->request->getPost('new_password')){
					session()->setFlashdata('warning', 'You used this password recently. Please choose a different one.');
					return redirect()->to(base_url('profile'));
				}else{
					$userData = array(
						'user_password' => hash('sha256', $this->request->getPost('new_password'))
					);
					$this->model->updateUser($user_id, $userData);

					$userHistoryData = array(
						'uhistory_id' => $this->model->getUserHistoryId(),
						'user_id' => $user_id,
						'uhistory_action' => 'Password Changed',
						'uhistory_time' => date('Y-m-d H:i:s')
					);
					$this->model->insertUserHistory($userHistoryData);

					$notifData = array(
						'notif_id' => $this->setting->getNotifId(),
						'sender_id' => $user_id,
						'recipient_id' => 'U120091600001',
						'notif_class' => 'fa fa-key media-object bg-silver-darker',
						'notif_title' => 'Password changed',
						'notif_desc' => 'Password for user with ID:'.$user_id.' has been changed successfully.',
						'notif_url' => 'user?id='.$user_id,
						'notif_date' => date('Y-m-d H:i:s'),
						'notif_data' => json_encode($userData),
						'is_read' => 0
					);
					$this->setting->insertNotif($notifData);

					$email_message = $this->send_email_password_changed($user_id);

					session()->setFlashdata('success', 'Password has been changed successfully. (SysCode: <a href="'.base_url('user?id='.$user_id).'" class="alert-link">'.$user_id.'</a>)');
					return redirect()->to(base_url('profile'));
				}
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function edit()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			$id = session('user_id');
			$user = $this->model->getUserDetail($id);
			if(@$this->request->getPost('country')){
				$state = $this->model->getState($this->request->getPost('country'));
			}elseif(@$user->country_id){
				$state = $this->model->getState($user->country_id);
			}else{
				$state = NULL;
			}
			if(@$this->request->getPost('state')){
				$city = $this->model->getCity($this->request->getPost('state'));
			}elseif(@$user->state_id){
				$city = $this->model->getCity($user->state_id);
			}else{
				$city = NULL;
			}
			if(@$this->request->getPost('city')){
				$district = $this->model->getDistrict($this->request->getPost('city'));
			}elseif(@$user->city_id){
				$district = $this->model->getDistrict($user->city_id);
			}else{
				$district = NULL;
			}
			if(@$this->request->getPost('district')){
				$sub_district = $this->model->getSubDistrict($this->request->getPost('district'));
			}elseif(@$user->district_id){
				$sub_district = $this->model->getSubDistrict($user->district_id);
			}else{
				$sub_district = NULL;
			}
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country_calling_code' => $this->model->getIddCode(),
				'country' => $this->model->getCountry(),
				'state' => $state,
				'city' => $city,
				'district' => $district,
				'sub_district' => $sub_district,
				'user' => $user
			);
			if($data['user']->user_email == $this->request->getPost('user_email')){
				$user_email_rules = 'required|valid_email';
				$email_verification = $data['user']->email_verification;
			}else{
				$user_email_rules = 'required|valid_email|is_unique[user.user_email]';
				$email_verification = 0;
			}
			if($data['user']->user_phone == $this->request->getPost('user_phone')){
				$user_phone_rules = 'required|numeric';
				$phone_verification = $data['user']->phone_verification;
			}else{
				$user_phone_rules = 'required|numeric|is_unique[user.user_phone]';
				$phone_verification = 0;
			}
			$validation = $this->validate([
				'first_name' => ['label' => 'First Name', 'rules' => 'required'],
				'last_name' => ['label' => 'Last Name', 'rules' => 'required'],
				'user_email' => ['label' => 'Email', 'rules' => $user_email_rules],
				'country_calling_code' => ['label' => 'Calling Code', 'rules' => 'required|numeric'],
				'user_phone' => ['label' => 'Phone', 'rules' => $user_phone_rules],
				'user_address' => ['label' => 'Address', 'rules' => 'required'],
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city' => ['label' => 'City', 'rules' => 'required'],
				'district' => ['label' => 'District', 'rules' => 'required'],
				'sub_district' => ['label' => 'Sub District', 'rules' => 'required'],
				'user_photo' => ['label' => 'Photo', 'rules' => 'permit_empty|ext_in[user_photo,png,jpg,gif]|max_size[user_photo,2048]']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('profile/form_edit_profile', $data);
				echo view('layout/footer');
			}else{
				$user_photo = $this->request->getFile('user_photo');
				if($user_photo != ''){
					if(is_dir('../public/assets/img/user/'.$id) == false){
						mkdir('../public/assets/img/user/'.$id);
					}
					$user_photo_name = $user_photo->getRandomName();
					$user_photo->move('../public/assets/img/user/'.$id, $user_photo_name);
					$user_photo_ext = $user_photo->getClientExtension();
					$user_photo_size = $user_photo->getSize();
				}else{
					$user_photo_name = $data['user']->user_photo_name;
					$user_photo_ext = $data['user']->user_photo_ext;
					$user_photo_size = $data['user']->user_photo_size;
				}
				$userData = array(
					'first_name' => $this->request->getPost('first_name'),
					'last_name' => $this->request->getPost('last_name'),
					'user_email' => $this->request->getPost('user_email'),
					'email_verification' => $email_verification,
					'country_calling_code' => $this->request->getPost('country_calling_code'),
					'user_phone' => $this->request->getPost('user_phone'),
					'phone_verification' => $phone_verification,
					'user_address' => $this->request->getPost('user_address'),
					'sdistrict_id' => $this->request->getPost('sub_district'),
					'user_photo_name' => $user_photo_name,
					'user_photo_ext' => $user_photo_ext,
					'user_photo_size' => $user_photo_size
				);
				$this->model->updateUser($id, $userData);

				$userHistoryData = array(
					'uhistory_id' => $this->model->getUserHistoryId(),
					'user_id' => $id,
					'uhistory_action' => 'Update',
					'uhistory_time' => date('Y-m-d H:i:s')
				);
				$this->model->insertUserHistory($userHistoryData);

				$notifData = array(
					'notif_id' => $this->setting->getNotifId(),
					'sender_id' => session('user_id'),
					'recipient_id' => 'U120091600001',
					'notif_class' => 'fa fa-edit media-object bg-silver-darker',
					'notif_title' => 'Update user data',
					'notif_desc' => 'User data with ID:'.$id.' has been updated successfully.',
					'notif_url' => 'user?id='.$id,
					'notif_date' => date('Y-m-d H:i:s'),
					'notif_data' => json_encode($userData),
					'is_read' => 0
				);
				$this->setting->insertNotif($notifData);

				$email_message = $this->send_email_verification($id);

				session()->setFlashdata($email_message['session_item'], 'User has been updated successfully. (SysCode: <a href="'.base_url('user?id='.$id).'" class="alert-link">'.$id.'</a>)'.$email_message['email_msg']);
				return redirect()->to(base_url('profile'));
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
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

			$web_title = @$this->setting->getSettingById(1)->setting_value;

			$this->email->setFrom($config['SMTPUser'], $web_title);
			$this->email->setTo(@$user->user_email);
			$this->email->setSubject('['.$web_title.'] Please verify your email address');
			$this->email->setMessage('Hi '.@$user->first_name.' '.@$user->last_name.',<br><br>To confirm that this is your email, please verify your email:<br><br><a href="'.base_url('login/verify_email/'.$id).'">Click here</a><br><br>Thank you,<br>'.$web_title.' Team');
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

	protected function send_email_password_changed($id)
	{
		$user = $this->model->getUserById($id);
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

		$web_title = @$this->setting->getSettingById(1)->setting_value;

		$this->email->setFrom($config['SMTPUser'], $web_title);
		$this->email->setTo(@$user->user_email);
		$this->email->setSubject('['.$web_title.'] Password changed');
		$this->email->setMessage('Hi '.@$user->first_name.' '.@$user->last_name.',<br><br>The password for your '.$web_title.' account on <a href="'.base_url().'">'.base_url().'</a> has been changed successfully.<br>If you did not initiate this change, please contact us immediately.<br><br>Thank you,<br>'.$web_title.' Team');
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
		return $data;
	}
}
