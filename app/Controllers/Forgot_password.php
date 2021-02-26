<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\UserModel;

class Forgot_password extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new UserModel();
		
		$this->email = \Config\Services::email();
	}

	public function index()
	{
		if($this->setting->getSettingById(11)->setting_value == 1){
			$validation = $this->validate([
				'user_email' => ['label' => 'Email', 'rules' => 'required|valid_email'],
				'g-recaptcha-response' => ['label' => 'Google reCAPTCHA', 'rules' => 'required']
			]);
			$data = array(
				'setting' => $this->setting,
				'web_title' => @$this->setting->getSettingById(1)->setting_value,
				'title' =>  'Forgot Password',
				'meta_desc' => @$this->setting->getSettingById(2)->setting_value,
				'nav_brand' => @$this->setting->getSettingById(5)->setting_value,
				'request' => $this->request,
				'validation' => $this->validator
			);
			if(!$validation){
				echo view('frontend/header', $data);
				echo view('frontend/form_forgot_password', $data);
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
					return redirect()->to(base_url('forgot_password'));
				}else{
					$check_email = $this->model->checkEmail($this->request->getPost('user_email'));
					if(!$check_email){
						session()->setFlashdata('warning', 'We couldn\'t find your account with that information.');
						return redirect()->to(base_url('forgot_password'));
					}else{
						$userData = array(
							'req_reset_pass' => 1
						);
						$this->model->updateUser(@$check_email->user_id, $userData);

						$userHistoryData = array(
							'uhistory_id' => $this->model->getUserHistoryId(),
							'user_id' => @$check_email->user_id,
							'uhistory_action' => 'Request Password Reset',
							'uhistory_time' => date('Y-m-d H:i:s')
						);
						$this->model->insertUserHistory($userHistoryData);

						$notifData = array(
							'notif_id' => $this->setting->getNotifId(),
							'sender_id' => @$check_email->user_id,
							'recipient_id' => 'U120091600001',
							'notif_class' => 'fa fa-lock media-object bg-silver-darker',
							'notif_title' => 'Request Password Reset',
							'notif_desc' => 'User with ID:'.@$check_email->user_id.' has requested to reset password.',
							'notif_url' => 'user?id='.@$check_email->user_id,
							'notif_date' => date('Y-m-d H:i:s'),
							'notif_data' => json_encode($userData),
							'is_read' => 0
						);
						$this->setting->insertNotif($notifData);

						$email_message = $this->send_email_reset_password(@$check_email->user_id);

						session()->setFlashdata($email_message['session_item'], 'Password reset has been successfully requested.'.$email_message['email_msg']);
						return redirect()->to(base_url('login'));
					}
				}
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login'));
		}
	}

	public function reset_password($id)
	{
		$user = $this->model->getUserById($id);
		if($user->req_reset_pass == 1){
			$validation = $this->validate([
				'user_password' => ['label' => 'New Password', 'rules' => 'required|min_length[6]|matches[user_repassword]'],
				'user_repassword' => ['label' => 'Confirm Password', 'rules' => 'required|min_length[6]']
			]);
			$data = array(
				'setting' => $this->setting,
				'web_title' => @$this->setting->getSettingById(1)->setting_value,
				'title' =>  'Reset Password',
				'meta_desc' => @$this->setting->getSettingById(2)->setting_value,
				'nav_brand' => @$this->setting->getSettingById(5)->setting_value,
				'validation' => $this->validator,
				'user' => $user
			);
			if(!$validation){
				echo view('frontend/header', $data);
				echo view('frontend/form_reset_password', $data);
				echo view('frontend/footer');
			}else{
				$userData = array(
					'user_password' => hash('sha256', $this->request->getPost('user_password')),
					'req_reset_pass' => 0
				);
				$this->model->updateUser($id, $userData);

				$userHistoryData = array(
					'uhistory_id' => $this->model->getUserHistoryId(),
					'user_id' => $id,
					'uhistory_action' => 'Reset Password',
					'uhistory_time' => date('Y-m-d H:i:s')
				);
				$this->model->insertUserHistory($userHistoryData);

				$notifData = array(
					'notif_id' => $this->setting->getNotifId(),
					'sender_id' => $id,
					'recipient_id' => 'U120091600001',
					'notif_class' => 'fa fa-unlock-alt media-object bg-silver-darker',
					'notif_title' => 'Reset Password',
					'notif_desc' => 'Password for user with ID:'.$id.' has been reset successfully.',
					'notif_url' => 'user?id='.$id,
					'notif_date' => date('Y-m-d H:i:s'),
					'notif_data' => json_encode($userData),
					'is_read' => 0
				);
				$this->setting->insertNotif($notifData);

				$email_message = $this->send_email_new_password($id);

				session()->setFlashdata($email_message['session_item'], 'Password reset successfully.'.$email_message['email_msg']);
				return redirect()->to(base_url('login'));
			}
		}else{
			session()->setFlashdata('warning', 'You did not request to reset your password.');
			return redirect()->to(base_url('login'));
		}
	}

	protected function send_email_reset_password($id)
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
		$this->email->setSubject('['.$web_title.'] Reset Password');
		$this->email->setMessage('Hi '.@$user->first_name.' '.@$user->last_name.',<br><br>You recently requested password reset for account associated with this email, please click on the following link to reset your password:<br><br><a href="'.base_url('forgot_password/reset_password/'.$id).'">Reset Password</a><br><br>Thank you,<br>'.$web_title.' Team');
		if($this->email->send()){
			$data = array(
				'session_item' => 'success',
				'email_msg' => ' Please check your email.'
			);
		}else{
			$data = array(
				'session_item' => 'warning',
				'email_msg' => '<br><br>'.$this->email->printDebugger(['headers'])
			);
		}
		return $data;
	}

	protected function send_email_new_password($id)
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
		$this->email->setSubject('['.$web_title.'] Password updated');
		$this->email->setMessage('Hi '.@$user->first_name.' '.@$user->last_name.',<br><br>The password for your '.$web_title.' account on <a href="'.base_url().'">'.base_url().'</a> has been updated.<br>If you did not initiate this change, please contact us immediately.<br><br>Thank you,<br>'.$web_title.' Team');
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
